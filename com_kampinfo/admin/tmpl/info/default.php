<?php

defined('_JEXEC') or die('Restricted Access');

$manifest = $this->getModel()->getItems();
?>

<div class="row">
    <div class="col-md-12">
        <div id="j-main-container" class="j-main-container">
			<h2>Informatie:</h2>
			<p>Dit is KampInfo, een Joomla component speciaal voor de HIT.</p>

            <table class="table table-striped">
			<tbody>
				<tr><th>Versie:</th><td><?php echo($manifest['version']);?></td></tr>
				<tr><th>Naam:</th><td><?php echo(JText::_($manifest['name']));?></td></tr>
				<tr><th>Omschrijving:</th><td><?php echo(JText::_($manifest['description']));?></td></tr>
				<tr><th>Copyright:</th><td><?php echo($manifest['copyright']);?></td></tr>
				<tr><th>Auteur:</th><td><?php echo($manifest['author']);?> (<?php echo($manifest['authorEmail']);?>)</td></tr>
				<tr><th>Code url:</th><td><a href="<?php echo($manifest['authorUrl']);?>"><?php echo($manifest['authorUrl']);?></a></td></tr>
				<tr><th>Start ontwikkeling:</th><td><?php echo($manifest['creationDate']);?></td></tr>
			</tbody>
			</table>

			<hr>

			<h2>Handleidingen:</h2>
			<ul>
				<li><a href="https://hit.scouting.nl/meehelpen-promotie/medewerker-downloads/2-handleiding-kampinfo-voor-kampen">Handleiding voor kampen</a></li>
				<li><a href="https://hit.scouting.nl/meehelpen-promotie/medewerker-downloads/1-handleiding-kampinfo-voor-plaatsen">Handleiding voor plaatsen</a></li>
			</ul>

			<hr>

			<h2>Laatste wijzigingen:</h2>
			<table class="table table-striped">
				<tr><th>Datum</th><th>Wijzigingen</th></tr>
				<tr><td>17-01-2020</td><td>Link naar HIT Helpdesk formulier gewijzigd</td></tr>
				<tr><td>xx-11-2019</td><td>Contentplugin voor het tonen van een lijst van kamponderdelen in een article</td></tr>
				<tr><td>30-11-2018</td><td>Datumprobleem in PDF export op plaatsniveau opgelost (<a href="https://github.com/mdonath/hit-joomla/issues/30">#30</a>)</td></tr>
				<tr><td>06-09-2018</td><td>Link naar SOL is aangepast (<a href="https://github.com/mdonath/hit-joomla/issues/26">#26</a>), bij kopi&euml;ren kamp van vorig jaar wordt het originele max aantal overgenomen bij nieuw maximum aantal voor dat jaar (<a href="https://github.com/mdonath/hit-joomla/issues/25">#25</a>)</td></tr>
				<tr><td>30-11-2017</td><td>Bug opgelost m.b.t. opslaan HIT Plaats.</td></tr>
				<tr><td>27-11-2017</td><td>Tabbladen Financiën verwijderd op verzoek van Dominicus.</td></tr>
				<tr><td>20-11-2017</td><td>Nadat een plaats akkoord heeft gegeven, kan een kamp de gegevens niet meer wijzigen.</td></tr>
			</table>
		</div>
	</div>
</div>
