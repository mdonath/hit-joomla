<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Een tijdveld, werkt samen met datumveld. */
class TijdVeld extends AbstractImportVeld {

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
