<?php

if(!defined('IN_APPLICATION')) die('Hack attempt');

define('APPLICATION_NAME', (getenv('APPLICATION_NAME') ? getenv('APPLICATION_NAME') : null));
if(APPLICATION_NAME == null) die('Hack attempt');

define('BASE_URL', (getenv('BASE_URL') ? getenv('BASE_URL') : '/'));
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('BASE_PATH', dirname(__FILE__));
define('PROJECT_PATH', realpath(BASE_PATH . '/../'));
define('APPS_PATH', PROJECT_PATH . '/apps');
define('APPS_CONFIG_PATH', APPS_PATH . '/_config');
define('APPLICATION_PATH', APPS_PATH . '/' . APPLICATION_NAME);
define('CONFIG_PATH', APPLICATION_PATH . '/configs');
define('LIBRARY_PATH', PROJECT_PATH . '/library');
define('VENDOR_PATH', PROJECT_PATH . '/vendor');
define('RESOURCES_PATH', PROJECT_PATH . '/resources/' . APPLICATION_NAME);
define('TMP_PATH', PROJECT_PATH . '/tmp');

define('STATIC_PATH', BASE_PATH . '/static');

define('UPLOAD_PATH', STATIC_PATH . '/upload');
define('UPLOAD_CACHE_PATH', UPLOAD_PATH . '/_cache');

require_once VENDOR_PATH . '/autoload.php';

define('DOMAIN_NAME', \WS\Tool::getDomain());

$dir = implode('/', array_intersect(explode('/', $_SERVER['REQUEST_URI']), explode('/', str_replace('\\', '/', BASE_PATH))));

if(isset($_SERVER['HTTPS'])) {
    $urlPrefix = 'https://'; 
} else {
    $urlPrefix = 'http://';
}

define('ADMIN_APPLICATION_URL', $urlPrefix . 'admin.' . DOMAIN_NAME . rtrim($dir, '/'));
define('DEFAULT_APPLICATION_URL', $urlPrefix . 'dev.' . DOMAIN_NAME . rtrim($dir, '/'));
define('PROMOTION_APPLICATION_URL', $urlPrefix . 'www.' . DOMAIN_NAME . rtrim($dir, '/'));

$errorHandler = new \WS\ErrorHandler();
if(APPLICATION_ENV == 'production')
{
    $errorHandler->registerHandler();
}

$application = null;
try {
    $application = new \Zend_Application(
        APPLICATION_ENV,
        CONFIG_PATH . '/application.php'
    );
    $application->bootstrap()
                ->run();
} catch(\Zend_Controller_Action_Exception $exc) {
    include RESOURCES_PATH . '/pages/404.php';
} catch(\Zend_Controller_Dispatcher_Exception $exc) {
    include RESOURCES_PATH . '/pages/404.php';
} catch(\PoradnikPiwny\Security\Exception $exc) {
    include RESOURCES_PATH . '/pages/403.php';
} catch(\Exception $exception) {
    $exc = $exception->getPrevious();
    if($exc instanceof \Zend_Controller_Action_Exception) {
        include RESOURCES_PATH . '/pages/404.php'; 
        return;
    } else if($exc instanceof \PoradnikPiwny\Security\Exception) {
        include RESOURCES_PATH . '/pages/403.php'; 
        return;
    } else {
        $displayException = false;
        if($application != null)
        {
            $options = $application->getBootstrap()->getOptions();
            if(!empty($options))
            {
                $displayException = $options['resources']['frontController']['params']['displayExceptions'];
            }
        }

        $errorHandler->phpException($exception);

        include RESOURCES_PATH . '/pages/500.php';
    }
}