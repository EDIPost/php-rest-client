<?php
    namespace EdipostService\Client;

    /** @XmlRoot(Items) */
    class Items{
    
        /** @XmlElement(Item, item) */
        public $items;

        public function __construct(){
            $this->items = new \ArrayObject();
        }
        
        public function addItem($item){
            $this->items[] = $item;
        }
    }