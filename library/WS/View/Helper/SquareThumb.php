<?php

namespace WS\View\Helper;

require_once LIBRARY_PATH . '/PhpThumb/ThumbLib.inc.php'; 

class SquareThumb extends \Zend_View_Helper_Abstract 
{        
    public function squareThumb($imagePath, $size, $tmpPath, $httpPath) 
    {
        $info = pathinfo($imagePath);
        $file = md5('hexagon:/' . $size . '/' . $imagePath) . '.png';
        $filePath = rtrim($tmpPath, '/') . '/' . $file;
        if (!file_exists($filePath)) { 
            if (!is_dir($tmpPath)) mkdir($tmpPath);

            $thumb = \PhpThumbFactory::create($imagePath);
            $thumb->adaptiveResize($size, $size);
            $thumb->save($filePath);
        }
        $httpPath = rtrim($httpPath) . '/' . $file;

        return $httpPath;
    }
}