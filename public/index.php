<?php

use Sirius\Kernel;
use Sirius\http\Request;

require dirname(__DIR__) . '/vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__) , '.env.local');
$dotenv->load();

$kernel = new Kernel();
$request = Request::create();
$response = $kernel->handleRequest($request);
$response->send();
exit();
