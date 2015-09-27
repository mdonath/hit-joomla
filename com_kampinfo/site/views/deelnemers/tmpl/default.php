<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

// config
$params =JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');

$project = $this->project;

$project->inschrijvingStartdatum = new DateTime($project->inschrijvingStartdatum);
$project->inschrijvingEinddatum = new DateTime($project->inschrijvingEinddatum);
$project->laatsteInschrijvingOp = new DateTime($project->laatsteInschrijvingOp);

$laatsteDatum = $project->laatsteInschrijvingOp;

$datumKolommen = array();
$datumHeaders = array();
for ($i = copyDate($project->inschrijvingStartdatum); $i <= $laatsteDatum; $i->add(new DateInterval('P1D'))) {
	$datumHeaders[] = $i->format('d-m');
	$datumKolommen[] = $i->format('Md');
}

function toTime($dateTime) {
	return strtotime($dateTime->format('Y-m-d'));
}

function copyDate($date) {
	return new DateTime($date->format('Y-m-d'));
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
								<h1 class="title rt-pagetitle">HIT Deelnemers <?php echo ($project->jaar); ?></h1>
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

<p>In dit overzicht vind je het verloop van de inschrijvingen
van <?php echo $project->inschrijvingStartdatum->format('d-m-Y'); ?> tot en
met <?php echo $laatsteDatum->format('d-m-Y')?>. Gebruik de scrollbar
(of pijltjestoetsen) om de laatste gegevens te zien die helemaal rechts staan. 
</p>

<div style="overflow: auto; ">
	<table class="inschrijvingen">
	<?php foreach ($project->plaatsen as $plaats) { ?>
		<thead>
		<tr>
			<th><?php echo $plaats->naam; ?></th>
			<?php foreach ($datumHeaders as $h) { ?>
				<th class='data'><?php echo str_replace('-', '<br/>', $h); ?></th>
			<?php } ?>
		</tr>
		</thead>
	
		<tbody>
		<?php foreach ($plaats->kampen as $kamp) { ?>
			<tr>
				<td><?php echo $kamp->naam; ?></td>
				<?php
				$totaal = 0;
				foreach ($datumKolommen as $col) {
					$aantalOpDag = $kamp->$col;
					if (!empty($aantalOpDag)) {
						$totaal += $aantalOpDag;
					}
					$plaats->$col += $totaal;
					$project->$col += $totaal;
					echo("<td class='data'>$totaal</td>");
				}
				?>
			</tr>
		<?php }?>
			<tr>
				<th>TOTAAL</th>
				<?php foreach ($datumKolommen as $col) { ?>
					<th class='data'><?php echo $plaats->$col; ?></th>
				<?php } ?>
			</tr>
			<tr>
				<td colspan="<?php echo count($datumKolommen) + 1; ?>">&nbsp;</td>
			</tr>
		</tbody>
	<?php }?>
	<tbody>
		<tr>
			<th>HIT NL</th>
			<?php foreach ($datumKolommen as $col) { ?>
				<th class='data'><?php echo $project->$col; ?></th>
			<?php } ?>
		</tr>
	</tbody>
	
	<tfoot>
		<tr>
			<th colspan="<?php echo count($datumKolommen) + 1; ?>">
				Laatst bijgewerkt op: <?php echo $this->project->laatstBijgewerktOp;?>
			</th>
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
</div>
