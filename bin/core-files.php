<?php

define('BIN_DIR', dirname(__FILE__));
define('PROJECT_DIR', realpath(BIN_DIR . '/../'));
define('VENDOR_MERGE_FILE', PROJECT_DIR . '/vendor.php');

$cli = (substr(php_sapi_name(), 0, 3) == 'cli') ? true : false;
$newline = $cli ? PHP_EOL : '<br />';

$files = file_get_contents(BIN_DIR . '/core-files.txt');

$buffer = '';
foreach($lines = preg_split( '/\r\n|\r|\n/', $files) as $file)
{
    $f = @fopen(PROJECT_DIR . $file, 'r');
    echo $file . ' - ';
    if ($f) {
        while (!feof($f)) {
            $buffer .= fgets($f, 4096);
        }
        fclose($f);
        echo 'OK';
    } else {
        echo 'READ ERROR';
    }
    echo $newline;
}
echo $newline;
$f = @fopen(VENDOR_MERGE_FILE, 'w+');
echo VENDOR_MERGE_FILE . ' - ';
//$header = '<?php' . "\r\n" . 'if(!defined(\'VENDOR_MERGE_FILE\')) die("Hack attempt");' . "\r\n";
$header = '<?php' . "\r\n";
if ($f) {
    $buffer = str_replace('<?php', '', $buffer);
    fwrite($f, $header . $buffer);
    fclose($f);
    echo 'OK';
} else {
    echo 'WRITE ERROR';
}