<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


class SiteField extends ListField {

    protected $type = 'Site';

    public function getOptions() {
        $filter = $this->form->getData()->get('filter');
        if ($filter != NULL && property_exists($filter, 'jaar') && isset($filter->jaar) && $filter->jaar != -1) {
            $options = KampInfoHelper::getHitSiteOptions($filter->jaar);
        } else {
            $options = KampInfoHelper::getHitSiteOptions();
        }
        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);
        
        return $options;
    }

}
