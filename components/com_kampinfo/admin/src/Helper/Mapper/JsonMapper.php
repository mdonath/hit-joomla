<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper;

defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\AbstractMapper;


class JsonMapper extends AbstractMapper {

    public function __construct($mapping) {
        parent::__construct($mapping);
    }

    public function readList($list) {
        $rows = [];

        if (!is_null($list)) {
            foreach ($list as $item) {
                $object = new \stdClass();
                foreach ($item as $key => $value) {
                    // echo($key . ' => '. $value . "\n");
                    if ($this->isMappable($key)) {
                        $veld = $this->mapping[$key];
                        $veld->set($object, $value);
                    }
                }
                if (isset($object->status)) {
                    if ($object->status == 'Deelname afgerond'
                    || $object->status == 'Deelnemer staat ingeschreven'
                    || $object->status == 'Participant registered'
                    || $object->status == 'Registered, but has todo iDEAL-payment'
                    ) {
                        $rows[] = $object;
                    }
                } else {
                    $rows[] = $object;
                }
            }
        }
        return $rows;
    }

}
