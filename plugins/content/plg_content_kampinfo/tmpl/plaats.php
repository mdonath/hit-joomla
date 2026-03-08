<?php

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$delim = $this->outputDelimiter($config);
?>

<?php if ($this->getParamIfExists($config, 'kopje') == '1') : ?>
    <h3>HIT <?= $config['plaats'] ?> <?= $config['jaar'] ?></h3>
<?php endif; ?>


<?php foreach ($result as $row): ?>
    <div class='kamp'>
        <span class='"naam'><?= $this->kampLink($row, $config) ?></span>
        <?= $delim ?>
        <span class='leeftijd'><?= "{$row->minl}-{$row->maxl} jaar" ?></span>
        <?php if ($this->getParamIfExists($config, 'icons') == '1') : ?>
            <?= $delim ?>
            <span class='icons'><?= HtmlHelper::_('kamp.icoontjes', $row, 'small') ?></span>
        <?php endif; ?>
        <?php if ($this->getParamIfExists($config, 'hitcourant') == '1') : ?>
            <?= $delim ?>
            <span class='hitcourant'><?= $row->hitcourant ?></span>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
