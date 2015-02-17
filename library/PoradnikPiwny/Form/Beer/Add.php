<?php

namespace PoradnikPiwny\Form\Beer;

use WS\Validate\Doctrine\NoRecordExists,
    WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    WS\Filter\HtmlPurifier as HtmlPurifierFilter,
    PoradnikPiwny\Entities\Beer,
    WS\Filter\Float as FloatFilter,
    WS\Tool;

class Add extends \WS\Form
{
    const ALLOWED_TAGS = 'p,b,i,u,em,strong,li,ul,ol,a[href]';
    
    protected $_c_prices = 0;
    protected $_prices = array();
    protected $_currencies = array();
    
    public function init() 
    {
        $isEdit = (bool)$this->getParam('isEdit');
        $id = $this->getParam('id');
        $beer = $this->getParam('beer');
        
        $exclude = array();
        if($isEdit)
        {
            $exclude = array(
                array(
                    'property' => 'id',
                    'value' => $id
                )
            );
        }
        
    	$name = new \Zend_Form_Element_Text('name');
        $name->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Nazwa piwa:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 50), true),
       	     	 	array(new NoRecordExists(array(
                            'class' => '\PoradnikPiwny\Entities\Beer',
                            'property' => 'name',
                            'exclude' => $exclude
                        )), true)
       	     	 ));
       	$name->getValidator('NoRecordExists')->setMessage('Piwo o danej nazwie istnieje już w bazie', NoRecordExists::ERROR_ENTITY_EXISTS);    		  	

        // --------->
               
        $status = new \Zend_Form_Element_Select('status');
        $status->setLabel('Status:')
             ->addMultiOptions(array(
                 Beer::STATUS_AKTYWNY => 'Aktywny',
                 Beer::STATUS_NIEAKTYWNY => 'Nieaktywny',
                 Beer::STATUS_DO_ZATWIERDZENIA => 'Do zatwierdzenia',
                 Beer::STATUS_ZAWIESZONY => 'Zawieszony'
             ));        
        
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
        
        $description_pl = new \Zend_Form_Element_Textarea('description_pl');
        $description_pl->setFilters(array(
                            'StringTrim', 
                            new HtmlPurifierFilter(array(
                                'HTML.Allowed' => self::ALLOWED_TAGS
                            ))
                       ))
                       ->setRequired(true)
                       ->setLabel('Opis:')
                       ->setAttrib('class', 'input-textarea multilang-textarea')
                       ->setAttrib('lang', 'PL');

        // --------->
        
        $description_en = new \Zend_Form_Element_Textarea('description_en');
        $description_en->setFilters(array(
                            'StringTrim', 
                            new HtmlPurifierFilter(array(
                                'HTML.Allowed' => self::ALLOWED_TAGS
                            ))
                       ))
                       ->setRequired(true)
                       ->setAttrib('class', 'input-textarea multilang-textarea')
                       ->setAttrib('lang', 'EN');
        
        // --------->
        
        $this->_prices = array();
        $this->_currencies = $this->getParam('currencies');
        
        $validators = array(
            new \WS\Validate\PriceAndSizeOfBottle(
                $this->_currencies,
                array(
                    'number' => array(
                        'min' => 0,
                        'max' => 99.99,
                        'decimals' => 2
                    ),
                    'sizeOfBottle' => array(
                        'min' => 0,
                        'max' => 9999,
                        'decimals' => 0
                    ),
                ))
        );
        if($beer == null) {
            $request = $this->getParam('request');
            foreach($request as $k => $v)
            {
                if(!Tool::startsWith($k, 'price_')) {
                    continue;
                }
                $price = new \WS\Form\Element\PriceAndSizeOfBottle('price_' . $this->_c_prices++, $this->_currencies);
                $price->setValue($v);
                $price->setLabel('[' . $this->_c_prices . ']');
                $price->setValidators($validators);

                $this->_prices[] = $price;
            } 
        } else {       
            foreach($beer->getBeerPrices() as $p)
            {
                $price = new \WS\Form\Element\PriceAndSizeOfBottle('price_' . $this->_c_prices++, $this->_currencies);
                $price->setValue(array(
                    'number' => $p->getValue(),
                    'currency' => $p->getCurrency()->getId(),
                    'sizeOfBottle' => $p->getSizeOfBottle()
                ));  
                $price->setLabel('[' . $this->_c_prices . ']');
                $price->setValidators($validators);
            
                $this->_prices[] = $price;
            }
        }
        
        // --------->
        
        $addPrice = new \Twitter_Bootstrap_Form_Element_Button('addPrice',
        array(
            'class' => 'btn-mini',
            'icon'  => 'plus',
            'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
        ));
        $addPrice->setLabel('Dodaj cenę'); 

        // --------->
        
        $alcohol = new \Zend_Form_Element_Text('alcohol');
        $alcohol->setValidators(array(
                    new \Zend_Validate_Float(),
                    new \Zend_Validate_Between(0,99.99)
                ))
                ->setFilters(array(new FloatFilter(array('decimals' => 2))))
                ->setAttrib('placeholder', '0.00')
                ->setAttrib('append', '%')
                ->setLabel('Zawartość alkoholu:');

        // --------->
        
        $extract = new \Zend_Form_Element_Text('extract');
        $extract->setValidators(array(
                    new \Zend_Validate_Float(),
                    new \Zend_Validate_Between(0,99.99)
                ))
                ->setFilters(array(new FloatFilter(array('decimals' => 2))))
                ->setAttrib('placeholder', '0.00')
                ->setAttrib('append', '%')
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
        
        $flavored = new \Twitter_Bootstrap_Form_Element_Radio('flavored');
        $flavored->setLabel('Smakowe:')
                ->addMultiOptions(array(
                     Beer::SMAKOWE_NIEWIEM => 'Nie wiem',
                     Beer::SMAKOWE_TAK => 'Tak',
                     Beer::SMAKOWE_NIE => 'Nie'
                 ))
                 ->setValue(Beer::SMAKOWE_NIEWIEM);
        
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
        $region->setLabel('Region:')
               ->setAttrib('disabled', 'disabled')
               ->setAttrib('autocomplete', 'off');
        
        // --------->
        
        $region_id = new \Zend_Form_Element_Hidden('region_id');
        $region_id->addValidator(new \Zend_Validate_Int());
        
        // --------->

        $city = new \Zend_Form_Element_Text('city');
        $city->setLabel('Miasto:')
             ->setAttrib('disabled', 'disabled')
             ->setAttrib('autocomplete', 'off');
        
        // --------->

        $city_id = new \Zend_Form_Element_Hidden('city_id');
        $city_id->addValidator(new \Zend_Validate_Int());
        
        // --------->
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beer"; return false;' );
        
        // --------->
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));

        if($isEdit) {
            $this->setAction('/beer/edit/id/' . $id);
            $submit->setLabel('Edytuj piwo');
        } else {
            $this->setAction('/beer/add');
            $submit->setLabel('Dodaj piwo');
        }
        
        $this->addElements(array(
            $name,
            $description_pl,
            $description_en,
            $status
        ));
        
        $this->addElements($this->_prices);
        
        $this->addElements(array(
            $addPrice,
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
            $filtered,
            $pasteurized,
            $flavored,
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
       
        $this->setAttrib('class', $this->getAttrib('class') . ' form_auto_regions_cities');
        $this->setMethod('post');
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
    
    public function populateByBeer(Beer $beer)
    {
        $this->name->setValue($beer->getName()); 
        
        foreach($beer->getBeerTranslations() as $translation)
        {
            $field = 'description_' . strtolower($translation->getLang()); 
            $this->$field->setValue($translation->getDescription());
        }
        
        $this->alcohol->setValue($beer->getAlcohol());
        $this->extract->setValue($beer->getExtract());
        $this->type->setValue($beer->getType());
        $this->malt->setValue($beer->getMalt());
        $this->filtered->setValue($beer->getFiltered());
        $this->pasteurized->setValue($beer->getPasteurized());
        $this->flavored->setValue($beer->getFlavored());
        $this->placeOfBrew->setValue($beer->getPlaceOfBrew());
        $this->status->setValue($beer->getStatus());

        $family = $beer->getFamily();
        if($family != null)
        {
            $this->family->setValue($beer->getFamily()->getId());
        }
        
        $country = $beer->getCountry();
        if($country != null)
        {
            $this->country->setValue($country->getId());
            $this->enableRegionField();
            $region = $beer->getRegion();
            if($region != null)
            {
                $this->region->setValue($region->getName());
                $this->region_id->setValue($region->getId());
                $this->enableCityField();
                $city = $beer->getCity();
                if($city != null)
                {
                    $this->city->setValue($city->getName());
                    $this->city_id->setValue($city->getId());
                }
            }
        }
        
        
        $manufacturer = $beer->getManufacturer();
        if($manufacturer != null)
        {
            $this->manufacturer->setValue($manufacturer->getId());
            
            $distributor = $manufacturer->getDistributor();
            $this->distributor->setValue($distributor->getId());
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
    
    public function getPrices()
    {
        $data = array();
        for($i = 0; $i < $this->_c_prices; $i++)
        {
            $price = $this->_prices[$i]->getValue();
            $price['currency'] = $this->_currencies[$price['currency']];
            $data[] = $price;
        }
        
        return $data;
    }
}