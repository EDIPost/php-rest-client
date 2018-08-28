<?php
    namespace EdipostService\Client;

    /** @XmlElement(item) */
    class Item{

        /** 
        * @XmlElement(double, weight) 
        */
        protected $weight;

        /** 
        * @XmlElement(double, height) 
        */
        protected $height;

        /** 
        * @XmlElement(double, width) 
        */
        protected $width;

        /** 
        * @XmlElement(double, length) 
        */
        protected $length;

		/**
		 * @XmlElement(double, cost)
		 */
		protected $cost;
        
        protected $itemNumber;

        public function __construct( $weight = 0, $height = 0, $width = 0, $length = 0 ){
            $this->setWeight( (double)$weight);
            $this->setHeight( (double)$height);
            $this->setWidth( (double)$width);
            $this->setLength( (double)$length);
        }

        public function setWeight($w){
            $this->weight = $w;   
        }

        public function setHeight( $h ){
            $this->height = $h;
        }

        public function setWidth( $w ){
            $this->width = $w;
        }

        public function setLength( $l ){
            $this->length = $l;
        }

		public function setCost( $cost ) {
			$this->cost = $cost;
		}


		public function getCost() {
			return $this->cost;
		}


		/**
		 * Sets the connotenumber
		 * @param $no
		 */
        public function setItemNumber( $no ){
            $this->itemNumber = $no;
        }
        
        /**
        * gets the item number
        * @return string
        */
        public function getItemNumber(){
            return $this->itemNumber;
        }
    }