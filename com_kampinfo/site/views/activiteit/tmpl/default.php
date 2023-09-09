<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params =JComponentHelper::getParams('com_kampinfo');
$activiteitengebiedenFolder = $params->get('activiteitengebiedenFolder');
$activiteitengebiedenExtension = $params->get('activiteitengebiedenExtension');
$iconFolderLarge = $params->get('iconFolderLarge');
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');
$shantiUrl = $params->get('shantiUrl');

// model
$timezone = KampInfoHelper::getTimezone();
$activiteit = $this->activiteit;

$start = new JDate($activiteit->startDatumTijd);
$start->setTimezone($timezone);

$eind = new JDate($activiteit->eindDatumTijd);
$eind->setTimezone($timezone);

$heeftEenYoutubeFilmpje = !empty($activiteit->youtube);

function replaceVariables($text, $act) {
	foreach ($act as $key => $value) {
		if (!is_array($value)) {
			$text = str_replace('${'.$key.'}', $value, $text);
 			// $text .= "<br>'$key' => '$value'";
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
		<?php foreach ($activiteit->activiteitengebieden as $gebied) { ?>
			<?php echo(KampInfoUrlHelper::imgUrl($activiteitengebiedenFolder, $gebied->value, $activiteitengebiedenExtension, $gebied->text, '') . ' '); ?>
		<?php } ?>
	</div>
	
	<div>
		<!-- Icoontjes-deel2 -->
		<p>
			<?php foreach ($activiteit->icoontjes as $icoon) { ?>
				<?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, $icoon->naam, $iconExtension, $icoon->tekst, ''));?>
			<?php }	?>
		</p>

		<div class="clear"> </div>
		
		<!-- Detailgegevens -->
		<p>
			<span style="white-space: nowrap;">Plaats: <b><?php echo($activiteit->plaats); ?></b></span>
			<span>|</span>
			<?php
				$startF = $start->format('j F', true);
				$eindF = $eind->format('j F', true);
			?>
			<span style="white-space: nowrap;">Datum: <b><?php echo("$startF t/m $eindF"); ?></b></span> 
			<span>|</span>
			<span style="white-space: nowrap;">Leeftijd: <b>
			<?php if ($activiteit->isouderkind == '1') {?>
				<?php echo($activiteit->minimumLeeftijd); ?> t/m <?php echo($activiteit->maximumLeeftijd); ?>,
				<?php echo($activiteit->minimumLeeftijdOuder); ?> t/m <?php echo($activiteit->maximumLeeftijdOuder); ?> (ouder)	jaar </b></span>
			<?php } else { ?>
				<?php echo($activiteit->minimumLeeftijd); ?> t/m <?php echo($activiteit->maximumLeeftijd); ?> jaar</b></span>
			<?php }?>
			<span>|</span>
			<span style="white-space: nowrap;">Groep: <b>
			<?php
				$subgroepMin = $activiteit->subgroepsamenstellingMinimum;
				$subgroepMax = $activiteit->subgroepsamenstellingMaximum;
				if ($subgroepMin == 0 || $subgroepMax == 0) {
					echo('&nbsp;');						
				} elseif ($subgroepMin == $subgroepMax) {
					echo("$subgroepMin pers.");
				} else {
					echo("$subgroepMin - $subgroepMax pers.");
				}
			?></b></span>
			<span>|</span>
			<span style="white-space: nowrap;">Prijs: <b>€ <?php echo($activiteit->deelnamekosten); ?></b></span>
		</p>
	</div>
	
	<p> </p>

	<div itemprop="articleBody" class="items-row cols-4 row">

		<div class="item span8">

			<!--  Header / kopjes -->
			<hgroup>
				<!-- Hoofd header -->
				<h1><?php echo($activiteit->naam); ?></h1>
				
				<!-- Subtitel -->				
				<?php if (strtolower($activiteit->titeltekst) == strtolower($activiteit->naam)) { ?>
					<h3></h3>
				<?php } else { ?>
					<h3><?php echo($activiteit->titeltekst);?></h3>
				<?php } ?>
			</hgroup>
		
			<!-- De complete artikeltekst -->
			<?php echo($activiteit->websiteTekst); ?>
			
			<!-- Een eventueel Youtube filmpje -->
			<?php if ($heeftEenYoutubeFilmpje) { ?>
				<iframe width="100%" height="435" src="https://www.youtube.com/embed/<?php echo($activiteit->youtube); ?>" frameborder="0" allowfullscreen></iframe>
			<?php } ?>
			
			<!-- Meer weten-blok met contactinformatie -->
			<?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres) or !empty($activiteit->websiteAdres)) { ?>
				<h3>Meer weten over het kamp?</h3>
				<table border="0">
					<tbody>
						<?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres)) { ?>
						<tr>
							<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'info', $iconExtension, '', 'Meer informatie? Mail of bel naar de contactpersoon van deze HIT')); ?></td>
							<td>
								<?php if (!empty($activiteit->websiteContactTelefoonnummer)) { ?>
									Bel bij vragen <?php echo($activiteit->websiteContactpersoon); ?>: <?php echo($activiteit->websiteContactTelefoonnummer); ?><br />
								<?php } ?>
								<?php if (!empty($activiteit->websiteContactEmailadres)) { ?>
									<?php echo JHtml::_('email.cloak', $activiteit->websiteContactEmailadres, 1, 'Mail naar '.$activiteit->websiteContactEmailadres, 0); ?>
									<br />
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if (!empty($activiteit->websiteAdres)) { ?>
						<tr>
							<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'web', $iconExtension, '', 'Link naar een website over dit HIT onderdeel')); ?></td>
							<td>
								<a href="<?php echo($activiteit->websiteAdres); ?>"><?php echo($activiteit->websiteAdres); ?></a><br/>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<p>Neem voor vragen over de inschrijving contact op met de <a href="contact/hit-helpdesk">helpdesk</a>.</p>
			<?php } ?>
			
			<h3>Aanvullende info:</h3>
			<p>
				<?php 
				if (KampInfoUrlHelper::isVol($activiteit)) {
					echo(KampInfoUrlHelper::imgUrl($iconFolderSmall, KampInfoUrlHelper::volOfLoterij(), $iconExtension, KampInfoUrlHelper::fuzzyIndicatieVol($activiteit), ''));
				}
				echo("<span>". KampInfoUrlHelper::fuzzyIndicatieVol($activiteit) ."</span>");
				?>
			
				<?php if(!empty($activiteit->maximumAantalUitEenGroep)) { ?>
				Er mogen maximaal <?php echo($activiteit->maximumAantalUitEenGroep); ?> leden uit dezelfde Scoutinggroep meedoen.
				<?php } ?>
				Er kunnen maximaal <?php echo($activiteit->maximumAantalDeelnemers); ?> deelnemers meedoen aan deze activiteit.
				<!-- Totale afstand die tijdens deze HIT afgelegd wordt is maximaal 50 kilometer. -->
				<?php
					$startDTF = $start->format('l j F \o\m H:i \u\u\r', true);
					$eindDTF = $eind->format('l j F H:i \u\u\r', true);
					$begintOpGoedeVrijdag = $start->format('N', true) == '5'; // vrijdag

				?>
				<?php if ($activiteit->subgroepsamenstellingExtra > '1') { ?>
					Er zijn speciale eisen aan de koppelgrootte, het aantal mensen moet een veelvoud zijn van
					<?php echo($activiteit->subgroepsamenstellingExtra);?>.
				<?php }?>
				Dit HIT-onderdeel start op <?php echo($startDTF); ?> en duurt tot en met <?php echo($eindDTF); ?>.
				<?php if ($activiteit->startElders == 1) { ?>
					Dit kamp start niet op de hoofdlocatie. 
				<?php }?>
				<?php if (!empty($activiteit->sublocatie)) { ?>
					Dit kamp vindt niet plaats op de hoofdlocatie, maar <?php echo($activiteit->sublocatie); ?>. 
				<?php }?>
			</p>
			<?php if ($activiteit->isouderkind == 1) { ?>
				<?php echo replaceVariables($activiteit->ouderkind, $activiteit); ?>
			<?php }?>
			
			<!-- Inschrijfknoppen -->
			<?php
				$nu = (new JDate('now'))->getTimestamp();
				$isInschrijvingGestart = $nu >= ((new JDate($activiteit->startInschrijving))->getTimestamp());
				$stopMoment = (new JDate($activiteit->eindInschrijving))->getTimestamp();
				$isInschrijvingNogNietGestopt = ($nu <= $stopMoment);
			?>
			
			<?php
				if ($activiteit->shantiFormuliernummer > 0 && $isInschrijvingGestart && $isInschrijvingNogNietGestopt) {
			?>
					<p><b>Let op! Doe de HIT inschrijving bij voorkeur op een laptop of desktop computer. De inschrijving kan op een tablet fout gaan!</b></p>
					<div>
 			<?php	if ($activiteit->ouderShantiFormuliernummer > 0) {
			?>
						<span>Inschrijven met: </span>
						<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer)); ?>" target="_self">kind is lid</a>
						<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->ouderShantiFormuliernummer)); ?>" target="_self">ouder is lid</a>
			<?php
						if ($activiteit->extraShantiFormuliernummer > 0) {
			?>
							<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->extraShantiFormuliernummer)); ?>" target="_self">extra kind</a>
			<?php
						}
					} else {
			?>
			<?php		if ($activiteit->extraShantiFormuliernummer > 0) {
			?>				<span>Inschrijven met: </span>
							<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer)); ?>" target="_self">kind is lid</a>
							<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->extraShantiFormuliernummer)); ?>" target="_self">extra kind</a>
			<?php		} else {
			?>				<a class="btn btn-primary" href="<?php echo(createInschrijfFormulierLink($shantiUrl, $activiteit->shantiFormuliernummer)); ?>" target="_self">Inschrijven</a>
			<?php		}
					}
			?>
					</div>
			<?php
				}
			?>
		</div>

		<div class="clear"> </div>

		<div class="item column-5 span5">
			<?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto1)) { ?>
				<img src="<?php echo($activiteit->webadresFoto1); ?>" alt="" border="0" width="100%" /><br /><br />
			<?php } ?>
			<?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto2)) { ?>
				<img src="<?php echo($activiteit->webadresFoto2); ?>" alt="" border="0" width="100%" /><br /><br />
			<?php } ?>
			<?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto3)) { ?>
				<img src="<?php echo($activiteit->webadresFoto3); ?>" alt="" border="0" width="100%" /><br /><br />
			<?php } ?>
		</div>

	</div>

</article>

