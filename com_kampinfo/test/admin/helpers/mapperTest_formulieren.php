<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('include_path',ini_get('include_path').':/home/martijn/git/hit-joomla/com_kampinfo/admin/helpers/:');

require_once 'mapper.php';
require_once 'SolMapping.php';

$jaar=2013;

$dlnMapping = SolMapping::getInschrijvingenMapping($jaar);


$mapper = new XmlMapper($dlnMapping);
$result = $mapper->read("6406_forms.xml");

// print_r($result);

$i=0;
foreach ($result as $e) {
	print($i);
	print_r($result[$i]);
	$i++;
}

print_r($result[27]);
$inschrijving = $result[27];

$isOuderKind = $inschrijving->subgroepcategorie != 'Koppelgroepje';
if ($isOuderKind) {
	print('Ouder-Kind!');
} else {
	print('Geen Ouder-Kind');
}