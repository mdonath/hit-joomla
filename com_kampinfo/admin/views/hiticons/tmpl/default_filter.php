<?php
// no direct access
defined('_JEXEC') or die;

?>

<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_KAMPINFO_SEARCH_IN_NAAM'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

		<select name="filter_soort" class="inputbox" onchange="this.form.submit()">
			<option value=""><?php echo JText::_('COM_KAMPINFO_SELECT_HITICON_SOORT');?></option>
			<?php echo JHtml::_('select.options', KampInfoHelper :: getHitIconSoortOptions(), 'value', 'text', $this->escape($this->state->get('filter.soort'))); ?>
		</select>
	</div>
</fieldset>
<div class="clr"> </div>
