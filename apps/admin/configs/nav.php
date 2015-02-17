<?php

if(!defined('IN_APPLICATION')) die('Hack attempt');

return array(
    new Zend_Navigation_Page_Mvc(
    	array(
            'label' => 'Panel administratora',
            'id' => 'top_menu_1',
            'controller' => 'index',
            'action' => 'index',
            'pages' => array(
                new Zend_Navigation_Page_Uri(array(
                    'uri' => 'javascript:void(0)',
                    'class' => 'nav-header',
                    'label' => 'ADMINISTRACJA',
                    'resource' => 'admin__settings',
                    'privilege' => 'index'           
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'settings',
                    'action' => 'index',
                    'label' => 'Ustawienia logowania',
                    'resource' => 'admin__settings',
                    'privilege' => 'index',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'settings',
                            'action' => 'edit',
                            'label' => 'Edytuj ustawienia'
                        ))
                    )
                )),
                new Zend_Navigation_Page_Mvc(
                    array(
                        'label' => 'Ustawienia strony',
                        'controller' => 'page',
                        'action' => 'index',
                        'resource' => 'admin__page',
                        'privilege' => 'index',
                        'pages' => array(
                            new Zend_Navigation_Page_Mvc(
                                array(
                                    'controller' => 'page',
                                    'action' => 'edit',
                                    'label' => 'Edytuj ustawienia'
                                )
                            )
                        )
                    )
                ),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'users',
                    'action' => 'index',
                    'label' => 'Użytkownicy',
                    'resource' => 'admin__users',
                    'privilege' => 'index',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'users',
                            'action' => 'add',
                            'label' => 'Dodaj użytkownika',
                            'resource' => 'admin__users',
                            'privilege' => 'add',
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'label' => 'Informacje o użytkowniku',
                            'controller' => 'users',
                            'action' => 'info',
                            'id' => 'admin_users_info',
                            'visible' => false
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'label' => 'Edycja użytkownika',
                            'controller' => 'users',
                            'action' => 'edit',
                            'id' => 'admin_users_edit',
                            'visible' => false
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'label' => 'Zmiana hasła',
                            'controller' => 'users',
                            'action' => 'changepass',
                            'id' => 'admin_users_changepass',
                            'visible' => false
                        ))
                    )
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'blocker',
                    'action' => 'index',
                    'label' => 'PPHulk',
                    'resource' => 'admin__blocker',
                    'privilege' => 'index',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'blocker',
                            'action' => 'add',
                            'label' => 'Dodaj nową regułę'
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'blocker',
                            'action' => 'edit',
                            'label' => 'Edytuj regułę',
                            'id' => 'admin_blacklist_edit',
                            'visible' => false
                        ))
                    )
                )),
                new Zend_Navigation_Page_Uri(array(
                    'uri' => 'javascript:void(0)',
                    'class' => 'nav-header',
                    'label' => 'KONTO'         
                )),
                new Zend_Navigation_Page_Mvc(
                    array(
                        'label' => 'Zmiana hasła',
                        'controller' => 'account',
                        'action' => 'changepass'
                    )
                ),
                new Zend_Navigation_Page_Mvc(
                    array(
                        'label' => 'Edytuj ustawienia',
                        'controller' => 'account',
                        'action' => 'settings'
                    )
                )
            )
    	)
    ),
    new Zend_Navigation_Page_Mvc(
        array(
            'label' => 'Panel piwosza',
            'id' => 'top_menu_2',
            'controller' => 'beer',
            'action' => 'index',
            'pages' => array(
                new Zend_Navigation_Page_Uri(array(
                    'uri' => 'javascript:void(0)',
                    'class' => 'nav-header',
                    'label' => 'PIWO',       
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'beer',
                    'action' => 'add',
                    'label' => 'Dodaj piwo'
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'beerfamily',
                    'action' => 'index',
                    'label' => 'Rodzina piwa',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beerfamily',
                            'action' => 'add',
                            'label' => 'Dodaj potomka'
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beerfamily',
                            'action' => 'edit',
                            'label' => 'Edytuj potomka',
                            'id' => 'admin_beerfamily_edit',
                            'visible' => false
                        ))
                    )
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'beerimage',
                    'action' => 'index',
                    'label' => 'Galeria piwa',
                    'id' => 'admin_beerimage',
                    'visible' => false,
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beerimage',
                            'action' => 'add',
                            'id' => 'admin_beerimage_add',
                            'label' => 'Dodaj zdjęcie'
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'label' => 'Edytuj zdjęcie',
                            'controller' => 'beerimage',
                            'action' => 'edit',
                            'id' => 'admin_beerimage_edit',
                            'visible' => false
                        ))
                    )
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Edytuj piwo',
                    'controller' => 'beer',
                    'action' => 'edit',
                    'id' => 'admin_beer_edit',
                    'visible' => false
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'label' => 'Informacje o piwie',
                    'controller' => 'beer',
                    'action' => 'info',
                    'id' => 'admin_beer_info',
                    'visible' => false
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'beer',
                    'action' => 'search',
                    'label' => 'Szukaj piwa'
                )),
                new Zend_Navigation_Page_Uri(array(
                    'uri' => '',
                    'label' => 'Wyniki wyszukiwania',
                    'id' => 'admin_beer_searchresult',
                    'visible' => false
                )), 
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'beerdistributor',
                    'action' => 'index',
                    'label' => 'Dystrybutorzy',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beerdistributor',
                            'action' => 'add',
                            'label' => 'Dodaj dystrybutora'
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beerdistributor',
                            'action' => 'edit',
                            'label' => 'Edytuj dystrybutora',
                            'id' => 'admin_beerdistributor_edit',
                            'visible' => false
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'beermanufacturer',
                            'action' => 'index',
                            'label' => 'Wytwórcy',
                            'id' => 'admin_beermanufacturer_index',
                            'visible' => false,
                            'pages' => array(
                                new Zend_Navigation_Page_Mvc(array(
                                    'controller' => 'beermanufacturer',
                                    'action' => 'add',
                                    'label' => 'Dodaj wytwórcę',
                                    'id' => 'admin_beermanufacturer_add'
                                )),
                                new Zend_Navigation_Page_Mvc(array(
                                    'controller' => 'beermanufacturer',
                                    'action' => 'edit',
                                    'label' => 'Edytuj wytwórcę',
                                    'id' => 'admin_beermanufacturer_edit',
                                    'visible' => false
                                )),
                                new Zend_Navigation_Page_Mvc(array(
                                    'controller' => 'beermanufacturerimage',
                                    'action' => 'index',
                                    'label' => 'Galeria wytwórcy',
                                    'id' => 'admin_beermanufacturerimage',
                                    'visible' => false,
                                    'pages' => array(
                                        new Zend_Navigation_Page_Mvc(array(
                                            'controller' => 'beermanufacturerimage',
                                            'action' => 'add',
                                            'id' => 'admin_beermanufacturerimage_add',
                                            'label' => 'Dodaj zdjęcie'
                                        )),
                                        new Zend_Navigation_Page_Mvc(array(
                                            'label' => 'Edytuj zdjęcie',
                                            'controller' => 'beermanufacturerimage',
                                            'action' => 'edit',
                                            'id' => 'admin_beermanufacturerimage_edit',
                                            'visible' => false
                                        ))
                                    )
                                ))
                            )
                        ))
                    )
                )),
                new Zend_Navigation_Page_Mvc(array(
                    'controller' => 'currency',
                    'action' => 'index',
                    'label' => 'Waluty',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'currency',
                            'action' => 'add',
                            'label' => 'Dodaj walutę'
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'currency',
                            'action' => 'edit',
                            'label' => 'Edytuj walutę',
                            'id' => 'admin_currency_edit',
                            'visible' => false
                        )),
                        new Zend_Navigation_Page_Mvc(array(
                            'controller' => 'currencyexchange',
                            'action' => 'index',
                            'label' => 'Przelicznik',
                            'id' => 'admin_currencyexchange_index',
                            'visible' => false,
                            'pages' => array(
                                new Zend_Navigation_Page_Mvc(array(
                                    'controller' => 'currencyexchange',
                                    'action' => 'edit',
                                    'label' => 'Edytuj przelicznik',
                                    'id' => 'admin_currencyexchange_edit',
                                    'visible' => false
                                ))
                            )
                        )),
                    )
                )) 
            )
        )
    )
);