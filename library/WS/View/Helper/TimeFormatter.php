<?php

namespace WS\View\Helper;

use WS\Exception;

class TimeFormatter {

    public function timeFormatter($time, $type = 's') 
    {
    	$translate = \Zend_Form::getDefaultTranslator();
    	switch ($type) {
    		case 's':
    			$m = 1;
    			break;
    		case 'm':
    			$m = 60;
    			break;
    		case 'h':
    			$m = 3600;
    			break;
    		case 'd':
    			$m = 7*3600;
    			break;
    		case 'w':
    			$m = 7*3600*2;
    			break;
    		default:
    			$m = 1;
    	}
    	$time = $time * $m;
		switch ($time) {
			case 60:
				return $translate->_('1 minute');
			case 120:
				return $translate->_('2 minutes');
			case 300:
				return $translate->_('5 minutes');
			case 600:
				return $translate->_('10 minutes');
			case 900:
				return $translate->_('15 minutes');
			case 1800:
				return $translate->_('30 minutes');
			case 2700:
				return $translate->_('45 minutes');
			case 3600:	
				return $translate->_('1 hour');
			case 7200:	
				return $translate->_('2 hours');
			case 18000:	
				return $translate->_('5 hours');	
			case 43200:	
				return $translate->_('12 hours');
			case 86400:	
				return $translate->_('1 day');
			case 172800:	
				return $translate->_('2 days');	
			case 432000:	
				return $translate->_('5 days');
			case 604800:	
				return $translate->_('1 week');		
			default:
				throw new Exception('Invalid time');
		}
    }
}