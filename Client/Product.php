<?php
    namespace EdipostService\Client;

    /** @XmlRoot(product) */
    class Product{
        /** @XmlAttribute(int, id) */
        private $id;
		private $name;
		private $status;

        /** @XmlElement(Services, services) */
        private $services;


        public function addService($service){
            $this->services[] = $service;
        }


		/**
		 * @param mixed $id
		 */
		public function setId( $id ) {
			$this->id = $id;
		}


		/**
		 * @return mixed
		 */
		public function getId() {
			return $this->id;
		}


		/**
		 * @param mixed $name
		 */
		public function setName( $name ) {
			$this->name = $name;
		}


		/**
		 * @return mixed
		 */
		public function getName() {
			return $this->name;
		}


		/**
		 * @param mixed $services
		 */
		public function setServices( $services ) {
			$this->services = $services;
		}


		/**
		 * @return mixed
		 */
		public function getServices() {
			return $this->services ? $this->services : array();
		}


		/**
		 * @param mixed $status
		 */
		public function setStatus( $status ) {
			$this->status = $status;
		}


		/**
		 * @return mixed
		 */
		public function getStatus() {
			return $this->status;
		}



    }
?>
