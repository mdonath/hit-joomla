<?php

use PHPUnit\Framework\TestCase;

use HITScoutingNL\Library\KampInfo\Icoon\IcoonUtil;

class IcoonUtilTest extends TestCase {

    public function testExplodeIcoontjes() {
        $kamp = new \stdClass();

        $iconenMap = [

        ];

        $result = IcoonUtil::explodeIcoontjes($kamp, $iconenMap);

        var_dump($result);
    }
}
