<?php

require_once( 'src/EdipostService.php' );
require_once( 'Properties.php' );

use EdipostService\Client\Builder\ConsignmentBuilder;
use EdipostService\Client\Item;
use EdipostService\EdipostService;
use PHPUnit\Framework\TestCase;


class ConsignmentTest extends TestCase {
	private $api;


	public function setUp() {
		$this->api = new EdipostService( Properties::$apiKey );
	}


	/*
	 * Calculate postage
	 */
	public function testCalculatePostage() {
		$builder = new ConsignmentBuilder();

		$consignment = $builder
			->setConsignorID( Properties::$consignorId )
			->setConsigneeID( Properties::$consigneeId )
			->setProductID( Properties::$servicepakkenProductId )
			->setTransportInstructions( '' )
			->setContentReference( '' )
			->setInternalReference( '' )
			->addItem( new Item( 5, 10, 10, 10 ) )
			->build();

		$result = $this->api->calculatePostage( $consignment );

		// Make sure we have at least one shipment item
		$this->assertGreaterThan( 0, count( $result->items->items ) );

		// Make sure all items has a cost
		foreach( $result->items->items as $item ) {
			$this->assertGreaterThan( 1, $item->getCost() );
		}
	}


	/*
	 * Create consignment
	 */
	public function testCreateConsignment() {
		$builder = new ConsignmentBuilder();

		$consignment = $builder
			->setConsignorID( Properties::$consignorId )
			->setConsigneeID( Properties::$consigneeId )
			->setProductID( Properties::$servicepakkenProductId )
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