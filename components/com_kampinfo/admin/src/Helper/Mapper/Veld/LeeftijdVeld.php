<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


/** Bewaart alleen de leeftijd tijdens de HIT. */
class LeeftijdVeld extends AbstractImportVeld {

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
