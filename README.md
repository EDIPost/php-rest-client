# PHP Client Library
> PHP Client Library for Edipost REST API

[![GitHub release](https://img.shields.io/badge/release-1.1.0-blue.svg)](https://github.com/EDIPost/php-rest-client/releases)
[![Language](https://img.shields.io/badge/language-PHP-brightgreen.svg)](http://www.php.net)

Client library for Edipost REST API. The client library supports the most common functionality in the API. 


## Installation

### Prerequisites

* PHP
* mod_curl


## Development setup

### Run unit tests
```
composer install
./vendor/bin/phpunit tests
```

## Examples

### Connect to service

```
$api = new EdipostService( $_POST['apiKey'] );
```


### Create consignee

```
$builder = new ConsigneeBuilder();

$consignee = $builder
	->setCompanyName( $_POST['companyName'] )
	->setCustomerNumber( $_POST['customerNumber'] )
	->setPostAddress( $_POST['postAddress'] )
	->setPostZip( $_POST['postZip'] )
	->setPostCity( $_POST['postCity'] )
	->setStreetAddress( $_POST['streetAddress'] )
	->setStreetZip( $_POST['streetZip'] )
	->setStreetCity( $_POST['streetCity'] )
	->setContactName( $_POST['contactName'] )
	->setContactEmail( $_POST['contactEmail'] )
	->setContactPhone( $_POST['contactPhone'] )
	->setContactCellPhone( $_POST['contactCellphone'] )
	->setContactTelefax( $_POST['contactTelefax'] )
	->setCountry( $_POST['country'] )
	->build();

$newConsignee = $api->createConsignee($consignee);
```


### Create consignment

```
$builder = new ConsignmentBuilder();

$consignment = $builder
	->setConsignorID( $_POST['consignorId'] )
	->setConsigneeID( $newConsignee->ID )
	->setProductID( $_POST['productId'] )
	->setTransportInstructions( $_POST['transportInstructions'] )
	->setContentReference( $_POST['contentReference'] )
	->setInternalReference( $_POST['internalReference'] )
	->addItem( new Item( $_POST['weight'], $_POST['height'], $_POST['width'], $_POST['length']) )
	->addService( 55, array( 'COD_AMOUNT' => '1500', 'COD_REFERENCE' => '12345678901' ) )
	->build();

$newConsignment = $api->createConsignment( $consignment );
```


### Print consignment

```
$pdf = $api->printConsignment( $newConsignment->id );
```

## Meta

Mathias Bjerke – [@mathbje](https://twitter.com/mathbje) – mathias@verida.no
