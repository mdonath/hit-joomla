<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params =JComponentHelper::getParams('com_kampinfo');
$vrijVraagBriefUrl = $params->get('vrijVraagBriefUrl');
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
?>

<article class="itempage" itemtype="http://schema.org/Article" itemscope="">

	<!-- Activiteitengebieden -->
	<div class="hidden-phone">
		<?php foreach ($activiteit->activiteitengebieden as $gebied) { ?>
			<?php echo(KampInfoUrlHelper::imgUrl($activiteitengebiedenFolder, $gebied->value, $activiteitengebiedenExtension, $gebied->text) . ' '); ?>
		<?php } ?>
	</div>
	
	<div style="float:right;">
		<!-- Icoontjes -->
		<p style="float: right;">
			<?php foreach ($activiteit->icoontjes as $icoon) { ?>
				<?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, $icoon->naam, $iconExtension, $icoon->tekst));?>
			<?php }	?>
		</p>

		<div class="clear"> </div>
		
		<!-- Detailgegevens -->
		<p style="float: right">
			<span style="white-space: nowrap;">Plaats: <b><?php echo($activiteit->plaats); ?></b></span>
			<span>|</span>
			<?php
				$startF = $start->format('j F', true);
				$eindF = $eind->format('j F', true);
			?>
			<span style="white-space: nowrap;">Datum: <b><?php echo("$startF t/m $eindF"); ?></b></span> 
			<span>|</span>
			<span style="white-space: nowrap;">Leeftijd: <b><?php echo($activiteit->minimumLeeftijd); ?> t/m <?php echo($activiteit->maximumLeeftijd); ?> jaar</b></span>
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
				<h2><?php echo($activiteit->naam); ?></h2>
				
				
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
							<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'info', $iconExtension, '','Meer informatie? Mail of bel naar de contactpersoon van deze HIT')); ?></td>
							<td>
								<?php if (!empty($activiteit->websiteContactTelefoonnummer)) { ?>
									Bel bij vragen <?php echo($activiteit->websiteContactpersoon); ?>: <?php echo($activiteit->websiteContactTelefoonnummer); ?><br />
								<?php } ?>
								<?php if (!empty($activiteit->websiteContactEmailadres)) { ?>
									<a href="mailto:<?php echo($activiteit->websiteContactEmailadres); ?>" >Mail naar <?php echo($activiteit->websiteContactEmailadres); ?></a><br />
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
						<?php if (!empty($activiteit->websiteAdres)) { ?>
						<tr>
							<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'web', $iconExtension, '','Link naar een website over dit HIT onderdeel')); ?></td>
							<td>
								<a href="<?php echo($activiteit->websiteAdres); ?>"><?php echo($activiteit->websiteAdres); ?></a><br/>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<p>Neem voor vragen over de inschrijving contact op met de <a href="contact/">helpdesk</a>.</p>
			<?php } ?>
			
			<h3>Aanvullende info:</h3>
			<p>
				<?php 
				if (KampInfoUrlHelper::isVol($activiteit)) {
					echo(KampInfoUrlHelper::imgUrl($iconFolderSmall, 'vol', $iconExtension, KampInfoUrlHelper::fuzzyIndicatieVol($activiteit)));
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
			
					$nu = (new JDate())->getTimestamp();
					$isInschrijvingGestart = $nu >= (new JDate($activiteit->startInschrijving))->getTimestamp();
					$isInschrijvingNogNietGestopt = $nu <= (new JDate($activiteit->startInschrijving))->getTimestamp();
				?>
				Dit HIT-onderdeel start op <?php echo($startDTF); ?> en duurt tot en met <?php echo($eindDTF); ?>.
				<?php if ($activiteit->startElders == 1) { ?>
					Dit kamp start niet op de hoofdlocatie. 
				<?php }?>
				<?php if (!empty($activiteit->sublocatie)) { ?>
					Dit kamp vindt niet plaats op de hoofdlocatie, maar <?php echo($activiteit->sublocatie); ?>. 
				<?php }?>
			</p>
			<?php if ($activiteit->isouderkind == 1) { ?>
				<p><b>BELANGRIJKE INFORMATIE VOOR OUDERS:</b><br/>Inschrijven voor dit HIT-onderdeel gebeurt met een groep van twee personen: één kind met één ouder als begeleider. De genoemde kampprijs geldt per persoon, en beide personen schrijven zich afzonderlijk in. Bij uitzondering kunnen ook ouders die geen lid zijn, zich voor dit onderdeel inschrijven, op voorwaarde dat het kind wél lid is van Scouting Nederland. Ouders die geen lid zijn, kiezen bij het inschrijven voor "inschrijven als niet-lid". De niet-leden vullen eerst hun persoonlijke gegevens in voordat ze in het inschrijfformulier terecht komen. Na het betalen via iDEAL volgt een standaardmelding dat er een automatische bevestigingsemail verstuurd zal worden. Voor niet-leden gaat dit niet automatisch; deze email wordt binnen enkele dagen handmatig verstuurd. Ouders die zich op deze manier inschrijven worden geregistreerd als "relatie van de landelijke HIT". Het aanvragen van een inlogaccount is voor een relatie niet mogelijk. Eventuele wijzigingen en annuleringen moeten daarom persoonlijk worden doorgegeven aan de HIT Helpdesk.</p>
			<?php }?>
			
			<?php if ($activiteit->shantiFormuliernummer > 0 && $isInschrijvingGestart) { ?>
				<input style="float: right" value="Inschrijven!" type="BUTTON" onclick="window.open('<?php echo($shantiUrl . $activiteit->shantiFormuliernummer); ?>')" />
			<?php } ?>
		</div>
		
		<div class="item column-4 span4">
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

