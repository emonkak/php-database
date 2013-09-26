<?php

require(__DIR__ . '/../vendor/autoload.php');

$loader = new Composer\Autoload\ClassLoader();
$loader->add('PDOInterface', __DIR__ . '/PDOInterface');
$loader->register();
