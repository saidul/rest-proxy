<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

use RestProxy\RestProxy;

$proxy = new RestProxy(Request::createFromGlobals());

$proxy->register('wiki', 'http://en.wikipedia.org/w/api.php');
$proxy->register('github', 'https://api.github.com');


$proxy->run();

echo $proxy->getContent();
