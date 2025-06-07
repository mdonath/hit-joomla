<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;


class JaarField extends ListField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Jaar';

    /**
     * Method to get the field options.
     *
     * @return  object[]  The field option objects.
     */
    public function getOptions() {
        // Merge any additional options in the XML definition.
        return array_merge(
            parent::getOptions(),
            $this->getJaarFieldOptions()
        );
    }

    private function getJaarFieldOptions() {
        $db     = $this->getDatabase();
        $query  = $db->getQuery(true)
            ->select([
                $db->quoteName('p.jaar', 'value'),
                $db->quoteName('p.jaar', 'text'),
            ])
            ->from($db->quoteName('#__kampinfo_hitproject', 'p'))
            ->order($db->quoteName('p.jaar'). ' DESC');

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
