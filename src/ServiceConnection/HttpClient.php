<?php

namespace EdipostService\ServiceConnection;

class HttpClient {

	/**
	 * Create a POST request
	 *
	 * @param string $url
	 * @param mixed $headers
	 * @param $body
	 * @return response
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function post($url, $headers, $body) {
		$options = array(
			'http' => array(
				'timeout' => 15,
				'ignore_errors' => true,
				'header' => $headers,
				'method' => 'POST',
				'content' => $body
			)
		);

		return $this->request($url, $options);
	}


	/**
	 * Create a GET request
	 *
	 * @param string $url
	 * @param mixed $headers
	 * @return response
	 * @throws CommunicationException
	 * @throws WebException
	 */
	public function get($url, $headers) {
		$options = array(
			'http' => array(
				'timeout' => 15,
				'ignore_errors' => true,
				'header' => $headers,
				'method' => 'GET'
			)
		);

		return $this->request($url, $options);
	}


	/**
	 * Do the HTTP request
	 *
	 * @param $url
	 * @param $options
	 * @return response
	 * @throws CommunicationException
	 * @throws WebException
	 */
	private function request($url, $options) {
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);

		if (!$result) {
			throw new CommunicationException('Error getting data from ' . $url);
		}

		$httpStatus = $this->getHttpCode($http_response_header);

		if ($httpStatus != 200 && $httpStatus != 201) {
			throw new WebException($result, $httpStatus);
		}

		return new response($httpStatus, $result);
	}


	/**
	 * Extract HTTP reponse code
	 * @param $http_response_header
	 * @return int
	 */
	private function getHttpCode($http_response_header) {
		if (is_array($http_response_header)) {
			$parts = explode(' ', $http_response_header[0]);

			if (count($parts) > 1) {
				return intval($parts[1]);
			}
		}

		return 0;
	}
}


class response {
	/** @var string */
	public $data = "";

	/** @var integer */
	public $code = 0;

	public function __construct($code, $data = "") {
		$this->code = $code;
		$this->data = $data;
	}
}
