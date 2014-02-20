<?php

require_once( 'EdipostService.php' );

use EdipostService\EdipostService;


class ProductTest extends PHPUnit_Framework_TestCase {
	private $api;


	public function __construct() {
		$this->api = new EdipostService( '32a2da7ecac520df81e626671ff882a7bdd5d161' );
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
}

?>