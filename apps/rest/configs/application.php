<?php

if(!defined('IN_APPLICATION')) die('Hack attempt');

$security = new Zend_Config_Ini(APPS_CONFIG_PATH . '/security.ini.php');
$config = include APPS_CONFIG_PATH . '/application.php';

return array_merge_recursive($config, array(
    'resources' => array(
        'frontController' => array(
            'moduleDirectory' => APPLICATION_PATH . '/modules'
        ),
        'modules' => array(),
        'security' => array_merge_recursive(array(
            'plugin' => array(
                'class' => '\PoradnikPiwny\Controller\Plugin\Security',
                'filename' => LIBRARY_PATH . '/PoradnikPiwny/Controller/Plugin/Security.php'
            )
        ), $security->toArray())
    ),
    'rest' => array(
        'default' => 'xml',
        'formats' => array(
            'json',
            'xml'
        )
    )
));