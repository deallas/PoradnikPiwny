<?php

namespace WS\View\Helper;

class UrlGetParams extends \Zend_View_Helper_Abstract {
        
    public function urlGetParams($params, array $getParams = array(),
            $name = null, $reset = false, $encode = true) 
    {      
        if($params == null) $params = array();
        
        $urlClass = new \Zend_View_Helper_Url();
        $str = $urlClass->url($params, $name, $reset, $encode);
        
        $terms = count($getParams);
        $str .= '?';
        foreach ($getParams as $field => $value)
        {
            $terms--;
            $str .= urlencode($field) . '=' . urlencode($value);
            if ($terms)
            {
                $str .= '&';
            }
        }
        
        return $str;
    }

}