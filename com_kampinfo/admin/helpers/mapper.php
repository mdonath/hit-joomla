<?php

include_once "kampinfo.php";

abstract class AbstractMapper {
	protected $mapping;
	protected $columns;

	public function __construct($mapping) {
		$this->mapping = $mapping;
	}

	protected function isMappable($name) {
		return  (array_key_exists($name, $this->mapping));
	}

}

class XmlMapper extends AbstractMapper {

	public function __construct($mapping) {
		parent::__construct($mapping);
	}

	public function read($filename) {
		$document = $this->readXmlFromFile($filename);
		$this->leesHeader($document);
		return $this->leesData($document);
	}
	
	public function readString($xml) {
		$document = $this->readXmlFromString($xml);
		$this->leesHeader($document);
		return $this->leesData($document);
	}
	
	private function readXmlFromString($xml) {
		$document = $this->createDomDocument();
		$document->loadXml($xml);
		return $document;
	}
	
	private function readXmlFromFile($filename) {
		$document = $this->createDomDocument();
		$document->load($filename);
		return $document;
	}

	private function createDomDocument() {
		$document = new DOMDocument('1.0', 'utf-8');
		$document->formatOutput = false;
		$document->preserveWhiteSpace = false;
		return $document;
	}
	
	private function leesHeader($document) {
		$domxpath = new DOMXPath($document);
		$elements = $domxpath->query('(//listheader)[1]/row/*');

		$rows = array();
		if (!is_null($elements)) {
			foreach ($elements as $element) {
				$nodes = $element->childNodes;
				foreach ($nodes as $node) {
					$rows[] = $node->nodeValue;
				}
			}
		}
		$this->columns = $rows;
	}

	private function leesData($document) {
		$rows = array();

		$domxpath = new DOMXPath($document);
		// Er is ook een tweede listbody met REST / pager -achtige dingen. We moeten alleen de eerst hebben.
		$elements = $domxpath->query('(//listbody)[1]/row');

		if (!is_null($elements)) {
			foreach ($elements as $element) {
				$data = $element->childNodes;
				$object = new stdClass();
				$num = $data->length;
				for ($i = 0; $i < $num; $i++) {
					$kolom = $this->columns[$i];
					if ($this->isMappable($kolom)) {
						$veld = $this->mapping[$kolom];
						$veld->set($object, $data->item($i)->nodeValue);
					}
				}
				if (isset($object->status)) {
					if ($object->status == 'Deelname afgerond' || $object->status == 'Deelnemer staat ingeschreven') {
						$rows[] = $object;
					}
				} else {
					$rows[] = $object;
				}
			}
		}
		return $rows;
	}
}

class CsvMapper extends AbstractMapper {

	public function __construct($mapping) {
		parent::__construct($mapping);
	}

	public function read($file) {
		$rows = array();
		if (($handle = fopen($file, "r")) !== FALSE) {
			$this->leesHeader($handle);
			$rows = $this->leesData($handle);
			fclose($handle);
		}
		return $rows;
	}

	private function leesHeader($handle) {
		$this->columns = $this->readLine($handle);
	}

	private function leesData($handle) {
		$rows = array();
		while (($data = $this->readLine($handle)) !== FALSE) {
			$object = new stdClass();
			$num = count($data);
			for ($i = 0; $i < $num; $i++) {
				$kolom = $this->columns[$i];
				if ($this->isMappable($kolom)) {
					$veld = $this->mapping[$kolom];
					$veld->set($object, $data[$i]);
				}
			}
			$rows[] = $object;
		}
		return $rows;
	}

	private function readLine($handle) {
		return fgetcsv($handle, 0, ",");
	}

}

/** Baseclass voor alle soorten importvelden. */
abstract class ImportVeld {
	private $kolom;
	private $actief;

	public function __construct($kolom, $actief=true) {
		$this->kolom = $kolom;
		$this->actief = $actief;
	}

	public function isActief() {
		return $this->actief;
	}

	public function getKolom() {
		return $this->kolom;
	}

	public function set($object, $value) {
		if($this->isActief()) {
			$kolom = $this->kolom;
			$object->$kolom = $this->convert($value);
		}
	}

	public function convert($value) {
		return $value; //iconv('UTF-8', 'ASCII//TRANSLIT', $value);
	}
}

/** Veld dat overgeslagen wordt. */
class IgnoredVeld extends ImportVeld {
	public function __construct() {
		parent::__construct(null, false);
	}
}

/** Een gewoon tekstveld. */
class GewoonVeld extends ImportVeld {
	public function __construct($kolom, $actief=true) {
		parent::__construct($kolom, $actief);
	}
}

/** Bewaart alleen de eerste letter. */
class GeslachtVeld extends ImportVeld {
	public function __construct($kolom, $actief=true) {
		parent::__construct($kolom, $actief);
	}
	
	public function convert($value) {
		return substr($value, 0, 1);
	}
}

/** Bewaart alleen de leeftijd tijdens de HIT. */
class LeeftijdVeld extends ImportVeld {
	private $jaar;
	public function __construct($kolom, $jaar, $actief=true) {
		parent::__construct($kolom, $actief);
		$this->jaar = $jaar;
	}
	
	public function convert($value) {
		$date = DateTime::createFromFormat('d-m-Y', $value);
		$eersteHitdag = KampInfoHelper::eersteHitDag($this->jaar);
		$interval = $eersteHitdag->diff($date);
		return $interval->y;
	}
}

/** Een datumveld, werkt samen met tijdveld. */
class DatumVeld extends ImportVeld {
	public function __construct($kolom, $actief=true) {
		parent::__construct($kolom, $actief);
	}

	public function set($object, $value) {
		if($this->isActief() && $value != '') {
			$kolom = $this->getKolom();
			$value = $this->convert($value);
			$date = DateTime::createFromFormat('d-m-Y', $value);
			$date->setTime(0, 0);
			if (property_exists($object, $kolom)) {
				$object->$kolom->setDate($date);
			} else {
				$object->$kolom = $date;
			}
		}
	}
}

/** Een tijdveld, werkt samen met datumveld. */
class TijdVeld extends ImportVeld {
	public function __construct($kolom, $actief=true) {
		parent::__construct($kolom, $actief);
	}

	public function set($object, $value) {
		if($this->isActief() && $value != '') {
			$kolom = $this->getKolom();
			$value = $this->convert($value);
			$time = DateTime::createFromFormat('G:i', $value);
			if (property_exists($object, $kolom)) {
				$value = explode(":", $value);
				$object->$kolom -> setTime($value[0], $value[1]);
			} else {
				$object->$kolom = $time;
			}
		}
	}
}

/** Een veld dat meerdere waardes kan bevatten. */
abstract class ArrayVeld extends ImportVeld {
	private $key;
	public function __construct($key, $kolom, $actief = true) {
		parent::__construct($kolom, $actief);
		$this->key = $key;
	}

	public function set($object, $value) {
		if($this->isActief() && $value != '') {
			$kolom = $this->getKolom();
			if (!property_exists($object, $kolom)) {
				$object->$kolom = array();
			}
			array_push($object->$kolom, $this->mapValue($this->convert($value)));
		}
	}

	function mapValue($value) {
		return $this->key;
	}
}

/** Speciaal veld voor activiteitengebieden. */
class ActiviteitengebiedVeld extends ArrayVeld {
	public function __construct($key, $kolom = 'activiteitengebieden', $actief = true) {
		parent::__construct($key, $kolom, $actief);
	}
}

/** Speciaal veld voor icoontjes. */
class IcoonVeld extends ArrayVeld {
	public function __construct($key, $kolom = 'icoontjes', $actief = true) {
		parent::__construct($key, $kolom, $actief);
	}
}

/** Speciaal veld voor doelgroepen. */
class DoelgroepVeld extends ArrayVeld {
	public function __construct($key, $kolom = 'doelgroepen', $actief = true) {
		parent::__construct($key, $kolom, $actief);
	}
}

