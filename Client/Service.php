<?php
    namespace EdipostService\Client;

    /** @XmlRoot(service) */
    class Service{
        /** @XmlAttribute(int, id) */
        private $id;
		private $name;
		private $cost;


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
		 * @param mixed $cost
		 */
		public function setCost( $cost ) {
			$this->cost = $cost;
		}


		/**
		 * @return mixed
		 */
		public function getCost() {
			return $this->cost;
		}
    }
?>
