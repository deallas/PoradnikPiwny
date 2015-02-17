<?php 

header("HTTP/1.0 500 Internal Server Error");  
$displayException = (isset($displayException) && $exception instanceof Exception) ? $displayException : false;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>500 - Application Error</title>
        <link href="<?php echo \WS\Tool::getDomain('/css/error-page.css') ?>" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="error_info_table">
            <div id="error_info_vcenter">
                <?php if ($displayException): ?>
                <div id="error_info_exception">
                    <h2>500 - Application Error</h2>
                    <br />	
                    <h4>Exception information:</h4>
                    <p><span>Message:</span> <?php echo $exception->getMessage() ?></p>

                    <h4>Stack trace:</h4>
                    <pre><?php echo $exception->getTraceAsString() ?></pre>

                    <h4>Request Parameters:</h4>
                    <pre>POST: <?php echo var_export($_POST) ?></pre>
                    <pre>GET: <?php echo var_export($_GET) ?></pre>
                </div>
                <?php else: ?>
                <div id="error_info_hcenter">
                    <h1>500 - Application Error</h1>
                </div>
                <?php endif ?>
            </div>
        </div>
    </body>
</html>