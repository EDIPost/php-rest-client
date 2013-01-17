<?php
    namespace EdipostService\Client;

    require_once( "\\".__NAMESPACE__."\\Address.php" );
    require_once( "\\".__NAMESPACE__."\\Contact.php" );
    require_once( "\\EdipostService\ServiceConnection\\Communication.php" );

    /** @XmlRoot(Consignee) */
    class Consignee extends \EdipostService\ServiceConnection\Communication{
        /** @XmlAttribute(int) */
        private $ID;
        /** @XmlElement(string, companyName) */
        private $companyName;
        /** @XmlElement(string, customerNumer) */
        private $customerNumber;
        /** @XmlElement(string, country) */
        private $country;

        /** @var EdipostService\Client\Address */
        /** @XmlElement(Address, streetAddress) */
        private $streetAddress = null;

        /** @var EdipostService\Client\Address */
        /** @XmlElement(Address, postAddress) */
        private $postAddress = null;

        /** @var EdipostService\Client\Contact */
        /** @XmlElement(Contact, contact) */
        private $contact = null;
        

        public function getID() {
            return $this->ID;
        }


        public function setID( $ID ) {
            $this->ID = $ID;
        }


        public function getCompanyName() {
            return $this->companyName;
        }


        public function setCompanyName( $companyName ) {
            $this->companyName = $companyName;
        }


        public function getCustomerNumber() {
            return $this->customerNumber;
        }


        public function setCustomerNumber( $customerNumber ) {
            $this->customerNumber = $customerNumber;
        }


        public function getCountry() {
            return $this->country;
        }


        public function setCountry( $country ) {
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
        public function setStreetAddress( \EdipostService\Client\Address $streetAddress ) {
            $this->streetAddress = $streetAddress;
        }


        public function getPostAddress() {
            return $this->postAddress;
        }


        public function setPostAddress( \EdipostService\Client\Address $postAddress ) {
            $this->postAddress = $postAddress;
        }


        public function getContact() {
            return $this->contact;
        }


        public function setContact( \EdipostService\Client\Contact $contact ) {
            $this->contact = $contact;
        }


        public function save() {
            //return $this->Locator.resolve( ConsigneeService.class ).saveConsignee( this );
        }


        public function remove() {
            //return $this->Locator.resolve( ConsigneeService.class ).removeConsignee( getID() );
        }
    }    

?>
