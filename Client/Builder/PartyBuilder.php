<?php
    namespace EdipostService\Client\Builder;
    use \EdipostService\Client as Client;
    
    class PartyBuilder{
        protected $id;
        protected $companyName;
        protected $customerNumber;
        protected $streetAddress;
        protected $streetZip;
        protected $streetCity;
        protected $postAddress;
        protected $postZip;
        protected $postCity;
        protected $country;
        protected $contactName;
        protected $contactPhone;
        protected $contactCellPhone;
        protected $contactEmail;



        public function __construct() {
        }


        public function setID( $id ) {
            $this->id = $id;
            return $this;
        }


        public function setCompanyName( $companyName ) {
            $this->companyName = $companyName;
            return $this;
        }


        public function setCustomerNumber( $customerNumber ) {
            $this->customerNumber = $customerNumber;
            return $this;
        }


        public function setStreetAddress( $streetAddress ) {
            $this->streetAddress = $streetAddress;
            return $this;
        }


        public function setStreetZip( $streetZip ) {
            $this->streetZip = $streetZip;
            return $this;
        }


        public function setStreetCity( $streetCity ) {
            $this->streetCity = $streetCity;
            return $this;
        }


        public function setPostAddress( $postAddress ) {
            $this->postAddress = $postAddress;
            return $this;
        }


        public function setPostZip( $postZip ) {
            $this->postZip = $postZip;
            return $this;
        }


        public function setPostCity( $postCity ) {
            $this->postCity = $postCity;
            return $this;
        }


        public function setCountry( $country ) {
            $this->country = $country;
            return $this;
        }


        public function setContactName( $contactName ) {
            $this->contactName = $contactName;
            return $this;
        }


        public function setContactPhone( $contactPhone ) {
            $this->contactPhone = $contactPhone;
            return $this;
        }


        public function setContactCellPhone( $contactCellPhone ) {
            $this->contactCellPhone = $contactCellPhone;
            return $this;
        }


        public function setContactEmail( $contactEmail ) {
            $this->contactEmail = $contactEmail;
            return $this;
        }


        
    }   
    
?>
