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


	public function testGetConsignee() {
		$consignee = $this->api->getConsignee( 1510077 );

		$this->assertGreaterThan( 0, $consignee->getID() );
		$this->assertEquals( 'Mariann Orvedal', $consignee->getCompanyName() );
		$this->assertEquals( 'NO', $consignee->getCountry() );

		$this->assertEquals( 'Vikveien 7', $consignee->getStreetAddress()->getAddress() );
		$this->assertEquals( '1337', $consignee->getStreetAddress()->getZipCode() );
		$this->assertEquals( 'Vik i Sogn', $consignee->getStreetAddress()->getCity() );

		$this->assertEquals( 'Vikveien 7', $consignee->getPostAddress()->getAddress() );
		$this->assertEquals( '1337', $consignee->getPostAddress()->getZipCode() );
		$this->assertEquals( 'Vik i Sogn', $consignee->getPostAddress()->getCity() );

		$this->assertEquals( 'Mariann Orvedal', $consignee->getContact()->getName() );
		$this->assertEquals( '97141810', $consignee->getContact()->getTelephone() );
		$this->assertEquals( '97141810', $consignee->getContact()->getCellphone() );
		$this->assertEquals( '', $consignee->getContact()->getEmail() );
		$this->assertEquals( '', $consignee->getContact()->getTelefax() );
	}
}

?>