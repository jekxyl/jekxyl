<?php

if (true === file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (true === file_exists(__DIR__ . '/../../autoload.php')) {
    require __DIR__ . '/../../autoload.php';
} else {
    die('Cannot find Composer autoloader.');
}

use Hoa\Protocol;

$hoaProtocol   = Protocol\Protocol::getInstance();
$hoaProtocol[] = new Protocol\Node('Jekxyl', __DIR__ . DS);
