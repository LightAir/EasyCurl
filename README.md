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

@TODO Необходимо написать тесты!