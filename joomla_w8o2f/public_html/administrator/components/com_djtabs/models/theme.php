<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsModelTheme extends JModelAdmin
{
	public function getTable($type = 'Themes', $prefix = 'DJTabsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_djtabs.theme', 'theme', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_djtabs.edit.theme.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	protected function prepareTable($table)
	{
		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$query = 'SELECT MAX(ordering) FROM #__djtabs_themes';
				//if($table->group_id) $query.= ' WHERE group_id='. (int) $table->group_id;
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}
}
