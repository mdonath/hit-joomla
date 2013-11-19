<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$vrijVraagBriefUrl = $params->get('vrijVraagBriefUrl');
$activiteitengebiedenFolder = $params->get('activiteitengebiedenFolder');
$activiteitengebiedenExtension = $params->get('activiteitengebiedenExtension');
$iconFolderLarge = $params->get('iconFolderLarge');
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');
$shantiUrl = $params->get('shantiUrl');

// model
$activiteit = $this->activiteit;

$start = new JDate($activiteit->startDatumTijd);
$eind = new JDate($activiteit->eindDatumTijd);

?>
<div class="rt-article">
	<div class="item-page">
		<div class="module-content-pagetitle">
			<div class="module-l">
				<div class="module-r">
					<div class="rt-headline">
						<div class="module-title">
							<div class="module-title2">
								<div class="activiteitengebiedenlist">
									<p>
										<?php
											foreach ($activiteit->activiteitengebieden as $gebied) {
												echo(KampInfoUrlHelper::imgUrl($activiteitengebiedenFolder, $gebied->value, $activiteitengebiedenExtension, $gebied->text) . ' ');
											}
										?>
									</p>
									<h1 class="title rt-pagetitle"><?php echo($activiteit->naam); ?></h1>
								</div>

								<div class="kampdata">
									<table border="0" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td valign="top"><b>Plaats</b></td>
											<td valign="top">:&nbsp;</td>
											<td valign="top"><?php echo($activiteit->plaats); ?></td>
										</tr>
										<tr>
											<td valign="top"><b>Datum</b></td>
											<td valign="top">:</td>
											<td valign="top">
											<?php
												$startF = $start->format('j F');
												$eindF = $eind->format('j F');
												echo("$startF t/m $eindF");
											?>
											</td>
										</tr>
										<tr>
											<td valign="top"><b>Leeftijd</b></td>
											<td valign="top">:</td>
											<td valign="top"><?php echo($activiteit->minimumLeeftijd); ?> t/m <?php echo($activiteit->maximumLeeftijd); ?> jaar</td>
										</tr>
										<tr>
											<td valign="top"><b>Groep</b></td>
											<td valign="top">:</td>
											<td valign="top">
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
											?>
											</td>
										</tr>
										<tr>
											<td valign="top"><b>Prijs</b></td>
											<td valign="top">:</td>
											<td valign="top">€ <?php echo($activiteit->deelnamekosten); ?></td>
										</tr>
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<div class="module-content">
			<div class="module-l">
				<div class="module-r">
					<div class="module-inner">
						<div class="module-inner2">
							<div>
								<div class="iconlist" style="float:right">
								<?php
									foreach ($activiteit->icoontjes as $icoon) {
										echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, $icoon->naam, $iconExtension, $icoon->tekst));
									}
								?>
								</div>
							</div>

							<div class="clear"></div>
							<?php if (strtolower($activiteit->titeltekst) == strtolower($activiteit->naam)) { ?>
								<h2></h2>
							<?php } else { ?>
								<h2><?php echo($activiteit->titeltekst);?></h2>
							<?php } ?>
							<?php
								$heeftEenYoutubeFilmpje = KampInfoUrlHelper::isYoutubeFilmpje($activiteit->webadresFoto3);
							?>
							<table border="0">
								<tbody>
									<tr>
										<td class="articleTekst">
											<p><?php echo($activiteit->websiteTekst); ?></p>
										</td>
										<td class="articleFotos">
											<?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto1)) { ?>
												<img src="<?php echo($activiteit->webadresFoto1); ?>" alt="" border="0" width="180" /><br /><br />
											<?php } ?>
											<?php if (!KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto2)) { ?>
												<img src="<?php echo($activiteit->webadresFoto2); ?>" alt="" border="0" width="180" /><br /><br />
											<?php } ?>
											<?php if (!($heeftEenYoutubeFilmpje or KampInfoUrlHelper::isEmptyUrl($activiteit->webadresFoto3))) { ?>
												<img src="<?php echo($activiteit->webadresFoto3); ?>" alt="" border="0" width="180" /><br /><br />
											<?php } ?>
										</td>
									</tr>
									
									<?php if ($heeftEenYoutubeFilmpje) { ?>
									<tr>
										<td colspan="2">
											<iframe width="670" height="400" src="<?php echo($activiteit->webadresFoto3); ?>" frameborder="0" allowfullscreen></iframe>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>

							<?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres) or !empty($activiteit->websiteAdres)) { ?>
							<h2>Meer weten over het kamp?</h2>
							<table border="0">
								<tbody>
									<?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres)) { ?>
									<tr>
										<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'info', $iconExtension, '','Meer informatie? Mail of bel naar de contactpersoon van deze HIT')); ?></td>
										<td>
											<p>
												<?php if (!empty($activiteit->websiteContactTelefoonnummer)) { ?>
													Bel bij vragen <?php echo($activiteit->websiteContactpersoon); ?>: <?php echo($activiteit->websiteContactTelefoonnummer); ?><br />
												<?php } ?>
												<?php if (!empty($activiteit->websiteContactEmailadres)) { ?>
													<a href="mailto:<?php echo($activiteit->websiteContactEmailadres); ?>" >Mail naar <?php echo($activiteit->websiteContactEmailadres); ?></a><br />
												<?php } ?>
											</p>
										</td>
									</tr>
									<?php } ?>
									<?php if (!empty($activiteit->websiteAdres)) { ?>
									<tr>
										<td><?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, 'web', $iconExtension, '','Link naar een website over dit HIT onderdeel')); ?></td>
										<td>
											<p>
												<a href="<?php echo($activiteit->websiteAdres); ?>"><?php echo($activiteit->websiteAdres); ?></a><br/>
											</p>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<p>Neem voor vragen over de inschrijving contact op met de <a href="contact/">helpdesk</a>.</p>
							<?php } ?>

							<h2>Aanvullende info:</h2>
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
									$startDTF = $start->format('l j F \o\m H:i \u\u\r');
									$eindDTF = $eind->format('l j F H:i \u\u\r');
									$begintOpGoedeVrijdag = $start->format('N') == '5'; // vrijdag
								?>
								Dit HIT-Kamp start op <?php echo($startDTF); ?> en duurt tot en met <?php echo($eindDTF); ?>.

								<?php if($begintOpGoedeVrijdag) {?>
								Dit HIT-Kamp begint op Goede Vrijdag. Sommige scholen zijn <b>NIET</b> vrij op Goede Vrijdag. 
								Mogelijk biedt <a href="<?php echo($vrijVraagBriefUrl); ?>" target="brief">deze standaardbrief</a> uitkomst om vrij aan te vragen. 
								<?php } ?>
							</p>
							<?php if (!empty($activiteit->doelgroepen)) { ?>
								<p>Let op: Inschrijven is alleen mogelijk voor de volgende doelgroepen: <?php echo $activiteit->doelgroepen; ?>. Het is daarom belangrijk dat je met de juiste rol bent ingelogd in ScoutsOnline.</p>
							<?php } ?>
							<?php if ($activiteit->shantiFormuliernummer > 0) { ?>
								<input style="float: right" value="Direct inschrijven" type="BUTTON" onclick="window.open('<?php echo($shantiUrl . $activiteit->shantiFormuliernummer); ?>')" />
							<?php } ?>
							<br />
							<br />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
