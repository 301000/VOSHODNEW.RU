<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsModelModal extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_djtabs.modal', 'modal', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	public function getTable($name = '', $prefix = 'Table', $options = Array())
	{
	}
	public function populateState()
	{
	}
	public function getItem($pk = NULL)
	{
	}
}
