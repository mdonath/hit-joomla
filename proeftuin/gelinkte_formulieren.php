<?php 

$formulier = array();
$deelnemer = new StdClass();
$deelnemer->formulier = "HIT Mook Eifel 50° Noord - Hunger Games (Winnaars fotowedstr) {12797}";

preg_match("/HIT .* \{(\d+)\}/", $deelnemer->formulier, $formulier);

if (count($formulier) == 0) {
	echo 'voldoet niet?!';
} else {
	echo '0: '. $formulier[0] . "\n";
	echo '1: '. $formulier[1] . "\n";
// 	echo $formulier[2] . "\n";
// 	echo $formulier[3] . "\n";
// 	echo $formulier[4] . "\n";
}