<?php
session_start();

date_default_timezone_set('Africa/Cairo');

require dirname(__FILE__) . '/config.php';
require dirname(__FILE__) . '/autoload.php';
// PSR4 autoload for classes with namespaces in src directory
//require dirname(__FILE__) . '/core/Psr4AutoloaderClass.php';
require dirname(__FILE__) . '/../vendor/autoload.php';

//$loader = new Psr4AutoloaderClass();
//$loader->register();
//$loader->addNamespace('MyApp', dirname(__FILE__) . '/../src');

