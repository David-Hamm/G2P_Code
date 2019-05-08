<?php
include_once 'DeviceWeights.php';


$xml = 'http://main.g2planet.com/codetest/example.xml';
$document = new DeviceWeights($xml);
print_r($document->deviceListInfoSummary($xml));

