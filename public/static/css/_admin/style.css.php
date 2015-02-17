<?php

define('IS_STATIC_FILE', true);

require_once '../../_static.php';

serve(dirname(__FILE__) . '/style.css', 
       array(),
       'text/css'
);