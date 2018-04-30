<?php

require_once( 'src/EdipostService.php' );

use EdipostService\EdipostService;
use PHPUnit\Framework\TestCase;


class ConsignorTest extends TestCase {
	private $api;


	public function setUp() {
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
}

?>