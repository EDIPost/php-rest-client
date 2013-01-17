<?php
    namespace EdipostService\Client\Builder;
    use \EdipostService\Client as Client;
    
    require_once( "\\".__NAMESPACE__."\\..\\Address.php" );
    require_once( "\\".__NAMESPACE__."\\..\\Contact.php" );
    require_once( "\\".__NAMESPACE__."\\..\\Consignee.php" );
    
    

    class ConsigneeBuilder{
        private $id;
        private $companyName;
        private $customerNumber;
        private $streetAddress;
        private $streetZip;
        private $streetCity;
        private $postAddress;
        private $postZip;
        private $postCity;
        private $country;
        private $contactName;
        private $contactPhone;
        private $contactCellPhone;
        private $contactEmail;



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


        public function build() {
            $po = new Client\Address();
            $po->setAddress( $this->postAddress );
            $po->setZipCode( $this->postZip );
            $po->setCity($this->postCity); 
            
            $sa = new Client\Address();
            $sa->setAddress( $this->streetAddress );
            $sa->setZipCode( $this->streetZip );
            $sa->setCity($this->streetCity);
            
            $contact = new Client\Contact();
            $contact->setName($this->contactName);
            $contact->setTelephone($this->contactPhone);
            $contact->setCellphone($this->contactCellPhone);
            $contact->setEmail($this->contactEmail);
            
            $consignee = new Client\Consignee();
            $consignee->setID($this->id);
            $consignee->setCompanyName($this->companyName);
            $consignee->setCustomerNumber($this->customerNumber);
            $consignee->setPostAddress($po);
            $consignee->setStreetAddress($sa);
            $consignee->setCountry($this->country);
            $consignee->setContact($contact);
            
            return $consignee;
        }
    }   
    
?>
