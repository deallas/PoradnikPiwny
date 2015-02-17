<?php

namespace WS\View\Helper;

class FormPriceAndSizeOfBottle extends \Zend_View_Helper_FormElement
{
    public function formPriceAndSizeOfBottle($name, $value = null, $attribs = null)
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
        
        $n_value = '';
        $c_value = '';
        $s_value = '';
        
        if(isset($attribs['number']))
        {
            $n_value = $attribs['number'];
            unset($attribs['number']);
        }
        
        if(isset($attribs['currency']))
        {
            $c_value = $attribs['currency'];
            unset($attribs['currency']);
        }
        
        if(isset($attribs['sizeOfBottle']))
        {
            $s_value = $attribs['sizeOfBottle'];
            unset($attribs['sizeOfBottle']);
        }
        
        $currencies = array();
        if(isset($attribs['currencies']))
        {
            $currencies = $attribs['currencies'];
            unset($attribs['currencies']);
        }
        
        $class = '';
        if(isset($attribs['class']))
        {
            $class = ltrim($attribs['class'] . ' ');
        }
        
        $curHtml = '';
        $curSymbol = null;
        foreach($currencies as $currency)
        {              
            $curHtml .= '<option value="' . $currency->getId() . '"'
                        . ' symbol="' . $currency->getSymbol() . '"';
            
            if($curSymbol == null)
            {
                $curSymbol = $currency->getSymbol();
            }
            
            if(($id = $currency->getId()) == $c_value)
            {
                $curSymbol = $currency->getSymbol();
                $curHtml .= ' selected="selected"';
            }
            
            $curHtml .= '>' . $currency->getName() . '</option>';
        }
        
        /*---------------------------------------------------------------------*/
        
        $attribs['class'] = $class . 't_price_number';
        
        $xhtml = '<div class="input-append t_price_number_container">
                  <input type="text"'
                . ' name="' . $this->view->escape($name) . '[number]"'
                . ' id="' . $this->view->escape($name) . '_number"'
                . ' value="' . $n_value . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag
                . '<span class="add-on add-on-number">' . $curSymbol . '</span></div>';
        
        /*---------------------------------------------------------------------*/
        
        $attribs['class'] = $class . 's_price_currency';        
                
        $xhtml .= '<select name="' . $this->view->escape($name) . '[currency]"'
                    . ' id="' . $this->view->escape($name) . '_currency"'
                    . $disabled
                    . $this->_htmlAttribs($attribs)
                    . '>' . $curHtml . '</select>';             
        
        /*---------------------------------------------------------------------*/
        
        $attribs['class'] = $class . 't_price_sizeOfBottle';

        $xhtml .= '<div class="input-append t_price_sizeOfBottle_container">
                    <input type="text"'
                . ' name="' . $this->view->escape($name) . '[sizeOfBottle]"'
                . ' id="' . $this->view->escape($name) . '_sizeOfBottle"'
                . ' value="' . $s_value . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag
                . '<span class="add-on add-on-sizeOfBottle">ml</span></div>';
        
        return $xhtml;
    }
}