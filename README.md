<p align="center">
    <img src="https://raw.githubusercontent.com/LightAir/EasyCurl/master/art/ec.png" alt="EasyCurl"/>
</p>

EasyCurl, its easy CURL wrapper for PHP.

[![Build Status](https://travis-ci.com/LightAir/EasyCurl.svg?branch=master)](https://travis-ci.com/LightAir/EasyCurl)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/LightAir/EasyCurl)
[![codecov](https://codecov.io/gh/LightAir/EasyCurl/branch/master/graph/badge.svg)](https://codecov.io/gh/LightAir/EasyCurl)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/90ca51e4ee4241898d9db1462c8e802c)](https://www.codacy.com/app/the/EasyCurl?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=LightAir/EasyCurl&amp;utm_campaign=Badge_Grade)

## Install
```shell
composer require lightair/easycurl
```

## How to use
```php
use LightAir\Utils\EasyCurl;

$easyCurl = new EasyCurl('http://yandex.ru');
$result = $easyCurl->get();
```

## Lumen and Laravel
No longer supported

## Run tests
```shell
./tests-run.sh
```
