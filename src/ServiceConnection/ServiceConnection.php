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
        * @throws CommunicationException
		* @throws WebException
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
		 * @return bool|\SimpleXMLElement|string
		 * @throws CommunicationException
		 * @throws WebException
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
		 * @return bool|\SimpleXMLElement|string
		 * @throws CommunicationException
		 * @throws WebException
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
		 * @return bool|\SimpleXMLElement|string
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
		 * @param $body
		 * @return response
		 * @throws CommunicationException
		 * @throws WebException
		 */
		private function _postRequest( $url, $headers, $body ) {
			$url = $this->_base . $url;

			$auth = base64_encode( 'api:' . $this->_apikey );
			$headers[] = "Authorization: Basic $auth";

			$httpClient = new HttpClient();
			return $httpClient->post( $url, $headers, $body );
		}


		/**
		 * Create a GET request
		 *
		 * @param string $url
		 * @param mixed $headers
		 * @return response
		 * @throws CommunicationException
		 * @throws WebException
		 */
		private function _getRequest($url, $headers) {
			$url = $this->_base . $url;

			$auth = base64_encode( 'api:' . $this->_apikey );
			$headers[] = "Authorization: Basic $auth";

			$httpClient = new HttpClient();
			return $httpClient->get( $url, $headers );
		}


		/**
		 * @param $url
		 * @param string $action
		 * @param null $data
		 * @param null $headers
		 * @return response
		 * @throws CommunicationException
		 * @throws WebException
		 */
		private function _execute($url, $action=REST_GET, $data=null, $headers = null ){
            if ( $action == REST_GET ){
                return $this->_getRequest($url, $headers);
            }elseif( $action == REST_CREATE ){
                return $this->_postRequest($url, $headers, $data);
            } else {
            	return null;
			}
        }


		/**
		 * Creates a simpleXML object from an XML string
		 *
		 * @param string $string
		 * @return \SimpleXMLElement|string
		 */
        private function _create_xml_object($string){
            libxml_use_internal_errors(true);
            $doc = simplexml_load_string($string);
            if ( !$doc ){
                return $string;
            }
            $xml = new \SimpleXMLElement($string);
            $xml->registerXPathNamespace('ns', '');

            return $xml;
        }
    }
