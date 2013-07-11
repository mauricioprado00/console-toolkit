<?php

ini_set('display_errors', '1');
error_reporting(-1);
ini_set("date.timezone", "America/Argentina/Cordoba");
include("../lib/CommandLineRouterAbstract.php");
include("../lib/CommandLineRouterFront.php");
include("../lib/CommandLineRouterStandard.php");
include("../lib/CommandLineRequest.php");
include("../lib/CommandLineDispatcher.php");
include("../lib/CommandLineAction.php");


//var_dump($GLOBALS, getopt("", array("some::"))) 

$request = new CommandLineRequest();
$router = new CommandLineRouterFront();
$standard = new CommandLineRouterStandard();

$router->addRouter("standard", $standard);

// adding module
$standard->addModule("customer", "customer", "customer");

// preparing dispatcher
$dispatcher = new CommandLineDispatcher();
$dispatcher->addBasePath(dirname(__FILE__) . '/modules/{!module}/controllers/{!controller}Controller.php', "{!module}_{!controller}Controller");

// http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Model/App.php ver metodo run
// http://svn.magentocommerce.com/source/branches/1.7/app/code/core/Mage/Core/Controller/Varien/Front.php ver metodo dispatch

$router->addDispatchListener(array($dispatcher, "dispatch"));
$router->addDispatchListener(function ($eventData){
    echo "this is yet another dispatch listener " . __FILE__.__LINE__ . " \n";
});


$router->dispatch($request);
