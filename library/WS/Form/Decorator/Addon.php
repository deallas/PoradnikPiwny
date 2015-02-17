<?php

namespace WS\Form\Decorator;

class Addon extends \Twitter_Bootstrap_Form_Decorator_Addon
{
    /**
     * Prepares and renders the item to be appended or prepended
     * 
     * @param mixed $addon            
     */
    protected function _prepareAddon (&$addon)
    {
        $addonClass = 'add-on';
        $content = '';
        if (is_array($addon)) {
            if(isset($addon['class'])) {
                $addonClass .= ' ' . $addon['class'];
            }
            if(isset($addon['iconClass'])) {
                $content = '<i class="' . $addon['iconClass'] . '"></i>';
            }
            if(isset($addon['content'])) {
                $content .= $addon['content'];
            } else {
                $content .= '';
            }         
        } else {
            $content = $addon;
        }
        $addon = '<span class="' . $addonClass . '">' . $content . '</span>';
    }
}
