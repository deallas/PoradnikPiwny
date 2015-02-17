<?php

if(!(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) 
        || defined('IS_STATIC_FILE'))) die('Hack attempt');

define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../../library'));

require_once LIBRARY_PATH . '/WS/Tool.php';
require_once LIBRARY_PATH . '/WS/Template.php';

define('DOMAIN_NAME', \WS\Tool::getDomain());

/**
 * Zwraca sparsowany plik na podstawie odpowiednich parametrów
 * 
 * @param string $file
 * @param array $params
 * @param string $contentType
 * @param int $cacheOffset
 * @param string $encoding
 */
function serve($file, $params, $contentType, 
               $cacheOffset = null, $encoding = 'UTF-8')
{
    if(extension_loaded('zlib')) 
    {
        ob_start('ob_gzhandler');
    } 
    
    $tpl = new \WS\Template($file);
    $tpl->add($params);
    
    header("Content-type: " . $contentType);
    header("Content-Type: " . $contentType . ";text/css; charset: " . $encoding);
    header("Cache-Control: must-revalidate");
    $offset = ($cacheOffset != null) ? $cacheOffset : 3600;
    $expire = "Expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
    header($expire);

    echo $tpl->render();
    
    if(extension_loaded('zlib'))
    {
        ob_end_flush();
    }
}