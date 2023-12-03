<?php

defined('_JEXEC') or die('Restricted Access');

$manifest = $this->getModel()->getItems();
?>

<p>Dit is KampInfo Import &amp; Export, een Joomla component speciaal voor de HIT om KampInfo data te importeren en te exporteren.</p>

<div class="row">
    <div class="col-md-12">
        <div id="j-main-container" class="j-main-container">
            <table class="table table-striped">
            <tbody>
                <tr><th>Versie:</th><td><?php echo($manifest['version']);?></td></tr>
                <tr><th>Naam:</th><td><?php echo(JText::_($manifest['name']));?></td></tr>
                <tr><th>Omschrijving:</th><td><?php echo(JText::_($manifest['description']));?></td></tr>
                <tr><th>Copyright:</th><td><?php echo($manifest['copyright']);?></td></tr>
                <tr><th>Auteur:</th><td><?php echo($manifest['author']);?> (<?php echo($manifest['authorEmail']);?>)</td></tr>
                <tr><th>Code url:</th><td><a href="<?php echo($manifest['authorUrl']);?>"><?php echo($manifest['authorUrl']);?></a></td></tr>
                <tr><th>Start ontwikkeling:</th><td><?php echo($manifest['creationDate']);?></td></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>
