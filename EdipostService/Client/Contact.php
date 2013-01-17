<?php
    namespace EdipostService\Client;

    class Contact{
        private $name;
        private $telephone;
        private $cellphone;
        private $telefax;
        private $email;


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
