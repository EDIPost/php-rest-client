# PHP wrapper for Edipost REST api


Please take a look at the folder tests for more examples.


## Connect to service

```
$api = new EdipostService( $_POST['apiKey'] );
```


## Create consignee

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


## Create consignment

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


## Print consignment

```
$pdf = $api->printConsignment( $newConsignment->id );
```
