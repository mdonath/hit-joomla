<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

$document = JFactory::getDocument();
$document->setMetaData( 'robots', 'noindex' );

// config
$params =JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');

$project = $this->project;

function bepaalStatus($kamp) {
	$status = '';
	if ($kamp->aantalDeelnemers >= $kamp->maximumAantalDeelnemers || KampInfoUrlHelper::isVolQuaGroepjes($kamp)) {
		$status = 'vol';
	} else if ($kamp->gereserveerd >= $kamp->maximumAantalDeelnemers) {
		$status = 'zoGoedAlsVol'; // groen
	} else if ($kamp->aantalDeelnemers >= (($kamp->maximumAantalDeelnemers + $kamp->minimumAantalDeelnemers)/2)) {
		$status = 'zoGoedAlsVol';
	} else if ($kamp->gereserveerd >= $kamp->minimumAantalDeelnemers && $kamp->aantalDeelnemers < $kamp->minimumAantalDeelnemers) {
		$status = 'quaReserveringen'; // oranje
	} else if ($kamp->aantalDeelnemers >= $kamp->minimumAantalDeelnemers || $kamp->gereserveerd >= $kamp->minimumAantalDeelnemers) {
		$status = 'zoGoedAlsVol';
			
	} else if ($kamp->aantalDeelnemers == 0) {
		$status = 'leeg'; // rood
	} else {
		$status = 'nogNietGenoeg';
	}
	return $status;
}

function printProgressbarKamp($kamp) {
	printProgressbar($kamp->minimumAantalDeelnemers, $kamp->aantalDeelnemers, $kamp->gereserveerd, $kamp->maximumAantalDeelnemers, $kamp->aantalSubgroepen, $kamp->maximumAantalSubgroepjes);
}

function printProgressbar($min, $aantal, $res, $max, $aantalGroep=0, $maxGroep=0) {
	$percentageVol = round(100 * $aantal / max($max,$res));
	$percentageVolText = round(100 * $aantal / $max);
	$percentageRes = round(100 * ($res - $aantal) / max($max,$res));
	if ($percentageRes + $percentageVol > 100) { // afrondingsverschillen
		$percentageRes = $percentageRes - 1;
	}
	$percentageGroep = 0;
	if ($maxGroep > 0 && $aantalGroep >= $maxGroep) {
		$percentageGroep = 100 - $percentageVol - $percentageRes;
	}
	$resplaats = ($res-$aantal);
	$volOfIng = $aantal == max($max,$res) ? "vol" : "ing";
	echo("<div class=\"progressbar\" style=\"width: $percentageVol%\">$percentageVolText% $volOfIng</div>");
	echo("<div class=\"progressbar\" style=\"width: $percentageRes%\" title=\"$resplaats\">&nbsp;</div>");
	if ($res < $min) {
		$tekort = $min - $res;
		$percTekort = round(100 * $tekort / max($max,$res));
		echo("<div class=\"progressbar remain\" style=\"width: $percTekort%\" title=\"$tekort\">$tekort</div>");
	}
	if ($percentageGroep > 0) {
		echo("<div class=\"progressbar\" style=\"width: $percentageGroep%\" title=\"Maximum aantal groepen bereikt\">vol</div>");
	}
}

function printInfo($kamp) {
	return 'Leeftijd: ' .$kamp->minimumLeeftijd .' - '. $kamp->maximumLeeftijd . ' jaar.';
}

function berekenRestCapaciteit($project, $kamp) {
	if (KampInfoUrlHelper::isVolQuaGroepjes($kamp)) {
		return 0;
	}
	return max(0, factorAantal($project, $kamp) * ($kamp->maximumAantalDeelnemers - $kamp->gereserveerd));
}

function factorAantal($project, $kamp) {
	// Pas sinds 2018 staan de inschrijving van de ouder bij de inschrijving van het kind.
	// De ene inschrijving telt dus voor twee deelnemers.
	if ($kamp->isouderkind == '1' && ($project->jaar == 2018 || $project->jaar == 2019)) {
		return 2;
	}
	return 1;
}

?>

<article class="itempage" itemtype="http://schema.org/Article" itemscope="">
	<hgroup>
		<h1>HIT-activiteiten <?php echo ($project->jaar); ?></h1>
	</hgroup>

	<div itemprop="articleBody">

<p>In dit overzicht vind je de inschrijfstatistieken. Onderaan staat de legenda met een verklaring van de kleuren.</p>

<p class="nogNietGenoeg"><strong>LET OP:</strong> met ingang van maandag 4 februari zijn de aantallen van de Ouder-Kind kampen verdubbeld zodat de ouders ook meetellen in de aantallen!</p>

<table id="inschrijvingen">
	<?php
		$minimumAantal = 0;
		$aantalIngeschreven = 0;
		$aantalGereserveerd = 0;
		$maximumAantal = 0;
		$hitRest = 0;
	?>
	<?php foreach ($project->plaatsen as $plaats) { ?>
	<thead>
		<tr>
			<th class="kolom1"><a name="<?php echo(strtolower($plaats->naam));?>"></a><?php echo($plaats->naam);?></th>
			<th class="kolom2" title="Minimum aantal deelnemers">Min.</th>
			<th class="kolom3" title="Aantal daadwerkelijk ingeschreven deelnemers">Ing.</th>
			<th class="kolom4" title="Aantal gereserveerde plekken">Res.</th>
			<th class="kolom5" title="Maximum aantal deelnemers">Max.</th>
			<th class="kolom7" title="Aantal subgroepjes (+ evt. maximum aantal subgroepjes)">Sub.</th>
			<th class="kolom9" title="Resterende capaciteit">Rest</th>
			<th class="kolom6" title="Vulling">Vol</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$plaatsMinimumAantal = 0;
			$plaatsAantalIngeschreven = 0;
			$plaatsAantalGereserveerd = 0;
			$plaatsMaximumAantal = 0;
			$plaatsRest = 0;
			?>
		<?php foreach ($plaats->kampen as $kamp) { ?>
			<?php
			$status = bepaalStatus($kamp);
			$factor = factorAantal($project, $kamp);
			$kampMinimumAantal = $factor * $kamp->minimumAantalDeelnemers;
			$kampAantalIngeschreven = $factor * $kamp->aantalDeelnemers;
			$kampAantalGereserveerd = $factor * $kamp->gereserveerd;
			$kampMaximumAantal = $factor * $kamp->maximumAantalDeelnemers;
			?>
		<tr>
			<td class="kolom1 <?php echo $status?>"
				title="<?php echo(printInfo($kamp)); ?>"
			>
				<?php echo($kamp->naam); ?>
			</td>
			<td class="kolom2"><?php echo($kampMinimumAantal); ?></td>
			<td class="kolom3"><?php echo($kampAantalIngeschreven); ?></td>
			<td class="kolom4"><?php echo($kampAantalGereserveerd); ?></td>
			<td class="kolom5"><?php echo($kampMaximumAantal); ?></td>
			<td class="kolom7"><?php echo($kamp->aantalSubgroepen . ($kamp->maximumAantalSubgroepjes == 0 ? '' : ('/'.$kamp->maximumAantalSubgroepjes))); ?></td>
			<?php $rest = berekenRestCapaciteit($project, $kamp); ?>
			<td class="kolom9"><?php echo($rest); ?></td>
			<td class="kolom6"><?php printProgressbarKamp($kamp); ?></td>
		</tr>
		<?php
			$plaatsMinimumAantal += $kampMinimumAantal;
			$plaatsAantalIngeschreven += $kampAantalIngeschreven;
			$plaatsAantalGereserveerd += $kampAantalGereserveerd;
			$plaatsMaximumAantal += $kampMaximumAantal;
			$plaatsRest += $rest;
		?>
		<?php } // foreach.kamp ?>
		<?php
			$minimumAantal += $plaatsMinimumAantal;
			$aantalIngeschreven += $plaatsAantalIngeschreven;
			$aantalGereserveerd += $plaatsAantalGereserveerd;
			$maximumAantal += $plaatsMaximumAantal;
			$hitRest += $plaatsRest;
		?>
		<tr>
			<th class="kolom1">TOTAAL <?php echo($plaats->naam);?>:</th>
			<th class="kolom2"><?php echo($plaatsMinimumAantal); ?></th>
			<th class="kolom3"><?php echo($plaatsAantalIngeschreven); ?></th>
			<th class="kolom4"><?php echo($plaatsAantalGereserveerd); ?></th>
			<th class="kolom5"><?php echo($plaatsMaximumAantal); ?></th>
			<th class="kolom7">&nbsp;</th>
			<th class="kolom9"><?php echo ($plaatsRest);?></th>
			<th class="kolom6"><?php printProgressbar($plaatsMinimumAantal, $plaatsAantalIngeschreven, $plaatsAantalGereserveerd, $plaatsMaximumAantal); ?></th>
		</tr>
		<tr>
			<td colspan="7">&nbsp;</td>
		</tr>
	</tbody>
	<?php } // foreach.plaats ?>
	<tfoot>
		<tr>
			<th class="kolom1">TOTAAL HIT:</th>
			<th class="kolom2"><?php echo($minimumAantal); ?></th>
			<th class="kolom3"><?php echo($aantalIngeschreven); ?></th>
			<th class="kolom4"><?php echo($aantalGereserveerd); ?></th>
			<th class="kolom5"><?php echo($maximumAantal); ?></th>
			<th class="kolom7">&nbsp;</th>
			<th class="kolom9"><?php echo ($hitRest);?></th>
			<th class="kolom6"><?php printProgressbar($minimumAantal, $aantalIngeschreven, $aantalGereserveerd, $maximumAantal); ?></th>
		</tr>
		<?php if (!empty($project->laatstBijgewerktOp)) { ?>
		<tr>
			<th colspan="8">Laatst bijgewerkt op: <?php echo($project->laatstBijgewerktOp); ?></th>
		</tr>
		<?php } ?>
		<tr>
			<th>Legenda</th>
			<th colspan="7">
				<div class="vol">Vol</div>
				<div class="zoGoedAlsVol">Gaat door op basis van inschrijvingen</div>
				<div class="quaReserveringen">Gaat wel door obv reserveringen, te weinig inschrijvingen</div>
				<div class="nogNietGenoeg">Nog te weinig deelnemers</div>
				<div class="leeg">Nog geen inschrijvingen</div>
			</th>
		</tr>
	</tfoot>
</table>

	</div>
</article>
