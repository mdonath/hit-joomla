<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');

$aantalIngeschreven = 0;
$aantalGereserveerd = 0;

$project = $this->project;


?> 
<div class="rt-article">
	<div class="item-page">
		<div class="module-content-pagetitle">
			<div class="module-l">
				<div class="module-r">
					<div class="rt-headline">
						<div class="module-title">
							<div class="module-title2">
								<h1 class="title rt-pagetitle">HIT-activiteiten <?php echo ($project->jaar); ?></h1>
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

<p><strong>In dit overzicht vind je alle HITs van <?php echo ($project->jaar); ?>, gesorteerd per HIT-plaats. </strong></p>
<p>Vind je het moeilijk een keuze te maken? Gebruik dan de speciale <a href="hit-activiteiten-<?php echo($project->jaar);?>/hit-kiezer-<?php echo($project->jaar);?>">HIT-kiezer</a>!
   Hiermee kun je kijken welke HIT er bij je past, op basis van je leeftijd tijdens de HIT, je budget, en dingen die je graag
   wilt doen bij een HIT of juist liever niet.</p>

<table id="overzicht">
	<?php foreach ($project->plaatsen as $plaats) { ?>
	<thead>
		<tr>
			<th class="kolom1"><a href="<?php echo(KampInfoUrlHelper::plaatsURL($plaats, $project->jaar, $useComponentUrls)); ?>"><?php echo($plaats->naam);?></a></th>
			<th class="kolom2">Leeftijd</th>
			<th class="kolom3">Groep</th>
			<th class="kolom4">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($plaats->kampen as $kamp) { ?>
		<?php 
				$aantalIngeschreven += $kamp->aantalDeelnemers;
				$aantalGereserveerd += $kamp->gereserveerd;
		?>
		<tr>
			<td class="kolom1">
				<a	href="<?php echo(KampInfoUrlHelper::activiteitURL($plaats, $kamp, $project->jaar, $useComponentUrls)); ?>"
					title="<?php echo(KampInfoUrlHelper::fuzzyIndicatieVol($kamp)); ?>"
				>
					<?php echo($kamp->naam); ?>
				</a>
			</td>
			<td class="kolom2"><?php echo($kamp->minimumLeeftijd); ?>&nbsp;-&nbsp;<?php echo($kamp->maximumLeeftijd); ?></td>
			<td class="kolom3">
				<?php
					$subgroepMin = $kamp->subgroepsamenstellingMinimum;
					$subgroepMax = $kamp->subgroepsamenstellingMaximum;
					if ($subgroepMin == 0 || $subgroepMax == 0) {
						echo('&nbsp;');						
					} elseif ($subgroepMin == $subgroepMax) {
						echo("$subgroepMin pers.");
					} else {
						echo("$subgroepMin - $subgroepMax pers.");
					}
				?>
			</td>
			<td class="kolom4">
				<?php
					if (KampInfoUrlHelper::isVol($kamp)) {
						echo(KampInfoUrlHelper::imgUrl($iconFolderSmall, 'vol', $iconExtension, KampInfoUrlHelper::fuzzyIndicatieVol($kamp)));
					}
					foreach ($kamp->icoontjes as $icoon) {
						echo(KampInfoUrlHelper::imgUrl($iconFolderSmall, $icoon->naam, $iconExtension, $icoon->tekst));
					}
					?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<th colspan="4">Totaal aantal gereserveerd: <?php echo($aantalGereserveerd); ?>, waarvan al ingeschreven: <?php echo ($aantalIngeschreven); ?>.
			<?php if (!empty($project->laatstBijgewerktOp)) { ?>
			 Laatst bijgewerkt op: <?php echo($project->laatstBijgewerktOp  /*->format('d-m-Y H:i')*/); ?></th>
			<?php } ?>
		</tr>
	</tfoot>
</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
