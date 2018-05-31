<?php

require_once('../src/EdipostService.php');

use EdipostService\EdipostService;


$apiUrl = 'https://api.pbshipment.com';
$apiKey = '5b10146aa8326ab219048595945b8592bc271ab0';

// Connect to API
$api = new EdipostService( $apiKey, $apiUrl );

// Get default consignor (sender)
$consignor = $api->getDefaultConsignor();

// Print consignor details
echo "CONSIGNOR ADDRESS\n";
echo "------------------\n";
echo $consignor->companyName . "\n";
echo $consignor->streetAddress->address . "\n";
echo $consignor->streetAddress->zipCode . ' ' . $consignor->streetAddress->zipCode . "\n";

?>
