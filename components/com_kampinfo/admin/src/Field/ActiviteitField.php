<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


class ActiviteitField extends ListField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Activiteit';

    /**
     * Method to get the field options.
     *
     * @return  object[]  The field option objects.
     */
    public function getOptions() {
        // Merge any additional options in the XML definition.
        $options = array_merge(
            parent::getOptions(),
            $this->getActiviteitFieldOptions()
        );

        return $options;
    }

    private function getActiviteitFieldOptions() {
        $db     = $this->getDatabase();
        $query  = $db->getQuery(true)
            ->select([
                    $db->quoteName('c.id', 'value'),
                    'CONCAT(' .
                        $db->quoteName('c.naam') .
                        ', ' .
                        $db->quote(' (') .
                        ', ' .
                        $db->quoteName('s.naam') .
                        ', ' .
                        $db->quote(' - ') .
                        ', ' .
                        $db->quoteName('p.jaar') .
                        ', ' .
                         $db->quote(')') .
                    ') AS text',
                    $db->quoteName('s.naam', 'plaats'),
                    $db->quoteName('s.id', 'hitsite_id'),
                ])
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('c.hitsite_id') .'='. $db->quoteName('s.id')
            )
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .'='. $db->quoteName('p.id')
            )
            ->order([
                $db->quoteName('p.jaar') . ' DESC',
                $db->quoteName('s.naam') . ' ASC',
                $db->quoteName('c.naam') . ' ASC',
            ]);

        // Get the options.
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
