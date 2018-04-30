<?php
    namespace EdipostService\Client;
    
    /** @XmlRoot(consignee) */
    class Consignee extends Party{
        
        /** @XmlAttribute(string, id) */
        public $ID;
       
    }    

?>
