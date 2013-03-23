<?php
ini_set('include_path',ini_get('include_path').':/home/martijn/git/hit-joomla/com_kampinfo/admin/helpers/:');

require_once "SolDownload.php";
require_once "mapper.php";


$privateKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAzw0f9iRs0SDrcY5X9K/H8XLUxlHZbCzjzZPmOJP5vwPVtt+b
TDN21Wz1ITVK7YCZ0EkuU/iRPU8Lsh8FREiGML3ylfGIvkzzIw9kdv505iESHGn5
uu3IKlHnv79Iy7jSHKt6lTSlxpMfPfsdk0GPWZyGNpL8NuUU+KGt9nI385+bjtwN
UCiiuzieNivj6i1mO70yiPyV/sJu7n9G7d3GvGGC5buyctg1LsCzzCc8Hi2ktBfg
cKLlEx1OxIg6LSvNGm9du4Pdzht/9rf1W0GCxzPvQLiOY0BNwiDtR8fXaFK4z66/
pVcKlP9wd/ulmfYI9C6H4lO5cTeKZWp0Z94vtQIDAQABAoIBAAP9fcMxmoNBPihX
FOzbQmAt1VnpE+aYyt8YXZRzjZmXylOW5ZhHWZ/tVKAKNeJz2wL/lgv6O1pSHqiV
HnXda/CeiIFJQVhhhUIEtuvwJXKV6pJvnGd006m1IGE6n32Fl08EIv2jIRq9lRmN
sFk5JzkT4iZFGOU57viHrSGVu+xO+0DFQfMHlLUWnX9QLGLcPLgU+nZQ8gpI/qer
782qMxKEtXz88ydb919ErJfofapAQYKrD34NqyVFVX/pjyat7tSaA1f45wQ8t/st
z84t/kPTqnGhpoo3kzD0gAAGtGKqgnOCfzq8ta/HXOuiZmfcQaXxgdbg29lynRRp
Mjuxv/UCgYEA7SqCL1MYKjidrhGBMKtFqMVjcicKbR8NITN8AuW0ubEVpkDkOR/f
qL2T/SWoayNHPESKOjcKdyXbl7W/oy5ga8vaXYmjSLa8pISc1ARHYTAsaBUTZGbE
CoSBWk/NXJ7UbqkhLIfXX1k9EDiUVtNaG1IEOaKYsT7CKbInBKS4L6MCgYEA335l
Em1zcBBsmwbwFO+6oAHdMDV5SHJzPPmGT6qEHOlJ1WiNqlAb3xfHMV0akf7EZ3AL
sPHPUY/r0fdCZRWufpbFqG6ij5cewyS4OD7lxZqxIFhrxHbjWaHQ1lvDQ5Rox9DT
JqCnG6DkfTuwrJHGY/cPzArjoCdrVsNsP/L8uMcCgYEAj/PkTxtNqJrywmwnkUX0
IyukX+5oerGFQ6i/VfbdSnS9Ikty+2VoWUCwqdAIuPcRzvf/OrSb6pQVBLGxnzeQ
GP4EDhB0Bre8KtO/aUUrmkcmPQrF2YQy6/tflFSp3tUdNgn8c84EIQDGeqkNCOKC
Z+DRZbZWngbszWgwHLZqgacCgYBWZM/BXdn/+flhvD7WeQ1ViSLt3d3yaXuumG6Z
ITtMycmm23HX4nvDs7dml60LvsZBjgiW8ALKbq4kTka2OLlpafMidxIUG8DigrFL
3NSJnJDYBhYyrhqYrXrDQlmrKBWRGGJnTjcK8RkiaA6Vxww/3GOY5qSzZkmSEp0n
41BaRQKBgQDL5Rgv21ITfznJr2DrQ77w6/j2Lq46ul3uuEn04OMTosXsPv+7DA6I
awTUPd1Z+q4SZovJVhBYPhrMpubTeWxsGf6MRg0ftlSrtR/MUS+eovOf0obzV9W3
ozlv1TR7kCfBjOUurPLEJYG4Rov401UFXZCU7jAHuCdCe+HkMnxSyg==
-----END RSA PRIVATE KEY-----';

// Dit moet allemaal te configureren zijn:

$user = 'hithelpdesk'; $password = 'hit2013'; 
//$user = 'martijn_donath'; $password = 'bl3bb3rb3i';

$role = '63,%SNID%,35,9121,9,1';


// ROLE := 
//   63:		"cfun_id	: Functienummer (nm.val=evenementbeheerder)"
// , %SNID%:	"per_id		: Lidnummer"
// , 35:		"corg_id	: Organisatie categorienr (nm.val=landelijk project)"
// , 9121:		"org_id		: Organisatienummer (nm.val=Hit Landelijk organisatieteam)", 

// sec_prime_id = Speleenheidnummer (120481)


$sui = 'hit';
$keypriv = $privateKey;
$wsdl = 'http://sol.scouting.nl/frontend/sol/sol.wsdl';
$evt_id = 3331;
$parts = NULL;

$sol = new SolDownload();

$button = 'participants_export';
$clientName = 'hit';

//$download = $sol->downloadHelp($user, $password, $role, $sui, $keypriv, $wsdl, $clientName);

//$download = $sol->downloadEvent($user, $password, $role, $sui, $keypriv, $wsdl, $evt_id, $button);


// OK!
//$download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_event', 'forms', 'export', array('evt_id'=>3331));

// Niet: tab_onbekend
$download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_event', 'participants', 'export', array('evt_id'=>3331));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_form', 'report', 'part_data', array('frm_id'=>5687, 'prt_st_id'=>array(1000)));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_form', 'participants', 'export', array('frm_id'=>5687));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_form', 'totals', 'list', array('frm_id'=>5687));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_form', 'team', 'list', array('frm_id'=>5687));

// NIET: foutmelding: Class 'as_partprint' not found
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'as_part', 'list_status', 'list', NULL);

// NIET: foutmelding: Not Found (maar kan ook aan hag_id liggen)
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'hrm_employee', 'list', 'export', array('hag_id'=>2567));



// NIET: geen rechten meer
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'listing_listing', 'list_start', 'b', NULL);


// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_function', 'list', 'roles', array('per_id'=>601975066));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_function', 'view', 'currentRole', NULL);



// NIET; foutmelding: Call to undefined function redirectToREST()
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_organisation', 'view', 'btn_detail', array('org_id'=>9121));

// WEL, maar geen kopjes
//$download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_organisation', 'view', 'btn_events', array('org_id'=>9121));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_organisation', 'view', 'btn_training', array('org_id'=>9121));


// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_person', 'view', 'btn_detail', array('per_id'=>601975066));

// NIET OK; krijg dezelfde gegevens als ma_person/view/btn_detail/per_id=....
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_person', 'view', 'btn_functions', array('per_id'=>601975066));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_person', 'view', 'btn_events', array('per_id'=>601975066));




// Geen rechten
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_section', 'view', 'btn_detail', array('org_id'=>9121, 'sec_id'=>1, 'age_id'=>9));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_section', 'view', 'btn_events', array('sec_prime_id'=>120481));

// OK!
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'ma_section', 'view', 'btn_training', array('sec_prime_id'=>120481));


// Niet: Tab onbekend
// $download = $sol->download($user, $password, $role, $sui, $keypriv, $wsdl, 'soap_client', 'list', 'tab', array('scl_nm'=>'hit'));


echo "Downloaded:\n";
print_r($download);

$fp = fopen($evt_id.'_'.$button.'.xml', 'w');
fwrite($fp, $download);
fclose($fp);
// $mapping = array(
// 				'Dln.nr.' => new GewoonVeld('dlnnr')
// 	 		, 'Lid plaats' => new GewoonVeld('plaats')
// 	 		, 'Land' => new GewoonVeld('land')
// 	 		, 'Lid geboortedatum' => new LeeftijdVeld('leeftijd', 2013)
// 	 		, 'Lid geslacht' => new GeslachtVeld('geslacht')
// 	 		, 'Datum inschrijving' => new DatumVeld('datumInschrijving')
// 	 		, 'Formulier' => new GewoonVeld('formulier')
// 		);

// $mapper = new XmlMapper($mapping);
// $result = $mapper->readString($download);

// print_r($result);

