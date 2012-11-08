<?php

class Mapper {
	private $mapping;
	private $columns;
	
	public function __construct($mapping) {
		$this->mapping = $mapping;
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
	
	private function isMappable($name) {
		return  (array_key_exists($name, $this->mapping));
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

