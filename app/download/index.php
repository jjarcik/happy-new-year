<?php
error_reporting(E_ALL);
require './downloader.class.php';
$downloader = new DownloaderMap();
$downloader->setUrl("http://otile3.mqcdn.com/tiles/1.0.0/sat/");
$downloader->setImageType("jpg");
$downloader->setSaveToFolder("../maps/map5/");
$downloader->setMaxZoom(5);
$downloader->run();
echo "ok";  