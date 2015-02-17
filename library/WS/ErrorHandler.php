<?php

namespace WS;

class ErrorHandler
{
    public function registerHandler()
    {
        set_error_handler(array($this, 'phpError'));
    }	

    public function phpError($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);	
    }

    public function phpException(\Exception $exception)
    {
        $msg = 'Uncaught exception "%s" with message "%s" in %s:%s ; '
                    . 'Stack trace: ' . '%s ; '
                    . 'Useragent: ' . $_SERVER['HTTP_USER_AGENT'] . ' ; ' 
                    . 'Ip: ' . $_SERVER['REMOTE_ADDR'] . ' ; '
                    . 'Date: ' . date('d.m.Y H:i:s') . ' ; ';

        $msg = sprintf(
                $msg,
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString()
        );
        error_log($msg, 4);
    }
}
