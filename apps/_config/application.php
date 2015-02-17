<?php

if(!defined('IN_APPLICATION')) die('Hack attempt');

$config = include APPS_CONFIG_PATH . '/env/' . APPLICATION_ENV . '.php';

return array_merge_recursive($config, array(
    'pluginPaths' => array(
        '\PoradnikPiwny\Application\Resource' => LIBRARY_PATH . '/PoradnikPiwny/Application/Resource',
        '\WS\Application\Resource' => LIBRARY_PATH . '/WS/Application/Resource',
    	'\Bisna\Application\Resource' => LIBRARY_PATH . '/Bisna/Application/Resource'
    ),
    'bootstrap' => array(
        'path' => APPLICATION_PATH . '/Bootstrap.php',
        'class' => "Bootstrap"
    ),
    'resources' => array(
        'layout' => array(
            'layoutPath' => APPLICATION_PATH . '/layouts/'
        ),
        'frontController' => array(
            'controllerDirectory' => APPLICATION_PATH . '/controllers',
            'throwExceptions' => 1,
            'baseUrl' => BASE_URL
        ),
        'locale' => array(
            'default' => 'pl'
        ),
        'cachemanager' => array(
            'file' => array(
                'frontend' => array(
                    'name' => 'Core',
                    'customFrontendNaming' => false,
                    'options' => array(
                        'lifetime' => 60*60, // 1 hour
                        'automatic_serialization' => true
                    )
                ),
                'backend' => array(
                    'name' => 'File',
                    'customBackendNaming' => false,
                    'options' => array(
                            'cache_dir' => TMP_PATH			
                    )	
                )
            ),
            'memory' => array(
                'frontend' => array(
                    'name' => 'Core',
                    'customFrontendNaming' => false,
                    'options' => array(
                        'lifetime' => 60*5, // 5m
                        'automatic_serialization' => true
                    )
                ),
                'backend' => array(
                    'name' => 'Apc'
                )                
            )
        ),
        'translate' => array(
            'registry_key' => 'Zend_Translate',
            'adapter' => 'gettext',
            'content' => RESOURCES_PATH . '/translate/',
            'options' => array(
                'scan' => 'filename',
                'logUntranslated' => false
            ),
            'disableNotices' => false,
            'locale' => 'pl'
        ),
    	'doctrine' => array(
            'dbal' => array(
                'defaultConnnection' => 'default',
                'connections' => array(
                    'default' => array(
                        'types' => array(
                            'zenddate'  => '\DoctrineExtensions\Types\ZendDateType'
                        )
                    ), // end default
                    'sphinx' => array()
                ) // end defaultConnection
            ), // end dbal
            'orm' => array(
                'defaultEntityManager' => 'default',
                'entityManagers' => array(
                    'default' => array(
                        'entityNamespaces' => array(
                            'app' => '\PoradnikPiwny\Entities'
                        ),
                        'connection' => 'default',
                        'proxy' => array(
                            'namespace' => 'PoradnikPiwny\Entities\Proxies',
                            'dir' => LIBRARY_PATH . '/PoradnikPiwny/Entities/Proxies'
                        ),
                        'metadataDrivers' => array(
                            'annotationRegistry' => array(
                                'annotationFiles' => array(VENDOR_PATH . '/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php')	
                            ),
                            'drivers' => array(
                                0 => array(
                                    'adapterClass' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                                    'mappingNamespace' => '\PiwnyPoradnik\Entities',
                                    'mappingDirs' => array(LIBRARY_PATH . "/PoradnikPiwny/Entities"),
                                    'annotationReaderClass' => '\Doctrine\Common\Annotations\AnnotationReader',
                                    'annotationReaderCache' => 'default'
                                )  							
                            )
                        )
                    ),
                    'sphinx' => array()
                ) // end entityManagers
            ) //end orm
    	) // end doctrine
    ) // end resources
));