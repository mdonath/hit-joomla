<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Een veld dat meerdere waardes kan bevatten. */
abstract class ArrayVeld extends AbstractImportVeld {

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
