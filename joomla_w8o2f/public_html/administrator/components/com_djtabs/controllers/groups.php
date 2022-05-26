<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

class DJTabsControllerGroups extends JControllerAdmin
{
	public function getModel($name = 'Group', $prefix = 'DJTabsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

 }
  
     