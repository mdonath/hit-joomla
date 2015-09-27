<?php
//////////////////////////////////////////////////////////////
// JOOMLA INTEGRATION STUFF START
//////////////////////////////////////////////////////////////
define( 'DS', DIRECTORY_SEPARATOR );

$rootFolder = explode(DS,dirname(__FILE__));

//current level in diretoty structure
$currentfolderlevel = 1;

array_splice($rootFolder,-$currentfolderlevel);

$base_folder = implode(DS,$rootFolder);

if(is_dir($base_folder.DS.'libraries'.DS.'joomla'))
{

	define( '_JEXEC', 1 );

	define('JPATH_BASE',implode(DS,$rootFolder));

	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	$userid='';
	$usertype='';
	$mainframe = JFactory::getApplication('site');
	$mainframe->initialise();
	$user = JFactory::getUser();
	$userid = $user->get('id');
	$usertype = $user->get('usertype');
}

if (!$userid) {
	// In case the user is not loged in redirect him to the index page
	$redirectURL = 'http://www.asite.com.au/';
	$mainframe->redirect($redirectURL);
	exit;
} else {

	//////////////////////////////////////////////////////////////
	// JOOMLA INTEGRATION STUFF END
	//////////////////////////////////////////////////////////////

	require_once('../libraries/fpdf/fpdf.php');

	class PDF extends FPDF
	{

		function Header()
		{
			//Put the watermark
			$this->SetFont('Arial','B',50);
			$this->SetTextColor(255,192,203);
			$this->Text(35,190,'W a t e r m a r k');
		}
	}

	//Var
	$pdf=new PDF();
	$emel = "xxx@xxx.xx";

	//Lets set the source
	$pagecount=$pdf->setSourceFile("test.pdf");
	//For each page lets do something
	for($nbp=1;$nbp<=$pagecount;$nbp++)
	{

		$pdf->AliasNbPages();
		$pdf->AddPage();
		$tplidx = $pdf->ImportPage($nbp);
		//$pdf->useTemplate($tplidx,5,5,200,250);
		$pdf->useTemplate($tplidx,5,5,0,0);
		$pdf->SetFont('Arial','',12);
		for($i=0;$i<25;$i++) {
			$pdf->Cell(0,5,$txt,0,'J');
		}
	}

	$pdf->Output();
}

?>