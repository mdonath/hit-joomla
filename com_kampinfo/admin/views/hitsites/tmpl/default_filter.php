<?php
// no direct access
defined('_JEXEC') or die;
?>
<select name="filter_jaar" class="inputbox" onchange="this.form.submit()">
	<option value="-1"><?php echo JText::_('COM_KAMPINFO_SELECT_HITPROJECT');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitProjectOptions(), 'value', 'text', $this->escape($this->state->get('filter.jaar'))); ?>
</select>
