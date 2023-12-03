<?php

use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted Access');

$projecten = $this->getModel()->getItems();

?>
<div class="row">
    <div class="col-md-12">
        <div id="j-main-container" class="j-main-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Naam</th>
                        <th scope="col">Export</th>
                        <th scope="col">Inhoud</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="row1">
                        <td>Alles</td>
                        <td><a href="<?php echo Route::_('index.php?option=com_kampinfoimexport&view=export&format=json'); ?>">Export JSON</a></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php foreach ($projecten as $project) { ?>
                        <tr>
                            <td><?php echo $project->jaar;?></td>
                            <td>
                                <a href="<?php echo Route::_('index.php?option=com_kampinfoimexport&view=export&format=json&jaar='.$project->jaar); ?>">Export JSON</a>
                            </td>
                            <td>
                                <table>
                                    <?php foreach ($project->plaatsen as $plaats) { ?>
                                        <tr>
                                            <td><?php echo $plaats->naam;?></td>
                                            <td>Aantal kampen: <?=count($plaats->kampen)?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
