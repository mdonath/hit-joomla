<?php

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;
use HITScoutingNL\Library\KampInfo\Helper\KampInfoUrlHelper;

// config
$params = ComponentHelper::getParams('com_kampinfo');
$shantiUrl = $params->get('shantiUrl');

// model
$timezone = KampInfoHelper::getTimezone();
$activiteit = $this->activiteit;

$start = new Date($activiteit->startDatumTijd);
$start->setTimezone($timezone);

$eind = new Date($activiteit->eindDatumTijd);
$eind->setTimezone($timezone);

$heeftEenYoutubeFilmpje = !empty($activiteit->youtube);
$isGeannuleerd = $activiteit->geannuleerd === 1;

function replaceVariables($text, $act) {
    foreach ($act as $key => $value) {
        if (!\is_array($value) && isset($value)) {
            $text = \str_replace('${'.$key.'}', $value, $text);
        }
    }
    return $text;
}

function createInschrijfFormulierLink($template, $id) {
    return str_replace('{0}', $id, $template);
}
?>

<article class="itempage" itemtype="http://schema.org/Article" itemscope="">

    <!-- Activiteitengebieden -->
    <div class="hidden-phone">
        <?= HTMLHelper::_('kamp.activiteitengebieden', $activiteit) ?>
    </div>

    <div>
        <!-- Icoontjes-deel2 -->
        <p>
            <?= HTMLHelper::_('kamp.icoontjes', $activiteit, 'large') ?>
        </p>

        <div class="clear"> </div>
        
        <!-- Detailgegevens -->
        <p>
            <span style="white-space: nowrap;">Plaats: <b><?= $activiteit->plaats ?></b></span>
            <span>|</span>
            <?php
                $startF = $start->format('j F', true);
                $eindF = $eind->format('j F', true);
            ?>
            <span style="white-space: nowrap;">Datum: <b><?= $startF ?> t/m <?= $eindF ?></b></span> 
            <span>|</span>
            <span style="white-space: nowrap;">Leeftijd: <b>
            <?php if ($activiteit->isouderkind == '1') : ?>
                <?= $activiteit->minimumLeeftijd ?> t/m <?= $activiteit->maximumLeeftijd ?>,
                <?= $activiteit->minimumLeeftijdOuder ?> t/m <?= $activiteit->maximumLeeftijdOuder ?> (ouder) jaar</b></span>
            <?php else : ?>
                <?= $activiteit->minimumLeeftijd ?> t/m <?= $activiteit->maximumLeeftijd ?> jaar</b></span>
            <?php endif; ?>
            <span>|</span>
            <span style="white-space: nowrap;">Groep: <b>
            <?php
                $subgroepMin = $activiteit->subgroepsamenstellingMinimum;
                $subgroepMax = $activiteit->subgroepsamenstellingMaximum;
                if ($subgroepMin == 0 || $subgroepMax == 0) {
                    echo '&nbsp;';
                } elseif ($subgroepMin == $subgroepMax) {
                    echo "$subgroepMin pers.";
                } else {
                    echo "$subgroepMin - $subgroepMax pers.";
                }
            ?></b></span>
            <span>|</span>
            <span style="white-space: nowrap;">Prijs: <b>€ <?= $activiteit->deelnamekosten ?></b></span>
        </p>
    </div>

    <p> </p>

    <div itemprop="articleBody" class="items-row cols-4 row">

        <div class="item span8">

            <!--  Header / kopjes -->
            <hgroup>
                <!-- Hoofd header -->
                <h1><?= HTMLHelper::_('kamp.naam', $activiteit) ?></h1>
                
                <!-- Subtitel -->
                <?php if (strtolower($activiteit->titeltekst) === strtolower($activiteit->naam)) : ?>
                    <h3></h3>
                <?php else : ?>
                    <h3><?= $activiteit->titeltekst ?></h3>
                <?php endif; ?>
            </hgroup>
        
            <!-- De complete artikeltekst -->
            <?= $activiteit->websiteTekst ?>
            
            <!-- Een eventueel Youtube filmpje -->
            <?php if ($heeftEenYoutubeFilmpje) : ?>
                <iframe width="100%" height="435" src="https://www.youtube.com/embed/<?= $activiteit->youtube ?>" frameborder="0" allowfullscreen></iframe>
            <?php endif; ?>
            
            <!-- Meer weten-blok met contactinformatie -->
            <?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres) or !empty($activiteit->websiteAdres)) : ?>
                <h3>Meer weten over het kamp?</h3>
                <table border="0">
                    <tbody>
                        <?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres)) : ?>
                        <tr>
                            <td><?= HTMLHelper::_('icoon.specifiek', 'info', 'large', 'Meer informatie? Mail of bel naar de contactpersoon van deze HIT') ?></td>
                            <td>
                                <?php if (!empty($activiteit->websiteContactTelefoonnummer)) { ?>
                                    Bel bij vragen <?= $activiteit->websiteContactpersoon ?>: <?= $activiteit->websiteContactTelefoonnummer ?><br />
                                <?php } ?>
                                <?php if (!empty($activiteit->websiteContactEmailadres)) { ?>
                                    <?= HTMLHelper::_('email.cloak', $activiteit->websiteContactEmailadres, 1, 'Mail naar '.$activiteit->websiteContactEmailadres, 0) ?>
                                    <br />
                                <?php } ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($activiteit->websiteAdres)) : ?>
                        <tr>
                            <td><?= HTMLHelper::_('icoon.specifiek', 'web', 'large', 'Link naar een website over dit HIT onderdeel') ?></td>
                            <td>
                                <a href="<?= $activiteit->websiteAdres ?>"><?= $activiteit->websiteAdres ?></a><br/>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <p>Neem voor vragen over de inschrijving contact op met de <a href="contact/hit-helpdesk">helpdesk</a>.</p>
            <?php endif; ?>
            
            <h3>Aanvullende info:</h3>
            <?php if ($isGeannuleerd) : ?>
                <p>Dit kamp gaat helaas niet door.
            <?php else : ?>
                <p>
                    <?php if (KampInfoUrlHelper::isVol($activiteit)) : ?>
                        <?= HTMLHelper::_('icoon.specifiek', KampInfoUrlHelper::volOfLoterij(), 'small', KampInfoUrlHelper::fuzzyIndicatieVol($activiteit)) ?>
                    <?php endif; ?>
                    <span><?= KampInfoUrlHelper::fuzzyIndicatieVol($activiteit) ?></span>
            
                    <?php if (!empty($activiteit->maximumAantalUitEenGroep)) : ?>
                        Er mogen maximaal <?= $activiteit->maximumAantalUitEenGroep ?> leden uit dezelfde Scoutinggroep meedoen.
                    <?php endif; ?>

                    Er kunnen maximaal <?= $activiteit->maximumAantalDeelnemers ?> deelnemers meedoen aan deze activiteit.

                    <?php if ($activiteit->subgroepsamenstellingExtra > '1') : ?>
                        Er zijn speciale eisen aan de koppelgrootte, het aantal mensen moet een veelvoud zijn van <?= $activiteit->subgroepsamenstellingExtra ?>.
                    <?php endif; ?>

                    <?php
                        $startDTF = $start->format('l j F \o\m H:i \u\u\r', true);
                        $eindDTF = $eind->format('l j F H:i \u\u\r', true);
                    ?>
                    Dit HIT-onderdeel start op <?= $startDTF ?> en duurt tot en met <?= $eindDTF ?>.

                    <?php if ($activiteit->startElders === 1) : ?>
                        Dit kamp start niet op de hoofdlocatie. 
                    <?php endif; ?>
                    <?php if (!empty($activiteit->sublocatie)) : ?>
                        Dit kamp vindt niet plaats op de hoofdlocatie, maar <?= $activiteit->sublocatie ?>. 
                    <?php endif;?>
                </p>

                <?php if ($activiteit->isouderkind === 1) : ?>
                    <?= replaceVariables($activiteit->ouderkind, $activiteit) ?>
                <?php endif; ?>
                
                <!-- Inschrijfknoppen -->
                <?php
                    $nu = (new Date('now'))->getTimestamp();
                    $isInschrijvingGestart = $nu >= ((new Date($activiteit->startInschrijving))->getTimestamp());
                    $stopMoment = (new Date($activiteit->eindInschrijving))->getTimestamp();
                    $isInschrijvingNogNietGestopt = ($nu <= $stopMoment);
                ?>
                
                <?php if ($activiteit->shantiFormuliernummer > 0 && $isInschrijvingGestart && $isInschrijvingNogNietGestopt) : ?>
                    <p><b>Let op! Doe de HIT inschrijving bij voorkeur op een laptop of desktop computer. De inschrijving kan op een tablet fout gaan!</b></p>
                    <div>
                    <?php if ($activiteit->ouderShantiFormuliernummer > 0) : ?>
                        <span>Inschrijven met: </span>
                        <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer) ?>" target="_self">kind is lid</a>
                        <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->ouderShantiFormuliernummer) ?>" target="_self">ouder is lid</a>
                        <?php  if ($activiteit->extraShantiFormuliernummer > 0) : ?>
                                <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->extraShantiFormuliernummer) ?>" target="_self">extra kind</a>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if ($activiteit->extraShantiFormuliernummer > 0) : ?>
                            <span>Inschrijven met: </span>
                            <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer) ?>" target="_self">kind is lid</a>
                            <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->extraShantiFormuliernummer) ?>" target="_self">extra kind</a>
                        <?php else: ?>
                            <a class="btn btn-primary" href="<?= createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer) ?>" target="_self">Inschrijven</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="clear"> </div>

        <div class="item column-5 span5">
            <?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto1)) : ?>
                <img src="<?= $activiteit->webadresFoto1 ?>" alt="" border="0" width="100%" /><br /><br />
            <?php endif; ?>
            <?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto2)) : ?>
                <img src="<?=  $activiteit->webadresFoto2 ?>" alt="" border="0" width="100%" /><br /><br />
            <?php endif; ?>
            <?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto3)) : ?>
                <img src="<?=  $activiteit->webadresFoto3 ?>" alt="" border="0" width="100%" /><br /><br />
            <?php endif; ?>
        </div>

    </div>

</article>

