<?php

namespace WS\Filter;

class Stripslashes implements \Zend_Filter_Interface 
{
    /**
     * Zwraca zmienną $value po sparsowaniu przez funkcje stripslashes
     *
     * @param  string $value
     * @return string
     */
    public function filter($value) 
    {
    	return stripslashes($value);
    }
}