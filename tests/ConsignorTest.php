<?php

require_once( 'src/EdipostService.php' );
require_once( 'Properties.php' );

use EdipostService\EdipostService;
use PHPUnit\Framework\TestCase;


class ConsignorTest extends TestCase {
	private $api;


	public function setUp() {
		$this->api = new EdipostService( Properties::$apiKey );
	}


	/*
	 * Get default consignor
	 */
	public function testDefaultConsignor() {
		$consignor = $this->api->getDefaultConsignor();

		$this->assertEquals( 3311, $consignor->getID() );
		$this->assertEquals( 'Edipost AS', $consignor->getCompanyName() );
	}
}

?>