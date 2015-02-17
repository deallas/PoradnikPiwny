<?php

namespace WS\View\Helper;

require_once LIBRARY_PATH . '/PhpThumb/ThumbLib.inc.php'; 

class Thumb extends \Zend_View_Helper_Abstract 
{        
    public function thumb($imagePath, $width, $height, $tmpPath, $httpPath) 
    {
        $info = pathinfo($imagePath);
        $file = md5(':/' . $width . '/' . $height . '/' . $imagePath) . '.' . $info['extension'];
        $filePath = rtrim($tmpPath, '/') . '/' . $file;
        if (!file_exists($filePath)) { 
            if (!is_dir($tmpPath)) mkdir($tmpPath);

            $thumb = \PhpThumbFactory::create($imagePath);
            $thumb->resize($width, $height);  
            $thumb->save($filePath);
        }
        $httpPath = rtrim($httpPath) . '/' . $file;

        return $httpPath;
    }

}