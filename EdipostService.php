<?php
    namespace EdipostService;

    // make sure we set up the environment
    require_once( 'init.php' );
    
    


    interface iEdipostService{
        /**
        * Returns the default consignor for the customer. Most customers have only one consignor.
        *
        * @return the default consignor
        */
        public function getDefaultConsignor();


        /**
        * Creates a builder object used to build consignees
        *
        * @return a builder object
        */
        /*public function consigneeBuilder();*/


        /**
        * Get a consignee by it's ID.
        *
        * The ID can be found by searching for consignees by findConsignee, or when you create a new consignee
        *
        * @param consigneeID the ID of the consignee
        * @return a consignee
        */
        /*   public function getConsignee( integer $consigneeID ); */


        /**
        * Search for consignees
        *
        * @param searchPhrase A phrase used when searching for consignees
        * @return a list of consignees
        */
        /*   public function findConsignee( string $searchPhrase ); */


        /**
        * Creates a builder object used to build consignments
        * 
        * @return a builder object
        */
        /*   public function consignmentBuilder(); */


        /**
        * Get a consignment by it's ID
        *
        * The ID can be found by searching for consignments by findConsignment, or when you create a new consignment
        *
        * @param consignmentID the ID of the consignment
        * @return a consignment
        */
        /*  public function getConsignment( integer $consignmentID ); */


        /**
        * Search for consignments
        *
        * @param searchPhrase A phrase used when searching for consignments
        * @return a list of consignments
        */
        /*  public function findConsignment( string $searchPhrase ); */

    }



    class EdipostService implements iEdipostService{
        private $conn = null;
        private $connection_status = null;
        private $pdf_folder = "";
        
        
        /**
        * The constructor
        * 
        * @param mixed $apikey
        * @return EdipostService
        */
        public function __construct($apikey = null, $pdf_folder = null, $apiurl = 'http://api.edipost.no'){
            if ( isset($apikey) ){
                $this->connect($apikey, $apiurl);            
            }
            
            $this->setPDF_folder($pdf_folder);
        } 


        /**
        * Sets a folder to store the PDF prints
        * 
        * @param string $folder
        */
        public function setPDF_folder( $folder = null ){
            if ( !isset($folder)  ){
                $folder = 'tmp/relable/pdf';
            }
             
            if ( !is_dir( $folder ) ){
                @mkdir( $folder, 0777, true );    
            }
            $this->pdf_folder = $folder;
        }

        /**
        * fetches the default consignor 
        * 
        * @return \EdipostService\Client\Consignor
        */
        public function getDefaultConsignor(){    
            $url = "/consignor/default";
            $headers = array( "Accept: application/vnd.edipost.party+xml" );
            
            $xml = $this->conn->get( $url, null, $headers );
            

            if ( !$xml ){
                return null;
            }

            $cb = new \EdipostService\Client\Builder\ConsignorBuilder();
            $consignor = $cb->setID( $this->xv($xml,"//consignor/@id") )
                ->setCompanyName( $this->xv($xml,"//consignor/companyName") )
                ->setPostAddress( $this->xv($xml,"//consignor/postAddress/address") )
                ->setPostZip($this->xv($xml,"//consignor/postAddress/zipCode"))
                ->setPostCity( $this->xv($xml,"//consignor/postAddress/city") )
                ->setStreetAddress( $this->xv($xml,"//consignor/streetAddress/address") )
                ->setStreetZip($this->xv($xml,"//consignor/streetAddress/zipCode"))
                ->setStreetCity( $this->xv($xml,"//consignor/streetAddress/city") )
                ->setContactName( $this->xv($xml,"//consignor/contact/name") )
                ->setContactCellPhone( $this->xv($xml,"//consignor/contact/cellphone") )
                ->setContactPhone( $this->xv($xml,"//consignor/contact/telephone") )
                ->setCountry($this->xv($xml,"//consignor/country"))
                ->build();    

            return $consignor;
        }
        
        
      
        
        /**
        * Creates a Consignee 
        * 
        * @param \EdipostService\Client\Consignee $consignee
        * @return Consignee
        */
        public function createConsignee($consignee){
            $url = "/consignee";
            $headers = array(
                'Accept: application/vnd.edipost.party+xml',
                'Content-Type: application/vnd.edipost.party+xml'
            );
            
            $xml = $this->conn->post( $url, new \SimpleXMLElement( $consignee->xml_serialize()), $headers );
            
            if ( !$xml instanceof \SimpleXMLElement ){
                return false;
            }
            
            $party = $this->_buildParty($xml, PARTY_CONSIGNEE);
            return $party;
        }
        
        /**
        * Creates a consignment
        * 
        * @param \EdipostService\Client\Consignment $consignment
        * @return integer
        */
        public function createConsignment($consignment){
            $url = "/consignment";
            $headers = array(
                'Accept: application/vnd.edipost.consignment+xml',
                'Content-Type: application/vnd.edipost.consignment+xml'
            );
            
            $xml = $this->conn->post( $url, new \SimpleXMLElement( $consignment->xml_serialize()), $headers );
            
            if ( !$xml instanceof \SimpleXMLElement ){
                return false;
            }
            
            $consignment->id = $this->xv($xml,"//consignment/@id");
            $consignment->shipmentNumber = $this->xv($xml,"//consignment/shipmentNumber");           
            $i=0;
            foreach( $xml->items->item as $k => $v ){
                $consignment->items->items{$i}->setItemNumber( reset($v->itemNumber) );
                $i++;
            }
            return $consignment;
        }
        
        
        /**
        * fetches the PDF data
        * 
        * @param mixed $consignment_id
        */
        public function printConsignment($consignment_id, $filename = null, $report = null ){
            $url = "/consignment/$consignment_id/print";
            
            if ( isset($report) ){
                $url .= "?report=".$report;
            }
            $headers = array( "Accept: application/pdf" );

            $response = $this->conn->get( $url, null, $headers );
            
            $filename = ( isset($filename) ? md5($filename) : md5($consignment_id) ).".pdf";
            if ( file_put_contents($this->pdf_folder . "/" . $filename, $response) === false ){
                // Error
            }
            
            return $this->pdf_folder . "/" . $filename;
        }

        
        
        
        /**
        * PRVATE FUNCTIONS
        */
        
        
        /**
        * Private method to build a party object
        * 
        * @param \SimpleXMLElement $xml
        * @param string $type
        * @return Party
        */
        private function _buildParty( $xml, $type = PARTY_CONSIGNEE){
            $namespace = $type;

            if ( $type = PARTY_CONSIGNEE ){
                $cb = new \EdipostService\Client\Builder\ConsigneeBuilder();
            }else{
                $cb = new \EdipostService\Client\Builder\ConsignorBuilder();
            }
            
            $party = $cb->setID( $this->xv($xml,"//$namespace/@id") )
                ->setCustomerNumber($this->xv($xml,"//$namespace/customerNumber") )
                ->setCompanyName( $this->xv($xml,"//$namespace/companyName") )
                ->setPostAddress( $this->xv($xml,"//$namespace/postAddress/address") )
                ->setPostZip($this->xv($xml,"//$namespace/postAddress/zipCode"))
                ->setPostCity( $this->xv($xml,"//$namespace/postAddress/city") )
                ->setStreetAddress( $this->xv($xml,"//$namespace/streetAddress/address") )
                ->setStreetZip($this->xv($xml,"//$namespace/streetAddress/zipCode"))
                ->setStreetCity( $this->xv($xml,"//$namespace/streetAddress/city") )
                ->setContactName( $this->xv($xml,"//$namespace/contact/name") )
                ->setContactCellPhone( $this->xv($xml,"//$namespace/contact/cellphone") )
                ->setContactPhone( $this->xv($xml,"//$namespace/contact/telephone") )
                ->setCountry($this->xv($xml,"//$namespace/country"))
                ->build();
            
            return $party;
        }


        /**
        * Connects to the api server
        * 
        * @param string $apikey
        * @return boolean connection_status
        */
        private function connect($apikey, $apiurl){
            if ( empty($apikey) ){
                $error_msg = "Invalid API key #404UC#";
                trigger_error( $error_msg, E_USER_ERROR );
            }

            $this->conn = new \EdipostService\ServiceConnection\ServiceConnection($apikey,$apiurl);
            if(( $this->connection_status = $this->conn->entryPoint()) !== true ){
                $error_msg = "No connection to API server #404UC#";
                trigger_error( $error_msg, E_USER_ERROR );
            } 

            return $this->connection_status;
        }


        /**
        * Fetches the correct value based on an x-path
        * 
        * @param \SimpleXMLElement $xml
        * @param string $xpath
        * @param string $default
        * @return string|numeric
        */
        private function xv(&$xml,$xpath, $default=""){
            try{
                $value = $xml->xpath($xpath);
                if ( is_array( $value ) ){
                    $value = reset($value);
                }
            }catch( \Exception $e){
                return $default;
            }
            return (string)$value;
        }

    }
?>
