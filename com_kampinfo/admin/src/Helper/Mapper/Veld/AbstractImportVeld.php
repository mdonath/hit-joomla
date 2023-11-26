<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');


/** Baseclass voor alle soorten importvelden. */
abstract class AbstractImportVeld {

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
