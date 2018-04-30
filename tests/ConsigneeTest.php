<?php

require_once( 'src/EdipostService.php' );

use EdipostService\Client\Builder\ConsigneeBuilder;
use EdipostService\Client\Builder\ConsignmentBuilder;
use EdipostService\Client\Item;
use EdipostService\EdipostService;
use PHPUnit\Framework\TestCase;


class ConsigneeTest extends TestCase {
	private $api;


	public function setUp() {
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
		$consignee = $this->api->getConsignee( 3517608 );

		$this->assertGreaterThan( 0, $consignee->getID() );
		$this->assertEquals( 'Folco AS', $consignee->getCompanyName() );
		$this->assertEquals( 'NO', $consignee->getCountry() );

		$this->assertEquals( 'Hundeveien 123', $consignee->getStreetAddress()->getAddress() );
		$this->assertEquals( '1337', $consignee->getStreetAddress()->getZipCode() );
		$this->assertEquals( 'Sandvika', $consignee->getStreetAddress()->getCity() );

		$this->assertEquals( 'Hundeveien 123', $consignee->getPostAddress()->getAddress() );
		$this->assertEquals( '1337', $consignee->getPostAddress()->getZipCode() );
		$this->assertEquals( 'Sandvika', $consignee->getPostAddress()->getCity() );

		$this->assertEquals( 'Kari Kontakt', $consignee->getContact()->getName() );
		$this->assertEquals( '11111111', $consignee->getContact()->getTelephone() );
		$this->assertEquals( '22222222', $consignee->getContact()->getCellphone() );
		$this->assertEquals( '', $consignee->getContact()->getEmail() );
		$this->assertEquals( '', $consignee->getContact()->getTelefax() );
	}
}

?>