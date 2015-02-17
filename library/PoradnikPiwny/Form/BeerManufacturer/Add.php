<?php

namespace PoradnikPiwny\Form\BeerManufacturer;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    WS\Filter\HtmlPurifier as HtmlPurifierFilter,
    PoradnikPiwny\Entities\BeerManufacturer,
    PoradnikPiwny\Validate\Doctrine\BeerManufacturerUnique;

class Add extends \WS\Form
{
    const ALLOWED_TAGS = 'p,b,i,u,em,strong,li,ul,ol,a[href]';
    
    public function init() 
    {
        $isEdit = (bool)$this->getParam('isEdit');
        $id = $this->getParam('id');
        
        $distributorId = $this->getParam('distributorId');
        
    	$name = new \Zend_Form_Element_Text('name');
        $name->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Nazwa wytwórcy:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 50), true),
       	     	 	array(new BeerManufacturerUnique($distributorId, $id), true)
       	     	 ));

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
                       ->setAttrib('class', 'input-textarea multilang-textarea')
                       ->setAttrib('lang', 'EN');
        
        // --------->   
        
        $website = new \Zend_Form_Element_Text('website');
        $website->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Strona WWW:')
       	     	 ->setValidators(array(
                    array(new \Zend_Validate_StringLength(0, 100), true),
                    new \WS\Validate\Uri()
       	     	 ));
        
        // ---------> 
        
        $email = new \Zend_Form_Element_Text('email');
        $email->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Adres email:')
       	     	 ->setValidators(array(
                    array(new \Zend_Validate_StringLength(0, 50), true),
                    new \WS\Validate\SimpleEmailAddress()
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
        
        $address = new \Zend_Form_Element_Text('address');
        $address->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Adres lokalu:')
       	     	 ->setValidators(array(
                    array(new \Zend_Validate_StringLength(0, 100), true)
       	     	 ));
        
        // ---------> 
        
        $longitude = new \Zend_Form_Element_Text('longitude');
        $longitude->addValidator(new \Zend_Validate_Float())
                  ->addFilter(new \WS\Filter\Float(array('decimals' => 5)))
                  ->setLabel('Długość geograficzna:')
                  ->setAttrib('disabled', 'disabled');
        
        // --------->
        
        $latitude = new \Zend_Form_Element_Text('latitude');
        $latitude->addValidator(new \Zend_Validate_Float())
                 ->addFilter(new \WS\Filter\Float(array('decimals' => 5)))
                 ->setLabel('Szerokość geograficzna:')
                 ->setAttrib('disabled', 'disabled');
        
        // --------->
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beermanufacturer/index/id/' . $distributorId . '"; return false;' );;
        
        // --------->
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));

        if($isEdit) {
            $this->setAction('/beermanufacturer/edit/id/' . $id);
            $submit->setLabel('Edytuj wytwórcę');
        } else {
            $this->setAction('/beermanufacturer/add/id/' . $distributorId);
            $submit->setLabel('Dodaj wytwórcę');
        }
        
        $this->addElements(array(
            $name,
            $description_pl,
            $description_en,
            $website,
            $email,
            $country,
            $region,
            $region_id,
            $city,
            $city_id,
            $address,
            $latitude,
            $longitude,
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
    
    public function populateByManufacturer(BeerManufacturer $manufacturer)
    {
        $this->name->setValue($manufacturer->getName());
        $this->website->setValue($manufacturer->getWebsite());
        $this->email->setValue($manufacturer->getEmail());

        foreach($manufacturer->getManufacturerTranslations() as $translation)
        {
            $field = 'description_' . strtolower($translation->getLang()); 
            $this->$field->setValue($translation->getDescription());
        }
        
        $this->latitude->setValue($manufacturer->getLatitude());
        $this->longitude->setValue($manufacturer->getLongitude());
        
        $country = $manufacturer->getCountry();
        if($country != null)
        {
            $this->country->setValue($country->getId());
            $this->enableRegionField();
            $region = $manufacturer->getRegion();
            if($region != null)
            {
                $this->region->setValue($region->getName());
                $this->region_id->setValue($region->getId());
                $this->enableCityField();
                $city = $manufacturer->getCity();
                if($city != null)
                {
                    $this->city->setValue($city->getName());
                    $this->city_id->setValue($city->getId());
                }
            }
        }
        
        $this->address->setValue($manufacturer->getAddress());
    }
         
    public function enableRegionField()
    {
        $this->region->setAttrib('disabled', null);
    }
    
    public function enableCityField()
    {
        $this->city->setAttrib('disabled', null);
        $this->latitude->setAttrib('disabled', null);
        $this->longitude->setAttrib('disabled', null);
    }
}