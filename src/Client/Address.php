<?php

namespace EdipostService\Client;


class Address {
	/** @XmlElement(string, address) */
	public $address;
    /** @XmlElement(string, address2) */
    public $address2;
	/** @XmlElement(string, zipCode) */
	public $zipCode;
	/** @XmlElement(string, city) */
	public $city;


	public function getAddress() {
		return $this->address;
	}


	public function setAddress($address) {
		$this->address = $address;
	}


    public function getAddress2() {
        return $this->address2;
    }


    public function setAddress2($address2) {
        $this->address2 = $address2;
    }


	public function getZipCode() {
		return $this->zipCode;
	}


	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}


	public function getCity() {
		return $this->city;
	}


	public function setCity($city) {
		$this->city = $city;
	}

}