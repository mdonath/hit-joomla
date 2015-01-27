<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('include_path',ini_get('include_path').':/home/martijn/git/hit-joomla/com_kampinfo/admin/helpers/:');

require_once 'mapper.php';
require_once 'SolMapping.php';

$jaar=2013;

$dlnMapping = SolMapping::getDeelnemergegevensMapping($jaar);

if (false) {
	$mapper = new CsvMapper($dlnMapping);
	$result = $mapper->read("3331_deelnemergegevens.csv");
} else {
	$mapper = new XmlMapper($dlnMapping);
	$result = $mapper->read("5022_participants_export.xml");
}
print_r($result);
