<?php

namespace EdipostService\Client;


/** @XmlRoot(Party) */
class Party extends \EdipostService\ServiceConnection\Communication {
	public $ID;
	/** @XmlElement(string, companyName) */
	public $companyName;
	/** @XmlElement(string, customerNumber) */
	public $customerNumber = "0";
	/** @XmlElement(string, country) */
	public $country;

	/** @XmlElement(Address, streetAddress) */
	public $streetAddress = null;

	/** @XmlElement(Address, postAddress) */
	public $postAddress = null;

	/** @XmlElement(Contact, contact) */
	public $contact = null;


	public function getID() {
		return $this->ID;
	}


	public function setID($ID) {
		$this->ID = $ID;
	}


	public function getCompanyName() {
		return $this->companyName;
	}


	public function setCompanyName($companyName) {
		$this->companyName = $companyName;
	}


	public function getCustomerNumber() {
		return $this->customerNumber;
	}


	public function setCustomerNumber($customerNumber) {
		$this->customerNumber = $customerNumber;
	}


	public function getCountry() {
		return $this->country;
	}


	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * Gets the streetAddress object
	 *
	 * @return Address
	 */
	public function getStreetAddress() {
		return $this->streetAddress;
	}

	/**
	 * Sets the address object
	 *
	 * @param Address $streetAddress
	 */
	public function setStreetAddress(Address $streetAddress) {
		$this->streetAddress = $streetAddress;
	}


	public function getPostAddress() {
		return $this->postAddress;
	}


	public function setPostAddress(Address $postAddress) {
		$this->postAddress = $postAddress;
	}


	public function getContact() {
		return $this->contact;
	}


	/**
	 * @param \EdipostService\Client\Contact $contact
	 */
	public function setContact(Contact $contact) {
		$this->contact = $contact;
	}


	public function save() {

	}


	public function remove() {

	}
}