<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\AbstractImportVeld;


/** Een gewoon tekstveld. */
class GewoonVeld extends AbstractImportVeld {

    public function __construct($kolom, $actief=true) {
        parent::__construct($kolom, $actief);
    }

}
