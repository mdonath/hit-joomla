<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper;

\defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\AbstractMapper;


class XmlMapper extends AbstractMapper {

    public function __construct($mapping) {
        parent::__construct($mapping);
    }

    public function read($filename) {
        $document = $this->readXmlFromFile($filename);
        $this->leesHeader($document);
        return $this->leesData($document);
    }

    public function readString($xml) {
        $document = $this->readXmlFromString($xml);
        $this->leesHeader($document);
        return $this->leesData($document);
    }

    private function readXmlFromString($xml) {
        $document = $this->createDomDocument();
        $document->loadXml($xml);
        return $document;
    }

    private function readXmlFromFile($filename) {
        $document = $this->createDomDocument();
        $document->load($filename);
        return $document;
    }

    private function createDomDocument() {
        $document = new DOMDocument('1.0', 'utf-8');
        $document->formatOutput = false;
        $document->preserveWhiteSpace = false;
        return $document;
    }

    private function leesHeader($document) {
        $domxpath = new DOMXPath($document);
        $elements = $domxpath->query('(//listheader)[1]/row/*');

        $rows = array();
        if (!is_null($elements)) {
            foreach ($elements as $element) {
                $nodes = $element->childNodes;
                foreach ($nodes as $node) {
                    $rows[] = $node->nodeValue;
                }
            }
        }
        $this->columns = $rows;
    }

    private function leesData($document) {
        $rows = array();

        $domxpath = new DOMXPath($document);
        // Er is ook een tweede listbody met REST / pager -achtige dingen. We moeten alleen de eerst hebben.
        $elements = $domxpath->query('(//listbody)[1]/row');

        if (!is_null($elements)) {
            foreach ($elements as $element) {
                $data = $element->childNodes;
                $object = new stdClass();
                $num = $data->length;
                for ($i = 0; $i < $num; $i++) {
                    $kolom = $this->columns[$i];
                    if ($this->isMappable($kolom)) {
                        $veld = $this->mapping[$kolom];
                        $veld->set($object, $data->item($i)->nodeValue);
                    }
                }
                if (isset($object->status)) {
                    if ($object->status == 'Deelname afgerond' || $object->status == 'Deelnemer staat ingeschreven') {
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
