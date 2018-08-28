<?php

namespace EdipostService\Client;

/** @XmlRoot(consignor) */
class Consignor extends Party {

	/** @XmlAttribute(string, id) */
	public $ID;

}