<?php
    namespace EdipostService\Client;

    /** @XmlRoot(product) */
    class Product{
        /** @XmlAttribute(int, id) */
        private $id;

        /** @XmlElement(Services, services) */
        private $services;

        
        public function setId($id){
            $this->id = (int)$id;
        }

        

        public function addServices($services){
            $this->services = $services;
        }
    }
?>
