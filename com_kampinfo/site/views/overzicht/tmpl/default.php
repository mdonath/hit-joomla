<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$project = $this->project;
?>
<h1>HIT-activiteiten van <?php echo $this->jaar; ?></h1>

<div class="headerAlgemeen">
	<p><strong>In dit overzicht vind je alle HITs van <?php echo $project->jaar; ?>, gesorteerd per HIT-plaats. </strong></p>
	<p>Vind je het moeilijk een keuze te maken? Gebruik dan de speciale <a href="/hit-activiteiten/hit-kiezer">HIT-kiezer</a>!
	   Hiermee kun je kijken welke HIT er bij je past, op basis van je leeftijd tijdens de HIT, je budget, en dingen die je graag
	   wilt doen bij een HIT of juist liever niet.</p>
</div>

<table id="overzicht">
	<?php foreach ($project->plaatsen as $plaats) { ?>
	<thead>
		<tr>
			<th class="kolom1"><a href="index.php?option=com_kampinfo&amp;view=overzichtplaats&amp;plaats=<?php echo($plaats->code);?>"><?php echo($plaats->naam);?></a></th>
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
			<td class="kolom2"><?php echo($kamp->minimumLeeftijd); ?>-<?php echo($kamp->maximumLeeftijd); ?></td>
			<td class="kolom3"><?php echo($kamp->groep); ?></td>
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
	<?php } ?>
	<tfoot>
		<tr>
			<th colspan="4">Laatst bijgewerkt op: {vandaag}, ingeschreven: {0}, gereserveerd: {0}</th>	
		</tr>
	</tfoot>
</table>
