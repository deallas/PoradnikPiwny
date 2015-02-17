<?php

if(!defined('IN_APPLICATION')) die('Hack attempt');

return array(
    'phpSettings' => array(
        'display_startup_errors' => 1,
        'display_errors' => 1
    ),
    'resources' => array(
        'frontController' => array(
            'params' => array(
                'displayExceptions' => 1,
                'noErrorHandler' => 1
            )
        ),
        'sphinx' => array(
            'host' => 'localhost',
            'port' => 9312
        ), // end sphinx
        'doctrine' => array(
            'dbal' => array(
                'connections' => array(
                    'default' => array(
                        'id' => 'default',
                        'parameters' => array(
                                'driver' => 'pdo_pgsql',
                                'dbname' => 'poradnikpiwny_db',
                                'host' => 'localhost',
                                'port' => 5432,
                                'user' => 'poradnikpiwny',
                                'password' => '12345',
                                'driverOptions' => array(
                                    1002 => "SET NAMES 'UTF8'"
                                )
                        )
                    ), // end default
                    'sphinx' => array(
                        'id' => 'sphinx',
                        'parameters' => array(
                            'driver' => 'pdo_mysql',
                            'host' => '127.0.0.1',
                            'port' => 9306,
                            'user' => 'poradnikpiwny',
                            'password' => '12345'
                        )
                    )
                ) // end connections
            ), // end dbal
            'cache' => array(
                'instances' => array(
                    'default' => array(
                        'adapterClass' => 'Doctrine\Common\Cache\ArrayCache'
                    ),
                    'sphinx' => array(
                        'adapterClass' => 'Doctrine\Common\Cache\ArrayCache'
                    )
                )
            ) //end cache
        )
    )
);