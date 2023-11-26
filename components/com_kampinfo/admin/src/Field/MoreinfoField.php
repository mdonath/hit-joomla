<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Field\NoteField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Field voor collapsible notes.
 */
class MoreinfoField extends NoteField {

    protected $type = 'Moreinfo';

    protected function getLabel() {
        if (empty($this->element['label']) && empty($this->element['description'])) {
            return '';
        }

        $class = [];

        if (!empty($this->class)) {
            $class[] = $this->class;
        }

        $class       = $class ? ' class="' . implode(' ', $class) . '"' : '';
        $title       = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
        $heading     = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
        $description = (string) $this->element['description'];
        $expand      = $this->element['expand'] ? (string) $this->element['expand'] : 'Expand';

        $html = [];
        $html[] = '</div>';
        $html[] = '<div>';
        $html[] = '<details>';
        $html[] = '<summary>' . $expand . '</summary>';
        $html[] = '<div>';
        $html[] = '<div ' . $class . '>';
        $html[] = !empty($title) ? '<' . $heading . '>' . Text::_($title) . '</' . $heading . '>' : '';
        $html[] = !empty($description) ? Text::_($description) : '';
        $html[] = '</div>';
        $html[] = '</div>';
        $html[] = '</details>';

        return implode('', $html);
    }

    public function getOptions() {
        $options = KampInfoHelper::getHitIconOptions();

        // Merge any additional options in the XML definition.
        $options = array_merge($this->getOptionsFromFormDefinition(), $options);

        return $options;
   }

}
