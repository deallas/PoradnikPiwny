<?php

if (PHP_SAPI != 'cli')
{
    exit('Uruchom skrypt w konsoli');
}

$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$hash_alg = array(
    1 => 'md5',
    2 => 'sha1',
    3 => 'sha128',
    4 => 'sha256',
    5 => 'sha512'
);
$pass = '';
$pre_pass = '';
$post_pass = '';

while(1)
{
    fwrite($stdout, 'Wybierz typ hashu [1-5]:' . PHP_EOL);
    fwrite($stdout, '1) MD5' . PHP_EOL);
    fwrite($stdout, '2) SHA1' . PHP_EOL);
    fwrite($stdout, '3) SHA128' . PHP_EOL);
    fwrite($stdout, '4) SHA256' . PHP_EOL);
    fwrite($stdout, '5) SHA512' . PHP_EOL);
    fscanf($stdin, "%d\n", $number);

    if($number > 5 || $number < 1)
    {
        fwrite($stdout, 'Wybierz liczbe od 1 do 5');
    } else {
        break;
    }
}

fwrite($stdout, 'Wybierz haslo:' . PHP_EOL);
$pass = trim(fgets($stdin));

fwrite($stdout, 'Wybierz wartosc przed haslem:' . PHP_EOL);
$pre_pass = trim(fgets($stdin));

fwrite($stdout, 'Wybierz wartosc po hasle:' . PHP_EOL);
$post_pass = trim(fgets($stdin));

$hash = hash($hash_alg[$number], $pre_pass . $pass . $post_pass);
fwrite($stdout, 'Wygenerowany hash:' . PHP_EOL);
fwrite($stdout, $hash . PHP_EOL);

fclose($stdin);
fclose($stdout);
