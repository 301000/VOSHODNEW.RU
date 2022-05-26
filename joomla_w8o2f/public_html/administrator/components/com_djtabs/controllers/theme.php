<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsControllerTheme extends JControllerForm
{
	public function save($key = NULL, $urlVar = NULL)
    {	
		parent::save();
		
		require_once (JPATH_ROOT.'/components/com_djtabs/helpers/helper.php');
		$app = JFactory::getApplication();
		DjTabsHelper::generateThemeCSS($app->input->get('id'));
    }
}

?>