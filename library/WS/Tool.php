<?php

namespace WS;

class Tool 
{	
    /**
     * Konwertuje znaki w danym wyrazie z UTF-8 na ASCII
     * 
     * @param strung $str
     * @param string $unknown
     * @return string|boolean 
     */
    public static function utf8ToAscii($str, $unknown = '?') 
    {
        $UTF8_TO_ASCII = array();
        $UTF8_TO_ASCII_DB = dirname(__FILE__) . '/Tool/Utf8ToAscii';

        if ( strlen($str) == 0 ) { return ''; }

        $len = strlen($str);
        $i = 0;

        ob_start();

        while ( $i < $len ) 
        {    
            $ord = NULL;
            $increment = 1;

            $ord0 = ord($str{$i});

            # Much nested if /else - PHP fn calls expensive, no block scope...

            # 1 byte - ASCII
            if ( $ord0 >= 0 && $ord0 <= 127 ) {    
                $ord = $ord0;
                $increment = 1;
            } else {
                # 2 bytes
                $ord1 = ord($str{$i+1});
                if ( $ord0 >= 192 && $ord0 <= 223 ) {
                    $ord = ( $ord0 - 192 ) * 64 + ( $ord1 - 128 );
                    $increment = 2;
                } else {
                    # 3 bytes
                    $ord2 = ord($str{$i+2});
                    if ( $ord0 >= 224 && $ord0 <= 239 ) {
                        $ord = ($ord0-224)*4096 + ($ord1-128)*64 + ($ord2-128);
                        $increment = 3;
                    } else {
                        # 4 bytes
                        $ord3 = ord($str{$i+3});
                        if ($ord0>=240 && $ord0<=247) {  
                            $ord = ($ord0-240)*262144 + ($ord1-128)*4096 
                                + ($ord2-128)*64 + ($ord3-128);
                            $increment = 4; 
                        } else { 
                            ob_end_clean();
                            trigger_error("utf8_to_ascii: looks like badly formed UTF-8 at byte $i");
                            return false;
                        }   
                    }
                }
            }

            $bank = $ord >> 8;

            # If we haven't used anything from this bank before, need to load it...
            if ( !array_key_exists($bank, $UTF8_TO_ASCII) ) {

                $bankfile = $UTF8_TO_ASCII_DB. '/'. sprintf("x%02x",$bank).'.php';
                if ( file_exists($bankfile) ) {
                    # Load the appropriate database
                    if ( !include  $bankfile ) {
                        ob_end_clean();
                        trigger_error("utf8_to_ascii: unable to load $bankfile");
                    } 
                } else {
                    # Some banks are deliberately empty
                    $UTF8_TO_ASCII[$bank] = array();

                }
            }

            $newchar = $ord & 255;

            if ( array_key_exists($newchar, $UTF8_TO_ASCII[$bank]) ) {
                echo $UTF8_TO_ASCII[$bank][$newchar];
            } else {
                echo $unknown;
            }

            $i += $increment;

        }

        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }

    /**
     * Generuje losowy ciąg znaków z danej tablicy
     * 
     * @param int $len
     * @param array $tab
     * @return string
     */
    public static function randomStringWithArray($len, array $tab) 
    {
        $chars = implode('', $tab);		
        $password = '';
        for ($i = 0; $i < $len; ++$i)
                $password .= substr($chars, (mt_rand() % strlen($chars)), 1);

        return $password;
    }

    /**
     * Generuje losowy ciąg znaków o danej długości
     * 
     * @param int $len
     * @return string 
     */
    public static function randomString($len) 
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $password = '';
        for ($i = 0; $i < $len; ++$i)
                $password .= substr($chars, (mt_rand() % strlen($chars)), 1);

        return $password;
    }

    /**
     * Generuje losowy ciąg cyfer o danej długości
     * 
     * @param int $len
     * @return string 
     */
    public static function randomInt($len) 
    {
        $chars = '0123456789';

        $password = '';
        for ($i = 0; $i < $len; ++$i)
                $password .= substr($chars, (mt_rand() % strlen($chars)), 1);

        return $password;	
    }

    public static function dateToTimestamp($date) 
    {
        $result = explode('/', $date);
        return mktime(0,0,0,$result[1], $result[0], $result[2]);
    }

    /**
     * Zamienia z danej tablicy/wyrazu wszystkie wywołania \' na '
     * 
     * @param string|array $value
     * @return string|array
     */
    public static function stripslashesArray($value) 
    {
        if (is_array($value)) {
            $value = array_map(array('WS_Tool', 'stripSlashesArray'), $value);
        } else {
            $value = stripslashes($value);
        }
        return $value;
    }

    /**
     * Wyłącza "magiczne cudzysłowia" w PHP 
     */
    public static function disableMagicQuotes() 
    {
        if (get_magic_quotes_gpc()) {
            $_GET = self::stripslashesArray($_GET);
            $_POST = self::stripslashesArray($_POST);
            $_REQUEST = self::stripslashesArray($_REQUEST);
            $_COOKIE = self::stripslashesArray($_COOKIE);
        }
    }

    public static function decodeFilters(\Zend_Form $form, $stripslashes = false) 
    {
        $elements = $form->getElements();
        foreach($elements as $element) 
        {
            if(!$element instanceof \Zend_Form_Element_Text 
                    && $element instanceof \Zend_Form_Element_Textarea)
            {
                continue;
            }     
            if ($stripslashes) $element->addFilter(new \WS\Filter\Stripslashes());
            $filter = $element->getFilter('Htmlspecialchars');
            if (!empty($filter))
            {
                $filter->setEncode(false);
            }	 
        }	
    }

    /**
     * Usuwa rekurencyjnie dany katalog
     * 
     * @param string $dir
     */
    public static function rmdirRecursive($dir) 
    {
        $files = @scandir($dir);
        if (empty($files)) return;
            @array_shift($files);    // remove '.' from array
            @array_shift($files);    // remove '..' from array

        foreach ($files as $file) 
        {
            $file = $dir . '/' . $file;
            if (is_dir($file)) {
                self::rmdir_recursive($file);
                @rmdir($file);
            } else {
                @unlink($file);
            }
        }
        @rmdir($dir);
    }
    
    /**
     * Zwraca maksymalny możliwy rozmiar uploadowanego pliku
     * 
     * @param int $size
     * @return int 
     */
    public static function getMaxUploadedSize($size)
    {
        $upload_max_filesize = self::getBytesByBit(ini_get('upload_max_filesize'));
        $postdata_max_size = self::getBytesByBit(ini_get('post_max_size'));
        if($size < $postdata_max_size && $size < $upload_max_filesize) return $size;
        else if($postdata_max_size < $size && $postdata_max_size < $size) return $postdata_max_size;
        
        return $upload_max_filesize;
    }
    
    /**
     * Konwertuje zapis rozmiaru w postaci np 5M na ilość bajtów
     * 
     * @param string $val
     * @return int 
     */
    public static function getBytesByBit($val) 
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 't':
            case 'tb':
                $val *= 1024;
            case 'g':
            case 'gb':
                $val *= 1024;
            case 'm':
            case 'mb':
                $val *= 1024;
            case 'k':
            case 'kb':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Konwertuje ilość bajtów na zapis rozmiaru w postaci np 5M
     * 
     * @param int $bytes
     * @return string 
     */
    public static function getXBytesByBit($bytes)
    {
        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return $bytes ? round($bytes/pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $filesizename[$i] : '0 Bytes';        
    }
    
    public static function generateUID($namespace = '')
    {
        static $hash = '';
        $uid = uniqid("", true);
        $data = $namespace;
        if(isset($_SERVER['REQUEST_TIME'])) {
            $data .= $_SERVER['REQUEST_TIME'];
        }
        if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $data .= $_SERVER['HTTP_USER_AGENT'];
        }
        if(isset($_SERVER['LOCAL_ADDR'])) {
            $data .= $_SERVER['LOCAL_ADDR'];
        }
        if(isset($_SERVER['LOCAL_PORT'])) {
            $data .= $_SERVER['LOCAL_PORT'];
        }
        if(isset($_SERVER['REMOTE_ADDR'])) {
            $data .= $_SERVER['REMOTE_ADDR'];
        }
        if(isset($_SERVER['REMOTE_PORT'])) {
            $data .= $_SERVER['REMOTE_PORT'];
        }

        return hash('ripemd128', $uid . $hash . md5($data));        
    }
    
    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    
    public static function getDomain($url = '')
    {
        if(empty($url)) {
            $url = $_SERVER['HTTP_HOST'];
        }
        
        $prefix = substr($url,0,6);
        if($prefix != 'http:/' || $prefix != 'https:') {
            $url = 'http://' . $url;
        }
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
    
    public static function getStaticUrl($url = '', $subdomain = null)
    {
        if(!empty($url))
        {
            $prefix = substr($url,0,6);
            if($prefix == 'http:/') {
                $url = substr($url, 8);
            } elseif($prefix == 'https:') {
                $url = substr($url, 9);
            }
            
            $url = ltrim($url, '/');
        }     
        
        if(defined('DOMAIN_NAME')) {
            $domain = DOMAIN_NAME;
        } else {
            $domain = self::getDomain();
            define('DOMAIN_NAME', $domain);
        }
        
        if($subdomain == null) {
            $subdomain = substr(crc32($url), 1, 1);
        }
        
        return 'http' . (isset($_SERVER['HTTPS'])?'s':'') . '://static'
                      . $subdomain . '.' . $domain . '/' . $url;
    }
    
    /**
     * @return string
     */
    public static function getRealIp()
    {
        $ip = null;
        if(isset($_SERVER['REMOTE_ADDR']))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        {
            if($_SERVER['HTTP_X_FORWARDED_FOR'] != '') 
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }

        return $ip;
    }
    
    /**
     * Pobiera wybrane parametry modelu
     * 
     * @param mixed $entity
     * @param array $a_params
     * @param array $g_params
     * @return array
     */
    public static function getParamsFromEntity($entity, $a_params, $g_params)
    {
        if(empty($g_params)) {      
            $params = $a_params;
        } else {
            $params = array();
            foreach($g_params as $k => $v)
            {
                if(in_array($k, $a_params)) {
                    $params[$k] = $g_params[$k];
                } elseif(in_array($v, $a_params)) {
                    $params[$v] = $v;
                }
            }
        }

        $r_params = array();
        foreach($params as $k => $v)
        {
            if(is_array($params[$k])) {
                $f_name = 'get' . ucfirst($k);
                $obj =  $entity->$f_name();
                if($obj == null) {
                    $r_params[$k] = null;
                } else {
                    $r_params[$k] = $obj->toArray($v);
                }
            } elseif(is_string($params[$k])) {
                $f_name = 'get' . ucfirst($v);
                $r_params[$v] = $entity->$f_name();
            } elseif(is_callable($params[$k])) {
                $f_name = 'get' . ucfirst($k);
                $obj =  $entity->$f_name();
                if($obj == null) {
                    $r_params[$k] = null;
                } else {
                    $r_params[$k] = $params[$k]($obj, $entity);
                }
            }
        }
        
        return $r_params;
    }
    
    /**
     * Sprawdza czy zadeklarowano stałe w klasie
     * 
     * @param mixed $class
     * @param mixed $c
     * @throws \Exception
     */
    public static function checkAbstractConstants($class, $c) 
    {
        $refelction = new \ReflectionClass ( $class );
        $constantsForced = $refelction->getConstants ();
        foreach ( $constantsForced as $constant => $value ) {
            if (constant ( "$c::$constant" ) == "abstract") {
                throw new \Exception ( "Undefined $constant in " . (string)$c );
            }
        }
    }
}