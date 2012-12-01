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
		<tr>
			<td class="kolom1">
				<a href="<?php echo(activiteitURL($plaats, $kamp, $useComponentUrls)); ?>"><?php echo($kamp->naam); ?>	</a>
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
						$b = $icoon->naam;
						$a = $icoon->tekst;
						echo ("<img src=\"media/com_kampinfo/images/iconen25pix/$b.gif\" title=\"$a\"/>");
					}
				?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4">Laatst bijgewerkt op: {vandaag}, ingeschreven: {0}, gereserveerd: {0}</th>	
		</tr>
	</tfoot>
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
