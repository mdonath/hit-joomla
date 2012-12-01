<?php defined('_JEXEC') or die('Restricted access');

function activiteitURL($plaats, $kamp, $use=TRUE) {
	if ($use) {
		$jaar = $plaats->jaar;
		$deelnemersnummer = $kamp->deelnemersnummer;
		return "index.php?option=com_kampinfo&amp;view=activiteit&amp;jaar=$jaar&amp;deelnemersnummer=$deelnemersnummer";
	} else {
		return plaatsURL($plaats, $use) . "/" . aliassify($kamp);
	}
}
function plaatsURL($plaats, $use=TRUE) {
	if ($use) {
		$code = $plaats->code;
		return "index.php?option=com_kampinfo&amp;view=overzichtplaats&amp;plaats=$code";
	} else {
		return "hits-in-" . strtolower($plaats->naam);
	}
}

function aliassify($kamp) {
	$pat = array();
	$rep = array();

	$pat[] = '/ - /';			$rep[] = '-';
	$pat[] = '/ /';				$rep[] = '-';
	$pat[] = '/[^a-z0-9\-]/';	$rep[] = '';
	$pat[] = '/-+/';			$rep[] = '-';

	return preg_replace($pat, $rep, strtolower($kamp->naam));
}

// config
$params = &JComponentHelper::getParams('com_kampinfo');
$useComponentUrls = $params->get('useComponentUrls') == 1;

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
			<th class="kolom1"><a href="<?php echo(plaatsURL($plaats, $useComponentUrls)); ?>"><?php echo($plaats->naam);?></a></th>
			<th class="kolom2">Leeftijd</th>
			<th class="kolom3">Groep</th>
			<th class="kolom4">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($plaats->kampen as $kamp) { ?>
		<tr>
			<td class="kolom1">
				<a href="<?php echo(activiteitURL($plaats, $kamp, $useComponentUrls)); ?>">
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
					foreach ($kamp->icoontjes as $icoon) {
						echo '<img src="media/com_kampinfo/images/iconen25pix/'.$icoon->naam.'.gif" title="'.$icoon->tekst.'"/>';
					}
				?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<?php } ?>
	<tfoot>
		<tr>
			<th colspan="4">Laatst bijgewerkt op: {vandaag}, ingeschreven: {0}, gereserveerd: {0}</th>	
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
