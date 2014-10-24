<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
include_once dirname(__FILE__).'/../../libraries/fpdf/fpdf.php';

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_HTML extends FPDF
{
	//variables of html parser
	var $skipFirstP;
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $fontList;
	var $issetfont;
	var $issetcolor;

	function PDF_HTML($orientation='P', $unit='mm', $format='A4')
	{
		//Call parent constructor
		$this->FPDF($orientation,$unit,$format);
		//Initialization
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
		$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
		$this->issetfont=false;
		$this->issetcolor=false;
	}

	function WriteHTML($html)
	{
		$this->skipFirstP=true;
		//HTML parser
		$html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
		$html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,stripslashes(txtentities($e)));
			}
			else
			{
				//Tag
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract attributes
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		//Opening tag
		switch($tag){
			case 'STRONG':
				$this->SetStyle('B',true);
				break;
			case 'EM':
				$this->SetStyle('I',true);
				break;
			case 'B':
			case 'I':
			case 'U':
				$this->SetStyle($tag,true);
				break;
			case 'A':
				$this->HREF=$attr['HREF'];
				break;
			case 'IMG':
				if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
					if(!isset($attr['WIDTH']))
						$attr['WIDTH'] = 0;
					if(!isset($attr['HEIGHT']))
						$attr['HEIGHT'] = 0;
					$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
				}
				break;
			case 'TR':
			case 'BLOCKQUOTE':
			case 'BR':
				$this->Ln(5);
				break;
			case 'P':
				if ($this->skipFirstP) {
					$this->skipFirstP=false;
				} else {
					$this->Ln(10);
				}
				break;
			case 'FONT':
				if (isset($attr['COLOR']) && $attr['COLOR']!='') {
					$coul=hex2dec($attr['COLOR']);
					$this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
					$this->issetcolor=true;
				}
				if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
					$this->SetFont(strtolower($attr['FACE']));
					$this->issetfont=true;
				}
				break;
		}
	}

	function CloseTag($tag)
	{
		//Closing tag
		if($tag=='STRONG')
			$tag='B';
		if($tag=='EM')
			$tag='I';
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
		if($tag=='FONT'){
			if ($this->issetcolor==true) {
				$this->SetTextColor(0);
			}
			if ($this->issetfont) {
				$this->SetFont('arial');
				$this->issetfont=false;
			}
		}
	}

	function SetStyle($tag, $enable)
	{
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
		{
			if($this->$s>0)
				$style.=$s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

}//end of class

class PDF extends PDF_HTML {
	private $jaar;
	private $plaats;
	private $kampen;
	private $kampNaam;

	private $iconFolder;
	private $iconExtension;

	public function __construct($plaats, $kampen) {
		parent::FPDF();
		$this->plaats = $plaats;
		$this->kampen = $kampen;
		$this->jaar = $kampen[0]->jaar;

		$params = &JComponentHelper::getParams('com_kampinfo');
		$this->iconFolder = $params->get('iconFolderLarge');
		$this->iconExtension = $params->get('iconExtension');
	}

	public function getPdfOutputName() {
		return 'HIT '.$this->plaats->naam.'-'.$this->jaar.'.pdf';
	}

	/**
	 * Page header.
	 */
	function Header()
	{
		$this->SetFont('Arial', 'B', 15);
		if ($this->kampNaam == '') {
			$this->Cell(0, 10, 'Overzicht gegevens HIT '. $this->plaats->naam .' '.$this->jaar, 'B', 0, 'C');
		} else {
			$this->Cell(0, 10, $this->c('HIT '. $this->plaats->naam .' '. $this->jaar ." - ". $this->kampNaam), 'B', 0, 'C');
		}
		$this->Ln(20);
	}

	/**
	 * Drukt alles van de hele plaats af.
	 */
	function printPlaats() {
		$this->AddPage();
		$data = array();
		$data[] = array('Naam:', $this->plaats->naam);
		$data[] = array('Projectcode:', $this->plaats->projectcode);
		$data[] = array('Contactpersoon:', $this->plaats->contactPersoonNaam .' / '. $this->plaats->contactPersoonEmail .' / '. $this->plaats->contactPersoonTelefoon);
		$data[] = array('Akkoord:', $this->plaats->akkoordHitPlaats == '1' ? 'Ja':'Nee');

		$this->ImprovedTable(array('Veld'=>40, 'Waarde'=>0), $data);

		$this->subheader('HIT Courant');
		$this->MultiCell(0, 5, $this->c($this->plaats->hitCourantTekst), 1, 'L');

		$this->printKampNamen();

		foreach($this->kampen as $kamp) {
			$this->printKamp($kamp);
		}
	}

	function printKampNamen() {
		$this->subheader('Kamponderdelen');

		foreach ($this->kampen as $kamp) {
			$link = $this->AddLink();
			$kamp->pdfLink = $link;
			$this->Cell(0, 6, $this->c($kamp->naam . " »»"), 'LR', 1, 'L', false, $link);
		}
		$this->Cell(0, 0, '', 'T');
	}

	function printKamp($kamp) {
		$this->kampNaam = $kamp->naam;
		$this->AddPage();
		$this->SetLink($kamp->pdfLink);

		/*
		 * ["titeltekst"]=> string(29) "Stookkamp, too HIT to handle!"
		*/

		$data = array();
		$data[] = array('Naam:', $this->c($kamp->naam));
		$data[] = array('Startdatum en -tijd:', $kamp->startDatumTijd);
		$data[] = array('Einddatum en -tijd:', $kamp->eindDatumTijd);
		$data[] = array('Prijs:', $this->c('€ '.$kamp->deelnamekosten));
		$data[] = array('Is ouderkind-kamp:', $kamp->isouderkind == "1" ? "Ja":"Nee");
		$ouder = '';
		if ($kamp->margeAantalDagenTeOud > "0") {
			$jaartjeOuder = $kamp->maximumLeeftijd + 1;
			$ouder = "(mag wel $kamp->margeAantalDagenTeOud dagen al $jaartjeOuder jaar zijn)";
		}
		$data[] = array('Leeftijd', "$kamp->minimumLeeftijd (-$kamp->margeAantalDagenTeJong dagen) tot en met $kamp->maximumLeeftijd jaar $ouder (marge van 90 dagen is standaard)");
		if ($kamp->margeAantalDagenTeJong != "90" || $kamp->margeAantalDagenTeOud != "90") {
			$data[] = array('Reden afwijking lft.:', $this->c($kamp->redenAfwijkingMarge));
		}

		if ($kamp->helpdeskOverschrijdingLeeftijd == "0") {
			$data[] = array("Te jong of te oud:", "NIET TOEGESTAAN; leeftijdsmarges zijn strikt");
		} else {
			$data[] = array("Te jong of te oud:", "Toegestaan");
			if ($kamp->helpdeskTeJongMagAantal != "" && $kamp->helpdeskTeJongMagAantal != "0") {
				$data[] = array("Aantal te jong:", "Helpdesk mag maximaal $kamp->helpdeskTeJongMagAantal deelnemers die te jong zijn inschrijven");
			}
			if ($kamp->helpdeskTeOudMagAantal != "" && $kamp->helpdeskTeOudMagAantal != "0") {
				$data[] = array("Aantal te oud:", "Helpdesk mag maximaal $kamp->helpdeskTeOudMagAantal deelnemers die te oud zijn inschrijven");
			}
		}

		$uitloop = '';
		if ($kamp->helpdeskOverschrijdingAantal != "") {
			$uitloop = "(max. $kamp->helpdeskOverschrijdingAantal extra mogelijk)";
		}
		$data[] = array('Aantal deelnemers:', "$kamp->minimumAantalDeelnemers - $kamp->maximumAantalDeelnemers $uitloop");
		$data[] = array('Contactgegevens voor helpdesk:', "$kamp->helpdeskContactpersoon / $kamp->helpdeskContactEmailadres / $kamp->helpdeskContactTelefoonnummer");
		$deelbaar = '';
		if ($kamp->subgroepsamenstellingExtra != '' && $kamp->subgroepsamenstellingExtra != "0") {
			$deelbaar = "(grootte moet deelbaar zijn door $kamp->subgroepsamenstellingExtra)";
		}
		$data[] = array('Subgroep grootte:', "$kamp->subgroepsamenstellingMinimum - $kamp->subgroepsamenstellingMaximum $deelbaar");
		if ($kamp->minimumAantalSubgroepjes == "0" && $kamp->maximumAantalSubgroepjes == "0") {
			$data[] = array('Aantal subgroepen:', "Maakt niet uit");
		} else {
			if ($kamp->minimumAantalSubgroepjes != "0") {
				$data[] = array('Min. aantal subgroepen:', $kamp->minimumAantalSubgroepjes);
			}
			if ($kamp->maximumAantalSubgroepjes != "0") {
				$data[] = array('Min. aantal subgroepen:', $kamp->maximumAantalSubgroepjes);
			}
		}
		$data[] = array('Max. dln uit 1 groep:', ($kamp->maximumAantalUitEenGroep == "0") ? "Maakt niet uit" : $kamp->maximumAantalUitEenGroep);
		$this->ImprovedTable(array('Gegeven'=> 45, 'Inhoud'=>0), $data);

		// Doelstelling
		$this->subheader('Doelstelling');
		$this->WriteHTML($this->c($kamp->doelstelling));
		
		// HitCourant
		$this->subheader('HIT Courant');
		$this->MultiCell(0, 5, $this->c($kamp->hitCourantTekst), 1, 'L');

		// icoontjes
		$this->subheader('Icoontjes');
		$this->Ln(1);
		$icoontjes =  $kamp->icoontjes;
		foreach ($icoontjes as $icon) {
			$img = JPATH_ROOT .'/'. $this->iconFolder .'/'. $icon->naam .$this->iconExtension;
			$this->Image($img, $this->GetX(), $this->GetY());
			$this->x += 12;
			$this->Cell(100, 12, $icon->tekst);
			$this->Ln();
		}
	}

	function ImprovedTable($header, $data)
	{
		// Header
		$w = array();
		$this->SetFont('Arial', 'B', 8);
		$this->grayOnBlack();
		foreach($header as $name=>$width) {
			$widths[] = $width;
			// width, height, txt, border, ln, align, fill, link
			$this->Cell($width, 7, $name, 1, 0, 'C', 1);
		}
		$this->Ln();
		$this->blackOnWhite();

		$this->SetFont('', '', 8);
		// Data
		foreach($data as $row)
		{
			$cellCount = 0;
			foreach($row as $cell) {
				// width, height, txt, border, ln, align, fill, link
				$this->Cell($widths[$cellCount], 6, $cell, 'LR');
				$cellCount++;
			}
			$this->Ln();
		}
		$this->Cell(0, 0, '', 'T');
	}

	function Footer()
	{
		// Go to 1.5 cm from bottom
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Pagina '.$this->PageNo(),'T',0,'C');
	}

	function blackOnWhite() {
		$this->SetFillColor(255);
		$this->SetTextColor(0);
	}

	function whiteOnBlack() {
		$this->SetFillColor(0);
		$this->SetTextColor(255);
	}

	function grayOnBlack() {
		$this->SetFillColor(128);
		$this->SetTextColor(255);
	}

	function subheader($text) {
		$this->Ln(10);
		$this->whiteOnBlack();
		$this->SetFont('Arial', 'B', 8);
		$this->Cell(0, 7, $text, 1, 1, 'C', 1);
		$this->SetFont('', '', 8);
		$this->blackOnWhite();
	}

	/**
	 * Converteer utf-8 naar windows encoding.
	 * @param unknown $str
	 */
	function c($str) {
		return iconv('UTF-8', 'windows-1252', $str);
	}

}

/**
 * HitSite PDF View.
 */
class KampInfoViewHitSite extends JView {

	public function display($tpl = null) {
		$plaats = $this->get('Item');
		$kampen = $this->get('Kampen');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		$pdf = new PDF($plaats, $kampen);
		$pdf->printPlaats();

		// http://localhost/HIT/administrator/index.php?option=com_kampinfo&view=hitsite&format=pdf&id=37
		/*
		* ["naam"]=> string(9) "Stookkamp"
		* ["isouderkind"]=> string(1) "1"
		* ["activiteitengebieden"]=> string(48) "buitenleven,sportenspel,uitdagend,veiligengezond"
		* ["titeltekst"]=> string(29) "Stookkamp, too HIT to handle!"
		* ["startDatumTijd"]=> string(19) "2014-04-18 18:00:00"
		* ["eindDatumTijd"]=> string(19) "2014-04-21 21:00:00"
		* ["deelnamekosten"]=> string(2) "35"
		* ["minimumLeeftijd"]=> string(2) "10"
		* ["maximumLeeftijd"]=> string(2) "14"
		* ["subgroepsamenstellingMinimum"]=> string(1) "1"
		* ["subgroepsamenstellingMaximum"]=> string(1) "3"
		* ["subgroepsamenstellingExtra"]=> string(1) "0"
		* ["websiteAdres"]=> string(23) "http://www.stookkamp.nl"
		* ["webadresFoto1"]=> string(41) "http://www.stookkamp.nl/img/88303-106.jpg"
		* ["webadresFoto2"]=> string(41) "http://www.stookkamp.nl/img/88303-107.jpg"
		* ["webadresFoto3"]=> string(41) "http://www.stookkamp.nl/img/88303-108.jpg"
		* ["websiteContactTelefoonnummer"]=> string(11) "06-51200626"
		* ["websiteContactEmailadres"]=> string(17) "info@stookkamp.nl"
		* ["websiteContactpersoon"]=> string(8) "Vincent2"
		* ["minimumAantalDeelnemers"]=> string(2) "30"
		* ["aantalDeelnemers"]=> string(2) "40"
		* ["gereserveerd"]=> string(2) "40"
		* ["maximumAantalDeelnemers"]=> string(2) "41"
		* ["maximumAantalSubgroepjes"]=> string(1) "0"
		* ["maximumAantalUitEenGroep"]=> string(2) "10"
		* ["margeAantalDagenTeJong"]=> string(2) "90"
		* ["margeAantalDagenTeOud"]=> string(2) "90"
		* ["redenAfwijkingMarge"]=> string(0) ""
		* ["aantalSubgroepen"]=> string(2) "22"
		* ["minimumAantalSubgroepjes"]=> string(1) "0"
		* ["hitCourantTekst"]=> string(66) "MOOOI! Uitroken die hap!2222 Geweldig, tags werken dus niet! dik"
		* ["helpdeskOverschrijdingAantal"]=> string(1) "0"
		* ["helpdeskOverschrijdingLeeftijd"]=> string(1) "0"
		* ["helpdeskTeJongMagAantal"]=> string(1) "0"
		* ["helpdeskTeOudMagAantal"]=> string(1) "0"
		* ["helpdeskContactpersoon"]=> string(7) "martijn"
		* ["helpdeskContactEmailadres"]=> string(23) "martijn@donath-thuis.nl"
		* ["helpdeskContactTelefoonnummer"]=> string(7) "martijn"
		*
		* ["icoontjes"]=> string(46) "staand,0pers,groepje,tent,opvuur,geenmobieltje"
		* ["websiteTekst"]=> string(1862) "
		*
		* ["id"]=> string(3) "505"
		* ["asset_id"]=> string(3) "173"
		* ["hitsite_id"]=> string(2) "37"
		* ["deelnemersnummer"]=> string(1) "0"
		* ["hitsite"]=> string(0) ""
		* ["doelgroepen"]=> string(0) ""
		* ["shantiFormuliernummer"]=> string(5) "11153"
		* ["akkoordHitKamp"]=> string(1) "1"
		* ["akkoordHitPlaats"]=> string(1) "1"
		* ["doelstelling"]=> string(23) "Alles in de fik!"
		* ["helpdeskOpmerkingen"]=> string(0) ""
		* ["published"]=> string(1) "1"
		* ["publish_up"]=> string(19) "0000-00-00 00:00:00"
		* ["publish_down"]=> string(19) "0000-00-00 00:00:00" }
		*/

		// export PDF
		$pdf->Output($pdf->getPdfOutputName(), 'D');
	}
}
?>