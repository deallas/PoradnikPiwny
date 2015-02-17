<?php

define('IS_STATIC_FILE', true);

require_once '../../_static.php';

serve(dirname(__FILE__) . '/style.css', 
       array(
           'hexagon-empty' => \WS\Tool::getStaticUrl('/images/hexagon-empty.png'),
           'hexagon-logo' => \WS\Tool::getStaticUrl('/images/hexagon-logo.png')
       ),
       'text/css'
);