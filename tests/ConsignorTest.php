<?php

require_once( 'EdipostService.php' );

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
}

?>