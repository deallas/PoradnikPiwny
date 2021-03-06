<?php

define('APPLICATION_NAME', 'admin');

define('APPLICATION_ENV', 'development');
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
define('DOCTRINE_CLI', true);

define('APPLICATION_URL', 'http://poradnikpiwny.local');

require_once VENDOR_PATH . '/autoload.php';
 
// Create application
$application = new Zend_Application(
    APPLICATION_ENV,
    CONFIG_PATH . '/application.php'
);

// Bootstrapping resources
$bootstrap = $application->bootstrap()->getBootstrap();

// Retrieve Doctrine Container resource
$container = $bootstrap->getResource('doctrine');

// Console
$cli = new \Symfony\Component\Console\Application(
    'Doctrine Command Line Interface',
    \Doctrine\Common\Version::VERSION
);

try {
    // Bootstrapping Console HelperSet
    $helperSet = array();

    if (($dbal = $container->getConnection(getenv('CONN') ?: $container->defaultConnection)) !== null) {
        $helperSet['db'] = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($dbal);
    }

    if (($em = $container->getEntityManager(getenv('EM') ?: $container->defaultEntityManager)) !== null) {
        $helperSet['em'] = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    }
} catch (\Exception $e) {
    $cli->renderException($e, new \Symfony\Component\Console\Output\ConsoleOutput());
}

$cli->setCatchExceptions(true);
$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet($helperSet));

$cli->addCommands(array(
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),

));

$cli->run();