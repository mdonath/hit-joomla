<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;
$iconFolderSmall = $params->get('iconFolderSmall');
$iconFolderLarge = $params->get('iconFolderLarge');
$iconExtension = $params->get('iconExtension');

$aantalIngeschreven = 0;
$aantalGereserveerd = 0;

// Model
$plaats = $this->plaats;

?> 
<div class="rt-article">
	<div class="item-page">
		<div class="module-content-pagetitle">
			<div class="module-l">
				<div class="module-r">
					<div class="rt-headline">
						<div class="module-title">
							<div class="module-title2">
								<h1 class="title rt-pagetitle">De HIT's van HIT <?php echo $plaats->naam; ?></h1>
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

<table id="overzicht">
	<thead>
		<tr>
			<th class="kolom1">&nbsp;</th>
			<th class="kolom2">Leeftijd</h>
			<th class="kolom3">Groep</h>
			<th class="kolom4">&nbsp;</h>
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
				<a	href="<?php echo(KampInfoUrlHelper::activiteitURL($plaats, $kamp, $useComponentUrls)); ?>"
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
	<?php if ($aantalIngeschreven > 75) { ?>
	<tfoot>
		<tr>
			<th colspan="4">Totaal aantal gereserveerd: <?php echo($aantalGereserveerd); ?>, waarvan al ingeschreven: <?php echo ($aantalIngeschreven); ?>.</th>
		</tr>
	</tfoot>
	<?php } ?>
</table>
<br />
<p>Zit er wat voor je bij? Schrijf je snel in en kom met Pasen naar <?php echo($plaats->naam); ?>!</p>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
