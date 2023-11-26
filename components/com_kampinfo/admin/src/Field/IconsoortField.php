<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


class IconsoortField extends ListField {

    protected $type = 'Iconsoort';

    public function getOptions() {
        return array_merge(
            parent::getOptions(),
            KampInfoHelper::getHitIconSoortOptions()
        );
    }
}
