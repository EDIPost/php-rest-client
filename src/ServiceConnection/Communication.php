<?php
    namespace EdipostService\ServiceConnection;

    
    abstract class Communication{
        public function __construct(){}

        public function xml_serialize(){
            self::html_encode_object($this);
            $dom = \Lexa\XmlSerialization\XmlSerializer::serialize($this);
            $dom->formatOutput = true;
            $xml = $dom->saveXML();
            $xml = html_entity_decode( html_entity_decode($xml) );
            
            // Make sure & gets correctly formatted
            $xml = str_replace("&", "", $xml );
            
            if ( !mb_check_encoding( $xml, "UTF-8" ) ){
                $xml = utf8_encode($xml);
            }
            
            return $xml;
        }
        
         private static function html_encode_object( &$object ){
            foreach( $object as $key => &$item ){
                switch ( gettype($item) ) {
                    case "object":  self::html_encode_object( $item ); break;
                    case "array":  self::html_encode_object( $item ); break;
                    case "string": $item = htmlentities($item); break;
                }
            }
        }
    }