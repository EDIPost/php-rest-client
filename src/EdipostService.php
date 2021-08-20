<?php

namespace EdipostService;

// make sure we set up the environment
use EdipostService\Client\Builder\ConsigneeBuilder;
use EdipostService\Client\Builder\ConsignorBuilder;
use EdipostService\Client\Consignor;
use EdipostService\Client\Product;
use EdipostService\Client\Service;
use EdipostService\ServiceConnection\CommunicationException;
use EdipostService\ServiceConnection\ServiceConnection;
use EdipostService\ServiceConnection\WebException;

require_once('init.php');


class EdipostService {
	/** @var ServiceConnection */
	private $conn = null;


	/**
	 * The constructor
	 *
	 * @param mixed $apiKey
	 * @param string $apiUrl
	 */
	public function __construct($apiKey = null, $apiUrl = 'https://api.pbshipment.com') {
		if (isset($apiKey)) {
			$this->connect($apiKey, $apiUrl);
		}
	}


	/**
	 * Fetches the default consignor
	 *
	 * @return \EdipostService\Client\Consignor
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function getDefaultConsignor() {
		$url = "/consignor/default";
		$headers = array("Accept: application/vnd.edipost.party+xml");

		$xml = $this->conn->get($url, null, $headers);


		if (!$xml) {
			return null;
		}

		$cb = new ConsignorBuilder();
		$consignor = $cb->setID($this->xv($xml, "//consignor/@id"))
			->setCompanyName($this->xv($xml, "//consignor/companyName"))
			->setPostAddress($this->xv($xml, "//consignor/postAddress/address"))
			->setPostZip($this->xv($xml, "//consignor/postAddress/zipCode"))
			->setPostCity($this->xv($xml, "//consignor/postAddress/city"))
			->setStreetAddress($this->xv($xml, "//consignor/streetAddress/address"))
			->setStreetZip($this->xv($xml, "//consignor/streetAddress/zipCode"))
			->setStreetCity($this->xv($xml, "//consignor/streetAddress/city"))
			->setContactName($this->xv($xml, "//consignor/contact/name"))
			->setContactCellPhone($this->xv($xml, "//consignor/contact/cellphone"))
			->setContactPhone($this->xv($xml, "//consignor/contact/telephone"))
			->setCountry($this->xv($xml, "//consignor/country"))
			->build();

		return $consignor;
	}


    /**
     * fetches all consignors
     *
     * @return Consignor[]
     * @throws CommunicationException
     * @throws WebException
     */
    public function getConsignors() {
        $url = "/consignor";
        $headers = array("Accept: application/vnd.edipost.collection+xml");

        $xml = $this->conn->get($url, null, $headers);

        if (!$xml) {
            return null;
        }

        $consignors = array();

        foreach ($xml->xpath('/collection/entry') as $consignor) {
            $newConsignor = new Consignor();
            $newConsignor->setId((string)$consignor->attributes()->id);
            $newConsignor->setCompanyName((string)$consignor->companyName);

            $consignors[] = $newConsignor;
        }

        return $consignors;
    }


	/**
	 * fetches the available products for a given consignee
	 *
	 * @param $consignorID
	 * @return Product[]
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function getProducts($consignorID) {
		$url = "/consignee/$consignorID/products";
		$headers = array("Accept: application/vnd.edipost.collection+xml");

		$xml = $this->conn->get($url, null, $headers);


		if (!$xml) {
			return null;
		}


		$products = array();

		foreach ($xml->xpath('/collection/entry') as $product) {
			$newProduct = new Product();
			$newProduct->setId((string)$product->attributes()->id);
			$newProduct->setName((string)$product->attributes()->name);
			$newProduct->setStatus((string)$product->status);

			foreach ($product->xpath('services/service') as $service) {
				$newService = new Service();
				$newService->setId((string)$service->attributes()->id);
				$newService->setName((string)$service->attributes()->name);

				$newProduct->addService($newService);
			}

			$products[] = $newProduct;
		}


		return $products;
	}


	/**
	 * fetches the available products
	 *
	 * @param $fromZipCode
	 * @param $fromCountryCode
	 * @param $toZipCode
	 * @param $toCountryCode
	 * @param $items
	 * @return Product[]
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function getAvailableProducts($fromZipCode, $fromCountryCode, $toZipCode, $toCountryCode, $items) {
		$url = "/product?fromZipCode=$fromZipCode&fromCountryCode=$fromCountryCode&toZipCode=$toZipCode&toCountryCode=$toCountryCode" . $this->getItemsUrlString($items);
		$headers = array("Accept: application/vnd.edipost.collection+xml");

		$xml = $this->conn->get($url, null, $headers);

		$products = array();

		if ($xml && is_object($xml)) {
			foreach ($xml->xpath('/collection/entry') as $product) {
				$newProduct = new Product();
				$newProduct->setId((string)$product->attributes()->id);
				$newProduct->setName((string)$product->attributes()->name);
				$newProduct->setStatus((string)$product->status);
				$newProduct->setDescription((string)$product->description);
				$newProduct->setTransporter((string)$product->transporter->attributes()->name);
				$newProduct->setCost((string)$product->cost);
				$newProduct->setVat((string)$product->vat);

				if ($product->serviceId) {
					$newService = new Service();
					$newService->setId((string)$product->serviceId);
					$newProduct->addService($newService);
				}

				$products[] = $newProduct;
			}
		}

		return $products;
	}


	private function getItemsUrlString($items) {
		$urlString = '';

		foreach ($items as $item) {
			$urlString .= '&item=' . $item['weight'] . '*' . $item['length'] . '*' . $item['width'] . '*' . $item['height'];
		}

		return $urlString;
	}


	/**
	 * Creates a Consignee
	 *
	 * @param \EdipostService\Client\Consignee $consignee
	 * @return bool|Client\Consignee|Client\Party
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function createConsignee($consignee) {
		$url = "/consignee";
		$headers = array(
			'Accept: application/vnd.edipost.party+xml',
			'Content-Type: application/vnd.edipost.party+xml'
		);

		$xml = $this->conn->post($url, new \SimpleXMLElement($consignee->xml_serialize()), $headers);

		if (!$xml instanceof \SimpleXMLElement) {
			return false;
		}

		$party = $this->_buildParty($xml, PARTY_CONSIGNEE);
		return $party;
	}


	/**
	 * Get a Consignee by ID
	 *
	 * @param $consigneeId
	 * @return bool|Client\Consignee|Client\Party
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function getConsignee($consigneeId) {
		$url = "/consignee/$consigneeId";

		$headers = array(
			'Accept: application/vnd.edipost.party+xml'
		);

		$xml = $this->conn->get($url, null, $headers);

		if (!$xml instanceof \SimpleXMLElement) {
			return false;
		}

		$party = $this->_buildParty($xml, PARTY_CONSIGNEE);

		return $party;
	}


	/**
	 * Creates a consignment
	 *
	 * @param \EdipostService\Client\Consignment $consignment
	 * @return bool|Client\Consignment
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function createConsignment($consignment) {
		$url = "/consignment";
		$headers = array(
			'Accept: application/vnd.edipost.consignment+xml',
			'Content-Type: application/vnd.edipost.consignment+xml'
		);

		$xml = $this->conn->post($url, new \SimpleXMLElement($consignment->xml_serialize()), $headers);

		if (!$xml instanceof \SimpleXMLElement) {
			return false;
		}

		$consignment->id = $this->xv($xml, "//consignment/@id");
		$consignment->shipmentNumber = $this->xv($xml, "//consignment/shipmentNumber");
		$i = 0;
		foreach ($xml->items->item as $k => $v) {
			$consignment->items->items{$i}->setItemNumber(reset($v->itemNumber));
			$i++;
		}
		return $consignment;
	}


	/**
	 * Calculate postage for a consignment
	 *
	 * @param \EdipostService\Client\Consignment $consignment
	 * @return bool|Client\Consignment
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function calculatePostage($consignment) {
		$url = "/consignment/postage";

		$headers = array(
			'Accept: application/vnd.edipost.consignment+xml',
			'Content-Type: application/vnd.edipost.consignment+xml'
		);

		$xml = $this->conn->post($url, new \SimpleXMLElement($consignment->xml_serialize()), $headers);

		if (!$xml instanceof \SimpleXMLElement) {
			return false;
		}


		/*
		// TODO - Add cost from services
		$j = 0;
		foreach( $xml->product->services->service as $service ){
			$services = $consignment->product->getServices();

			$services{$j}->setCost( reset($service->cost) );
			$j++;
		}
		*/


		$i = 0;
		foreach ($xml->items->item as $k => $v) {
			$consignment->items->items{$i}->setCost(reset($v->cost));
			$i++;
		}


		return $consignment;
	}


	/**
	 * Fetches the PDF data
	 *
	 * @param $consignment_id
	 * @param null $report
	 * @return \SimpleXMLElement
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function printConsignment($consignment_id, $report = null) {
		$url = "/consignment/$consignment_id/print";

		if (isset($report)) {
			$url .= "?report=" . $report;
		}
		$headers = array("Accept: application/pdf");

		$response = $this->conn->get($url, null, $headers);

		return $response;
	}


	/**
	 * fetches the ZPL data
	 *
	 * @param mixed $consignment_id
	 * @param null $report
	 * @return \SimpleXMLElement
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function printConsignmentZpl($consignment_id, $report = null) {
		$url = "/consignment/$consignment_id/print";

		if (isset($report)) {
			$url .= "?report=" . $report;
		}
		$headers = array("Accept: text/vnd.edipost.consignment+zpl");

		$response = $this->conn->get($url, null, $headers);

		return $response;
	}


	/**
	 * PRVATE FUNCTIONS
	 */


	/**
	 * Private method to build a party object
	 *
	 * @param \SimpleXMLElement $xml
	 * @param string $type
	 * @return Client\Consignee|Client\Party
	 */
	private function _buildParty($xml, $type = PARTY_CONSIGNEE) {
		$namespace = $type;

		if ($type = PARTY_CONSIGNEE) {
			$cb = new ConsigneeBuilder();
		} else {
			$cb = new ConsignorBuilder();
		}

		$party = $cb->setID($this->xv($xml, "//$namespace/@id"))
			->setCustomerNumber($this->xv($xml, "//$namespace/customerNumber"))
			->setCompanyName($this->xv($xml, "//$namespace/companyName"))
			->setPostAddress($this->xv($xml, "//$namespace/postAddress/address"))
			->setPostZip($this->xv($xml, "//$namespace/postAddress/zipCode"))
			->setPostCity($this->xv($xml, "//$namespace/postAddress/city"))
			->setStreetAddress($this->xv($xml, "//$namespace/streetAddress/address"))
			->setStreetZip($this->xv($xml, "//$namespace/streetAddress/zipCode"))
			->setStreetCity($this->xv($xml, "//$namespace/streetAddress/city"))
			->setContactName($this->xv($xml, "//$namespace/contact/name"))
			->setContactCellPhone($this->xv($xml, "//$namespace/contact/cellphone"))
			->setContactPhone($this->xv($xml, "//$namespace/contact/telephone"))
			->setCountry($this->xv($xml, "//$namespace/country"))
			->build();

		return $party;
	}


	/**
	 * Connects to the api server
	 *
	 * @param string $apiKey
	 * @param $apiUrl
	 */
	private function connect($apiKey, $apiUrl) {
		if (empty($apiKey)) {
			$error_msg = "Invalid API key #404UC#";
			trigger_error($error_msg, E_USER_ERROR);
		}

		$this->conn = new ServiceConnection($apiKey, $apiUrl);
	}


	/**
	 * Fetches the correct value based on an x-path
	 *
	 * @param \SimpleXMLElement $xml
	 * @param $xpath
	 * @param string $default
	 * @return string
	 */
	private function xv(&$xml, $xpath, $default = "") {
		try {
			$value = $xml->xpath($xpath);
			if (is_array($value)) {
				$value = reset($value);
			}
		} catch (\Exception $e) {
			return $default;
		}
		return (string)$value;
	}

}
