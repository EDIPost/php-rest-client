<?php
    namespace EdipostService\ServiceConnection;

    class ServiceConnection{
        private $_base = null;
        private $_apikey = null;

        public function __construct( $apiKey, $url ){
            $this->_base = $url;
            $this->_apikey = $apiKey;
        }

        /**
        * Checks that we have a connection
        * 
        */
        public function entryPoint(){
            $response = $this->_execute("/");    

            return ($response->code == 200);
        }


        /**
        * handle all post requests
        * 
        * @param string $url
        * @param \SimpleXMLElement $xml
        * @param mixed $headers
        */
        public function post( $url, $xml, $headers ){
            if ( !$xml instanceof \SimpleXMLElement ){
                return false;
            }
            $response = $this->_execute($url, REST_CREATE, $xml->asXML(), $headers);

            return $this->_response($response, REST_CREATE);
        }




        /**
        * Handle all GET requests
        *         
        * @param string $url
        * @param mixed $data
        * @param mixed $headers
        */
        public function get( $url, $data = null, $headers = null ){
            $response = $this->_execute($url, REST_GET, $data, $headers);

            return $this->_response($response, REST_GET );
        }



        /**
        * format the response
        * 
        * @param mixed $response
        * @param mixed $type
        * @return \SimpleXMLElement
        */
        private function _response( $response, $type ){
            if ( $type == REST_GET && $response->code = 200 ){
                return $this->_create_xml_object($response->data);
            }
            if ( $type == REST_CREATE && ($response->code == 200 || $response->code == 201) ){
                return new \SimpleXMLElement($response->data);    
            }

            return false;       
        }  



        /**
        * Create a POST request
        * 
        * @param string $url
        * @param mixed $headers
        * @param string/xml $body
        */
        private function _postRequest( $url, $headers, $body ) {
            $conn = curl_init();
            curl_setopt($conn, CURLOPT_URL, $this->_base . $url  );
            curl_setopt($conn, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($conn, CURLOPT_USERPWD, "api:". $this->_apikey);
            curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($conn, CURLOPT_POSTFIELDS, $body );
            curl_setopt($conn, CURLOPT_POST, TRUE);
            curl_setopt($conn, CURLINFO_HEADER_OUT, true);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($conn, CURLOPT_ENCODING, '');
            curl_setopt($conn, CURLOPT_TIMEOUT, 15);

            // Handle connection errors
			if( ($data = curl_exec($conn)) === false) {
				$curlError = curl_error($conn);
				curl_close($conn);

				throw new CommunicationException( $curlError );
			}

            $data = curl_exec($conn);
            $httpStatus = curl_getinfo($conn, CURLINFO_HTTP_CODE);
			curl_close($conn);

			// Handle HTTP errors
            if( $httpStatus != 200 && $httpStatus != 201 ) {
				throw new WebException( $data, $httpStatus );
            }

            return new response($httpStatus, $data);
        }


        private function _getRequest( $url, $headers, $body = null ){
            $conn = curl_init();
            curl_setopt($conn, CURLOPT_URL, $this->_base . $url  );
            curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($conn, CURLOPT_TIMEOUT,        100);
            curl_setopt($conn, CURLOPT_ENCODING,'gzip');
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($conn, CURLOPT_HTTPHEADER,  $this->_header($headers) );
            curl_setopt($conn, CURLOPT_CUSTOMREQUEST, REST_GET );

			// Handle connection errors
            if( ($data = curl_exec($conn)) === false) {
                $curlError = curl_error($conn);
                curl_close($conn);

                throw new CommunicationException( $curlError );
            }

			$httpStatus = curl_getinfo($conn, CURLINFO_HTTP_CODE);
			curl_close($conn);

			// Handle HTTP errors
			if( $httpStatus != 200 ) {
				throw new WebException( $data, $httpStatus );
			}

            return new response($httpStatus, $data);
        }


        private function _execute($url, $action=REST_GET, $data=null, $headers = null ){
            if ( $action == REST_GET ){
                return $this->_getRequest($url, $headers, $data );
            }elseif( $action == REST_CREATE ){
                return $this->_postRequest($url, $headers, $data);
            }
        }


        private function _header($headers){

            if ( !isset($headers) ){
                $headers = array();
            }

            $aHeader = array_merge(array(
                /*"Content-type: text/xml;charset=\"utf-8\"",*/
                "Authorization: Basic " . base64_encode("api:".$this->_apikey)
                ), $headers);

            return $aHeader;
        }

        /**
        * Creates a simpleXML object from an XML string
        * 
        * @param string $string
        * @return \SimpleXMLElement
        */
        private function _create_xml_object($string){
            libxml_use_internal_errors(true);
            $doc = simplexml_load_string($string);
            if ( !$doc ){
                return $string;
            }
            $xml = new \SimpleXMLElement($string);
            $namespaces = $xml->getDocNamespaces(); 
            //$xml->registerXPathNamespace('ns', $namespaces['']);
            $xml->registerXPathNamespace('ns', '');

            return $xml;
        }
    }


    class response{
        /** @var string */
        public $data = "";

        /** @var integer */
        public $code = 0;

        public function __construct($code, $data = "" ){
            $this->code = $code;
            $this->data = $data;
        }
    }    
?>
