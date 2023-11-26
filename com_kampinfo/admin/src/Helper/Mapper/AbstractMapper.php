<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper;

\defined('_JEXEC') or die('Restricted access');

/**
 * Parent-class voor Mappers.
 */
abstract class AbstractMapper {

    protected $mapping;
    protected $columns;

    public function __construct($mapping) {
        $this->mapping = $mapping;
    }

    protected function isMappable($name) {
        return  (\array_key_exists($name, $this->mapping));
    }

}
