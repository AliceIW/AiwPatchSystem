<?php

return [
    'dsn'      => 'mysql:host=localhost;dbname=test_patcher',
    'username' => 'root',
    'password' => 'root',
    'options'  => [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
    ]
];
