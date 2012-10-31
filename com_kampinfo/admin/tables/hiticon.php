<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * HIT Icon Table class
 */
class KampInfoTableHitIcon extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
        function __construct(&$db) 
        {
                parent::__construct('#__kampinfo_hiticon', 'id', $db);
        }
}