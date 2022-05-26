<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsController extends JControllerLegacy
{
	protected $default_view = 'cpanel';
	
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/helper.php';
		DJTabsAdminHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'cpanel'));
		
		parent::display($cachable, $urlparams);
	}
}
