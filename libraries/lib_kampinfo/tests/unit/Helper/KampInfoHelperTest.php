<?php

namespace HITScoutingNL\Library\KampInfo\Helper;

use PHPUnit\Framework\TestCase;

use Joomla\Registry\Registry;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;


class KampInfoHelperTest extends TestCase {

    public function testGetActivityAreaOptions() {
        $options = KampInfoHelper::getActivityAreaOptions();

        $this->assertIsArray($options);
        $this->assertCount(8, $options);

        foreach ($options as $option) {
            $this->assertObjectHasProperty('value', $option);
            $this->assertObjectHasProperty('text', $option);
        }
    }

    public function testGetHitIconSoortOptions() {
        $options = KampInfoHelper::getHitIconSoortOptions();

        $this->assertIsArray($options);
        $this->assertCount(7, $options);

        $this->assertArrayHasKeyValue('?', 'Gewoon', $options);
        $this->assertArrayHasKeyValue('B', 'Beweging', $options);
        $this->assertArrayHasKeyValue('I', 'Inschrijven', $options);
        $this->assertArrayHasKeyValue('O', 'Overnachten', $options);
        $this->assertArrayHasKeyValue('A', 'Afstand', $options);
        $this->assertArrayHasKeyValue('K', 'Koken', $options);
        $this->assertArrayHasKeyValue('S', 'Systeem', $options);
    }

    public function testReverse() {
        $app = $this->createStub(\Joomla\CMS\Application\CMSApplication::class);
        $app->method('getSession')->willReturn($this->createStub(\Joomla\CMS\Session\Session::class));
        $app->method('getIdentity')->willReturn($this->createStub(\Joomla\CMS\User\User::class));

        $config = new Registry();
        $config->set('offset', 'Europe/Amsterdam');
        $app->method('getConfig')->willReturn($config);
        \Joomla\CMS\Factory::$application = $app;

        $this->assertEquals('30-08-2026', KampInfoHelper::reverse('2026-08-30'));
        $this->assertEquals('30-08-2026 16:30', KampInfoHelper::reverse('2026-08-30 14:30', true));
        $this->assertEquals('30-01-2026 15:30', KampInfoHelper::reverse('2026-01-30 14:30', true));
    }

    public function testAantalOvernachtingen() {
        $kamp = new \stdClass();
        $kamp->startDatumTijd = '2026-08-30 09:00';
        $kamp->eindDatumTijd = '2026-08-30 21:00';

        $this->assertEquals(0, KampInfoHelper::aantalOvernachtingen($kamp));

        $kamp->eindDatumTijd = '2026-08-31 00:01';
        $this->assertEquals(1, KampInfoHelper::aantalOvernachtingen($kamp));

        $kamp->eindDatumTijd = '2026-09-01';
        $this->assertEquals(2, KampInfoHelper::aantalOvernachtingen($kamp));
    }

    public function testHerberekenDatum() {
        $result = KampInfoHelper::herberekenDatum('2026-04-03 09:12:34');

        $this->assertEquals('2027-03-26 09:12:34', $result);
    }

    public function testEersteHitDag() {
        $result = KampInfoHelper::eersteHitDag(2027);
        $this->assertEquals('2027-03-26', $result->format('Y-m-d'));
    }

    private function isPeriodeActief(string $periode, string $startProperty, string $eindProperty) {
        $start = new \Joomla\CMS\Date\Date('now');
        $eind = new \Joomla\CMS\Date\Date('now');

        $activiteit = new \stdClass();
        $method = new \ReflectionMethod(KampInfoHelper::class, 'is' . $periode . 'Actief');

        $start->sub(new \DateInterval('P3D'));
        $eind->add(new \DateInterval('P3D'));
        $activiteit->$startProperty = $start->format('Y-m-d H:i:s');
        $activiteit->$eindProperty = $eind->format('Y-m-d H:i:s');
        $this->assertTrue($method->invoke(null, $activiteit), "$periode should be active when current date is within the range.");

        $start->add(new \DateInterval('P10D'));
        $eind->add(new \DateInterval('P10D'));
        $activiteit->$startProperty = $start->format('Y-m-d H:i:s');
        $activiteit->eindProperty = $eind->format('Y-m-d H:i:s');
        $this->assertFalse($method->invoke(null, $activiteit), "$periode should not be active when current date is before the range.");

        $start->sub(new \DateInterval('P20D'));
        $eind->sub(new \DateInterval('P20D'));
        $activiteit->$startProperty = $start->format('Y-m-d H:i:s');
        $activiteit->$eindProperty = $eind->format('Y-m-d H:i:s');
        $this->assertFalse($method->invoke(null, $activiteit), "$periode should not be active when current date is after the range.");
    }

    public function testIsInschrijvingActief() {
        $this->isPeriodeActief('Inschrijving', 'startInschrijving', 'eindInschrijving');
    }

    public function testIsLoterijActief() {
        $this->isPeriodeActief('Loterij', 'startLoterij', 'eindLoterij');
    }

    public function testIsWachtlijstActief() {
        $this->isPeriodeActief('Wachtlijst', 'startInschrijving', 'startLoterij');
    }

    private function assertArrayHasKeyValue($key, $value, $array) {
        $this->assertArrayHasKey($key, $array);
        $this->assertEquals($value, $array[$key]);
    }

}
