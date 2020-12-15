<p align="center">
    <img src="https://raw.githubusercontent.com/LightAir/EasyCurl/master/art/ec.png" alt="EasyCurl"/>
</p>

EasyCurl, its easy CURL wrapper for PHP.

[![Build Status](https://travis-ci.com/LightAir/EasyCurl.svg?branch=master)](https://travis-ci.com/LightAir/EasyCurl)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/LightAir/EasyCurl)
[![codecov](https://codecov.io/gh/LightAir/EasyCurl/branch/master/graph/badge.svg)](https://codecov.io/gh/LightAir/EasyCurl)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/9945beb63c244e3baddb80b893601805)](https://www.codacy.com/gh/LightAir/EasyCurl/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=LightAir/EasyCurl&amp;utm_campaign=Badge_Grade)

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
