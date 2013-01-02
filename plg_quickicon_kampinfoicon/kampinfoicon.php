<?php defined('_JEXEC') or die;

class plgQuickiconKampinfoicon extends JPlugin
{
	public function onGetIcons($context)
	{
		if (($context == $this->params->get('context', 'mod_quickicon')) && JFactory::getUser()->authorise(‘core.manage’, ‘com_kampinfo’)) {
			return array(array(
					'link' => 'index.php?option=com_kampinfo',
					'image' => JURI::root().'media/com_kampinfo/images/kampinfo-48x48.png',
					'text' => 'KampInfo',
					'id' => 'plg_quickicon_kampinfo'
			));
		} else {
			return;
		}
	}
}
