<?php

require_once('../src/EdipostService.php');

use EdipostService\EdipostService;


$apiUrl = 'https://api.pbshipment.com';
$apiKey = '5b10146aa8326ab219048595945b8592bc271ab0';

// Connect to API
$api = new EdipostService( $apiKey, $apiUrl );

// Get shipping label for a given ID as PDF
$pdf = $api->printConsignment( 3567110 );

// Save PDF data to file 
file_put_contents( 'label.pdf', $pdf );

?>
