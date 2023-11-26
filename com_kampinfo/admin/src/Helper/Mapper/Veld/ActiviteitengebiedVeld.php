<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractArrayVeld;


/** Speciaal veld voor activiteitengebieden. */
class ActiviteitengebiedVeld extends AbstractArrayVeld {

    public function __construct($key, $kolom = 'activiteitengebieden', $actief = true) {
        parent::__construct($key, $kolom, $actief);
    }

}
