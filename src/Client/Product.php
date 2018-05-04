<?php
namespace EdipostService\Client;

/** @XmlRoot(product) */
class Product {
	/** @XmlAttribute(int, id) */
	private $id;
	private $name;
	private $description;
	private $status;
	private $transporter;
	private $cost;
	private $vat;


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


	public function setDescription( $description ) {
		$this->description = $description;
	}


	public function getDescription() {
		return $this->description;
	}


	public function setStatus( $status ) {
		$this->status = $status;
	}


	public function getStatus() {
		return $this->status;
	}


	public function getTransporter() {
		return $this->transporter;
	}


	public function setTransporter( $transporter ) {
		$this->transporter = $transporter;
	}


	public function getCost() {
		return $this->cost;
	}


	public function setCost( $cost ) {
		$this->cost = $cost;
	}


	public function getVat() {
		return $this->vat;
	}


	public function setVat( $vat ) {
		$this->vat = $vat;
	}

}

?>
