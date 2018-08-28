<?php

namespace EdipostService\Client\Builder;


class ConsignmentBuilder {
	protected $id;
	protected $consigneeId;
	protected $consignorId;
	protected $contentReference;
	protected $transportInstructions;
	protected $internalReference;
	protected $productId;
	protected $items = array();
	protected $services = array();


	public function __construct() {
	}


	public function setConsignorID($id) {
		$this->consignorId = strval($id);
		return $this;
	}

	public function setConsigneeID($id) {
		$this->consigneeId = strval($id);
		return $this;
	}

	public function setProductID($id) {
		$this->productId = strval($id);
		return $this;
	}

	public function setContentReference($txt) {
		$this->contentReference = $txt;
		return $this;
	}

	public function setTransportInstructions($txt) {
		$this->transportInstructions = $txt;
		return $this;
	}

	public function setInternalReference($txt) {
		$this->internalReference = $txt;
		return $this;
	}

	public function addItem($item) {
		$this->items[] = $item;
		return $this;
	}

	public function addService($serviceId, $keyValue = array()) {
		$service = new \EdipostService\Client\Service();
		$service->setId($serviceId);

		// Add properties, if any
		if (count($keyValue) > 0) {
			$properties = new \EdipostService\Client\Properties();

			foreach ($keyValue as $key => $value) {
				$property = new \EdipostService\Client\Property();
				$property->setKey($key);
				$property->setValue($value);

				$properties->addProperty($property);
			}

			$service->setProperties($properties);
		}


		$this->services[] = $service;

		return $this;
	}


	public function build() {
		$consignment = new \EdipostService\Client\Consignment();

		$consignor = new \EdipostService\Client\Consignor();
		$consignor->ID = $this->consignorId;
		$consignment->consignor = $consignor;

		$consignee = new \EdipostService\Client\Consignee();
		$consignee->ID = $this->consigneeId;
		$consignment->consignee = $consignee;

		$product = new \EdipostService\Client\Product();
		$product->setId($this->productId);


		// Add services
		$services = new \EdipostService\Client\Services();

		foreach ($this->services as $service) {
			$services->addService($service);
		}

		$product->addServices($services);


		$consignment->product = $product;

		$items = new \EdipostService\Client\Items();
		foreach ($this->items as $i) {
			$items->addItem($i);
		}
		$consignment->addItems($items);
		$consignment->contentReference = (empty($this->contentReference) ? "." : substr($this->contentReference, 0, 70));
		$consignment->transportInstructions = (empty($this->transportInstructions) ? "." : substr($this->transportInstructions, 0, 500));
		$consignment->internalReference = (empty($this->internalReference) ? "." : substr($this->internalReference, 0, 35));


		return $consignment;
	}

}