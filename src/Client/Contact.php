<?php
    namespace EdipostService\Client;

    /** @XmlRoot(Contact) */
    class Contact{
        /** @XmlElement(string, name) */
        public $name;
        /** @XmlElement(string, telephone) */
        public $telephone;
        /** @XmlElement(string, cellphone) */
        public $cellphone;
        /** @XmlElement(string, telefax) */
        public $telefax;
        /** @XmlElement(string, email) */
        public $email;


        public function getName() {
            return $this->name;
        }


        public function setName( $name ) {
            $this->name = $name;
        }


        public function getTelephone() {
            return $this->telephone;
        }


        public function setTelephone( $telephone ) {
            $this->telephone = $telephone;
        }


        public function getCellphone() {
            return $this->cellphone;
        }


        public function setCellphone( $cellphone ) {
            $this->cellphone = $cellphone;
        }


        public function getTelefax() {
            return $this->telefax;
        }


        public function setTelefax( $telefax ) {
            $this->telefax = $telefax;
        }


        public function getEmail() {
            return $this->email;
        }


        public function setEmail( $email ) {
            $this->email = $email;
        }
    }
?>
