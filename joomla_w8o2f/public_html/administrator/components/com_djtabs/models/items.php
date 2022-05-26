<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

class DJTabsModelItems extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'groupid', 'a.group_id', 'group_name',
				'ordering', 'a.ordering',
				'published', 'a.published'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$group = $this->getUserStateFromRequest($this->context.'.filter.group', 'filter_group', '');
		$this->setState('filter.group', $group);
		$app->setUserState('djtabs.filter.group', $group);
		
		$app->setUserState('djtabs.tab_href', null);

		// List state information.
		parent::populateState('a.ordering', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.group');
		
		return parent::getStoreId($id);
	}
	
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('#__djtabs_items AS a');
		
		// Join over the groups.
		$query->select('c.title AS group_name');
		$query->join('LEFT', '#__djtabs_groups AS c ON c.id = a.group_id');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}
		
		// Filter by category state
		$group = $this->getState('filter.group');
		if (is_numeric($group)) {
			$query->where('a.group_id = ' . (int) $group);
		}
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' )');
			}
		}
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'group_name') {
			$orderCol = 'c.title  '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));
		
		return $query;
	}

	function getGroups()
	{
		$db = JFactory::getDBO();
		$query = "SELECT id value, title text FROM #__djtabs_groups WHERE published=1 ORDER BY title";
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		return $groups;
	}
	
}
