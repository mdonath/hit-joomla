<?php

namespace HITScoutingNL\Library\KampInfo\Helper;

use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Kamp;
use PHPUnit\Framework\TestCase;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoUrlHelper;


class KampInfoUrlHelperTest extends TestCase {

    public function testIsYoutubeFilmpje() {
        $this->assertTrue(KampInfoUrlHelper::isYoutubeFilmpje('https://www.youtube.com/watch?v=abc123'));
        $this->assertTrue(KampInfoUrlHelper::isYoutubeFilmpje('https://youtu.be/abc123'));
        $this->assertFalse(KampInfoUrlHelper::isYoutubeFilmpje('https://www.example.com/video'));
    }

    public function testIsEmptyUrl() {
        $this->assertTrue(KampInfoUrlHelper::isEmptyUrl(''));
        $this->assertTrue(KampInfoUrlHelper::isEmptyUrl('http://'));
        $this->assertFalse(KampInfoUrlHelper::isEmptyUrl('https://www.example.com'));
    }

    public function testFuzzyIndicatieVol() {
        $startInschrijving = new \Joomla\CMS\Date\Date('now');
        $startInschrijving->sub(new \DateInterval('P3D'));
        $startLoterij = new \Joomla\CMS\Date\Date('now');
        $startLoterij->add(new \DateInterval('P3D'));

        $kamp = new \stdClass();
        $kamp->startInschrijving = $startInschrijving->format('Y-m-d H:i:s');
        $kamp->startLoterij = $startLoterij->format('Y-m-d H:i:s');
        $kamp->aantalDeelnemers = 12;
        $kamp->gereserveerd = 11;
        $kamp->maximumAantalDeelnemers = 10;
        $kamp->maximumAantalSubgroepjes = 5;
        $kamp->aantalSubgroepen = 4;

        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Er zijn meer aanmeldingen dan plaatsen, er gaat geloot worden!', $result);

        $startLoterij->sub(new \DateInterval('P4D'));
        $kamp->startLoterij = $startLoterij->format('Y-m-d H:i:s');
        $kamp->aantalDeelnemers = 10;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Vol: alleen nog inschrijven op 1 gereserveerde plaats.', $result);

        $kamp->aantalDeelnemers = 8;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Vol: alleen nog inschrijven op 3 gereserveerde plaatsen.', $result);

        $kamp->aantalDeelnemers = $kamp->gereserveerd;
        $kamp->aantalSubgroepen = $kamp->maximumAantalSubgroepjes;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Vol: Maximum aantal groepjes is bereikt. Inschrijven is niet meer mogelijk', $result);

        $kamp->maximumAantalSubgroepjes = 0;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Vol: inschrijven is niet meer mogelijk.', $result);

        $kamp->subgroepsamenstellingMinimum = 1;

        $kamp->gereserveerd = 0;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Nog ruim voldoende plaatsen beschikbaar.', $result);

        $kamp->gereserveerd = $kamp->maximumAantalDeelnemers - 5;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Nog voldoende plaatsen beschikbaar.', $result);

        $kamp->gereserveerd = $kamp->maximumAantalDeelnemers - 1;
        $result = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
        $this->assertEquals('Bijna vol: Nog 1 plaats beschikbaar.', $result);
    }

    public function testMeervoudPlaats() {
        $this->assertEquals('plaatsen', KampInfoUrlHelper::meervoudPlaats(0));
        $this->assertEquals('plaats', KampInfoUrlHelper::meervoudPlaats(1));
        $this->assertEquals('plaatsen', KampInfoUrlHelper::meervoudPlaats(2));
    }

    public function testIsVol() {
        $kamp = new \stdClass();
        $kamp->aantalDeelnemers = 0;
        $kamp->gereserveerd = 0;
        $kamp->maximumAantalDeelnemers = 10;
        
        $kamp->aantalSubgroepen = 0;
        $kamp->maximumAantalSubgroepjes = 0;

        $this->assertFalse(KampInfoUrlHelper::isVol($kamp));

        $kamp->gereserveerd = $kamp->maximumAantalDeelnemers;
        $this->assertTrue(KampInfoUrlHelper::isVol($kamp));

        $kamp->gereserveerd = 0;
        $kamp->aantalSubgroepen = 5;
        $kamp->maximumAantalSubgroepjes = 5;
        $this->assertTrue(KampInfoUrlHelper::isVol($kamp));
    }

    public function testVolOfLoterij() {
        $start = new \Joomla\CMS\Date\Date('now');
        $eind = new \Joomla\CMS\Date\Date('now');

        $kamp = new \stdClass();

        $start->sub(new \DateInterval('P3D'));
        $eind->add(new \DateInterval('P3D'));
        $kamp->startInschrijving = $start->format('Y-m-d H:i:s');
        $kamp->startLoterij = $eind->format('Y-m-d H:i:s');

        $result = KampInfoUrlHelper::volOfLoterij($kamp);
        $this->assertEquals('loterij', $result, 'zou loterij terug moeten geven als de loterij nog moet beginnen');

        $start->sub(new \DateInterval('P10D'));
        $eind->sub(new \DateInterval('P10D'));
        $kamp->startInschrijving = $start->format('Y-m-d H:i:s');
        $kamp->startLoterij = $eind->format('Y-m-d H:i:s');

        $result = KampInfoUrlHelper::volOfLoterij($kamp);

        $this->assertEquals('vol', $result, 'zou vol terug moeten geven als de loterij is begonnen');
    }

}
