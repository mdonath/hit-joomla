<?php

class SolConfiguration {

	public $user = null;
	public $password = null;
	public $role = null;
	public $sui = null;
	public $keypriv = null;
	public $wsdl = null;
	
	public function __construct($params) {
		$this->user = $params->get('soapUser');
		$this->password = $params->get('soapPassword');
		$this->role = $params->get('soapRolemask');
		$this->sui = $params->get('soapSui');
		$this->keypriv = $params->get('soapPrivateKey');
		$this->wsdl = $params->get('soapWsdl');
	}
}