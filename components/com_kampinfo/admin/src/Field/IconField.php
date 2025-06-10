<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\Database\ParameterType;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;
use HITScoutingNL\Library\KampInfo\Helper\KampInfoUrlHelper;


/**
 * Field voor icoontjes.
 */
class IconField extends FormField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Icon';

    /**
     * Flag to tell the field to always be in multiple values mode.
     * 
     * @var    boolean
     */
    protected $forceMultiple = true;

    /**
     * Method to get the field options.
     *
     * @return  object[]  The field option objects.
     */
    protected function getInput() {
        $html = [];
        $class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

        $options = $this->getOptions();

        foreach ($options as $i => $option) {
            $checked = (in_array((string) $option->value, (array) $this->value, true) ? ' checked="checked"' : '');
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $icoon = new \stdClass();
            $icoon->bestandsnaam = $option->value;
            $icoon->tekst = $option->text;

            $uitleg = !empty($option->uitleg) ? htmlspecialchars($option->uitleg, ENT_COMPAT, 'UTF-8') : '';

            $html[] = '<div class="control-group">';
            $html[] = '  <div class="controls">';
            $html[] = '    <label for="' . $this->id . $i . '"' . $class . '>' . HTMLHelper::_('icoon.image', $icoon, 'large') . '</label>';
            $html[] = '    <input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '>';
            $html[] = '    <label for="' . $this->id . $i . '"' . $class . '>' . Text::_($option->text) . '</label>';
            if (!empty($uitleg)) {
                $html[] = '    <div>';
                $html[] = '      <small>' . $uitleg . '</small>';
                $html[] = '    </div>';
            }
            $html[] = '  </div>';
            $html[] = '</div>';
        }

        return implode($html);
    }

    public function getOptions() {
        // Merge any additional options in the XML definition.
        return array_merge(
            $this->getOptionsFromFormDefinition(),
            $this->getHitIconFieldOptions()
        );
    }

    private function getOptionsFromFormDefinition() {
        $options = [];

        foreach ($this->element->children() as $option) {
            // Only add <option /> elements.
            if ($option->getName() != 'option') {
                continue;
            }

            // Create a new option object based on the <option /> element.
            $tmp = HtmlHelper::_(
                'select.option',
                (string) $option['value'],
                trim((string) $option),
                'value',
                'text',
                ((string) $option['disabled'] == 'true')
            );

            // Set some option attributes.
            $tmp->class = (string) $option['class'];

            // Set some JavaScript option attributes.
            $tmp->onclick = (string) $option['onclick'];

            // Add the option object to the result set.
            $options[] = $tmp;
        }

        reset($options);

        return $options;
    }

    private function getHitIconFieldOptions() {
        $systeemType = 'S';
        $db     = $this->getDatabase();
        $query  = $db->getQuery(true)
            ->select([
                $db->quoteName('i.bestandsnaam', 'value'),
                $db->quoteName('i.tekst', 'text'),
                $db->quoteName('i.uitleg')
            ])
            ->from($db->quoteName('#__kampinfo_hiticon', 'i'))
            ->where($db->quoteName('i.soort') .' <> :systeem')
            ->bind(':systeem', $systeemType, ParameterType::STRING)
            ->order($db->quoteName('i.volgorde'));

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
