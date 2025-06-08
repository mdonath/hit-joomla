<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF;

\defined('_JEXEC') or die('Restricted Access');

use DateTimeZone;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\PDF\KampInfoPdf;

class PlaatsPdf extends KampInfoPdf {

    private $jaar;
    private $plaats;
    private $kampen;
    private $kampNaam;

    public function __construct($plaats = null, $kampen = []) {
        parent::__construct();
        $this->plaats = $plaats;
        $this->kampen = $kampen;
        $this->jaar = $kampen[0]->jaar;
    }

    public function getPdfOutputName() {
        return "HIT {$this->plaats->naam}-{$this->jaar}.pdf";
    }

    public function Header() {
        $this->SetFont('Arial', 'B', 15);
        if ($this->kampNaam == '') {
            $this->Cell(0, 10, "Overzicht gegevens HIT {$this->plaats->naam} {$this->jaar}", 'B', 0, 'C');
        } else {
            $this->Cell(0, 10, $this->c("HIT {$this->plaats->naam} {$this->jaar} - {$this->kampNaam}"), 'B', 0, 'C');
        }
        $this->Ln(20);
    }

    /**
     * Drukt alles van de hele plaats af.
     */
    public function printPlaats() {
        $this->AddPage();
        $data = [];
        $data[] = ['Naam:', $this->c($this->plaats->naam)];
        $data[] = ['Projectcode:', $this->plaats->projectcode];
        $data[] = ['Contactpersoon:', "{$this->c($this->plaats->contactPersoonNaam)} / {$this->plaats->contactPersoonEmail} / {$this->plaats->contactPersoonTelefoon}"];
        $data[] = ['Akkoord:', ($this->plaats->akkoordHitPlaats == '1') ? 'Ja' : 'Nee'];

        $this->improvedTable(['Plaats' => 40, 'Gegevens' => 0], $data);

        $this->subheader('HIT Courant');
        $this->MultiCell(0, 5, $this->c($this->plaats->hitCourantTekst), 1, 'L');

        $this->printKampNamen();

        foreach ($this->kampen as $kamp) {
            $this->printKamp($kamp);
        }
    }

    private function printKampNamen() {
        $this->subheader('Kamponderdelen');

        foreach ($this->kampen as $kamp) {
            $link = $this->AddLink();
            $kamp->pdfLink = $link;
            $this->Cell(0, 6, $this->c($this->kampnaam($kamp) . " »»"), 'LR', 1, 'L', false, $link);
        }
        $this->Cell(0, 0, '', 'T');
    }

    private function kampnaam($kamp) {
        if ($kamp->geannuleerd == 1) {
            return "{$kamp->naam} (GAAT NIET DOOR)";
        }
        return $kamp->naam;
    }

    private function printKamp($kamp) {
        $this->kampNaam = $this->kampnaam($kamp);
        $this->AddPage();
        $this->SetLink($kamp->pdfLink);

        $data = [];
        $data[] = ['Naam:', $this->c($this->kampnaam($kamp))];
        $data[] = ['Startdatum en -tijd:', $this->datumTijd($kamp->startDatumTijd)];
        $data[] = ['Einddatum en -tijd:', $this->datumTijd($kamp->eindDatumTijd)];
        $data[] = ['Prijs:', $this->c("€ {$kamp->deelnamekosten}")];
        $data[] = ['Is ouderkind-kamp:', $kamp->isouderkind == '1' ? "Ja" : "Nee"];
        $ouder = '';
        if ($kamp->margeAantalDagenTeOud > '0') {
            $jaartjeOuder = $kamp->maximumLeeftijd + 1;
            $ouder = "(mag wel {$kamp->margeAantalDagenTeOud} dagen al {$jaartjeOuder} jaar zijn)";
        }
        $data[] = ['Leeftijd', "{$kamp->minimumLeeftijd} (-{$kamp->margeAantalDagenTeJong} dagen) tot en met {$kamp->maximumLeeftijd} jaar {$ouder} (marge van 90 dagen is standaard)"];
        if ($kamp->margeAantalDagenTeJong != "90" || $kamp->margeAantalDagenTeOud != "90") {
            $data[] = ['Reden afwijking lft.:', ($kamp->redenAfwijkingMarge != '') ? $this->c($kamp->redenAfwijkingMarge) : 'NIET OPGEGEVEN'];
        }

        if ($kamp->helpdeskOverschrijdingLeeftijd == '0') {
            $data[] = ['Te jong of te oud:', 'NIET TOEGESTAAN; leeftijdsmarges zijn strikt'];
        } else {
            $data[] = ['Te jong of te oud:', 'Toegestaan'];
            if ($kamp->helpdeskTeJongMagAantal != '' && $kamp->helpdeskTeJongMagAantal != '0') {
                $data[] = ['Aantal te jong:', "Helpdesk mag maximaal {$kamp->helpdeskTeJongMagAantal} deelnemers die te jong zijn inschrijven"];
            }
            if ($kamp->helpdeskTeOudMagAantal != '' && $kamp->helpdeskTeOudMagAantal != '0') {
                $data[] = ['Aantal te oud:', "Helpdesk mag maximaal {$kamp->helpdeskTeOudMagAantal} deelnemers die te oud zijn inschrijven"];
            }
        }
        if ($kamp->isouderkind == '1') {
            $leeftijdenOuder = "{$kamp->minimumLeeftijdOuder} - {$kamp->maximumLeeftijdOuder} jaar";
            $data[] = ['Leeftijd ouder:', $leeftijdenOuder];
        }

        $uitloop = '';
        if ($kamp->helpdeskOverschrijdingAantal != '') {
            $uitloop = "max. {$kamp->helpdeskOverschrijdingAantal} extra mogelijk";
        }
        $data[] = ['Aantal deelnemers:', "{$kamp->minimumAantalDeelnemers} - {$kamp->maximumAantalDeelnemers} ( {$kamp->maximumAantalDeelnemersOrigineel} + {$uitloop} )"];
        $data[] = ['Contactgegevens voor helpdesk:', "{$this->c($kamp->helpdeskContactpersoon)} / {$kamp->helpdeskContactEmailadres} / {$kamp->helpdeskContactTelefoonnummer}"];
        if ($kamp->helpdeskOpmerkingen != '') {
            $data[] = ['Opmerkingen voor helpdesk:', $this->c($kamp->helpdeskOpmerkingen)];
        }
        $deelbaar = '';
        if ($kamp->subgroepsamenstellingExtra != '' && $kamp->subgroepsamenstellingExtra != '0') {
            $deelbaar = "(grootte moet deelbaar zijn door {$kamp->subgroepsamenstellingExtra})";
        }
        $data[] = ['Subgroep grootte:', "$kamp->subgroepsamenstellingMinimum - $kamp->subgroepsamenstellingMaximum $deelbaar"];
        if ($kamp->minimumAantalSubgroepjes == '0' && $kamp->maximumAantalSubgroepjes == '0') {
            $data[] = ['Aantal subgroepen:', "Maakt niet uit"];
        } else {
            if ($kamp->minimumAantalSubgroepjes != '0') {
                $data[] = ['Min. aantal subgroepen:', $kamp->minimumAantalSubgroepjes];
            }
            if ($kamp->maximumAantalSubgroepjes != '0') {
                $data[] = ['Min. aantal subgroepen:', $kamp->maximumAantalSubgroepjes];
            }
        }
        $data[] = ['Max. dln uit 1 groep:', ($kamp->maximumAantalUitEenGroep == '0') ? "Maakt niet uit" : $kamp->maximumAantalUitEenGroep];
        $this->improvedTable(array('Kamponderdeel' => 45, 'Gegevens' => 0), $data);

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
        $even = false;
        foreach ($icoontjes as $icon) {
            $img = JPATH_ROOT .DIRECTORY_SEPARATOR. $this->iconFolder .DIRECTORY_SEPARATOR. $icon->naam . $this->iconExtension;
            $this->Image($img, $this->getX(), $this->GetY());
            $this->x += 12;
            $this->Cell(80, 12, $this->c($icon->tekst));
            if ($even) {
                $this->Ln();
            }
            $even = !$even;
        }
    }

}
