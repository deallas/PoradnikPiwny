<?php

namespace PoradnikPiwny\Form\Beer;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\BeerSearch,
    PoradnikPiwny\Entities\Beer;

class Search extends \WS\Form
{
    public function init() 
    {
    	$query = new \Zend_Form_Element_Text('query');
        $query->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Wyszukiwana fraza:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 50), true)
       	     	 ));

        // --------->
        
        $ranking = new \WS\Form\Element\MinMax('ranking');
        $ranking->setValidators(array(
                    new \WS\Validate\MinMax(array(
                        'values' => array(
                            'min' => 0,
                            'max' => 5
                        ),
                        'decimals' => 1
                    ))
                ))
                ->setValue(array(
                    'placeholder' => array(
                        'min' => '0.0',
                        'max' => '5.0'
                    )
                ))
                ->setLabel('Ocena:');
        
        // --------->
        /*
        $min_price = new \Zend_Form_Element_Text('min_price');
        $min_price->setValidators(array(
                    new \Zend_Validate_Float(),
                    new \Zend_Validate_Between(0,100)
                ))
                ->setFilters(array(new \WS\Filter\Float()))
                ->setAttrib('placeholder', '0.00')
                ->setAttrib('append', 'zł')
                ->setLabel('Min. cena:');

        // --------->
        
        $max_price = new \Zend_Form_Element_Text('max_price');
        $max_price->setValidators(array(
                    new \Zend_Validate_Float(),
                    new \Zend_Validate_Between(0,100)
                ))
                ->setFilters(array(new \WS\Filter\Float()))
                ->setAttrib('placeholder', '99.99')
                ->setAttrib('append', 'zł')
                ->setLabel('Max. cena:');
        */
        // --------->
        
        $distributors = array(0 => '');
        foreach($this->getParam('distributors') as $distributor)
        {
            $distributors[$distributor->getId()] = $distributor->getName();
        }
        
        $distributor = new \Zend_Form_Element_Select('distributor');
        $distributor->setLabel('Dystrybutor:')
             ->addMultiOptions($distributors);
        
        // --------->
        
        $manufacturer = new \Zend_Form_Element_Select('manufacturer');
        $manufacturer->setLabel('Wytwórca:');
        $manufacturer->setAttrib('disabled', 'disabled');
        
        // --------->

        $alcohol = new \WS\Form\Element\MinMax('alcohol');
        $alcohol->setValidators(array(
                    new \WS\Validate\MinMax(array(
                        'values' => array(
                            'min' => 0,
                            'max' => 99.99
                        ))
                    )
                ))
                ->setValue(array(
                    'symbol' => '%', 
                    'placeholder' => array(
                        'min' => '0.00',
                        'max' => '99.99'
                    )
                ))
                ->setLabel('Zawartość alkoholu:');

        // --------->

        $extract = new \WS\Form\Element\MinMax('extract');
        $extract->setValidators(array(
                    new \WS\Validate\MinMax(array(
                        'values' => array(
                            'min' => 0,
                            'max' => 99.99
                        ))
                    )
                ))
                ->setValue(array(
                    'symbol' => '%', 
                    'placeholder' => array(
                        'min' => '0.00',
                        'max' => '99.99'
                    )
                ))
                ->setLabel('Ekstrakt:');

        // --------->
              
        $malt = new \Zend_Form_Element_Select('malt');
        $malt->setLabel('Słód:')
             ->addMultiOptions(array(
                 0 => '',
                 Beer::SLOD_JECZMIENNY => 'Jęczmienny',
                 Beer::SLOD_PSZENNY => 'Pszenny',
                 Beer::SLOD_INNY => 'Inny'
             ));       
        
        // --------->
        
        $families = $this->getParam('families');
        $arr = array_reverse($families, true);
        $arr[0] = '';
        $families = array_reverse($arr, true); 
        
        $family = new \Zend_Form_Element_Select('family');
        $family->setLabel('Rodzina piwa:')
             ->addMultiOptions($families); 
        
        // --------->
        
        $type = new \Zend_Form_Element_Select('type');
        $type->setLabel('Typ piwa:')
             ->addMultiOptions(array(
                 0 => '',
                 Beer::PIWO_BEZALKOHOLOWE => 'Bezalkoholowe',
                 Beer::PIWO_LEKKIE => 'Lekkie',
                 Beer::PIWO_PELNE => 'Pełne',
                 Beer::PIWO_MOCNE => 'Mocne'
             ));
        
        // --------->
        
        $flavored = new \Twitter_Bootstrap_Form_Element_Radio('flavored');
        $flavored->setLabel('Smakowe:')
                 ->addMultiOptions(array(
                     Beer::SMAKOWE_NIEWIEM => 'Nie wiem',
                     Beer::SMAKOWE_TAK => 'Tak',
                     Beer::SMAKOWE_NIE => 'Nie'
                 ))
                 ->setValue(Beer::SMAKOWE_NIEWIEM);
        
        // --------->
        
        $filtered = new \Twitter_Bootstrap_Form_Element_Radio('filtered');
        $filtered->setLabel('Filtrowane:')
                 ->addMultiOptions(array(
                     Beer::FILTOWANE_NIEWIEM => 'Nie wiem',
                     Beer::FILTROWANE_TAK => 'Tak',
                     Beer::FILTROWANE_NIE => 'Nie'
                 ))
                 ->setValue(Beer::FILTOWANE_NIEWIEM);

        // --------->
        
        $pasteurized = new \Twitter_Bootstrap_Form_Element_Radio('pasteurized');
        $pasteurized->setLabel('Pasteryzowane:')
                ->addMultiOptions(array(
                     Beer::PASTERYZOWANE_NIEWIEM => 'Nie wiem',
                     Beer::PASTERYZOWANE_TAK => 'Tak',
                     Beer::PASTERYZOWANE_NIE => 'Nie'
                 ))
                 ->setValue(Beer::PASTERYZOWANE_NIEWIEM);
        
        // --------->   
        
        $placeOfBrew = new \Zend_Form_Element_Select('placeOfBrew');
        $placeOfBrew->setLabel('Miejsce warzenia:')
             ->addMultiOptions(array(
                 0 => '',
                 Beer::MIEJSCE_WARZENIA_BROWAR => 'Browar',
                 Beer::MIEJSCE_WARZENIA_RESTAURACJA => 'Restauracja',
                 Beer::MIEJSCE_WARZENIA_DOM => 'Dom'
             ));
        
        // --------->   
        
        $countries = array(0 => '');
        foreach($this->getParam('countries') as $country)
        {
            $countries[$country->getId()] = $country->getName();
        }
        
        $country = new \Zend_Form_Element_Select('country');
        $country->setLabel('Kraj pochodzenia:')
             ->addMultiOptions($countries);         
        
        // --------->
        
        $region = new \Zend_Form_Element_Text('region');
        $region->setLabel('Województwo:')
               ->setAttrib('disabled', 'disabled')
               ->setAttrib('autocomplete', 'off');
        
        // --------->
        
        $region_id = new \Zend_Form_Element_Hidden('region_id');
        
        // --------->

        $city = new \Zend_Form_Element_Text('city');
        $city->setLabel('Miasto:')
             ->setAttrib('disabled', 'disabled')
             ->setAttrib('autocomplete', 'off');
        
        // --------->

        $city_id = new \Zend_Form_Element_Hidden('city_id');
        
        // --------->

        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beer"; return false;' );;
        
        // --------->
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        $submit->setLabel('Szukaj');
        
        $this->addElements(array(
            $query,
            $ranking,
            /*$min_price,
            $max_price,*/
            $distributor,
            $manufacturer,
            $alcohol,
            $extract,
            $malt,
            $family,
            $type,
            $country,
            $region,
            $region_id,
            $city,
            $city_id,
            $flavored,
            $filtered,
            $pasteurized,
            $placeOfBrew,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        $this->setMethod('get');
        $this->setAction('/beer/search');
    }
    
    public function populateByManufacturers($manufacturers)
    {
        $manufac_data = array();
        foreach($manufacturers as $manufacturer)
        {
            $manufac_data[$manufacturer->getId()] = $manufacturer->getName(); 
        }
        
        $this->manufacturer->addMultiOptions($manufac_data); 
        $this->manufacturer->setAttrib('disabled', null); 
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerSearch $bs
     * @throws SearchResultNotFoundException
     */
    public function populateBySearchValues(BeerSearch $bs)
    {
        $this->query->setValue($bs->getQuery());
        
        $ranMin = $bs->getRankingMin();
        if($ranMin == 0) {
            $ranMin = '';
        } else {
            $ranMin = number_format($ranMin, 2, '.', '');
        }

        $ranMax = $bs->getRankingMax();
        if($ranMax == 0) {
            $ranMax = '';
        } else {
            $ranMax = number_format($ranMax, 2, '.', '');
        }
        
        $this->ranking->setValue(array(
            'min' => $ranMin,
            'max' => $ranMax
        ));
        /*$this->min_price->setValue($cacheData['min_price']);
        $this->max_price->setValue($cacheData['max_price']);*/
        $dis = $bs->getDistributor();
        if($dis != null)
        {
            $this->distributor->setValue($dis->getId());
            $man = $bs->getManufacturer();
            if($man != null)
            {
                $this->manufacturer->setValue($man->getId());   
            }
        }

        $alcMin = $bs->getAlcoholMin();
        if($alcMin == 0) {
            $alcMin = '';
        } else {
            $alcMin = number_format($alcMin, 2, '.', '');
        }
        
        $alcMax = $bs->getAlcoholMax();
        if($alcMax == 0) {
            $alcMax = '';        
        } else {
            $alcMax = number_format($alcMax, 2, '.', '');
        }
        
        $this->alcohol->setValue(array(
            'min' => $alcMin, 
            'max' => $alcMax
        ));
        
        $extMin = $bs->getExtractMin();
        if($extMin == 0) {
            $extMin = '';
        }  else {
            $extMin = number_format($extMin, 2, '.', '');
        }
        
        $extMax = $bs->getExtractMax();
        if($extMax == 0) {
            $extMax = '';  
        } else {
            $extMax = number_format($extMax, 2, '.', '');
        }
        $this->extract->setValue(array(
            'min' => $extMin,
            'max' => $extMax
        ));
        $this->malt->setValue($bs->getMalt());
        $this->type->setValue($bs->getType());
        
        $cou = $bs->getCountry();
        
        if($cou != null)
        {
            $this->country->setValue($cou->getId()); 
            $this->enableRegionField();

            $reg = $bs->getRegion();
            if($reg != null)
            {
                $this->region_id->setValue($reg->geId());
                $this->region->setValue($reg->getName());           
                $this->enableCityField();

                $cit = $bs->getCity();
                if($cit != null) {
                    $this->city_id->setValue($cit->getId()); 
                    $this->city->setValue($cit->getName()); 
                }
            }
        }

        $this->flavored->setValue($bs->getFlavored());
        $this->filtered->setValue($bs->getFiltered());
        $this->pasteurized->setValue($bs->getPasteurized());
        $this->placeOfBrew->setValue($bs->getPlaceofbrew());
        
        $fam = $bs->getFamily();
        if($fam != null)
        {
            $this->family->setValue($fam->getId());
        }
    }
    
    public function enableRegionField()
    {
        $this->region->setAttrib('disabled', null);
    }
    
    public function enableCityField()
    {
        $this->city->setAttrib('disabled', null);
    }
}