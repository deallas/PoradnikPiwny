<?php

namespace WS\Config\Writer;

class IniPhp extends \Zend_Config_Writer_Ini
{
    public function render()
    {
        $string = ';<?php die("Hack attempt"); ?>' . PHP_EOL;
        $string .= parent::render();

        return $string;
    }
}