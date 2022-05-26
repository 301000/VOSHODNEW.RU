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

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Model for a Grid.
 *
 * @since  4.0.12
 */
class GridModel extends AdminModel
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
	public $typeAlias = 'com_minitekwall.grid';

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

			return \JFactory::getUser()->authorise('core.delete', 'com_minitekwall');
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
		$user = \JFactory::getUser();

		// Check for existing item.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_minitekwall.grid.' . (int) $record->id);
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
	public function getTable($type = 'Grid', $prefix = 'Administrator', $config = array())
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
			return $item;
		}
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
		$form = $this->loadForm('com_minitekwall.grid', 'grid', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = \JFactory::getApplication()->input;

		/*
		 * The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		 * The back end uses id so we use that the rest of the time and set it to 0 by default.
		 */
		$id = $jinput->get('a_id', $jinput->get('id', 0));

		// Determine correct permissions to check.
		if ($this->getState('grid.id'))
		{
			$id = $this->getState('grid.id');
		}

		$user = \JFactory::getUser();

		// Check for existing item.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_minitekwall.grid.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_minitekwall')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an item you can edit.
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
		$app = \JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_minitekwall.edit.grid.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Group, Access) in edit form if those have been selected in filters
			if ($this->getState('grid.id') == 0)
			{
				$filters = (array) $app->getUserState('com_minitekwall.grids.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
			}
		}

		$this->preprocessData('com_minitekwall.grid', $data);

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
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
	}
}
