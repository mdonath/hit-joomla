<?php
/**
 * Change this setting to prevent problems when a new WSDL is released.
 */
ini_set('soap.wsdl_cache_enabled', '0');
/**
 * Soapclient implementation for use with Scouts Online.
*/
class SolSoapClient extends SoapClient {

	/**
	 * Scouts OnLine Unique Identifier
	 *
	 * @var string
	 */
	private $_SUI;

	/**
	 * SOL session identifier of the logged in user
	 * @var string
	 */
	private $_session_id = '';
	public $pub_session_id = '';

	/**
	 * SOL session name
	 * @var string
	 */
	private $_session_nm = '';
	public $pub_session_nm = '';

	/**
	 * The PRIVATE KEY.
	 * The private key needs to be supported when an instance of the class is constructed.
	 */
	private $_private_key = '';

	/**
	 * A url that points to the WSDL file that will be used by the client
	 */
	private $_wsdl_url = '';

	/**
	 * Constructor
	 * Creates the SoapClient
	 *
	 * @param string $SUI ScoutsOnLine Unique Identifier
	 * @param string $private_key Private key of the X509 certificate
	 * @param string $wsdl_url Url to the wsdl file that has to be used.
	 */
	function __construct($SUI, $private_key, $wsdl_url) {
		$this->_SUI = $SUI;
		$this->_private_key = $private_key;
		$this->_wsdl_url = $wsdl_url;
		/*
		 * Note: the trace option enables the use of the SOAP functions
		* - __getLastRequest()
		* - __getLastResponse()
		*/
		parent::__construct($wsdl_url, array(
				'soap_version' => SOAP_1_2,
				'trace' => 1,
				'exceptions' => 1)
		);
	}

	/**
	 * Get accessor for the SOL session id
	 * @return string
	 */
	public function getSessionId() {
		$this->_session_id = $this->pub_session_id;
		return $this->pub_session_id;
	}

	/**
	 * Get accessor for the SOL session id
	 *
	 * @return string
	 */
	public function getSessionNm() {
		$this->_session_nm = $this->pub_session_nm;
		return $this->pub_session_nm;
	}

	/**
	 * Performs the actual request
	 * Also adds the name and signature headers
	 * Note that it throws an exception when a SOAP fault is received
	 *
	 * @throws SOAPFault Exception
	 * @param string $function_name
	 * @param array $arguments
	 * @return string Or returns FALSE when an error occured.
	 */
	public function __call($function_name, $arguments) {
		$this->_session_id = $this->pub_session_id;
		$this->_session_nm = $this->pub_session_nm;
		//calculate signature
		$timezone_correction = time() + (6 * 60 * 60);
		$text_content = $this->_getTextContent($arguments);
		if ($function_name == 'signOn') {
			$signature_string = "<sign><time>" . $timezone_correction . "</time><check>" .md5($text_content) . "</check></sign>";
		} else {
			$signature_string = "<sign><time>" . $timezone_correction . "</time><sess_id>". $this->pub_session_id . "</sess_id><check>" .md5($text_content) . "</check></sign>";
		}
		$priv_key = openssl_get_privatekey($this->_private_key);
		openssl_private_encrypt($signature_string, $signed_data, $priv_key);
		$encoded_data = base64_encode($signed_data);
		$input_headers = array(new SoapHeader('http://dev1.scouting.nl', 'signature', $encoded_data), new SoapHeader('http://dev1.scouting.nl', 'name', $this->_SUI));
		$result = parent::__soapCall($function_name, $arguments, null, $input_headers, $output_headers);
		if (!$this->_checkOutputHeaders($output_headers)) {
			$result = false;
		}
		if ($function_name == 'signOn') {
			$this->_session_id = $result['sess_id'];
			$this->_session_nm = $result['sess_nm'];
			$this->pub_session_id = $result['sess_id'];
			$this->pub_session_nm = $result['sess_nm'];
		}
		return $result;
	}

	/**
	 * Determines the text content of the $arguments
	 *
	 * @param array $arguments
	 * @return string
	 */
	private function _getTextContent($arguments) {
		$result = '';
		foreach ($arguments as $value) {
			if (is_array($value)) {
				$result .= $this->_getTextContent_recursive($value);
			} else {
				$result .= $value;
			}
		}

		return $result;
	}

	/**
	 * Determines the text content of $argument
	 * Note that this function differs from _getTextContent
	 *
	 * @param array $argument
	 * @return string
	 */
	private function _getTextContent_recursive($arguments) {
		$result = '';
		foreach ($arguments as $key=>$value) {
			if (!is_numeric($key)) {
				$result .= $key;
			}

			if (is_array($value)) {
				$result .= $this->_getTextContent_recursive($value);
			} else {
				$result .= $value;
			}
		}
		return $result;
	}

	/**
	 * Checks of the headers of the last response are valid.
	 * This means that this method checks if the response was from SOL
	 * and if the signature was signed by SOL
	 * and is a signature of the content of the last response.
	 *
	 * @param array &$outputheaders
	 * @return boolean
	 */
	private function _checkOutputHeaders(&$outputheaders) {
		//Not implemented in this example
		return true;
	}
}
?>