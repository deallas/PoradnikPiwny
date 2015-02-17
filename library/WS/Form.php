<?php

namespace WS;

use WS\Exception,
    WS\Form\AbstractParameterForm;

/**
 * Base on \Twitter_Bootstrap_Form 
 */
abstract class Form extends AbstractParameterForm
{
    /**
     * Class constants
     */
    const DISPOSITION_HORIZONTAL = 'horizontal';
    const DISPOSITION_INLINE     = 'inline';
    const DISPOSITION_SEARCH     = 'search';

    /**
     * @var string
     */
    protected $_disposition;
    
    /**
     * @var boolean
     */
    protected $_prefixesInitialized = false;    
    
    /**
     * @var string
     */
    protected static $_baseUrl = false;

    protected static $_file_decorators = array(
        array('FieldSize'),
        array('Addon'),
        array('File'),
        array('ElementErrors'),
        array('Description', array('tag' => 'p', 'class' => 'help-block')),
        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
        array('Label', array('class' => 'control-label')),
        array('Wrapper')
    );
    
    protected static $_field_decorators = array(
        array('FieldSize'),
        array('ViewHelper'),
        array('Addon'),
        array('ElementErrors'),
        array('Description', array('tag' => 'p', 'class' => 'help-block')),
        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
        array('Label', array('class' => 'control-label')),
        array('Wrapper')
    );
    
    protected static $_button_decorators = array(
        array('FieldSize'),
        array('ViewHelper'),
        array('Addon'),
        array('ElementErrors'),
        array('Description', array('tag' => 'p', 'class' => 'help-block'))
    );
    
    /**
     * @param array $params
     * @param array|\Zend_Config $options
     * @param string $disposition 
     */
    public function __construct(array $params = array(), $options = array(), $disposition = self::DISPOSITION_HORIZONTAL)
    {
        $this->_initializePrefixes();
        $this->setDisposition($disposition);

        switch($this->_disposition)
        {
            case self::DISPOSITION_INLINE:
                $this->setElementDecorators(array(
                    array('FieldSize'),
                    array('ViewHelper'),
                    array('Description', array('tag' => 'p', 'class' => 'help-block')),
                    array('Addon')
                ));
                break;
            case self::DISPOSITION_SEARCH:
                $renderButton = true;
                if (isset($options['renderInNavBar']) && true === $options['renderInNavBar']) {
                    $this->_removeClassNames('form-search');
                    $classes = array('navbar-search');
                    if (isset($options['pullItRight']) && true === $options['pullItRight']) {
                        $classes[] = 'pull-right';
                        unset($options['pull-right']);
                    }

                    $this->_addClassNames($classes);
                    unset($options['renderInNavBar']);
                    $renderButton = false;
                }

                // Add the search input
                $inputName = isset($options['inputName']) ? $options['inputName'] : 'searchQuery';
                $this->addElement('text', $inputName, array(
                    'class' => 'search-query'
                ));

                if ($renderButton) {
                    $buttonLabel = isset($options['submitLabel']) ? $options['submitLabel'] : 'Submit';
                    $this->addElement('submit', 'submit', array(
                        'class' => 'btn',
                        'label' => $buttonLabel
                    ));
                }
                break;
            case self::DISPOSITION_HORIZONTAL:
            default:    
                $this->setElementDecorators(self::$_button_decorators);
                
                break;
        }
        
        $this->setDisableLoadDefaultDecorators(true);
        
        parent::__construct($params, $options);
        
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }

    /**
     * @param string $action
     * @return \WS\Form 
     */
    public function setAction($action)
    {
        if(empty($action)) {
            $url = $_SERVER['REQUEST_URI'];
        } else {
            $url = rtrim($this->getBaseUrl(), '/') .$action;
        }
        parent::setAction($url);

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if(!self::$_baseUrl)
        {
            $fc = \Zend_Controller_Front::getInstance();
            self::$_baseUrl = $fc->getBaseUrl();	
        }

        return self::$_baseUrl;
    }

    /**
     * @param type $baseUrl
     * @throws type 
     */
    public static function setBaseUrl($baseUrl)
    {
        if (!is_string($baseUrl)) throw Exception('Base URL isn\'t string');
        self::$_baseUrl = $baseUrl;	
    }
    
    /**
     * 
     * @param string|\Zend_Form_Element $element
     * @param string $name
     * @param array $options
     * @return \WS\Form
     * @throws \Zend_Form_Exception 
     */
    public function addElement($element, $name = null, $options = null)
    {
        if (is_string($element)) {
            if (null === $name) {
                throw new \Zend_Form_Exception('Elements specified by string must have an accompanying name');
            }

            if (is_array($this->_elementDecorators)) {
                if (null === $options) {
                    $options = array('decorators' => $this->_elementDecorators);
                } elseif ($options instanceof Zend_Config) {
                    $options = $options->toArray();
                }
                if (is_array($options)
                    && !array_key_exists('decorators', $options)
                ) {
                    $options['decorators'] = $this->_elementDecorators;
                }
            }

            $this->_elements[$name] = $this->createElement($element, $name, $options);
        } elseif ($element instanceof \Zend_Form_Element) {
            $prefixPaths              = array();
            $prefixPaths['decorator'] = $this->getPluginLoader('decorator')->getPaths();
            if (!empty($this->_elementPrefixPaths)) {
                $prefixPaths = array_merge($prefixPaths, $this->_elementPrefixPaths);
            }

            if (null === $name) {
                $name = $element->getName();
            }

            $this->_elements[$name] = $element;
            $this->_elements[$name]->addPrefixPaths($prefixPaths);
            
            $this->_fixDecorators($element, $name);
        } else {
            throw new \Zend_Form_Exception('Element must be specified by string or Zend_Form_Element instance');
        }

        $this->_order[$name] = $this->_elements[$name]->getOrder();
        $this->_orderUpdated = true;
        $this->_setElementsBelongTo($name);

        return $this;
    }
    
    /**
     * FIX - Bootstrap decorator for Zend_Form_Element
     * 
     * @param \Zend_Form_Element $element
     * @param string $name
     */
    private function _fixDecorators(\Zend_Form_Element $element, $name)
    {
        if($this->_disposition == self::DISPOSITION_HORIZONTAL)
        {
            if($element instanceof \Zend_Form_Element_Hidden || $element instanceof \Zend_Form_Element_Hash)
            {
                $this->_elements[$name]->setDecorators(array(
                    array('ViewHelper')
                ));
                return;          
            }
            if($element instanceof \Zend_Form_Element_Submit || $element instanceof \Zend_Form_Element_Button) {
                $this->_elements[$name]->setDecorators(self::$_button_decorators);
                return;
            }
            if($element instanceof \Zend_Form_Element_File) {                
                $this->_elements[$name]->setDecorators(self::$_file_decorators);
                return;
            }
        }
        $this->_elements[$name]->setDecorators(self::$_field_decorators);
    }
    
    protected function _initializePrefixes()
    {
        if (!$this->_prefixesInitialized)
        {
            if (null !== $this->getView())
            {
                $this->getView()->addHelperPath(
                    'Twitter/Bootstrap/View/Helper',
                    'Twitter_Bootstrap_View_Helper'
                );
            }
            
            $this->addPrefixPath(
                'Twitter_Bootstrap_Form_Element',
                'Twitter/Bootstrap/Form/Element',
                'element'
            );
            
            $this->addPrefixPath(
                '\WS\Form\Element',
                LIBRARY_PATH . '/WS/Form/Element',
                'element'
            );
            
            $this->addElementPrefixPath(
                'Twitter_Bootstrap_Form_Decorator',
                'Twitter/Bootstrap/Form/Decorator',
                'decorator'
            );
            
            $this->addElementPrefixPath(
                '\WS\Form\Decorator',
                LIBRARY_PATH . '/WS/Form/Decorator',
                'decorator'
            );
            
            $this->addDisplayGroupPrefixPath(
                'Twitter_Bootstrap_Form_Decorator',
                'Twitter/Bootstrap/Form/Decorator'
            );
            
            $this->setDefaultDisplayGroupClass('Twitter_Bootstrap_Form_DisplayGroup');
            
            $this->_prefixesInitialized = true;
        }
    }

    /**
     * Adds default decorators if none are specified in the options and then calls Zend_Form::createElement()
     * (non-PHPdoc)
     * @see Zend_Form::createElement()
     */
    public function createElement($type, $name, $options = null)
    {
        // If we haven't specified our own decorators, add the default ones in.
        if (is_array($this->_elementDecorators)) {
            if (null === $options) {
                $options = array('decorators' => $this->_elementDecorators);
            } elseif ($options instanceof Zend_Config) {
                $options = $options->toArray();
            }

            if ( is_array($options) && !array_key_exists('decorators', $options) ) {
                $options['decorators'] = $this->_elementDecorators;
            }
        }
        
        return parent::createElement($type, $name, $options);
    }
    
    /**
     * @param string $disposition
     */
    public function setDisposition($disposition)
    {
        if (
            in_array(
                $disposition,
                array(
                    self::DISPOSITION_HORIZONTAL,
                    self::DISPOSITION_INLINE,
                    self::DISPOSITION_SEARCH
                )
            )
        ) {
            $this->_disposition = $disposition;
            $this->_addClassNames('form-' . $disposition);
        } else {
            $this->_disposition = self::DISPOSITION_HORIZONTAL;
        }
    }

    /**
     * @return string
     */
    public function getDisposition()
    {
        return $this->_disposition;
    }
    
    /**
     * Adds a class name
     *
     * @param string $classNames
     */
    protected function _addClassNames($classNames)
    {
        $classes = $this->_getClassNames();

        foreach ((array) $classNames as $className) {
            $classes[] = $className;
        }

        $this->setAttrib('class', trim(implode(' ', $classes)));
    }

    /**
     * Removes a class name
     *
     * @param string $classNames
     */
    protected function _removeClassNames($classNames)
    {
        $classes = $this->getAttrib('class');

        foreach ((array) $classNames as $className) {
            if (false !== strpos($classes, $className)) {
                str_replace($className . ' ', '', $classes);
            }
        }
    }

    /**
     * Extract the class names from a Zend_Form_Element if given or from the
     * base form
     *
     * @param \Zend_Form_Element $element
     * @return array
     */
    protected function _getClassNames(\Zend_Form_Element $element = null)
    {
        if (null !== $element) {
            return explode(' ', $element->getAttrib('class'));
        }

        return explode(' ', $this->getAttrib('class'));
    }
}
