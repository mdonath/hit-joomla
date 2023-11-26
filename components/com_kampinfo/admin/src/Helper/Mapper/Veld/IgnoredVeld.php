<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Veld dat overgeslagen wordt. */
class IgnoredVeld extends AbstractImportVeld {

    public function __construct() {
        parent::__construct(null, false);
    }

}
