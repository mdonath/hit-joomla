<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\Database\ParameterType;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;


class SiteField extends ListField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Site';

    /**
     * Method to get the field options.
     *
     * @return  object[]  The field option objects.
     */
    public function getOptions() {
        // Merge any additional options in the XML definition.
        return array_merge(
            parent::getOptions(),
            $this->getHitSiteFieldOptions()
        );
    }

    private function getHitSiteFieldOptions() {
        $db     = $this->getDatabase();
        $query  = $db->getQuery(true)
            ->select([
                $db->quoteName('s.id', 'value'),
                'CONCAT(' .
                    $db->quoteName('s.naam') .
                    ', ' .
                    $db->quote(' (') .
                    ', ' .
                    $db->quoteName('p.jaar') .
                    ', ' .
                    $db->quote(')') .
                ') AS text'
            ])
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('p.id') .' = '. $db->quoteName('s.hitproject_id')
            )
            ->order([
                $db->quoteName('p.jaar') . ' DESC',
                $db->quoteName('s.naam')
            ]);

        $filter = $this->form->getData()->get('filter');
        if ($filter != NULL && property_exists($filter, 'jaar') && isset($filter->jaar) && $filter->jaar != -1) {
            $query
                ->where($db->quoteName('s.hitproject_id') . ' = :hitproject_id')
                ->bind(':hitproject_id', $filter->jaar, ParameterType::INTEGER)
            ;
        }

        $db->setQuery($query);

        $options = [];
        try {
            $options = $db->loadObjectList();
        } catch (\RuntimeException $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }
        return $options;
    }

}
