<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

use LightAir\Examples\ReSmush;

$example = new ReSmush();
$example->baseGet();
$example->sendImageGet();
$example->sendImagePost();
