<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfourl.php';

/**
 * Field voor icoontjes.
 */
class JFormFieldIcon extends JFormField {

	protected $type = 'Icon';
	protected $forceMultiple = true;

	/**
	 * Method to get the field input markup for check boxes.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$params =JComponentHelper::getParams('com_kampinfo');
		$iconFolderSmall = $params->get('iconFolderSmall');
		$iconFolderLarge = $params->get('iconFolderLarge');
		$iconExtension = $params->get('iconExtension');
		
		// Initialize variables.
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="checkboxes ' . (string) $this->element['class'] . '"' : ' class="checkboxes"';

		// Start the checkbox field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$options = $this->getOptions();

		// Build the checkbox field output.
		$html[] = '<table>';
		foreach ($options as $i => $option)
		{
			// Initialize some option attributes.
			$checked = (in_array((string) $option->value, (array) $this->value, true) ? ' checked="checked"' : '');
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';
			$uitleg = !empty($option->uitleg) ? htmlspecialchars($option->uitleg, ENT_COMPAT, 'UTF-8') : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			$html[] = '<tr>';
			$html[] = '<td><input type="checkbox" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled 
				. '/></td>';

			$html[] = '<td style="width:48px"><label for="' . $this->id . $i . '"' . $class . '>';
			$html[] = KampInfoUrlHelper::imgUrl($iconFolderLarge, $option->value, '', $iconExtension, JText::_($option->text));
			$html[] = '</label></td>';
			$html[] = '<td><label for="' . $this->id . $i . '"' . $class . '>';
			$html[] =  JText::_($option->text) . '</label></td>';
			$html[] =  '<td>' . $uitleg . '</td>';
			$html[] = '</tr>';
		}
		$html[] = '</table>';

		// End the checkbox field output.
		$html[] = '</fieldset>';

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
			$tmp = JHtml::_(
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