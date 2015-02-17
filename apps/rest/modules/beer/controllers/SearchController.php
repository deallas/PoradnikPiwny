<?php

use PoradnikPiwny\Controller\Action\RestAction,
    PoradnikPiwny\Form\Beer\Search as SearchForm,
    WS\Tool;

class Beer_SearchController extends RestAction
{   
    public function getAction()
    {
        $uid = $this->getParam('search_id', null);
        if($uid == null) {
            $this->view->msg = self::NULL_POINTER;
            $this->_response->badRequest();
            return;
        }
        
        /* Numer strony */
        $page = $this->_getParam('page', 1);
        
        /* Ilość elementów na stronie */
        $d_items = 3;
        $items = $this->_getParam('items', $d_items);
        
        if(!in_array($items, array($d_items,5,10)))
        {
            $items = $d_items;
        }
        
        try {
            $paginator = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerSearch')
                                   ->getSearchPaginator($uid, $page, $items, $this->_user);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->view->msg = self::BEER_SEARCH_RESULT_NOT_FOUND;
            $this->_response->notFound();
            return;
        }
        
        if($paginator->getTotalItemCount() == 0) {
            $this->view->msg = self::BEER_SEARCH_NOT_FOUND;
            $this->_response->notFound();
            return;
        }
        
        $data = array();
        
        foreach($paginator as $sCon)
        {
            $data[] = $sCon->getBeer()->toArray(array(
                'id', 
                'name', 
                'rankingAvg', 
                'rankingWeightedAvg', 
                'dateAdded' => $this->_getDateFilter(),
                'distributor' => array(
                    'id',
                    'name'
                ),
                'manufacturer' => array(
                    'id',
                    'name'
                ),
                'image' => array(
                    'id',
                    'title',
                    'path' => $this->_getImageThumbFilter(),
                    'dateAdded' => $this->_getDateFilter()
                )
            ));
        }

        $this->view->beers = $data;
        $this->view->currentPageNumber = $paginator->getCurrentPageNumber();
        $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
        $this->view->totalItemCount = $paginator->getTotalItemCount();
    }
    
    public function postAction()
    {
        $countries = $this->_em->getRepository('\PoradnikPiwny\Entities\Country')
                               ->getSortedCountriesName();
        $families = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                              ->getSortedParents();
        
        $distributors = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                                  ->getSortedDistributors(); 
        
        $form = new SearchForm(array(
            'distributors' => $distributors,
            'countries' => $countries,
            'families' => $families
        ));
        
        $manufacturers = $this->_populateManufacturers($form);
            
        if($form->isValid($_POST))
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
                $this->view->msg = self::BEER_SEARCH_NOT_FOUND;
                $this->_response->notFound();
                return;
            }
            $this->view->result_id = $uid;
        } else {
            $this->view->errors = $form->getMessages();
            $this->view->msg = self::INVALID_FORM_VALUES;
            $this->_response->badRequest();
            return;
        }   
            
        $this->_response->ok();
    }
    
    /* ---------------------------------------------------------------------- */
    
    protected function _populateManufacturers($form, \PoradnikPiwny\Entities\BeerSearch $bs = null)
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
}