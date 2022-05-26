<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsModelItem extends JModelAdmin
{
	public function getTable($type = 'Items', $prefix = 'DJTabsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_djtabs.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_djtabs.edit.item.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			if(!$data->id){
				$data->group_id = JFactory::getApplication()->getUserState('djtabs.filter.group');
			}
		}

		return $data;
	}
	
	protected function prepareTable($table)
	{
		$app = JFactory::getApplication();

		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$query = 'SELECT MAX(ordering) FROM #__djtabs_items';
				if($table->group_id) $query.= ' WHERE group_id='. (int) $table->group_id;
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}

		if($app->input->get('task') == 'apply'){
			$app->setUserState('djtabs.tab_href', $app->input->getStr('tab_href'));
		}else{
			$app->setUserState('djtabs.tab_href', null);
		}
	}
	
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'group_id = '.(int) $table->group_id;

		return $condition;
	}
	
}
