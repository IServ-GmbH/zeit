# IServ Zeit Library

[![coverage report](https://git.iserv.eu/iserv/lib/zeit/badges/master/coverage.svg)](https://git.iserv.eu/iserv/lib/zeit/commits/master)
[![pipeline status](https://git.iserv.eu/iserv/lib/zeit/badges/master/pipeline.svg)](https://git.iserv.eu/iserv/lib/zeit/commits/master)

## Usage

### Zeit and Clock

The Zeit library offers you some assitance in handling date and time related information. One the one side we have the `Zeit` util and
a `Clock` which can be asked for the current time. It will use the system clock by default, but a fixed value can be injected for testing.

```php
use IServ\Library\Zeit\Clock\FixedClock;
use IServ\Library\Zeit\Zeit;

$now = Zeit::now();
printf("It is %s o'clock", $now->format('H'));

Zeit::setClock(FixedClock::fromString('2021-04-23 12:00:00'));

$now = Zeit::now();
printf("It is %s o'clock", $now->format('H'));
 // Will print 12 o'clock
```

There's also the option to `usleep` the clock so that you can do time elapsing testing, too.

### Date and Time

The library also offers `Date` and `Time` domain objects. These can be used to model only relevant data where you'd normally rely on PHP's
built-in DateTime objects. The models can be converted to DateTime objects for further processing, but keep in mind that PHP will add the 
missing parts for you.

```php
use IServ\Library\Zeit\Date;
use IServ\Library\Zeit\Time;

$date = Date::fromParts(2021, 4, 23);
echo $date->getValue();
// will print the normalized string representation "2021-04-23"

$time = Time::fromParts(10, 37); // The third parameter for seconds is optional and defaults to zero 
echo $time->getValue();
// will print the normalized string representation "10:37:00"

// You can merge a Date and a Time to \DateTimeImmutable
$dateTime = $date->withTime($time);
echo $dateTime->format('Y:m:d H:i:s');
// will print "2021-04-23 10:37:00"
```

The real power of `Date` and `Time` can be leveraged with the `ZeitBridge` compoment which integrates the library into your Doctrine/Symfony
project and allows to map the Zeit models into entities and forms directly.
