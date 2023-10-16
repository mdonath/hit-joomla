<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;

defined('_JEXEC') or die;

class CampController extends FormController {

    protected function allowEdit($data = array(), $key = 'id') {
        $id = isset( $data[ $key ] ) ? $data[ $key ] : 0;
        if( !empty( $id ) ) {
            $user = Factory::getUser();
            return $user->authorise("hitcamp.edit", "com_kampinfo.hitcamp." . $id );
        }
    }

}
