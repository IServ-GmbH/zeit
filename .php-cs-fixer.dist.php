<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__);
$config = new PhpCsFixer\Config();

return $config->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder)
;
