<?php defined('_JEXEC') or die('Restricted access');

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$vrijVraagBriefUrl = $params->get('vrijVraagBriefUrl');
 
// model
$activiteit = $this->activiteit;

$start = new JDate($activiteit->startDatumTijd);
$eind = new JDate($activiteit->eindDatumTijd);

function isEmptyUrl($url) {
	return empty($url) or $url == 'http://'; 
}

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
												echo '<img src="media/com_kampinfo/images/activiteitengebieden/'.$gebied->value.'.png" title="'.$gebied->text.'"/> ';
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
								<div class="iconlist" style="float:right"
								<?php
									foreach ($activiteit->icoontjes as $icoon) {
										$b = $icoon->naam;
										$a = $icoon->tekst;
										echo ("<img src=\"media/com_kampinfo/images/iconen40pix/$b.gif\" title=\"$a\"/>");
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
								$dotcom = strpos($activiteit->webadresFoto3, 'www.youtube.com') !== false;
								$dotbe = strpos($activiteit->webadresFoto3, 'youtu.be') !== false;
								$heeftEenYoutubeFilmpje = $dotcom || $dotbe;
							?>
							<table border="0">
								<tbody>
									<tr>
										<td class="articleTekst">
											<p><?php echo($activiteit->websiteTekst); ?></p>
										</td>
										<td class="articleFotos">
											<?php if (!isEmptyUrl($activiteit->webadresFoto1)) { ?>
												<img src="<?php echo($activiteit->webadresFoto1); ?>" alt="" border="0" width="180" /><br /><br />
											<?php } ?>
											<?php if (!isEmptyUrl($activiteit->webadresFoto2)) { ?>
												<img src="<?php echo($activiteit->webadresFoto2); ?>" alt="" border="0" width="180" /><br /><br />
											<?php } ?>
											<?php if (!($heeftEenYoutubeFilmpje or isEmptyUrl($activiteit->webadresFoto3))) { ?>
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
							<br />
							<table border="0">
								<tbody>
									<?php if (!empty($activiteit->websiteContactTelefoonnummer) or !empty($activiteit->websiteContactEmailadres)) { ?>
									<tr>
										<td><img src="media/com_kampinfo/images/iconen40pix/info.gif" alt="Meer informatie? Mail of bel naar de contactpersoon van deze HIT" border="0" /></td>
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
										<td><img src="media/com_kampinfo/images/iconen40pix/web.gif" alt="Link naar een website over dit HIT onderdeel" border="0" /></td>
										<td>
											<p>
												<a href="<?php echo($activiteit->websiteAdres); ?>"><?php echo($activiteit->websiteAdres); ?></a><br/>
											</p>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
							
							<h2>Aanvullende info:</h2>
							<p>
							<!--
							VOLVOL!<br />
							-->
							
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
								<?php } // TODO url voor vrijvraagbrief ?>
							</p>
							<?php if ($activiteit->shantiFormuliernummer > 0) { ?>
								<input style="float: right" value="Direct inschrijven" type="BUTTON" onclick="window.open('https://sol.scouting.nl/frontend/sol/index.php?task=as_registration&amp;action=add&amp;frm_id=<?php echo($activiteit->shantiFormuliernummer); ?>')" />
							<?php } ?>
							<br />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
