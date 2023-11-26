<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\RangeField;
use Joomla\CMS\Form\Field\NumberField;
use Joomla\CMS\HTML\HTMLHelper;

class RangenumField extends NumberField {

    protected $type = 'Rangenum';

    protected function getInput() {
        $attributes = [
            $this->class ? 'class="form-range ' . $this->class . '"' : 'class="form-range"',
            $this->disabled ? 'disabled' : '',
            $this->readonly ? 'readonly' : '',
            !empty($this->max) ? 'max="' . $this->max . '"' : '',
            !empty($this->step) ? 'step="' . $this->step . '"' : '',
            !empty($this->min) ? 'min="' . $this->min . '"' : '',
            $this->autofocus ? 'autofocus' : '',
            !empty($this->onchange) ? 'onchange="' . $this->onchange . '"' : '',
        ];

        $value = $this->value;
        $value = is_numeric($value) ? (float) $value : $min;
        $value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');

        $html = [];
        $html[] = '<div class="field-range">';
        $html[] = ' <div class="input-group" style="justify-content: end;">';
        $html[] = '  <input type="range" name="' . $this->name . '" id="' . $this->id . '"';
        $html[] = '    value="'. $value . '" ' . implode(' ', $attributes);
        $html[] = '    oninput="this.form.' . $this->id . '_display.value=this.form.' . $this->id . '.value;"';
        $html[] = '/>&nbsp;<output id="' . $this->id . '_display" for="'.$this->name.'">' . $value . '</output>';
        $html[] = ' </div>';
        $html[] = '</div>';

        return implode($html);
    }

}
