<?php

ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);
date_default_timezone_set("America/New_York");
require 'synchro.class.php';

$synchro = new Synchro();

if (isset($_GET["action"])) {
    
    switch($_GET["action"]) {
        
        case "clear":
            $synchro->clear();
            die ("clear ok");
            break;
        
        case "push":
            if (isset($_GET["gps_lat"]) && isset($_GET["gps_lng"])) {
                $synchro->push($_GET["gps_lat"], $_GET["gps_lng"]);
                die ("push ok");
            } else {
                die ("GPS lat or GPS lng is not set!");
            }                        
            break;
        
        default:
            die ("bad action");
        
    }
}

die ("not action selected!");
