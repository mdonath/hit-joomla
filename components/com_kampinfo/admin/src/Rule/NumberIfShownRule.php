<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Rule;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;

class NumberIfShownRule extends FormRule {

    /**
     * Method to test the range for a number value using min and max attributes.
     * 
     * Extra: if the field is required but has a showon condition, and that condition is not met, the field is not required.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as an array container for the field.
     *                                       For example if the field has name="foo" and the group value is set to "bar" then the
     *                                       full field name would end up being "bar[foo]".
     * @param   Registry           $input    An optional Registry object with the entire data set to validate against the entire form.
     * @param   Form               $form     The form object for which the field is being tested.
     */
    public function test(\SimpleXMLElement $element, $value, $group = null, ?Registry $input = null, ?Form $form = null) {
        // Check if the field is required.
        $required = ((string) $element['required'] === 'true' || (string) $element['required'] === 'required');

        // If the value is empty and the field is not required return True.
        if (($value === '' || $value === null) && ! $required) {
            return true;
        }

        // MODIFICATION HERE - START

        if ($required && $element['showon'] ?? false) {
            $showon = explode(':', $element['showon']);
            $showon_field = $showon[0];
            $showon_value = $showon[1] ?? null;

            $input_value = $input[$showon_field] ?? null;
            if ($input_value != $showon_value) {
                // not shown, so not required
                return true;
            }
        }

        // MODIFICATION HERE - END


        $float_value = (float) $value;

        if (isset($element['min'])) {
            $min = (float) $element['min'];

            if ($min > $float_value) {
                return false;
            }
        }

        if (isset($element['max'])) {
            $max = (float) $element['max'];

            if ($max < $float_value) {
                return false;
            }
        }

        return true;
    }

}
