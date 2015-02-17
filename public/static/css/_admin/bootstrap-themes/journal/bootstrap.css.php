<?php

define('IS_STATIC_FILE', true);

require_once '../../../../_static.php';

serve(dirname(__FILE__) . '/bootstrap.css', 
       array(
           'glyphicons-halflings' => \WS\Tool::getStaticUrl('images/glyphicons-halflings.png'),
           'glyphicons-halflings-white' => \WS\Tool::getStaticUrl('images/glyphicons-halflings-white.png')
       ),
       'text/css'
);