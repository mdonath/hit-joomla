<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.helper');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$option = JRequest::getVar('option');
$prefix = "../index.php?option=".$option;
$statistiekPrefix = $prefix . '&view=statistiek&soort=';
?>

<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<ul>
		<li><a target="_blank" href="<?php echo($prefix); ?>&task=hitcourant.generate">HIT Courant</a></li>
		<li><a target="_blank" href="<?php echo($prefix); ?>&view=shanti&format=raw">Shanti dump</a></li>
		<li><a target="_blank" href="<?php echo($prefix); ?>&view=financien&format=raw">Financiën</a></li>
	</ul>

	<h2>Grafiekjes</h2>
	<ul>
		<li><a target="_blank" href="<?php echo($statistiekPrefix); ?>InschrijvingenPerDagPerJaar">Inschrijvingen per dag per jaar</a></li>
		<li><a target="_blank" href="<?php echo($statistiekPrefix); ?>TotaalInschrijvingenPerJaar">Totaal aantal inschrijvingen</a></li>
		<li><a target="_blank" href="<?php echo($statistiekPrefix); ?>InschrijvingenPerPlaatsInSpecifiekJaar&jaar=2016">Inschrijvingen per plaats (2016)</a></li>
		<li><a target="_blank" href="<?php echo($statistiekPrefix); ?>OpbouwLeeftijdPerJaar">Opbouw leeftijd per jaar</a></li>
		<li><a target="_blank" href="<?php echo($statistiekPrefix); ?>AantalKampenVoorLeeftijdInJaar&jaar=2016">Aantal kampen voor leeftijd (2016)</a></li>
	</ul>
</div>
