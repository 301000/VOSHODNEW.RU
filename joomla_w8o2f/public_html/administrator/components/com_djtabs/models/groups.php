<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class DJTabsModelGroups extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'ordering', 'a.ordering',
				'published', 'a.published'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
		
		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$orderCol = $this->getState('list.ordering', 'a.title');
		$orderDirn	= $this->getState('list.direction');

		$where = '';
		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= JFactory::getDBO();
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$where .= " AND a.title LIKE ".$search." ";
		}
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$where .= ' AND a.published = ' . (int) $published;
		}
	
		$query = "SELECT a.* FROM #__djtabs_groups a "
				."  WHERE 1 ".$where." ORDER BY ".$orderCol." ".$orderDirn." ";

				//die($query);
		return $query;
	}

	// protected function getReorderConditions($table)
	// {
	// 	$condition = array();
	// 	$condition[] = 'cat_id = '.(int) $table->cat_id;
	// 	return $condition;
	// }
}
