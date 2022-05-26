<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\Model;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Model for an Item.
 *
 * @since  4.0.12
 */
class ItemModel extends AdminModel
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  4.0.12
	 */
	protected $text_prefix = 'COM_MINITEKWALL';

	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  4.0.12
	 */
	public $typeAlias = 'com_minitekwall.item';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   4.0.12
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return false;
			}

			return Factory::getUser()->authorise('core.delete', 'com_minitekwall');
		}

		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   4.0.12
	 */
	protected function canEditState($record)
	{
		$user = Factory::getUser();

		// Check for existing item.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_minitekwall');
		}

		// Default to component settings if item unknown.
		return parent::canEditState($record);
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\Table\Table  A JTable object
	 *
	 * @since   4.0.12
	 */
	public function getTable($type = 'Item', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the images field to an array.
			$registry = new Registry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Convert the urls field to an array.
			$registry = new Registry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();

			$item->description = trim($item->description);
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 *
	 * @since   4.0.12
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_minitekwall.item', 'item', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = Factory::getApplication()->input;

		/*
		 * The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		 * The back end uses id so we use that the rest of the time and set it to 0 by default.
		 */
		$id = $jinput->get('a_id', $jinput->get('id', 0));

		// Determine correct permissions to check.
		if ($this->getState('item.id'))
		{
			$id = $this->getState('item.id');

			// Existing record. Can only edit in selected groups.
			$form->setFieldAttribute('groupid', 'action', 'core.edit');

			// Existing record. Can only edit own item in selected groups.
			$form->setFieldAttribute('groupid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected groups.
			$form->setFieldAttribute('groupid', 'action', 'core.create');
		}

		$user = Factory::getUser();

		// Check for existing item.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_minitekwall'))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_minitekwall')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an item you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   4.0.12
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_minitekwall.edit.item.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Group, Access) in edit form if those have been selected in filters
			if ($this->getState('item.id') == 0)
			{
				$filters = (array) $app->getUserState('com_minitekwall.items.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
				$data->set('groupid', $app->input->getInt('groupid', (!empty($filters['group_id']) ? $filters['group_id'] : null)));
				$data->set('access', $app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : Factory::getConfig()->get('access'))));
			}
		}

		$this->preprocessData('com_minitekwall.item', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   4.0.12
	 */
	public function save($data)
	{
		$input  = Factory::getApplication()->input;
		$filter = \JFilterInput::getInstance();

		if (isset($data['created_by_alias']))
		{
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new Registry;
			$registry->loadArray($data['images']);
			$data['images'] = (string) $registry;
		}

		if (isset($data['urls']) && is_array($data['urls']))
		{
			$check = $input->post->get('jform', array(), 'array');

			foreach ($data['urls'] as $i => $url)
			{
				if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc'))
				{
					if (preg_match('~^#[a-zA-Z]{1}[a-zA-Z0-9-_:.]*$~', $check['urls'][$i]) == 1)
					{
						$data['urls'][$i] = $check['urls'][$i];
					}
					else
					{
						$data['urls'][$i] = PunycodeHelper::urlToPunycode($url);
					}
				}
			}

			unset($check);

			$registry = new Registry($data['urls']);

			$data['urls'] = (string) $registry;
		}

		if (parent::save($data))
		{
			return true;
		}

		return false;
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   \Joomla\CMS\Table\Table  $table  A Table object.
	 *
	 * @return  void
	 *
	 * @since   4.0.12
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();

		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = Factory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = null;
		}

		// Reorder the items within the group so the new item is first
		if (empty($table->id))
		{
			$table->reorder('groupid = ' . (int) $table->groupid . ' AND state >= 0');
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'groupid = '.(int)$table->groupid;

		return $condition;
	}
}
