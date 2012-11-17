<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$plaats = $this->plaats;
?>
<p><strong>De HIT's van HIT <?php echo $plaats->naam; ?></strong></p>

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
				<a href="index.php?option=com_kampinfo&amp;view=activiteit&amp;id=<?php echo($kamp->id); ?>">
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
					// TODO: alt-text door in model al icoon-objecten te maken
					$kamp->icoontjes = explode(',', $kamp->icoontjes);
					foreach ($kamp->icoontjes as $icoon) {
						echo '<img src="media/com_kampinfo/images/iconen25pix/'.$icoon.'.gif"/>';
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

<p>Zit er wat voor je bij? Schrijf je snel in en kom met Pasen naar <?php echo($plaats->naam); ?>!</p>