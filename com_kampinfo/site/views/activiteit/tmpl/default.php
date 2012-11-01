<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$activiteit = $this->activiteit;
?>
<table border="0">
	<tbody>
		<tr>
			<td>
				<p>
					<img src="https://hit.scouting.nl/images/stories/activiteitengebieden/uitdagend.png" alt="Uitdagende Scoutingtechnieken: activiteiten rondom een techniek, zoals hakken, stoken, kaart en kompas, routetechnieken, pionieren, zeilen en kamperen." title="Uitdagende Scoutingtechnieken: activiteiten rondom een techniek, zoals hakken, stoken, kaart en kompas, routetechnieken, pionieren, zeilen en kamperen." style="margin: 0pt; border: 0pt none;" />
					<img src="https://hit.scouting.nl/images/stories/activiteitengebieden/expressie.png"  alt="Expressie: activiteiten waarmee je je kunt uitdrukken, zoals dansen, filmen, handvaardigheid, toneelspelen, muziek maken, schrijven." title="Expressie: activiteiten waarmee je je kunt uitdrukken, zoals dansen, filmen, handvaardigheid, toneelspelen, muziek maken, schrijven." style="margin: 0pt; border: 0pt none;" border="0" />
					<img src="https://hit.scouting.nl/images/stories/activiteitengebieden/buitenleven.png" alt="Buitenleven: activiteiten rondom het beleven van de natuur en overleven in de natuur, zoals survival, kennis van plant en dier, milieu, natuurbeheer en weer." title="Buitenleven: activiteiten rondom het beleven van de natuur en overleven in de natuur, zoals survival, kennis van plant en dier, milieu, natuurbeheer en weer." style="margin: 0pt; border: 0pt none;" border="0" />
				</p>
			</td>
		</tr>
		<tr>
			<td><br /></td>
		</tr>
	</tbody>
</table>

<div id="content">

<table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td rowspan="6" valign="top">
			<h1><?php echo($activiteit->naam); ?></h1>
			</td>
		</tr>
		<tr>
			<td valign="top"><b>Plaats</b></td>
			<td valign="top">:</td>
			<td valign="top"><?php echo($activiteit->plaats); ?></td>
		</tr>
		<tr>
			<td valign="top"><b>Datum</b></td>
			<td valign="top">:</td>
			<td valign="top">7 april t/m 9 april</td>
		</tr>
		<tr>
			<td valign="top"><b>Leeftijd</b></td>
			<td valign="top">:</td>
			<td valign="top"><?php echo($activiteit->minimumLeeftijd); ?> t/m <?php echo($activiteit->maximumLeeftijd); ?> jaar</td>
		</tr>
		<tr>
			<td valign="top"><b>Groep</b></td>
			<td valign="top">:</td>
			<td valign="top"><?php echo($activiteit->groep); ?> pers.</td>
		</tr>
		<tr>
			<td valign="top"><b>Prijs</b></td>
			<td valign="top">:</td>
			<td valign="top">€ <?php echo($activiteit->deelnamekosten); ?></td>
		</tr>
	</tbody>
</table>
 
<table style="width: 100%;" border="0" cellpadding="0">
	<tbody>
		<tr>
			<td><br mce_bogus="1" /></td>
		</tr>
		<tr>
			<td>
				<div style="text-align: right;">
				<?php
					// TODO: alt-text door in model al icoon-objecten te maken
					$activiteit->icoontjes = explode(',', $activiteit->icoontjes);
					foreach ($activiteit->icoontjes as $icoon) {
						echo '<img src="media/com_kampinfo/images/iconen40pix/'.$icoon.'.gif"/>';
					}
				?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<h2>Dit is de subkop</h2>
					<h2></h2>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<table border="0">
	<tbody>
		<tr>
			<td valign="top" width="480">
			<?php echo($activiteit->websiteTekst); ?>
			</td>
			<td valign="top" width="190">
				<img src="https://hit.scouting.nl/images/stories/hitkampenfotos/img-150646-1-b.jpg" alt="Foto 1" border="0" width="180" />
				<br /><br />
				<img src="https://hit.scouting.nl/images/stories/hitkampenfotos/img-150646-3-b.jpg" alt="Foto 2" border="0" width="180" />
				<br /><br /><br />
			</td>
		</tr>
	</tbody>
</table>

<table border="0">
	<tbody>
		<tr>
			<td><img src="https://hit.scouting.nl/images/iconen40pix/info.gif" alt="Meer informatie? Mail of bel naar de contactpersoon van deze HIT" border="0" /></td>
			<td>
				<p>
					Bel bij vragen Wouter en Bram: 06-13946054<br /> <a href="mailto:jongerenhike@gmail.com" >Mail naar jongerenhike@gmail.com</a>
					<br mce_bogus="1" />
				</p>
			</td>
		</tr>
		<tr>
			<td><img src="https://hit.scouting.nl/images/iconen40pix/web.gif" mce_src="https://hit.scouting.nl/images/iconen40pix/web.gif" alt="Link naar een website over dit HIT onderdeel" border="0" /></td>
			<td>
				<p>
					<a href="http://www.wix.com/jongerenhike/2012">http://www.wix.com/jongerenhike/2012</a>
					<br mce_bogus="1" />
				</p>
			</td>
		</tr>
	</tbody>
</table>


<h2>Aanvullende info:</h2>
<p>VOLVOL!<br />
	Er kunnen maximaal 150 deelnemers meedoen aan deze activiteit.
	Totale afstand die tijdens deze HIT gelopen wordt is maximaal 50 kilometer.
	Dit HIT-Kamp start op 7 april om 14:00 uur en duurt tot en met 9 april 18:00 uur.
</p>

<input style="float: right" value="Direct inschrijven" type="BUTTON" />
<br />
</div>
