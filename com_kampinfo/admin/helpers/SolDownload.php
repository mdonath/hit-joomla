<?php

include_once "SolSoapClient.php";

class SolDownload {

	public function downloadForm($user, $password, $role, $sui, $keypriv, $wsdl, $frm_id, $parts) {
		return $this->download($user, $password, $role, $sui, $keypriv, $wsdl
				, 'as_form', 'report', 'part_data', array('frm_id' => $frm_id, 'prt_st_id' => $parts));
	}
	
	public function downloadEvent($user, $password, $role, $sui, $keypriv, $wsdl, $evt_id, $what) {
		return $this->download($user, $password, $role, $sui, $keypriv, $wsdl
				, 'as_event', $what, 'export', array('evt_id' => $evt_id));
	}


	public function downloadHelp($user, $password, $role, $sui, $keypriv, $wsdl, $clientName) {
		return $this->download($user, $password, $role, $sui, $keypriv, $wsdl
				, 'soap_client', 'list', 'tab', array('scl_nm' => $clientName));
	}

	
	// https://sol.scouting.nl/as/event/7239/forms/?evt_id=7239&export=true
	public function downloadEventNew($user, $password, $role, $sui, $keypriv, $wsdl, $evt_id, $what) {
		return $this->downloadNew($user, $password, $role, $sui, $keypriv, $wsdl
				, 'event', $evt_id, $what, array(/*'export' => 'true', */'evt_id' => $evt_id));
	}
	
	public function downloadNew($user, $password, $role, $sui, $keypriv, $wsdl, $module, $id, $what, $params) {
		$client = new SolSoapClient($sui, $keypriv, $wsdl);
	
		try {
			self::signOnWithRole($client, $user, $password, $role);
			$form_data = $client->doTAB($module, $id, $what, $params);
			$result = self::toXml($form_data);
			$soap = $client->logout();
	
			return $result;
		} catch (Exception $e) {
			// En nou?
			print_r($e);
			return false;
		}
	}
	
	public function download($user, $password, $role, $sui, $keypriv, $wsdl, $task, $action, $button, $params) {
		$client = new SolSoapClient($sui, $keypriv, $wsdl);
	
		try {
			self::signOnWithRole($client, $user, $password, $role);
			$form_data = $client->doTAB($task, $action, $button, $params);
			$result = self::toXml($form_data);
			$soap = $client->logout();
	
			return $result;
		} catch (Exception $e) {
			// En nou?
			print_r($e);
			return false;
		}
	}
	
	
	private static function signOnWithRole($client, $user, $password, $role) {
		$soap = $client->signOn($user, $password);
		$per_id = $soap['per_id'];
		//self::switchToRole($client, str_replace('%SNID%', $per_id, $role));
	}

	private static function switchToRole($client, $role) {
		$switched_role = $client->doTAB("ma_function", "edit", "changeRole", array('role_id'=>$role));

		$document = new DomDocument();
		$document->loadXML($switched_role);
		$XPath = new DomXPath($document);
		$nodes = $XPath->query("//result/var[@name='sess_id']");
		foreach ($nodes as $node) {
			$client->pub_session_id = $node->textContent;
		}
	}

	private static function toXml($form_data) {
		$document = new DomDocument();
		$document->loadXML($form_data);
		$document->formatOutput = true;
		return $document->saveXML();
	}
}