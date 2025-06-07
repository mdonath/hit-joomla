<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


/**
 * Field voor Iconsoorten.
 */
class IconsoortField extends ListField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Iconsoort';

    /**
     * Method to get the field options.
     *
     * @return  object[]  The field option objects.
     */
    public function getOptions() {
        return array_merge(
            parent::getOptions(),
            KampInfoHelper::getHitIconSoortOptions()
        );
    }

}
