<?php

require_once( 'EdipostService.php' );

use EdipostService\Client\Builder\ConsigneeBuilder;
use EdipostService\Client\Builder\ConsignmentBuilder;
use EdipostService\Client\Item;
use EdipostService\EdipostService;


class EdipostTest extends PHPUnit_Framework_TestCase {
	private $api;


	public function __construct() {
		$this->api = new EdipostService( '32a2da7ecac520df81e626671ff882a7bdd5d161' );
	}


	/*
	 * Get default consignor
	 */
	public function testDefaultConsignor() {
		$consignor = $this->api->getDefaultConsignor();

		$this->assertEquals( 3311, $consignor->getID() );
		$this->assertEquals( 'Edipost AS', $consignor->getCompanyName() );
	}


	/*
	 * Get products
	 */
	public function testProducts() {
		// Get products
		$products = $this->api->getProducts( 1510077 );
		$this->assertGreaterThan( 0, count( $products ) );

		// Make sure we have the product 'Klimanøytral Servicepakke' and that all properties is set
		$servicepakken = reset( array_filter( $products, function ( $product ) {
			return $product->getId() == 8;
		} ) );
		$this->assertNotNull( $servicepakken );
		$this->assertEquals( 8, $servicepakken->getId() );
		$this->assertEquals( 'Klimanøytral Servicepakke', $servicepakken->getName() );
		$this->assertEquals( 'Available', $servicepakken->getStatus() );
		$this->assertGreaterThan( 0, count( $servicepakken->getServices() ) );

		// Make sure we have the service 'Cash On Delivery' and that all properties is set
		$cod = reset( array_filter( $servicepakken->getServices(), function ( $service ) {
			return $service->getId() == 55;
		} ) );
		$this->assertNotNull( $cod );
		$this->assertEquals( 55, $cod->getId() );
		$this->assertEquals( 'Cash On Delivery', $cod->getName() );
	}


	/*
	 * Calculate postage
	 */
	public function testCalculatePostage() {
		$builder = new ConsignmentBuilder();

		$consignment = $builder
			->setConsignorID( 3311 )
			->setConsigneeID( 1510077 )
			->setProductID( 8 )
			->setTransportInstructions( '' )
			->setContentReference( '' )
			->setInternalReference( '' )
			->addItem( new Item( 5, 10, 10, 10 ) )
			->build();

		$result = $this->api->calculatePostage( $consignment );

		// Make sure we have at least one shipment item
		$this->assertGreaterThan( 0, count( $result->items->items ) );

		// Make sure all items has a cost
		foreach( $consignment->items->items as $item ) {
			$this->assertGreaterThan( 1, $item->getCost() );
		}
	}


	/*
	 * Create consignee
	 */
	public function testCreateConsignee() {
		$builder = new ConsigneeBuilder();

		$consignee = $builder
			->setCompanyName( 'MyCompany AS' )
			->setCustomerNumber( '1234' )
			->setPostAddress( 'MyPostAddress' )
			->setPostZip( '2847' )
			->setPostCity( 'Kolbu' )
			->setStreetAddress( 'MyStreetAddress' )
			->setStreetZip( '2847' )
			->setStreetCity( 'Kolbu' )
			->setContactName( 'MyContact' )
			->setContactEmail( '' )
			->setContactPhone( '' )
			->setContactCellPhone( '' )
			->setContactTelefax( '' )
			->setCountry( 'NO' )
			->build();

		$newConsignee = $this->api->createConsignee( $consignee );

		$this->assertGreaterThan( 1, $newConsignee->ID );
		$this->assertEquals( '1234', $newConsignee->customerNumber );
		$this->assertEquals( 'MyCompany AS', $newConsignee->companyName );

		$this->assertEquals( 'MyStreetAddress', $newConsignee->streetAddress->address );
		$this->assertEquals( '2847', $newConsignee->streetAddress->zipCode );
		$this->assertEquals( 'Kolbu', $newConsignee->streetAddress->city );

		$this->assertEquals( 'MyPostAddress', $newConsignee->postAddress->address );
		$this->assertEquals( '2847', $newConsignee->postAddress->zipCode );
		$this->assertEquals( 'Kolbu', $newConsignee->postAddress->city );

		$this->assertEquals( 'MyContact', $newConsignee->contact->name );
	}


	/*
	 * Create consignment
	 */
	public function testCreateConsignment() {
		$builder = new ConsignmentBuilder();

		$consignment = $builder
			->setConsignorID( 3311 )
			->setConsigneeID( 1510077 )
			->setProductID( 8 )
			->setTransportInstructions( '' )
			->setContentReference( '' )
			->setInternalReference( '' )
			->addItem( new Item( 5, 10, 10, 10 ) );

		$newConsignment = $this->api->createConsignment( $consignment->build() );

		// Make sure we have a shipment ID and a shipment number
		$this->assertGreaterThan( 1, $newConsignment->id );
		$this->assertEquals( 17, strlen( $newConsignment->shipmentNumber ) );

		// Make sure we have at least one shipment item
		$this->assertGreaterThan( 0, count( $newConsignment->items->items ) );

		// Make sure all items has a shipment item number
		foreach( $newConsignment->items->items as $item ) {
			$this->assertEquals( 18, strlen( $item->getItemNumber() ) );
		}
	}
}

?>