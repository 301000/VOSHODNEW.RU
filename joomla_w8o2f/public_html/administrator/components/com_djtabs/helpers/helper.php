<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class DJTabsAdminHelper
{
	public static function addSubmenu($vName = 'cpanel')
	{
		JHtmlSidebar::addEntry(JText::_('COM_DJTABS_CPANEL'), 'index.php?option=com_djtabs&view=cpanel', $vName=='cpanel');
		JHtmlSidebar::addEntry(JText::_('COM_DJTABS_ITEMS'), 'index.php?option=com_djtabs&view=items', $vName=='items');
		JHtmlSidebar::addEntry(JText::_('COM_DJTABS_GROUPS'), 'index.php?option=com_djtabs&view=groups', $vName=='groups');
		JHtmlSidebar::addEntry(JText::_('COM_DJTABS_THEMES'), 'index.php?option=com_djtabs&view=themes', $vName=='themes');
	}

	public static function getBSClasses()
	{
		$cl = new JObject;

		if(version_compare(JVERSION, '4', '>=')) { // Bootstrap 4
			$cl->set('tab', 'uitab');
			$cl->set('row', 'row');
			$cl->set('col', 'col-md-');
		}else{ // Boostrap 2.3.2
			$cl->set('tab', 'bootstrap');
			$cl->set('row', 'row-fluid');
			$cl->set('col', 'span');
		}

		return $cl;
	}
}

?>