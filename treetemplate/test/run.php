<?php

ini_set('display_errors', '1');
error_reporting(-1);
ini_set("date.timezone", "America/Argentina/Cordoba");
include("../lib/TreeTemplate.php");



$x = new TreeTemplate();
$x->loadTemplateFromFile("moduletemplate/template.xml");
$x->setReplaces(array(
    "Package" => "Somepackage",
    "Module" => "Themodule",
    "module" => "themodule",
    "version" => "1.1.1.1",
));
$x->generateFromTemplate("target");
/*
*/