[![Build Status](https://travis-ci.org/LightAir/EasyCurl.svg?branch=master)](https://travis-ci.org/LightAir/EasyCurl)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/LightAir/EasyCurl)
[![codecov](https://codecov.io/gh/LightAir/EasyCurl/branch/master/graph/badge.svg)](https://codecov.io/gh/LightAir/EasyCurl)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/90ca51e4ee4241898d9db1462c8e802c)](https://www.codacy.com/app/the/EasyCurl?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=LightAir/EasyCurl&amp;utm_campaign=Badge_Grade)
# A basic CURL wrapper for PHP

## install
```shell
composer require lightair/easycurl
```

## using
```php
use LightAir\EasyCurl\EasyCurl;

$easyCurl = new EasyCurl('http://yandex.ru');
$result = $easyCurl->get();
```

## tests
```shell
./tests-run.sh
```

@TODO Необходимо написать тесты!