<?php

require 'vendor/autoload.php';

use Hoa\Protocol;

$hoaProtocol   = Protocol\Protocol::getInstance();
$hoaProtocol[] = new Protocol\Node('Jekxyl', __DIR__ . DS);
