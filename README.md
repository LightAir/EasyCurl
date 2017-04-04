<p align="center">
    <img src="https://raw.githubusercontent.com/LightAir/EasyCurl/master/art/ec.png" alt="EasyCurl"/>
</p>

EasyCurl, it a easy CURL wrapper for PHP.

[![Build Status](https://travis-ci.org/LightAir/EasyCurl.svg?branch=master)](https://travis-ci.org/LightAir/EasyCurl)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/LightAir/EasyCurl)
[![codecov](https://codecov.io/gh/LightAir/EasyCurl/branch/master/graph/badge.svg)](https://codecov.io/gh/LightAir/EasyCurl)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/90ca51e4ee4241898d9db1462c8e802c)](https://www.codacy.com/app/the/EasyCurl?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=LightAir/EasyCurl&amp;utm_campaign=Badge_Grade)

## Install
```shell
composer require lightair/easycurl
```

## How to use (PHP)
```php
use LightAir\EasyCurl\EasyCurl;

$easyCurl = new EasyCurl('http://yandex.ru');
$result = $easyCurl->get();
```

## How to use (With Lumen)
In file bootstrap/app.php uncomment ```$app->withFacades();``` and add:
```php
$app->register(LightAir\EasyCurl\EasyCurlServiceProvider::class);

if (!class_exists('ECurl')) {
    class_alias(LightAir\EasyCurl\EasyCurlFacade::class, 'ECurl');
}
```

```php
$result = \ECurl::get([], 'http://yandex.ru');
dd($result, \ECurl::getHttpStatusCode());
```

## Run tests
```shell
./tests-run.sh
```
