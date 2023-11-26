<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\AbstractMapper;


class CsvMapper extends AbstractMapper {

    public function __construct($mapping) {
        parent::__construct($mapping);
    }

    public function read($file) {
        $rows = array();
        if (($handle = \fopen($file, "r")) !== FALSE) {
            $this->leesHeader($handle);
            $rows = $this->leesData($handle);
            \fclose($handle);
        }
        return $rows;
    }

    private function leesHeader($handle) {
        $this->columns = $this->readLine($handle);
    }

    private function leesData($handle) {
        $rows = array();
        while (($data = $this->readLine($handle)) !== FALSE) {
            $object = new \stdClass();
            $num = \count($data);
            for ($i = 0; $i < $num; $i++) {
                $kolom = $this->columns[$i];
                if ($this->isMappable($kolom)) {
                    $veld = $this->mapping[$kolom];
                    $veld->set($object, $data[$i]);
                }
            }
            $rows[] = $object;
        }
        return $rows;
    }

    private function readLine($handle) {
        return \fgetcsv($handle, 0, ",");
    }

}
