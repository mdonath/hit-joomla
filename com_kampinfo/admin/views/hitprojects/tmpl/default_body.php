<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo $item->id; ?>
                </td>
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                	<?php if ($this->canDo->get('hitproject.edit')) { ?>
                        <a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitproject.edit&id='.(int)$item->id); ?>">
                        	<?php echo $item->jaar; ?>
                        </a>
                    <?php } else { ?>
                    	<?php echo $item->jaar; ?>
                    <?php } ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inschrijvingStartdatum); ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inschrijvingEinddatum); ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inschrijvingWijzigenTotDatum); ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inschrijvingKosteloosAnnulerenDatum); ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inschrijvingGeenRestitutieDatum); ?>
                </td>
                <td>
                        <?php echo KampInfoHelper::reverse($item->inningsdatum); ?>
                </td>
                
                
        </tr>
<?php endforeach; ?>