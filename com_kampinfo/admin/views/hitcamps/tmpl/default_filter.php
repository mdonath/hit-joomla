<?php
// no direct access
defined('_JEXEC') or die;
?>

<select name="filter_jaar" class="inputbox" onchange="this.form.submit()">
	<option value=""><?php echo JText::_('COM_KAMPINFO_SELECT_HITPROJECT');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitProjectOptions(), 'value', 'text', $this->escape($this->state->get('filter.jaar'))); ?>
</select>

<select name="filter_plaats" class="inputbox" onchange="this.form.submit()">
	<option value=""><?php echo JText::_('COM_KAMPINFO_SELECT_HITSITE');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitSiteOptions(), 'value', 'text', $this->escape($this->state->get('filter.plaats'))); ?>
</select>
