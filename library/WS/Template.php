<?php

namespace WS;

class Template 
{
    /**
     * @var string 
     */
    protected $tmpl;
    
    /**
     * @var array 
     */
    private $data = array();

    /**
     *
     * @param string $tpl
     * @throws \WS\Template\Exception 
     */
    public function __construct($tpl) 
    {
        if (!file_exists($tpl)) {
            throw new \Exception('File "' . $tpl . '" not exists.');	
        }
        if(is_string($tpl)) 
        {
            $this->tmpl = file_get_contents($tpl);
        } else {
            throw new \Exception('Filename must be a string.');
        }
    }

    /**
     * Dodanie wartości do szablonu
     * 
     * @param string $name
     * @param string $value 
     */
    public function add($name, $value = '') {
        if (is_array($name)) {
            $this->data = array_merge($this->data, $name);
        } else if (!empty($value)) {
            $this->data[$name] = $value;
        }
    }

    /**
     * Wyrenderowanie szablonu z danymi
     * 
     * @return string
     */
    public function render() {
        return preg_replace('/<<([^>]+)>>/e', '$this->data["\\1"]',
                $this->tmpl);
    }
}