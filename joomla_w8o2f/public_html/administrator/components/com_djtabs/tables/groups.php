<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class DJTabsTableGroups extends JTable
{
	public function __construct(&$db) {
		parent::__construct('#__djtabs_groups', 'id', $db);
	}

	/*
	function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		if(empty($array['alias'])) {
			$array['alias'] = $array['title'];
		}
		$array['alias'] = JFilterOutput::stringURLSafe($array['alias']);
		if(trim(str_replace('-','',$array['alias'])) == '') {
			$array['alias'] = JFactory::getDate()->format("Y-m-d-H-i-s");
		}
		
		return parent::bind($array, $ignore);
	}
	
	public function store($updateNulls = false)
	{		
		$table = JTable::getInstance('Groups', 'DJTabsTable');
		if ($table->load(array('alias'=>$this->alias,'parent_id'=>$this->parent_id)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_DJTABS_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		return parent::store($updateNulls);
	}
	*/
}
