<?php

class SolConfiguration {

	public $apiKey = null;
	public $prefix = 'https://sol.scouting.nl/as/event/';
	public $api = '/api.json';
	public $typeParam = '?a=';
	
	public function __construct($params) {
		if (isset($params)) {
			$this->apiKey = $params->get('apikey');
		}
	}
}