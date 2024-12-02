<?php

// ubah ke direktori vignt kalian
// change to your vignt's directory
chdir('../');

require 'autoload.vignt';
require "routes/routes.virts.php";

$usr_ur = $_SERVER['REQUEST_URI'];
$temp_usr = '';
$return = '';

for ($i = 0; $i < strlen($usr_ur); $i++) {
    if ($usr_ur[$i] != '?') {
        $temp_usr .= $usr_ur[$i];
    } else {
        break;
    }
}

$usr_ur = $temp_usr;

routes($usr_ur);

