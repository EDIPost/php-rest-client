<?php
    namespace EdipostService\Client\Builder;
    use \EdipostService\Client as Client;
    
    class ConsignorBuilder extends PartyBuilder{

		/**
		 * @return Client\Consignor
		 */
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
            
            $consignor = new Client\Consignor();
            $consignor->setID($this->id);
            $consignor->setCompanyName($this->companyName);
            $consignor->setCustomerNumber($this->customerNumber);
            $consignor->setPostAddress($po);
            $consignor->setStreetAddress($sa);
            $consignor->setCountry($this->country);
            $consignor->setContact($contact);
            
            return $consignor;
        }


        
    }