<?php
// no direct access
defined('_JEXEC') or die;
?>
<select name="filter_jaar" class="inputbox" onchange="this.form.submit()">
	<option value="-1"><?php echo JText::_('COM_KAMPINFO_SELECT_HITPROJECT');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitProjectOptions(), 'value', 'text', $this->escape($this->state->get('filter.jaar'))); ?>
</select>

<select name="filter_published" class="inputbox" onchange="this.form.submit()">
	<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
	<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->escape($this->state->get('filter.published')), true); ?>
</select>