<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DjtabsControllerLicense extends JControllerLegacy {

	public function save()
	{
		$app = JFactory::getApplication();
		
		$license = $app->input->getStr('license');
		$name = $app->input->getStr('extension');

		echo DJLicense::storeLicense($license, $name);

		$app->close();
	}
}
