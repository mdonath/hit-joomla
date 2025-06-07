<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


/**
 * Field voor activiteitengebieden.
 */
class ActivityareaField extends FormField {

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Activityarea';

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
        $class = $this->element['class'] ? ' class="checkboxes '. (string) $this->element['class'] .'"' : ' class="checkboxes"';

        $options = $this->getOptions();

        foreach ($options as $i => $option) {
            $checked = (in_array((string) $option->value, (array) $this->value, true) ? ' checked="checked"' : '');
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $html[] = '<div class="control-group">';
            $html[] = '  <div class="controls">';
            $html[] = '    <label for="' . $this->id . $i . '"' . $class . '>' . HTMLHelper::_('icoon.activiteitengebied', $option->value, $option->text) . '</label>';
            $html[] = '    <input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '>';
            $html[] = '    <label for="' . $this->id . $i . '"' . $class . '>'. Text::_($option->text) . '</label>';
            $html[] = '  </div>';
            $html[] = '</div>';
        }

        return implode($html);
    }

    public function getOptions() {
        // Merge any additional options in the XML definition.
        return array_merge(
            $this->getOptionsFromFormDefinition(),
            KampInfoHelper::getActivityAreaOptions()
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
            $tmp = HTMLHelper::_(
                'select.option',
                (string) $option['value'],
                trim((string) $option),
                'value',
                'text',
                (string) $option['disabled'] == 'true'
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

}
