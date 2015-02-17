<?php

namespace WS\View\Helper;

class FormMinMax extends \Zend_View_Helper_FormElement
{
    public function formMinMax($name, $value = null, $attribs = null)
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
        
        $min_value = '';
        $max_value = '';
        $symbol_value = null;
        $placeholder_min = null;
        $placeholder_max = null;
        
        if(isset($attribs['min']))
        {
            $min_value = $attribs['min'];
            unset($attribs['min']);
        }
        
        if(isset($attribs['max']))
        {
            $max_value = $attribs['max'];
            unset($attribs['max']);
        }
        
        if(isset($attribs['symbol']))
        {
            $symbol_value = $attribs['symbol'];
            unset($attribs['symbol']);
        }
        
        if(isset($attribs['placeholder']))
        {
            $placeholder_value = $attribs['placeholder'];
            if(isset($placeholder_value['min']))
            {
                $placeholder_min = $placeholder_value['min'];
            }
            if(isset($placeholder_value['max']))
            {
                $placeholder_max = $placeholder_value['max'];
            }
            unset($attribs['placeholder']);
        }
        
        $class = '';
        if(isset($attribs['class']))
        {
            $class = ltrim($attribs['class'] . ' ');
        }
        
        /*---------------------------------------------------------------------*/
        
        $attribs['class'] = $class . 't_min';
        
        $xhtml = '<div class="input-append t_min_container">
                  <input type="text"'
                . ' name="' . $this->view->escape($name) . '[min]"'
                . ' id="' . $this->view->escape($name) . '_min"'
                . ' value="' . $min_value . '"';
        
        if($placeholder_min != null)
        {
            $xhtml .= ' placeholder="' . $placeholder_min . '"';
        }
        
        $xhtml .= $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;
        
        if($symbol_value != null)
        {
            $xhtml .= '<span class="add-on add-on-min">' . $symbol_value . '</span>';
        }
        
        $xhtml .= '</div><span class="t_separator">-</span>';
        
        /*---------------------------------------------------------------------*/
        
        $attribs['class'] = $class . 't_max';

        $xhtml .= '<div class="input-append t_max_container">
                    <input type="text"'
                . ' name="' . $this->view->escape($name) . '[max]"'
                . ' id="' . $this->view->escape($name) . '_max"'
                . ' value="' . $max_value . '"';
                
        if($placeholder_max != null)
        {
            $xhtml .= ' placeholder="' . $placeholder_max . '"';
        }
        
        $xhtml .= $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;
        
        if($symbol_value != null)
        {
            $xhtml .= '<span class="add-on add-on-max">' . $symbol_value . '</span>';
        }
        
        $xhtml .= '</div>';
        
        return $xhtml;
    }
}