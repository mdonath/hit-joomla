<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('include_path',ini_get('include_path').':/home/martijn/git/hit-joomla/com_kampinfo/admin/helpers/:');

require_once 'mapper.php';
require_once 'SolMapping.php';

$jaar=2013;

$dlnMapping = SolMapping::getInschrijvingenMapping($jaar);


$mapper = new XmlMapper($dlnMapping);
$result = $mapper->read("6406_formulieren.xml");

// print_r($result);

print_r($result[9]);

$inschrijving = $result[9];

$isOuderKind = empty($inschrijving->subgroeptypenr);
if ($isOuderKind) {
	print('Ouder-Kind!');
} else {
	print('Geen Ouder-Kind');
}