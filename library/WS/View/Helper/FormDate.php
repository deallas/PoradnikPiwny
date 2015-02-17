<?php

namespace WS\View\Helper;

class FormDate extends \Zend_View_Helper_FormElement
{
    public function formDate($name, $value = null, $attribs = null)
    { 	
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof \Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }
        
        $value = array();
        if(isset($attribs['values']))
        {
            $value = $attribs['values'];
            unset($attribs['values']);
        }
        
        $class = '';
        if(!empty($attribs['class']))
        {
            $class = $attribs['class'] . ' ';
        }

        $attribs['class'] = $class . 'date_day';
        $attribs['maxlength'] = 2;
        
        $xhtml = '<input type="text"'
                . ' name="' . $this->view->escape($name) . '[day]"'
                . ' id="' . $this->view->escape($name) . '_day"'
                . ' value="' . (isset($value['day']) ? $value['day'] : '') . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        $xhtml .= '<span>/</span>';
                
        $attribs['class'] = $class . 'date_month';        
                
        $xhtml .= '<input type="text"'
                . ' name="' . $this->view->escape($name) . '[month]"'
                . ' id="' . $this->view->escape($name) . '_month"'
                . ' value="' . (isset($value['month']) ? $value['month'] : '') . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;                

        $xhtml .= '<span>/</span>';
                
        $attribs['class'] = $class . 'date_year';
        $attribs['maxlength'] = 4;
        
        $xhtml .= '<input type="text"'
                . ' name="' . $this->view->escape($name) . '[year]"'
                . ' id="' . $this->view->escape($name) . '"'
                . ' value="' . (isset($value['year']) ? $value['year'] : '') . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;                
                
        return $xhtml;
    }
}