<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class plgEditorsxtdDjtabsInstallerScript
{
	function postflight($type, $parent)
	{
		if($type == 'install'){
			$db = JFactory::getDBO();
			$db->setQuery("UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element='djtabs' AND folder='editors-xtd'");
			$db->execute();
		}
	}
}
?>