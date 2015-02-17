<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\BeerManufacturer\Add as AddForm;

class BeermanufacturerController extends AdminAction
{    
    /**
     * Google Api Key
     * 
     * @var string
     */
    protected $_apiKey;
    
    public function indexAction()
    {
        $id = $this->getParam('id', null);
        $distributor = $this->_checkDistributor($id);
        
        $this->_enableAddMenu($id);
        $this->_enableIndexMenu($id);
        
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer');
        $options = $this->_setupOptionsPaginator($rep); 
        
        $paginator = $rep->getPaginator($options, $distributor);
        
        $this->view->manufacturers = $paginator;
        $this->view->distributor = $distributor;
    }

    public function addAction()
    {
        $id = $this->getParam('id', null);
        $distributor = $this->_checkDistributor($id);
          
        $this->_enableAddMenu($id);
        $this->_enableIndexMenu($id);
        
        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName(); 
        
        $form = new AddForm(array(
            'distributorId' => $id,
            'countries' => $countries
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {   
                $countryId = $form->getValue('country', null);
                $regionId = $this->getParam('region_id', null);
                
                $longitude = null;
                $latitude = null;
                
                if($countryId == null || $countryId == 0) {
                    $country = null;
                    $region = null;
                    $city = null;
                } else {
                    $country = $countries[$countryId];
                    $region = $this->_checkRegion($this->getParam('region_id', null), $countryId, true);
                    $city = $this->_checkCity($this->getParam('city_id', null), $regionId);
                    
                    if($city != null) {
                        $longitude = $form->getValue('longitude');
                        $latitude = $form->getValue('latitude');
                    } 
                }     
                
                $descriptions = array(
                    'EN' => $form->getValue('description_en'),
                    'PL' => $form->getValue('description_pl')
                );
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                          ->addManufacturer($form->getValue('name'), 
                                            $form->getValue('website'),
                                            $form->getValue('email'),
                                            $distributor,
                                            $descriptions,
                                            $country,
                                            $region,
                                            $city,
                                            $form->getValue('address'),
                                            $form->getValue('longitude'),
                                            $form->getValue('latitude'));
                
                $this->_helper->FlashMessenger(array('info' => 'Wytwórca został dodany'));              
                $this->_redirect('/beermanufacturer/index/id/' . $id);
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->apiKey = $this->_loadGoogleApiKey();
        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->getParam('id', null);
        $manufacturer = $this->_checkManufacturer($id);
        
        $distributor = $manufacturer->getDistributor();
        $distributorId = $distributor->getId();
        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName(); 
        
        $this->_enableIndexMenu($distributorId);
        $this->_enableEditMenu($id);
        
        $form = new AddForm(array(
            'distributorId' => $distributorId,
            'id' => $id,
            'isEdit' => true,
            'countries' => $countries
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $countryId = $form->getValue('country', null);
                $regionId = $this->getParam('region_id', null);
                
                $longitude = null;
                $latitude = null;
                
                if($countryId == null || $countryId == 0) {
                    $country = null;
                    $region = null;
                    $city = null;
                } else {
                    $country = $countries[$countryId];
                    $region = $this->_checkRegion($this->getParam('region_id', null), $countryId, true);
                    $city = $this->_checkCity($this->getParam('city_id', null), $regionId);
                    
                    if($city != null) {
                        $longitude = $form->getValue('longitude');
                        $latitude = $form->getValue('latitude');
                    } 
                }
                
                $descriptions = array(
                    'EN' => $form->getValue('description_en'),
                    'PL' => $form->getValue('description_pl')
                );
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                          ->editManufacturer($manufacturer,
                                              $form->getValue('name'), 
                                              $form->getValue('website'),
                                              $form->getValue('email'),
                                              $distributor,
                                              $descriptions,
                                              $country,
                                              $region,
                                              $city,
                                              $form->getValue('address'),
                                              $longitude,
                                              $latitude);
                
                $this->_helper->FlashMessenger(array('success' => 'Wytwórca został zedytowany'));              
                $this->_redirect('/beermanufacturer/index/id/' . $distributorId);
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
                \WS\Tool::decodeFilters($form, true);
                $form->populateByManufacturer($manufacturer);
        }

        $this->view->apiKey = $this->_loadGoogleApiKey();
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id', null);
        $manufacturer = $this->_checkManufacturer($id);

        $disId = $manufacturer->getDistributor()->getId();
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                  ->removeManufacturer($manufacturer);

        $this->_helper->FlashMessenger(array('success' => 'Wytwórca został usunięty'));              
        $this->_redirect('/beermanufacturer/index/id/' . $disId);
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $cityId
     * @param int $regionId
     * @return PoradnikPiwny\Entities\City
     * @throws \Zend_Controller_Action_Exception
     */
    protected function _checkCity($cityId, $regionId)
    {
        try {
            $city = $this->_em->getRepository('\PoradnikPiwny\Entities\City')
                             ->getCityByIdAndRegionId($cityId, $regionId);
        } catch(\Exception $exc) {
            throw new \Zend_Controller_Action_Exception();
        }
        
        return $city;
    }
    
    /**
     * @param int $regionId
     * @param int $countryId
     * @param boolean $forceNull
     * @return PoradnikPiwny\Entities\Region
     * @throws \Zend_Controller_Action_Exception
     */
    protected function _checkRegion($regionId, $countryId = null, $forceNull = false)
    {
        try {
            if($countryId == null) {
                $region = $this->_em->getRepository('\PoradnikPiwny\Entities\Region')
                                    ->getRegionById($regionId, $forceNull);
            } else {
                $region = $this->_em->getRepository('\PoradnikPiwny\Entities\Region')
                                    ->getRegionByIdAndCountryId($regionId, $countryId, $forceNull);
            }
        } catch(\Exception $exc) {
            throw new \Zend_Controller_Action_Exception();
        } 
        
        return $region;            
    }
    
    /**
     * @param int $countryId
     * @return PoradnikPiwny\Entities\Country
     * @throws \Zend_Controller_Action_Exception
     */
    protected function _checkCountry($countryId)
    {
        try {
            $country = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                                 ->getCountryById($countryId);
        } catch(\Exception $exc) {
            throw new \Zend_Controller_Action_Exception();
        }
        
        return $country;            
    }
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    protected function _checkDistributor($id)
    {     
        try {
            $distributor = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                                     ->getDistributorById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora dystrybutora'));              
            $this->_redirect('/beerdistributor');            
        } catch(PoradnikPiwny\Exception\DistributorNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany dystrybutor nie istnieje'));
            $this->_redirect('/beerdistributor');            
        }
        
        return $distributor;
    }
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    protected function _checkManufacturer($id)
    {     
        try {
            $manufacturer = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                                      ->getBeerManufacturerById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora wytwórcy'));              
            $this->_redirect('/beerdistributor');            
        } catch(\PoradnikPiwny\Exception\BeerManufacturerNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany wytwórca nie istnieje'));
            $this->_redirect('/beerdistributor');            
        }
        
        return $manufacturer;
    }
    
    protected function _enableIndexMenu($id)
    {
        $menu = $this->_navigation->findById('admin_beermanufacturer_index');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));        
    }
    
    protected function _enableAddMenu($id)
    {
        $menu = $this->_navigation->findById('admin_beermanufacturer_add');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
    }
    
    protected function _enableEditMenu($id)
    {
        $menu = $this->_navigation->findById('admin_beermanufacturer_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
    }
    
    protected function _enableBeerMenu($id)
    {
        $menu = $this->_navigation->findById('admin_beermanufacturer_beer');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
    }
    
    protected function _loadGoogleApiKey()
    {
        if($this->_apiKey == null)
        {
            $config = new \Zend_Config_Ini(APPS_CONFIG_PATH . '/maps.ini.php');
            $this->_apiKey = $config->google->apiKey;
        }
        
        return $this->_apiKey;
    }
}