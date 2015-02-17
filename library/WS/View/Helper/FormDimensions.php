<?php

namespace WS\View\Helper;

class FormDimensions extends \Zend_View_Helper_FormElement
{
    public function formDimensions($name, $value = null, $attribs = null)
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
        if(isset($attribs['class']))
        {
            $class = ltrim($attribs['class'] . ' ');
        }

        $attribs['class'] = $class . 'dimensions_width';
        $attribs['maxlength'] = 4;

        $xhtml = '<input type="text"'
                . ' name="' . $this->view->escape($name) . '[width]"'
                . ' id="' . $this->view->escape($name) . '_width"'
                . ' value="' . (isset($value['width']) ? $value['width'] : '') . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        $xhtml .= '<span>x</span>';
                
        $attribs['class'] = $class . 'dimensions_height';
           
        $xhtml .= '<input type="text"'
                . ' name="' . $this->view->escape($name) . '[height]"'
                . ' id="' . $this->view->escape($name) . '_height"'
                . ' value="' . (isset($value['height']) ? $value['height'] : '') . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;                
              
                
        return $xhtml;
    }
}