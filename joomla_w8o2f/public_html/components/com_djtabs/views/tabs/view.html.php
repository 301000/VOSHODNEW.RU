<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined ('_JEXEC') or die('Restricted access');

class DJTabsViewTabs extends JViewLegacy
{
	function display($tpl = null)
	{	
        $app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params = $app->getParams();
		$menus	= $app->getMenu('site');
		$model = $this->getModel();
		$m_active = $menus->getActive();
		$layout = $app->input->get('layout', 'default');

		if($m_active){
			if($m_active->getParams()->get('menu-meta_keywords')){
				$document->setMetaData('keywords',$m_active->getParams()->get('menu-meta_keywords'));
			}
			if($m_active->getParams()->get('menu-meta_description')){
				$document->setDescription($m_active->getParams()->get('menu-meta_description'));
			}
		}
		
		$groupid = $params->get('group_id',0);
		if(!$groupid){
	        return false;
		}
		
		$tabs = $model->getTabs($groupid);

		$this->tabs = $tabs;
		$this->params = $params;

		DjTabsHelper::addThemeCSS($params);
		DjTabsHelper::addTabsScriptDeclaration($layout, $params);

        parent::display($tpl);
    }
}

?>