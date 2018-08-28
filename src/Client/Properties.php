<?php

namespace EdipostService\Client;

/** @XmlRoot(Properties) */
class Properties {

	/** @XmlElement(Property, property) */
	private $properties;

	public function __construct() {
		$this->properties = new \ArrayObject();
	}

	public function addProperty($property) {
		$this->properties[] = $property;
	}
}