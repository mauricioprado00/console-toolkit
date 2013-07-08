<?php

ini_set('display_errors', '1');
error_reporting(-1);
ini_set("date.timezone", "America/Argentina/Cordoba");
include("../lib/CommandLineRouterAbstract.php");
include("../lib/CommandLineRouterFront.php");
include("../lib/CommandLineRouterStandard.php");
include("../lib/CommandLineRequest.php");



//var_dump($GLOBALS, getopt("", array("some::"))) 

$request = new CommandLineRequest();
$router = new CommandLineRouterFront();
$standard = new CommandLineRouterStandard();

$router->addRouter("standard", $standard);

// http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Model/App.php ver metodo run
// http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Controller/Varien/Front.php ver metodo dispatch

$router->addDispatchListener(function ($eventData){
    echo "this is the dispatch listener " . __FILE__.__LINE__ . " \n";
    var_dump($eventData);
});


$router->dispatch($request);
