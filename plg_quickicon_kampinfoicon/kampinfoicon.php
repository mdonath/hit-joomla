<?php defined('_JEXEC') or die;

class plgQuickiconKampinfoicon extends JPlugin
{
	public function onGetIcons($context)
	{
		if (($context == $this->params->get('context', 'mod_quickicon')) && JFactory::getUser()->authorise('core.manage', 'com_kampinfo')) {
			JFactory::getDocument()->addStyleSheet(JURI::root()."media/com_kampinfo/css/quickicon.css");
			return array(array(
					'link' => 'index.php?option=com_kampinfo',
					'image' => 'kampinfo',
					'icon' => JURI::root().'media/com_kampinfo/images/kampinfo-48x48.png',
					'text' => 'KampInfo',
					'access' => array('core.manage', 'com_kampinfo'),
					'id' => 'plg_quickicon_kampinfo'
			));
		}
	}
}
