<?php 
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

class DJTabsViewCpanel extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->addToolbar();
		if (class_exists('JHtmlSidebar') && version_compare(JVERSION, '4', '<')){
			$this->sidebar = JHtmlSidebar::render();
		}
			
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{  
		JToolBarHelper::title(JText::_('COM_DJTABS').' · '.JText::_('COM_DJTABS_CPANEL'), 'logo');
		JToolBarHelper::preferences('com_djtabs', 550, 500);
	}
}
