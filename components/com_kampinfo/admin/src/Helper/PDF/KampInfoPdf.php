<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF;

\defined('_JEXEC') or die('Restricted Access');

use DateTime;
use Joomla\CMS\Component\ComponentHelper;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF\PdfHtml;
use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;


class KampInfoPdf extends PdfHtml {

    protected $iconFolder;
    protected $iconExtension;

    public function __construct() {
        parent::__construct();

        $params = ComponentHelper::getParams('com_kampinfo');
        $this->iconFolder = $params->get('iconFolderLarge');
        $this->iconExtension = $params->get('iconExtension');
    }

    protected function datumTijd($datumtijd) {
        $dt = new DateTime($datumtijd);
        $dt->setTimezone(KampInfoHelper::getTimeZone());
        return $dt->format('d-m-Y H:i');
    }

    protected function improvedTable($header, $data) {
        // Header
        $widths = [];
        $this->SetFont('Arial', 'B', 8);
        $this->whiteTextOnGreenBackground();
        foreach ($header as $name=>$width) {
            $widths[] = $width;
            // width, height, txt, border, ln, align, fill, link
            $this->Cell($width, 7, $name, 1, 0, 'C', 1);
        }
        $this->Ln();
        $this->blackTextOnWhiteBackground();

        $this->SetFont('', '', 8);
        // Data
        foreach ($data as $row) {
            $cellCount = 0;
            foreach ($row as $cell) {
                // width, height, txt, border, ln, align, fill, link
                $this->Cell($widths[$cellCount], 6, $cell, 'LR');
                $cellCount++;
            }
            $this->Ln();
        }
        $this->Cell(0, 0, '', 'T');
    }

    protected function subheader($text) {
        $this->Ln(10);
        $this->blackTextOnYellowBackground();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 7, $text, 1, 1, 'C', 1);
        $this->SetFont('', '', 8);
        $this->blackTextOnWhiteBackground();
    }

    public function Footer() {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 'T', 0, 'C');
    }

    private function blackTextOnWhiteBackground() {
        $this->SetTextColor(0);
        $this->SetFillColor(255);
    }

    private function whiteTextOnGreenBackground() {
        $this->SetTextColor(255);
        $this->SetFillColor(0, 128, 0);
    }

    private function blackTextOnYellowBackground() {
        $this->SetTextColor(0);
        $this->SetFillColor(252, 221, 23);
    }

    /**
     * Converteert een string van utf-8 naar windows encoding.
     * 
     * @param string $str
     */
    protected function c(string $str) {
        return iconv('UTF-8', 'windows-1252', $str);
    }

}
