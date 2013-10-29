<?php
    namespace EdipostService\Client;

    /** @XmlRoot(consignment) */
    class Consignment extends \EdipostService\ServiceConnection\Communication{
        /** @XmlElement(Consignor, consignor) */
        public $consignor;
        
        /** @XmlElement(Consignee, consignee) */
        public $consignee;

        /** @XmlElement(Product, product) */                      
        public $product;   
        
        /** @XmlElement(Items, items) */
        public $items; 
        
        /** @XmlElement(string, contentReference) */                      
        public $contentReference = "";
        
        /** @XmlElement(string, transportInstructions) */                      
        public $transportInstructions = "";
        
        /** @XmlElement(string, internalReference) */                      
        public $internalReference = "";
        
        public $shipmentNumber = "";
        
        public $id;
        
        
        
        public function addItems($items){
            $this->items = $items;
        }
        

    }
?>
