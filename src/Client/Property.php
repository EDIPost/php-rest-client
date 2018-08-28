<?php

namespace EdipostService\Client;

/** @XmlRoot(property) */
class Property {
	/** @XmlAttribute(string, key) */
	private $key;


	/** @XmlAttribute(string, value) */
	private $value;


	/**
	 * @param mixed $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}


	/**
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}


	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}


}