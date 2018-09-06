<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.helper');
// load tooltip behavior
JHtml::_('behavior.tooltip');

// Haal manifest op
$table = JTable::getInstance('extension');
$id    = $table->find(array('type' => 'component', 'element' => 'com_kampinfo'));
if (!empty($id)) {
	$table->load($id);
	$registry = new JRegistry();
	$registry->loadString($table->manifest_cache);
	$manifest_cache = $registry->toArray();
}
?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<p>Dit is KampInfo, een Joomla component speciaal voor de HIT.</p>
<table class="table table-striped">
	<tr><td><strong>Versie:</strong></td><td><?php echo($manifest_cache['version']);?></td></tr>
	<tr><td><strong>Naam:</strong></td><td><?php echo(JText::_($manifest_cache['name']));?></td></tr>
	<tr><td><strong>Omschrijving:</strong></td><td><?php echo(JText::_($manifest_cache['description']));?></td></tr>
	<tr><td><strong>Copyright:</strong></td><td><?php echo($manifest_cache['copyright']);?></td></tr>
	<tr><td><strong>Auteur:</strong></td><td><?php echo($manifest_cache['author']);?> (<?php echo($manifest_cache['authorEmail']);?>)</td></tr>
	<tr><td><strong>Start ontwikkeling:</strong></td><td><?php echo($manifest_cache['creationDate']);?></td></tr>
</table>

<hr>

<h2>Laatste wijzigingen:</h2>
<table class="table table-striped">
	<tr><th>Datum</th><th>Wijzigingen</th></tr>
	<tr><td>06-09-2018</td><td>Link naar SOL is aangepast (<a href="https://github.com/mdonath/hit-joomla/issues/26">#26</a>), bij kopi&euml;ren kamp van vorig jaar wordt het originele max aantal overgenomen bij niew maximum aantal voor dat jaar (https://github.com/mdonath/hit-joomla/issues/25)</td></tr>
	<tr><td>30-11-2017</td><td>Bug opgelost m.b.t. opslaan HIT Plaats.</td></tr>
	<tr><td>27-11-2017</td><td>Tabbladen Financiën verwijderd op verzoek van Dominicus.</td></tr>
	<tr><td>20-11-2017</td><td>Nadat een plaats akkoord heeft gegeven, kan een kamp de gegevens niet meer wijzigen.</td></tr>
</table>

</div>

