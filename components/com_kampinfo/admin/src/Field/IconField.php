<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoUrlHelper;


/**
 * Field voor icoontjes.
 */
class IconField extends FormField {

    protected $type = 'Icon';
    protected $forceMultiple = true;

    protected function getInput() {
        $params = ComponentHelper::getParams('com_kampinfo');
        $iconFolderSmall = $params->get('iconFolderSmall');
        $iconFolderLarge = $params->get('iconFolderLarge');
        $iconExtension = $params->get('iconExtension');
        
        $html = [];
        $class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

        $options = $this->getOptions();

        foreach ($options as $i => $option) {
            $checked = (in_array((string) $option->value, (array) $this->value, true) ? ' checked="checked"' : '');
            $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
            $disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
            $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

            $uitleg = !empty($option->uitleg) ? htmlspecialchars($option->uitleg, ENT_COMPAT, 'UTF-8') : '';

            $html[] = '<div class="control-group">';
            $html[] = '  <div class="controls">';
            $html[] = '    <input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . "/>";
            $html[] = '    <label for="' . $this->id . $i . '"' . $class . '>';
            $html[] = KampInfoUrlHelper::imgUrl($iconFolderLarge, $option->value, $iconExtension, '', Text::_($option->text));
            $html[] = Text::_($option->text);
            $html[] =  '   </label>';
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
        $options = KampInfoHelper::getHitIconOptions();
        
        // Merge any additional options in the XML definition.
        $options = array_merge($this->getOptionsFromFormDefinition(), $options);
        
        return $options;
    }

    protected function getOptionsFromFormDefinition()
    {
        // Initialize variables.
        $options = array();

        foreach ($this->element->children() as $option)
        {

            // Only add <option /> elements.
            if ($option->getName() != 'option')
            {
                continue;
            }

            // Create a new option object based on the <option /> element.
            $tmp = HtmlHelper::_(
                'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
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
}
