<?php

require_once "SolConfiguration.php";

class SolDownload {

	public function downloadInschrijvingen($eventId, $solConfig) {
		return $this->download($eventId, 'event', $solConfig, 'forms');
	}
	
	public function downloadDeelnemers($eventId, $solConfig) {
		return $this->download($eventId, 'participants', $solConfig, 'participants');
	}

	function download($eventId, $type, $solConfig, $root) {
		$url = $solConfig->prefix . $eventId . $solConfig->api . $solConfig->typeParam . $type;
		$json = $this->urlGetContents($url, $solConfig->apiKey);
		$obj = json_decode($json);
		return $obj->$root;
	}

	function urlGetContents($url, $apikey, $useragent='cURL') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . base64_encode($apikey)
		));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}