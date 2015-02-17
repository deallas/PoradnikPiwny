<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Beer\Add as AddForm,
    PoradnikPiwny\Form\Beer\Search as SearchForm,
    WS\Tool;

class BeerController extends AdminAction
{   
    public function indexAction()
    {   
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer');  
        $options = $this->_setupOptionsPaginator($rep);  
        $paginator = $rep->getBeersPaginator($options, $this->_user);

        $this->view->beers = $paginator;
    }

    public function addAction()
    {   
        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName();
        $families = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                              ->getSortedParents();
        
        $currencies = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                                ->getSortedCurrencies();
        
        $distributors = $this->_checkDistributors();
        
        $form = new AddForm(array(
            'distributors' => $distributors,
            'countries' => $countries,
            'families' => $families,
            'currencies' => $currencies,
            'request' => $_POST
        ));

        if($this->getRequest()->isPost())
        {
            $manufacturers = $this->_populateManufacturers($form);
            $this->_checkFormValues($form);
            if($form->isValid($_POST))
            {   
                $distributor = $form->getValue('distributor');
                if(isset($distributors[$distributor])) {
                    $distributor = $distributors[$distributor];
                } else {
                    $distributor = null;
                }
                
                if($manufacturers != null) {
                    $manufacturer = $manufacturers[$form->getValue('manufacturer')];
                } else {
                    $manufacturer = null;
                }
                
                $countryId = $form->getValue('country', null);
                $regionId = $this->getParam('region_id', null);
                
                if($countryId == null || $countryId == 0) {
                    $country = null;
                    $region = null;
                    $city = null;
                } else {
                    $country = $countries[$countryId];
                    $region = $this->_checkRegion($this->getParam('region_id', null), $countryId, true);
                    $city = $this->_checkCity($this->getParam('city_id', null), $regionId);
                }  
                
                $familyId = $form->getValue('family');
                $family = null;
                
                if($familyId != 0) {
                    $family = $families[$form->getValue('family')];
                }
                
                $descriptions = array(
                    'EN' => $form->getValue('description_en'),
                    'PL' => $form->getValue('description_pl')
                );
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                           ->addBeer($form->getValue('name'),
                                     $descriptions,
                                     $form->getPrices(),
                                     $form->getValue('alcohol'),
                                     $form->getValue('extract'),
                                     $form->getValue('malt'),
                                     $form->getValue('type'),
                                     $form->getValue('filtered'),
                                     $form->getValue('pasteurized'),
                                     $form->getValue('flavored'),
                                     $form->getValue('placeOfBrew'),
                                     $form->getValue('status'),
                                     $family,
                                     $distributor,
                                     $manufacturer,
                                     $country,
                                     $region,
                                     $city);
                
                $this->_helper->FlashMessenger(array('info' => 'Piwo zostało dodane'));              
                $this->_redirect('/beer');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->form = $form;
        $this->view->currencies = $currencies;
    }

    public function editAction()
    {        
        $id = $this->_getParam('id', null);
        $beer = $this->_checkBeer($id);
        
        $menu = $this->_navigation->findById('admin_beer_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));

        $distributors = $this->_checkDistributors();

        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName();
        $families = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                              ->getSortedParents();      
        $currencies = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                                ->getSortedCurrencies();
        
        $isPost = $this->getRequest()->isPost();
        
        $form = new AddForm(array(
            'id' => $id,
            'isEdit' => true,
            'distributors' => $distributors,
            'countries' => $countries,
            'families' => $families,
            'currencies' => $currencies,
            'beer' => ($isPost) ? null : $beer,
            'request' => $_POST
        ));
        if($isPost)
        {
            $manufacturers = $this->_populateManufacturers($form);
            
            if($form->isValid($_POST))
            {      
                $distributor = $form->getValue('distributor');
                if(isset($distributors[$distributor])) {
                    $distributor = $distributors[$distributor];
                } else {
                    $distributor = null;
                }
                
                if($manufacturers != null) {
                    $manufacturer = $manufacturers[$form->getValue('manufacturer')];
                } else {
                    $manufacturer = null;
                }
   
                $countryId = $form->getValue('country', null);
                $regionId = $this->getParam('region_id', null);
                
                if($countryId == null || $countryId == 0) {
                    $country = null;
                    $region = null;
                    $city = null;
                } else {
                    $country = $countries[$countryId];
                    $region = $this->_checkRegion($this->getParam('region_id', null), $countryId, true);
                    $city = $this->_checkCity($this->getParam('city_id', null), $regionId);
                }  

                $familyId = $form->getValue('family');
                $family = null;
                
                if($familyId != 0) {
                    $family = $families[$form->getValue('family')];
                }

                $descriptions = array(
                    'EN' => $form->getValue('description_en'),
                    'PL' => $form->getValue('description_pl')
                );
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                          ->editBeer($beer,
                                     $form->getValue('name'), 
                                     $descriptions,
                                     $form->getPrices(),
                                     $form->getValue('alcohol'),
                                     $form->getValue('extract'),
                                     $form->getValue('malt'),
                                     $form->getValue('type'),
                                     $form->getValue('filtered'),
                                     $form->getValue('pasteurized'),
                                     $form->getValue('flavored'),
                                     $form->getValue('placeOfBrew'),
                                     $form->getValue('status'),
                                     $family,
                                     $distributor,
                                     $manufacturer,
                                     $country,
                                     $region,
                                     $city); 
                $this->_helper->FlashMessenger(array('success' => 'Piwo zostało zedytowane'));              
                $this->_redirect('/beer');
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByBeer($beer);  
            
            $manufacturer = $beer->getManufacturer();
            
            if($manufacturer != null) {
                $form->populateByManufacturers($this->_checkManufacturers($manufacturer->getDistributor()));
            }
        }

        $this->view->form = $form;
        $this->view->currencies = $currencies;
    }
    
    public function infoAction()
    {
        $id = $this->_getParam('id', null);
        $beer = $this->_checkBeer($id);	

        $menu = $this->_navigation->findById('admin_beer_info');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
        
        $this->view->beer = $beer;
        $this->view->beerPrices = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerPrice')
                                            ->getPricesByBeerId($id);
        $this->view->adminRanks = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerRanking')
                                             ->getAdminRankingsByBeerId($id);
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id', null);
        $beer = $this->_checkBeer($id);

        $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')->removeBeer($beer);

        $this->_helper->FlashMessenger(array('success' => 'Piwo zostało usunięte'));             
        $this->_redirect('/beer');
    }
    
    public function searchAction()
    {
        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName();
        $families = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                              ->getSortedParents();
        
        $distributors = $this->_checkDistributors();
        
        $form = new SearchForm(array(
            'distributors' => $distributors,
            'countries' => $countries,
            'families' => $families
        ));

        if(isset($_GET['submit']))
        {
            $manufacturers = $this->_populateManufacturers($form);
            
            if($form->isValid($_GET))
            {    
                $manufacturer = null;
                if($manufacturers != null) {
                    if(isset($manufacturers[$form->getValue('manufacturer')]))
                    {
                        $manufacturer = $manufacturers[$form->getValue('manufacturer')];
                    }
                }
                
                $distributor = null; 
                if(isset($distributors[$form->getValue('distributor')])) {
                    $distributor = $distributors[$form->getValue('distributor')];
                }
   
                $countryId = $form->getValue('country', null);
                $regionId = $this->getParam('region_id', null);
                
                if($countryId == null || $countryId == 0) {
                    $country = null;
                    $region = null;
                    $city = null;
                } else {
                    $country = $countries[$countryId];
                    $region = $this->_checkRegion($this->getParam('region_id', null), $countryId, true);
                    $city = $this->_checkCity($this->getParam('city_id', null), $regionId);
                }  

                $familyId = $form->getValue('family');
                $family = null;
                
                if($familyId != 0) {
                    $family = $families[$form->getValue('family')];
                }
                
                $ranking = $form->getValue('ranking');
                $alcohol = $form->getValue('alcohol');
                $extract = $form->getValue('extract');
                
                try {
                    $uid = Tool::generateUID();
                    $params = array('uid' => $uid);
                    $this->_em->getRepository('\PoradnikPiwny\Entities\BeerSearch')
                              ->searchBeers($uid, 
                                            $form->getValue('query'),
                                            $this->_user,
                                            $ranking['min'],
                                            $ranking['max'],
                                            $alcohol['min'],
                                            $alcohol['max'],
                                            $extract['min'],
                                            $extract['max'],
                                            $form->getValue('malt'),
                                            $form->getValue('type'),
                                            $form->getValue('filtered'),
                                            $form->getValue('pasteurized'),
                                            $form->getValue('flavored'),
                                            $form->getValue('placeOfBrew'),
                                            $family,
                                            $distributor,
                                            $manufacturer,
                                            $country,
                                            $region,
                                            $city
                                      );
                } catch(\PoradnikPiwny\Exception\SearchNotFoundException $exc) {
                    $params['error'] = 'notfound';               
                }
                $url = $this->view->urlGetParams(array('action' => 'result'), $params);
                $this->redirect($url);
            }
        } else {
            if(isset($_GET['uid'])) {
                try {
                    $values = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerSearch')
                                        ->getSearchValues($_GET['uid']);
                    
                    $form->populateBySearchValues($values);
                    $this->_populateManufacturers($form, $values);
                } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
                    $this->_helper->FlashMessenger(array('warning' => 'Rezultat wyszukiwania uległ wygaśnięciu'));              
                    $this->_redirect('/beer');            
                }
            }
        }
        
        $this->view->form = $form;
    }
    
    /* Rezultat wyszukiwania */
    public function resultAction()
    {   
        if(!isset($_GET['uid'])) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora rezulatu wyszukiwania'));              
            $this->_redirect('/beer');
        }
        
        $uid = $_GET['uid'];
        $this->view->uid = $uid;
        
        $menu = $this->_navigation->findById('admin_beer_searchresult');
        $menu->setVisible(true);
        $menu->setActive(true);
        $params = array(
            'uid' => $uid
        );
        $url = $this->view->urlGetParams(null, $params); 
        $menu->setUri($url); 
        
        if(isset($_GET['error']))
        {
            if($_GET['error'] == 'notfound')
            {
                $this->view->isSearchNotFound = true;
                return;
            }
        }
        
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerSearch');  
        $options = $this->_setupOptionsPaginator($rep);  
        
        try {
            $bCons = $rep->getSearchPaginator($options, $uid, $this->_user);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Rezultat wyszukiwania uległ wygaśnięciu'));              
            $this->_redirect('/beer');            
        }
        
        if($bCons->getTotalItemCount() == 0) {
            $this->view->isSearchNotFound = true;
            return;
        }
        
        $this->view->bCons = $bCons;
    }
    
    /* -------------------------------------- */

    public function getcitiesAction()
    {
        $this->_checkAjaxConnection();
        
        $id = $this->getParam('regionId', null);
        $query = $this->getParam('query', '');
        $region = $this->_checkRegion($id, null);

        $data_cities = $this->_em->getRepository('\PoradnikPiwny\Entities\City')
                                 ->getAssocSortedCities($region, $query, 5);
        
        echo \Zend_Json::encode($data_cities);
        die();
    }
    
    public function getregionsAction()
    {
        $this->_checkAjaxConnection();
        
        $id = $this->getParam('countryId', null);
        $query = $this->getParam('query', '');
        $country = $this->_checkCountry($id);
        
        $data_regions = $this->_em->getRepository('\PoradnikPiwny\Entities\Region')
                                  ->getAssocSortedRegions($country, $query);

        echo \Zend_Json::encode($data_regions);  
        die();
    }
    
    public function getmanufacturersAction()
    {
        $this->_checkAjaxConnection();
        
        $id = $this->getParam('id', null);
        $distributor = $this->_checkDistributor($id);
        
        $data_manufac = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                                  ->getAssocSortedManufacturers($distributor);
        
        echo \Zend_Json::encode($data_manufac);
    }
    
    public function rankingAction()
    {
        $this->_checkAjaxConnection(); 
        
        $beerId = $this->getParam('id', null);
        $beer = $this->_checkBeer($beerId);
        
        $rank = $this->getParam('rank', null);
        $checkRank = $this->getParam('checkRank',null);

        if($checkRank) {
            echo \Zend_Json::encode(
                    $this->_em->getRepository('\PoradnikPiwny\Entities\BeerRanking')
                              ->getRankingByBeerIdAndUserId($beerId, $this->_user->getId()));
        } else if($rank!=null) {
            $this->_em->getRepository('\PoradnikPiwny\Entities\BeerRanking')
                      ->updateRankingAndAvg($beer,$this->_user,$rank);
            echo \Zend_Json::encode($beer->getRankingAvg());
        }
        die();
    }

    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $cityId
     * @param int $regionId
     * @return PoradnikPiwny\Entities\City
     */
    protected function _checkCity($cityId, $regionId)
    {
        try {
            $city = $this->_em->getRepository('\PoradnikPiwny\Entities\City')
                              ->getCityByIdAndRegionId($cityId, $regionId);
        } catch(\Exception $exc) {
            return null;
        }
        
        return $city;
    }
    
    /**
     * @param int $regionId
     * @param int $countryId
     * @param boolean $forceNull
     * @return PoradnikPiwny\Entities\Region
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
            return null;
        } 
        
        return $region;            
    }
    
    /**
     * @param int $countryId
     * @return PoradnikPiwny\Entities\Country
     */
    protected function _checkCountry($countryId)
    {
        try {
            $country = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                                 ->getCountryById($countryId);
        } catch(\Exception $exc) {
            return null;
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
     * @return \PoradnikPiwny\Entities\Beer
     */
    protected function _checkBeer($id)
    {   
        try {
            $beer = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                              ->getBeerById($id); 
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora piwa'));              
            $this->_redirect('/beer');            
        } catch(PoradnikPiwny\Exception\BeerNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dane piwo nie istnieje'));
            $this->_redirect('/beer');            
        }
        
        return $beer;
    }
    
    protected function _checkDistributors()
    {
        $distributors = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                                  ->getNotEmptySortedDistributors(); 
        
        if(empty($distributors))
        {
            $this->_helper->FlashMessenger(array('warning' => 'Przed dodaniem piwa dodaj dystrybutora'));
            $this->_redirect('/beerdistributor/add');
            return;            
        }
        
        return $distributors;
    }
    
    protected function _checkManufacturers($distributor)
    {
        $manufacturers = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                                   ->getSortedManufacturers($distributor); 
        if(empty($manufacturers))
        {
            $this->_helper->FlashMessenger(array('warning' => 'Przed dodaniem piwa przypisz wytwórcę do dystrybutora'));
            $this->_redirect('/beerdistributor/index');
            return;            
        }
        
        return $manufacturers;
    }
    
    protected function _checkFormValues($form)
    {
        if(isset($_POST['country']))
        {
            if($_POST['country'] != 0)
            {
                $form->enableRegionField();
            }
        }
        if(isset($_POST['region']))
        {
            if(!empty($_POST['region']))
            {
                $form->enableCityField();
            }
        }
    }
    
    protected function _populateManufacturers($form, 
                                                \PoradnikPiwny\Entities\BeerSearch $bs = null)
    {
        $_dis = null;
        if($bs == null)
        {
            if(isset($_REQUEST['distributor'])) 
            {
                $_dis = $_REQUEST['distributor'];
            }
        } else {
            $distributor = $bs->getDistributor();
            if($distributor != null)
            {
                $_dis = $distributor->getId();
            }
        }
        if($_dis != null)
        {       
            $manufacturers = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                                       ->getSortedManufacturers($_dis);
            if(!empty($manufacturers))
                $form->populateByManufacturers($manufacturers);
            
            return $manufacturers;
        }
        
        return null;
    } 
}