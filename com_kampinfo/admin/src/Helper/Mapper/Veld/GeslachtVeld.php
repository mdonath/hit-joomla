<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Bewaart alleen de eerste letter. */
class GeslachtVeld extends AbstractImportVeld {

    private $man = 'M';
    private $vrouw = 'V';

    public function __construct($kolom, $man='M', $vrouw='V', $actief=true) {
        parent::__construct($kolom, $actief);
        $this->man = $man;
        $this->vrouw = $vrouw;
    }

    public function convert($value) {
        $first = \substr($value, 0, 1);
        if ($first == $this->vrouw) {
            $first = 'V';
        } else if ($first == $this->man) {
            $first = 'M';
        }
        return $first;
    }

}
