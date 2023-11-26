<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractArrayVeld;


/** Speciaal veld voor icoontjes. */
class IcoonVeld extends ArrayVeld {

    public function __construct($key, $kolom = 'icoontjes', $actief = true) {
        parent::__construct($key, $kolom, $actief);
    }

}
