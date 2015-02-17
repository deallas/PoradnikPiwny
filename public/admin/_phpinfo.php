<?php

if(!(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) || PHP_SAPI == 'cli')) die('Hack attempt');

phpinfo(); 