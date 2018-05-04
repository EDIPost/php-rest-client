<?php

require_once( 'src/EdipostService.php' );
require_once( 'Properties.php' );

use EdipostService\EdipostService;
use PHPUnit\Framework\TestCase;


class ProductTest extends TestCase {
	private $api;


	public function setUp() {
		$this->api = new EdipostService( Properties::$apiKey );
	}


	/*
	 * Get products
	 */
	public function testProducts() {
		// Get products
		$products = $this->api->getProducts( Properties::$consigneeId );
		$this->assertGreaterThan( 0, count( $products ) );


		// Make sure we have the product 'Klimanøytral Servicepakke' and that all properties is set
		$servicepakken = array_filter( $products, function ( $product ) {
			return $product->getId() == 8;
		} );

		$servicepakken = reset( $servicepakken );

		$this->assertNotNull( $servicepakken );
		$this->assertEquals( 8, $servicepakken->getId() );
		$this->assertEquals( 'Klimanøytral Servicepakke', $servicepakken->getName() );
		$this->assertEquals( 'Available', $servicepakken->getStatus() );
		$this->assertGreaterThan( 0, count( $servicepakken->getServices() ) );


		// Make sure we have the service 'Cash On Delivery' and that all properties is set
		$cod = array_filter( $servicepakken->getServices(), function ( $service ) {
			return $service->getId() == 55;
		} );

		$cod = reset( $cod );

		$this->assertNotNull( $cod );
		$this->assertEquals( 55, $cod->getId() );
		$this->assertEquals( 'Cash On Delivery', $cod->getName() );
	}
}

?>