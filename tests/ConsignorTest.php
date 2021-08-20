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


    /*
     * Get all consignors
     */
    public function testGetAllConsignors() {
        $consignors = $this->api->getConsignors();

        $this->assertGreaterThanOrEqual(1, count($consignors));
        $this->assertGreaterThanOrEqual(2, strlen($consignors[0]->getID()));
        $this->assertGreaterThanOrEqual(5, strlen($consignors[0]->getCompanyName()));
    }
}

?>