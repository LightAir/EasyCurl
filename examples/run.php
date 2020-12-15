<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

require_once 'ReSmush.php';

$example = new ReSmush();
$example->baseGet();
$example->sendImageGet();
$example->sendImagePost();
