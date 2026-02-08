<?php
defined('_JEXEC') or die;

$document = $app->getDocument();
$wa = $document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('mod_hitcountdown');
$wa->useScript('mod_hitcountdown.scripts');
$wa->useStyle('mod_hitcountdown.styles');


$elementId = $params->get('elementId', 'countdown');

$widthOnDesktop = $params->get('widthOnDesktop', 75);
$widthOnMobile = $params->get('widthOnMobile', 100);
$colorCounters = $params->get('colorCounters', '#000000');
$colorLabels = $params->get('colorLabels', '#000000');
$colorBorders = $params->get('colorBorders', '#000000');

$wa->addInlineStyle("
    #{$elementId} ul#mhc-countdown {
        width: {$widthOnDesktop}%;
        color: {$colorCounters};
        border: 1px solid {$colorBorders};
    }

    #{$elementId} ul#mhc-countdown .label {
        color: {$colorLabels};
    }

    @media only screen and (max-width: 768px) {
        #{$elementId} ul#mhc-countdown {
            width: {$widthOnMobile}%;
        }
    }
");

$endDate = $params->get('endDate');
$someOffset = $params->get('someOffset', 0);

$wa->addInlineScript(
    "$( () => window.startTimer('{$endDate}', {$someOffset}, '{$elementId}') );",
    ['name' => "mod_hitcountdown.init{$elementId}"],
    ['type' => 'module'],
    ['jquery']
);

$showSeconds = $params->get('showSeconds', 1);
?>

<div id='<?="{$elementId}"?>' class="mhc-countdown-wrapper">

    <?php if ($params->get('showTitle', 1)) { ?>
        <h2><?= $params->get('title', '') ?></h2>
    <?php } ?>

    <?php if ($params->get('showSubTitle', 1)) { ?>
        <p><?= $params->get('subtitle', '') ?></p>
    <?php } ?>

    <ul id="mhc-countdown" class="<?= $showSeconds ? 'showfour' : 'showthree' ?>">

        <li id="days">
            <div class="number">0</div>
            <div class="label"><?= $textDays ?></div>
        </li>

        <li id="hours">
            <div class="number">0</div>
            <div class="label"><?= $textHours ?></div>
        </li>

        <li id="minutes">
            <div class="number">0</div>
            <div class="label"><?= $textMinutes ?></div>
        </li>

        <?php if ($showSeconds) { ?>
            <li id="seconds">
                <div class="number">0</div>
                <div class="label"><?= $textSeconds ?></div>
            </li>
        <?php } ?>

    </ul>
</div>
