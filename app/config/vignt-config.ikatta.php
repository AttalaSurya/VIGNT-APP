<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
if (php_sapi_name() !== 'cli') {
    set_error_handler('customErrorHandler');
    set_exception_handler('customExceptionHandler');
}
?>