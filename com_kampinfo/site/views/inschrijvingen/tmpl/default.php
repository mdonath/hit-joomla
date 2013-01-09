<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');

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

<p>In dit overzicht vind je de inschrijfstatistieken. Onderaan staat de legenda met een verklaring van de kleuren.</p>

<table id="inschrijvingen">
	<?php
		$minimumAantal = 0;
		$aantalIngeschreven = 0;
		$aantalGereserveerd = 0;
		$maximumAantal = 0;
	?>
	<?php foreach ($project->plaatsen as $plaats) { ?>
	<thead>
		<tr>
			<th class="kolom1"><a name="<?php echo(strtolower($plaats->naam));?>"></a><?php echo($plaats->naam);?></th>
			<th class="kolom2">Minimum</th>
			<th class="kolom3">Ingeschreven</th>
			<th class="kolom4">Gereserveerd</th>
			<th class="kolom5">Maximum</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$plaatsMinimumAantal = 0;
			$plaatsAantalIngeschreven = 0;
			$plaatsAantalGereserveerd = 0;
			$plaatsMaximumAantal = 0;
			?>
		<?php foreach ($plaats->kampen as $kamp) { ?>
			<?php
			$status = '';
			 if ($kamp->aantalDeelnemers > $kamp->maximumAantalDeelnemers) {
				$status = 'proppievol'; // Heel donker groen
			} if ($kamp->aantalDeelnemers == $kamp->maximumAantalDeelnemers) {
				$status = 'vol'; // donkergroen
			} else if ($kamp->gereserveerd >= $kamp->maximumAantalDeelnemers) {
				$status = 'zoGoedAlsVol'; // groen
			} else if ($kamp->aantalDeelnemers >= (($kamp->maximumAantalDeelnemers + $kamp->minimumAantalDeelnemers)/2)) {
				$status = 'overDeHelft';
			} else if ($kamp->aantalDeelnemers >= $kamp->minimumAantalDeelnemers || $kamp->gereserveerd >= $kamp->minimumAantalDeelnemers) {
				$status = 'quaInschrijvingen'; // geel
			} else if ($kamp->gereserveerd > $kamp->minimumAantalDeelnemers) {
				$status = 'quaReserveringen'; // oranje
			
			} else if ($kamp->aantalDeelnemers == 0) {
				$status = 'leeg'; // rood
			} else {
				$status = 'nogNietGenoeg';
			}
			?>
		<tr>
			<td class="kolom1 <?php echo $status?>"><?php echo($kamp->naam); ?></td>
			<td class="kolom2"><?php echo($kamp->minimumAantalDeelnemers); ?></td>
			<td class="kolom3"><?php echo($kamp->aantalDeelnemers); ?></td>
			<td class="kolom4"><?php echo($kamp->gereserveerd); ?></td>
			<td class="kolom5"><?php echo($kamp->maximumAantalDeelnemers); ?></td>
		</tr>
		<?php
			$plaatsMinimumAantal += $kamp->minimumAantalDeelnemers;
			$plaatsAantalIngeschreven += $kamp->aantalDeelnemers;
			$plaatsAantalGereserveerd += $kamp->gereserveerd;
			$plaatsMaximumAantal += $kamp->maximumAantalDeelnemers;
		?>
		<?php } // foreach.kamp ?>
		<?php
			$minimumAantal += $plaatsMinimumAantal;
			$aantalIngeschreven += $plaatsAantalIngeschreven;
			$aantalGereserveerd += $plaatsAantalGereserveerd;
			$maximumAantal += $plaatsMaximumAantal;
			?>
		<tr>
		<?php $percentageVol = round(100 * $plaatsAantalIngeschreven / $plaatsMaximumAantal); ?>
			<th colspan="5" style="text-align: left;">
				<div style="text-align: center; background-color: lime; width: <?php echo ($percentageVol) ?>%"><?php echo $percentageVol?>% vol</div>
			</th>
		</tr>
		<tr>
			<th>TOTAAL <?php echo($plaats->naam);?>:</th>
			<th><?php echo($plaatsMinimumAantal); ?>
			<th><?php echo($plaatsAantalIngeschreven); ?>
			<th><?php echo($plaatsAantalGereserveerd); ?>
			<th><?php echo($plaatsMaximumAantal); ?>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
	</tbody>
	<?php } // foreach.plaats ?>
	<tfoot>
		<tr>
			<?php $percentageVol = round(100 * $aantalIngeschreven / $maximumAantal); ?>
			<th colspan="5" style="text-align: left;">
				<div style="text-align: center; background-color: lime; width: <?php echo ($percentageVol) ?>%"><?php echo $percentageVol?>% vol</div>
			</th>
		</tr>
		<tr>
			<th>TOTAAL HIT:</th>
			<th><?php echo($minimumAantal); ?>
			<th><?php echo($aantalIngeschreven); ?>
			<th><?php echo($aantalGereserveerd); ?>
			<th><?php echo($maximumAantal); ?>
		</tr>
		<tr>
			<th>Legenda</th>
			<th colspan="4">
				<div class="proppievol">Zelfs meer dan het maximum</div>
				<div class="vol">Helemaal vol</div>
				<div class="zoGoedAlsVol">Bijna vol</div>
				<div class="overDeHelft">Voorbij gemiddelde van minimum en maximum</div>
				<div class="quaInschrijvingen">Gaat door obv inschrijvingen</div>
				<div class="quaReserveringen">Gaat door obv reserveringen</div>
				<div class="nogNietGenoeg">Nog te weinig deelnemers</div>
				<div class="leeg">Nog geen inschrijvingen</div>
			</th>
		</tr>
		<?php if (!empty($project->laatstBijgewerktOp)) { ?>
		<tr>
			<th colspan="5">Laatst bijgewerkt op: <?php echo($project->laatstBijgewerktOp); ?></th>
		</tr>
		<?php } ?>
	</tfoot>
</table>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
