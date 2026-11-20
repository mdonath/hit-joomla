<?php

// No direct access
defined('_JEXEC') or die;
?>

<ul class="hith-place-list">
<?php foreach ($result as $row): ?>
    <li>
        <a href="https://hit.scouting.nl/<?= strtolower($row->plaats) ?>">
            <strong><?= $row->plaats ?></strong>
            <?= $row->aantal ?> kampen
        </a>
    </li>
<?php endforeach; ?>
</ul>
