<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$columnCount = $this->state->get('list.columnCount');
?>
<tr>
	<td colspan="<?php echo($columnCount); ?>"><?php echo $this->pagination->getListFooter(); ?></td>
</tr>