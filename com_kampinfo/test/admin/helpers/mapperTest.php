<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('include_path',ini_get('include_path').':/home/martijn/git/hit-joomla/com_kampinfo/admin/helpers/:');

require_once 'mapper.php';
require_once 'SolMapping.php';

$jaar = 2019;

$insMapping = SolMapping::getInschrijvingenMapping($jaar, "json");

if (true) {
	$dlnMapping = SolMapping::getDeelnemergegevensMapping($jaar);
	$mapper = new CsvMapper($dlnMapping);
	$result = $mapper->read("2018-xxxx_deelnemergegevens.csv");
} else {
	if (false) {
		$mapper = new JsonMapper($insMapping);
		$string = file_get_contents("./event-download.json");
		$result = $mapper->readString($string);
	} else {
		$dlnMapping = SolMapping::getDeelnemergegevensMapping($jaar, "json");
		$mapper = new JsonMapper($dlnMapping);
		$string = file_get_contents("./participants-output.json");
		$result = $mapper->readString($string);
	}
}
var_dump($result);

