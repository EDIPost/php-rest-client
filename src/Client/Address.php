<?php
  namespace EdipostService\Client;
  
  
  class Address{
    /** @XmlElement(string, address) */
    public $address;
    /** @XmlElement(string, zipCode) */
    public $zipCode;
    /** @XmlElement(string, city) */
    public $city;


    public function getAddress() {
        return $this->address;
    }


    public function setAddress( $address ) {
        $this->address = $address;
    }


    public function getZipCode() {
        return $this->zipCode;
    }


    public function setZipCode( $zipCode ) {
        $this->zipCode = $zipCode;
    }


    public function getCity() {
        return $this->city;
    }


    public function setCity( $city ) {
        $this->city = $city;
    }
   
  }