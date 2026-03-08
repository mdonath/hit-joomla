<?php

// No direct access
defined('_JEXEC') or die;

$delim = $this->outputDelimiter($config);
?>

<?php if ($this->getParamIfExists($config, 'kopje') == '1') : ?>
    <h3>HIT <?= $config['jaar'] ?></h3>
<?php endif; ?>

<?php foreach ($result as $row): ?>
    <div class='kamp'>
        <span class='"plaats'><?= $row->plaats ?></span>
        <?= $delim ?>
        <span class='"naam'><?= $this->kampLink($row, $config) ?></span>
        <?= $delim ?>
        <span class='leeftijd'><?= "{$row->minl}-{$row->maxl} jaar" ?></span>
    </div>
<?php endforeach; ?>
