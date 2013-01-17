<?php
    namespace EdipostService\ServiceConnection;

    require_once "EdipostService/Utils/lexa-xml-serialization.php";


    abstract class Communication{
        public function __construct(){}

        public function xml_serialize(){
            $dom = \Lexa\XmlSerialization\XmlSerializer::serialize($this);
            $dom->formatOutput = true;
            return $dom->saveXML();
        }     
    }
?>
