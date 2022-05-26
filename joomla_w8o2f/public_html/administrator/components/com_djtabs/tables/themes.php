<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsTableThemes extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djtabs_themes', 'id', $db);
	}

	function bind($array, $ignore = '')
	{	
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}				
		
		return parent::bind($array, $ignore);
	}
}
