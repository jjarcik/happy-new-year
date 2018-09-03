<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require './mergemap.class.php';
$merger = new MapMerge();

$merger->setInputDir("../maps/map3/");
$merger->setOutputDir("../maps/");
$merger->setOutputFile("map3_4.jpg");
$merger->setImageType("png");
$merger->setSaveAs("png");
$merger->setBaseZoom(4);


$merger->run();
die("<a href='" . $merger->getOutputFileLink() . "'>" . $merger->getOutputFileLink() . "</a>");
