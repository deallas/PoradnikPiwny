<?php

namespace WS\View\Helper;

require_once LIBRARY_PATH . '/PhpThumb/ThumbLib.inc.php'; 

class HexagonThumb extends \Zend_View_Helper_Abstract 
{       
    public function hexagonThumb($imagePath, $size, $tmpPath, $httpPath) 
    {
        $info = pathinfo($imagePath);
        $file = md5('hexagona:/' . $size . '/' . $imagePath) . '.png';
        $filePath = rtrim($tmpPath, '/') . '/' . $file;
        if (!file_exists($filePath)) { 
            if (!is_dir($tmpPath)) mkdir($tmpPath);
            
            $mask = $this->_loadMask($size, $tmpPath);
            $xSize = imagesx($mask);
            
            $thumb = \PhpThumbFactory::create($imagePath);
            $thumb->adaptiveResize($xSize, $size);
            $image = $thumb->getWorkingImage();
            
            $this->_useMask($image, $mask);
            //$this->_useGreyFilter($image);

            imagepng($image, $filePath);
            imagedestroy($image);
        }
        $httpPath = rtrim($httpPath) . '/' . $file;

        return $httpPath;
    }
    
    /*private function _loadMask($size, $tmpPath)
    {
        $file = md5('hexagon-mask:/' . $size) . '.png';
        $filePath = rtrim($tmpPath, '/') . '/../' . $file;
        if (!file_exists($filePath)) { 
            $mask = $this->_createMask($size);
            imagepng($mask, $filePath);
            imagedestroy($mask);
        }
        
        return imagecreatefrompng($filePath);
    }*/
    
    
    private function _loadMask($size, $tmpPath)
    {
        $file = md5('hexagon-mask:/' . $size) . '.png';
        $filePath = rtrim($tmpPath, '/') . '/../' . $file;
        if (!file_exists($filePath)) 
        { 
            $fMask = STATIC_PATH . '/images/hex.png';
            $thumb = \PhpThumbFactory::create($fMask);
            $image = $thumb->getOldImage();
            $xSize = round(imagesx($image) * $size / imagesy($image));
            $thumb->resize($xSize, $size);
            $thumb->save($filePath);   
        }
        
        return imagecreatefrompng($filePath);
    }
    /*
    private function _createMask($size)
    {
        $size = $size / 2;
        
        $xSide = $size*sin(deg2rad(60));
        $ySide = $size*sin(deg2rad(30));
        $boardWidth = $xSide*1*2;
        $boardHeight = $size + $ySide + $ySide;

        $image = imagecreate($boardWidth, $boardHeight);
        imagesavealpha($image, true);
        $bg = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $bg);
        $fg = imagecolorallocate($image, 255, 255, 255);

        // draw the board
        $values = array();

        $x1 = 0;
        $y1 = $boardHeight/2;
        $y1Dif = $size/2;

        $x2 = $x1 + $xSide;
        $y2 = $y1;
        $y2Dif = $ySide;

        $x3 = $x2 + $xSide;

        $values[] = $x1;
        $values[] = $y1 - $y1Dif;
        $values[] = $x2;
        $values[] = $y2 - $y1Dif - $y2Dif;

        $values[] = $x2;
        $values[] = $y2 - $y1Dif - $y2Dif;
        $values[] = $x3;
        $values[] = $y1 - $y1Dif;

        $values[] = $x3;
        $values[] = $y1 + $y1Dif;
        $values[] = $x2;
        $values[] = $y2 + $y1Dif + $y2Dif;

        $values[] = $x2;
        $values[] = $y2 + $y1Dif + $y2Dif;
        $values[] = $x1;
        $values[] = $y1 + $y1Dif;

        imagefilledpolygon($image, $values, count($values)/2, $fg);
        
        return $image;
    }
    */
    
    private function _useMask(&$picture, $mask) 
    {
        $xSize = imagesx( $picture );
        $ySize = imagesy( $picture );
        $newPicture = imagecreatetruecolor( $xSize, $ySize );
        imagesavealpha( $newPicture, true );
        imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

        if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
            $tempPic = imagecreatetruecolor( $xSize, $ySize );
            imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
            imagedestroy( $mask );
            $mask = $tempPic;
        }

        for( $x = 0; $x < $xSize; $x++ ) {
            for( $y = 0; $y < $ySize; $y++ ) {
                $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
                $alpha = 127 - floor( $alpha[ 'red' ] / 2 );
                $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
                imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
            }
        }

        imagedestroy( $picture );
        
        $picture = $newPicture;
    }
    
    /*
    private function _useGreyFilter($image)
    {
        imagefilter($image, IMG_FILTER_GRAYSCALE);
    }
    */
}