<?php

echo file_get_contents('php://input');

foreach (getallheaders() as $name => $value) {
    echo "\n$name: $value";
}

echo "\n" . $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    foreach ($_GET as $name => $value) {
        echo "\n$name: $value";
        if ($value === 'timeout') {
            sleep(50);
        }
    }
}