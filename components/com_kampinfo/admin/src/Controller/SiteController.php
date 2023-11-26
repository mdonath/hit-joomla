<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;


class SiteController extends FormController {

    protected function allowEdit($data = array(), $key = 'id') {
        $id = isset( $data[ $key ] ) ? $data[ $key ] : 0;
        if( !empty( $id ) ) {
            $user = Factory::getUser();
            return $user->authorise("hitsite.edit", "com_kampinfo.site." . $id );
        }
    }

}
