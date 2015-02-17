<?php header("HTTP/1.0 404 Not Found");  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>404 - Page not found</title>
        <link href="<?php echo \WS\Tool::getDomain('/css/error-page.css') ?>" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="error_info_table">
            <div id="error_info_vcenter">
                <div id="error_info_hcenter">
                    <h1>404 - Page not found</h1>
                </div>
            </div>
        </div>
    </body>
</html>