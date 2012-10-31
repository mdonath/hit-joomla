<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$plaats = $this->plaats;
?>
<h1>De HIT's van HIT <?php echo $plaats->naam; ?></h1>

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
				<a href="/hits-in-<?php echo($plaats->naam); ?>/<?php echo($kamp->naam); ?>">
					<?php echo($kamp->naam); ?>
				</a>
			</td>
			<td class="kolom2"><?php echo($kamp->minimumLeeftijd); ?>-<?php echo($kamp->maximumLeeftijd); ?></td>
			<td class="kolom3"><?php echo($kamp->groep); ?></td>
			<td class="kolom4">
				<img src="https://hit.scouting.nl/images/iconen25pix/vol.gif" alt="Dit kamp is vol!" title="Zo goed als vol"/>
				<img src="https://hit.scouting.nl/images/iconen25pix/hike.gif" />
				<img src="https://hit.scouting.nl/images/iconen25pix/groepje.gif" />
				<img src="https://hit.scouting.nl/images/iconen25pix/hike.gif" />
				<img src="https://hit.scouting.nl/images/iconen25pix/groepje.gif" />
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