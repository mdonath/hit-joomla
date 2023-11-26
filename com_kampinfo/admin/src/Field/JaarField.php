<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

class JaarField extends ListField {

    protected $type = 'Jaar';

    public function getOptions() {
        return array_merge(
            parent::getOptions(),
            KampInfoHelper::getHitJaarOptions()
        );
    }
}
