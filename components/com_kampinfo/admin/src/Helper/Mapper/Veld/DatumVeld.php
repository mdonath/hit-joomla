<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Een datumveld, werkt samen met tijdveld. */
class DatumVeld extends AbstractImportVeld {

    private $format = null;

    public function __construct($kolom, $format='d-m-Y', $actief=true) {
        parent::__construct($kolom, $actief);
        $this->format = $format;
    }

    public function set($object, $value) {
        if($this->isActief() && $value != '') {
            $kolom = $this->getKolom();
            $value = $this->convert($value);
            $date = DateTime::createFromFormat($this->format, $value);
            $date->setTime(0, 0);
            if (property_exists($object, $kolom)) {
                $object->$kolom->setDate($date);
            } else {
                $object->$kolom = $date;
            }
        }
    }

}
