<?php
    return array(
        'base' => 'bin',
        'image' => 'images',
        'timeZone' => 'PRC',
        'charset' => 'utf-8',
        'defaultController' => 'site',
        'defaultAction' => 'index',
        'defaultView' => 'default',
        'import' => array(
            'component',
            'controller',
            'interface',
            'model'
        ),
        'component' => array(
            'db' => array(
                'class' => 'CDBMySQL',
                'dsn' => 'mysql:unix_socket=/dev/shm/mysql.sock;dbname=framework',
                'username' => 'root',
                'password' => 'root',
                'options' => array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
                )
            ),
            'redis' => array(
                'class' => 'CRedis',
                'host' => array(
                    '127.0.0.1:6379',
                    '127.0.0.1:6379',
                    '127.0.0.1:6379',
                    '127.0.0.1:6379',
                    '127.0.0.1:6379'
                )
            )
        )
    );
?>
