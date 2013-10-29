<?php
    namespace EdipostService\Client;
    
    /** @XmlRoot(Consignor) */
    class Consignor extends Party{
        
        /** @XmlAttribute(string, id) */
        public $ID;
       
    }    

?>
