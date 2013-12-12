<?php
namespace EdipostService\Client;

/** @XmlRoot(product) */
class Product {
	/** @XmlAttribute(int, id) */
	private $id;
	private $name;
	private $status;


	/** @XmlElement(Services, services) */
	private $services = array();


	public function addService( $service ) {
		$this->services[ ] = $service;
	}


	public function addServices( $services ) {
		$this->services = $services;
	}


	public function getServices() {
		return $this->services;
	}


	public function setId( $id ) {
		$this->id = (int)$id;
	}


	public function getId() {
		return $this->id;
	}


	public function setName( $name ) {
		$this->name = $name;
	}


	public function getName() {
		return $this->name;
	}


	public function setStatus( $status ) {
		$this->status = $status;
	}


	public function getStatus() {
		return $this->status;
	}
}

?>
