<?php

ini_set('display_errors', '1');
error_reporting(-1);
ini_set("date.timezone", "America/Argentina/Cordoba");
include("../lib/xmlmerge.php");



$x = new XmlMerge();
$x->setSource("config.xml");
$x->mergeByFile("add.xml");
echo $x->asXml();
/*
*/