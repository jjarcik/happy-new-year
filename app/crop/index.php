<?php

error_reporting(E_ALL);

require './maptiler.class.php';

$maptiler = new MapTiler();
$maptiler->setSource("2048x2048_b.png");
$maptiler->setBaseZoom(3);
$maptiler->setOutputDir("map3");
$maptiler->run();

echo "ok";