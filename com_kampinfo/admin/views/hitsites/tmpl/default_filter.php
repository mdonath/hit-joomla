<?php
// no direct access
defined('_JEXEC') or die;
?>
<select name="filter_jaar" class="inputbox" onchange="this.form.submit()">
	<option value="-1"><?php echo JText::_('COM_KAMPINFO_SELECT_HITPROJECT');?></option>
	<?php echo JHtml::_('select.options', KampInfoHelper :: getHitProjectOptions(), 'value', 'text', $this->escape($this->state->get('filter.jaar'))); ?>
</select>

<?php 


?>
<select name="filter_published" class="inputbox" onchange="this.form.submit()">
	<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
	<?php 
	$options = array();
	$options[] = JHtml::_('select.option', '1', 'JPUBLISHED');
	$options[] = JHtml::_('select.option', '0', 'JUNPUBLISHED');
	?>
	<?php echo JHtml::_('select.options', $options, 'value', 'text', $this->escape($this->state->get('filter.published')), true); ?>
</select>