<?php

if(PHP_SAPI != 'cli') die('Hack attempt');

define('MIN_NUMBER_RANKING_VOTES', 2);

define('APPLICATION_NAME', 'admin');
define('APPLICATION_ENV', 'production');
define('BASE_URL', '/');

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

define('IN_APPLICATION', true);

require_once VENDOR_PATH . '/autoload.php';

$config = include APPS_CONFIG_PATH . '/application.php';

$dbContainer = new \Bisna\Doctrine\Container($config['resources']['doctrine']);
$conn = $dbContainer->getConnection();
$sql = 'SELECT updateRankingWeightAvg(' . MIN_NUMBER_RANKING_VOTES . ')';
try {
    $conn->beginTransaction();
    $conn->query($sql);
    $conn->commit();
} catch(\Exception $exc) {
    $conn->rollBack();
    throw $exc;
}