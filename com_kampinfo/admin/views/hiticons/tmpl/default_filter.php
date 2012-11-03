<?php
// no direct access
defined('_JEXEC') or die;
?>

<select name="filter_soort" class="inputbox" onchange="this.form.submit()">
	<option value=""><?php echo JText::_('COM_KAMPINFO_SELECT_HITICON_SOORT');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitIconSoortOptions(), 'value', 'text', $this->escape($this->state->get('filter.soort'))); ?>
</select>
