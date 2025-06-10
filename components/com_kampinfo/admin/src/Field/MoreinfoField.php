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

    /**
     * The form field type.
     * 
     * @var    string
     */
    protected $type = 'Moreinfo';

    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     */
    protected function getLabel() {
        if (empty($this->element['label']) && empty($this->element['description'])) {
            return '';
        }

        $class = [];

        if (!empty($this->class)) {
            $class[] = $this->class;
        }

        $class       = $class ? ' class="' . implode(' ', $class) . '"' : '';
        $title       = (string) $this->element['label'] ?: ($this->element['title'] ?: '');
        $heading     = (string) $this->element['heading'] ?: 'h4';
        $description = (string) $this->element['description'];
        $expand      = (String) $this->element['expand'] ?: 'Expand';

        $html = [];
        $html[] = '</div>';
        $html[] = '<div>';
        $html[] = '  <details>';
        $html[] = '    <summary class="rule-notes">' . $expand . '</summary>';
        $html[] = '    <div>';
        $html[] = '      <div ' . $class . '>';
        $html[] = !empty($title) ? '<' . $heading . '>' . Text::_($title) . '</' . $heading . '>' : '';
        $html[] = !empty($description) ? Text::_($description) : '';
        $html[] = '      </div>';
        $html[] = '    </div>';
        $html[] = '  </details>';

        return implode('', $html);
    }

}
