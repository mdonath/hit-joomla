<?php
defined('_JEXEC') or die;

$document = $app->getDocument();
$wa = $document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_hitcountdown');
$wa->useScript('mod_hitcountdown.countdown');
$wa->useStyle('mod_hitcountdown.countdown');

$endDate = $params->get('endDate');
$someOffset = $params->get('someOffset', 0);

$document->addScriptOptions('mod_hitcountdown.vars', [
    'endDate' => $endDate,
    'someOffset' => $someOffset,
]);

$widthOnDesktop = $params->get('widthOnDesktop', 75);
$widthOnMobile = $params->get('widthOnMobile', 100);
$colorCounters = $params->get('colorCounters', '#000000');
$colorLabels = $params->get('colorLabels', '#000000');
$colorBorders = $params->get('colorBorders', '#000000');

$wa->addInlineStyle("
    ul#mhc-countdown {
        width: {$widthOnDesktop}%;
        color: {$colorCounters};
        border: 1px solid {$colorBorders};
    }

    ul#mhc-countdown .label {
        color: {$colorLabels};
    }

    @media only screen and (max-width: 768px) {
        ul#mhc-countdown {
            width: {$widthOnMobile}%;
        }
    }
");

$showSeconds = $params->get('showSeconds', 1);
?>

<div class="mhc-countdown-wrapper">

    <?php if ($params->get('showTitle', 1)) { ?>
        <h2><?= $params->get('title', '') ?></h2>
    <?php } ?>

    <?php if ($params->get('showSubTitle', 1)) { ?>
        <p><?= $params->get('subtitle', '') ?></p>
    <?php } ?>

    <ul id="mhc-countdown" class="<?= $showSeconds ? 'showfour' : 'showthree' ?>">

        <li id="days">
            <div class="number">00</div>
            <div class="label"><?= $textDays ?></div>
        </li>

        <li id="hours">
            <div class="number">00</div>
            <div class="label"><?= $textHours ?></div>
        </li>

        <li id="minutes">
            <div class="number">00</div>
            <div class="label"><?= $textMinutes ?></div>
        </li>

        <?php if ($showSeconds) { ?>
            <li id="seconds">
                <div class="number">00</div>
                <div class="label"><?= $textSeconds ?></div>
            </li>
        <?php } ?>

    </ul>
</div>
