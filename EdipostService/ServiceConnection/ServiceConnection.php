<?php
    namespace EdipostService\ServiceConnection;
    
    require_once( "\\".__NAMESPACE__."\\CommunicationException.php" );

    define("REST_GET", "GET");
    define("REST_CREATE", "POST");
    define("REST_UPDATE", "PUT");
    define("REST_DELETE","DELETE");
    
    class ServiceConnection{
        private $_base = null;
        private $_apikey = null;
        
        public function __construct( $apiKey, $url ="http://api.edipost.no" ){
            $this->_base = $url;
            $this->_apikey = $apiKey;
        }
        
        public function entryPoint(){
            return $this->_execute("");    
        }

        
        private function _execute($method, $action=REST_GET, $data=null){
            $conn = curl_init();
            curl_setopt($conn, CURLOPT_URL, $this->_base . "/". $method  );
            curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($conn, CURLOPT_TIMEOUT,        100);
            curl_setopt($conn, CURLOPT_ENCODING,'gzip');
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($conn, CURLOPT_HTTPHEADER,  $this->_header() );

            curl_setopt($conn, CURLOPT_CUSTOMREQUEST, $action );
            
            //curl_setopt($conn, CURLOPT_POSTFIELDS,     $this->_envelope($request, $methodname) );
            //curl_setopt($conn, CURLOPT_HTTPHEADER,     $this->_header($request, $methodname) );

            if( ($result = curl_exec($conn)) === false) {
                curl_close($conn);
                throw new CommunicationException(curl_error($conn));
            } else {
                curl_close($conn);
            }  
            
            $xml = new \SimpleXMLElement($result);
            
            return $xml;
            //unset($result_body);

            //$namespaces = $xml->getDocNamespaces(); 
            //$xml->registerXPathNamespace('ns', $namespaces['']); 

            //convert to an stdClass object and sets the correct filetypes
            $xml = json_decode(json_encode((array) $xml), 1);
            $xml = $this->_arrayToObject($xml);

            return $result;         
        }
        
        private function _arrayToObject($array, $name="") {
            if(!is_array($array)) {
                if ( (string)(int)$array == $array && substr($array,0,1) != "0" ){
                    return (int)$array;
                }elseif( (string)(float)$array == $array && substr($array,0,1) != "0" ){
                    return (float)$array;
                }elseif( (string)(bool)$array == $array ){
                    return (bool)$array;    
                }else{
                    return $array;
                }
            }

            $object = new \stdClass();
            if (is_array($array) && count($array) > 0) {
                foreach ($array as $name=>$value) {
                    $name = trim($name);
                    if (!empty($name)) {
                        $object->$name = $this->_arrayToObject($value,$name);
                    }
                }
                return $object; 
            }else {
                return false;
            }
        }
        
         private function _header(){
            return array(
                /*"Content-type: text/xml;charset=\"utf-8\"",*/
                "Authorization: Basic " . base64_encode("api:".$this->_apikey)
            );
        }
    }
?>
