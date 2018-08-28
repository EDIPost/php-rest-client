<?php

namespace EdipostService\Client;

/** @XmlRoot(Services) */
class Services {

	/** @XmlElement(Service, service) */
	private $services;

	public function __construct() {
		$this->services = new \ArrayObject();
	}

	public function addService($service) {
		$this->services[] = $service;
	}
}