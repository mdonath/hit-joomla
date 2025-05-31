<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Rule;

\defined('_JEXEC') or die('Restricted Access');

use DateTime;
use DateTimeZone;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

class TijdensDeHitPeriodeRule extends FormRule implements DatabaseAwareInterface {

    use DatabaseAwareTrait;

    /**
     * Method to test the range for a date value using the database.
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
        $db    = $this->getDatabase();
        $query = $db->getQuery(true);

        $hitSiteId = $input['hitsite_id'];

        $query
            ->select([
                $db->quoteName('p.vrijdag', 'vrijdag'),
                $db->quoteName('p.maandag', 'maandag'),
                "(date_format(:startDatumTijd, '%Y-%m-%d %H:%i:00') between p.vrijdag and date_add(p.maandag, interval 1 day)) as resultaat",
            ])
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id')
            ->where($db->quoteName('s.id') . ' = :hitSiteId')
            ->bind(':hitSiteId', $hitSiteId, ParameterType::INTEGER)
            ->bind(':startDatumTijd', $value, ParameterType::STRING)
            ;

        // Set and query the database.
        $db->setQuery($query);
        $row = $db->loadAssoc();

        if ($row['resultaat'] === 1) {
            return true;
        }

        $wat = $element['name'];
        $gekozen = TijdensDeHitPeriodeRule::reformatDate($value);
        $beginPeriode = TijdensDeHitPeriodeRule::reformatDate($row['vrijdag']);
        $eindPeriode = TijdensDeHitPeriodeRule::reformatDate($row['maandag']);

        $error_message = "De $wat '$gekozen' valt niet in de periode van de HIT ($beginPeriode - $eindPeriode)";

        // how you write the message attribute to the XML element depends on whether it's already set
        $attr = $element->attributes();
        if (isset($attr['message'])) {
            $attr->message = $error_message;
        } else {
            $element->addAttribute('message', $error_message);
        }
        return false;
    }

    private static function reformatDate($value) {
        return DateTime::createFromFormat('Y-m-d G:i:s', $value)->format('d-m-Y');
    }

}
