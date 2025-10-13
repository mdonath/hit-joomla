<?php

\defined('_JEXEC') or die('Restricted Access');

$manifest = $this->getModel()->getItems();
?>

<div class="row">
    <div class="col-md-12">
        <div id="j-main-container" class="j-main-container">
           <p>Dit is HIT Downloads, een Joomla component speciaal voor de HIT.</p>

            <table class="table table-striped">
            <tbody>
                <tr><th>Versie:</th><td><?= $manifest['version'] ?></td></tr>
                <tr><th>Naam:</th><td><?= JText::_($manifest['name']) ?></td></tr>
                <tr><th>Omschrijving:</th><td><?= JText::_($manifest['description']) ?></td></tr>
                <tr><th>Copyright:</th><td><?= $manifest['copyright'] ?></td></tr>
                <tr><th>Auteur:</th><td><?= $manifest['author'] ?> (<?= $manifest['authorEmail'] ?>)</td></tr>
                <tr><th>Code url:</th><td><a href="<?= $manifest['authorUrl'] ?>"><?= $manifest['authorUrl'] ?></a></td></tr>
                <tr><th>Start ontwikkeling:</th><td><?= $manifest['creationDate'] ?></td></tr>
            </tbody>
            </table>

        </div>
    </div>
</div>
