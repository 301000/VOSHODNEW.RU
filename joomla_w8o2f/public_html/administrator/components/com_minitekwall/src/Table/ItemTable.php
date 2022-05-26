<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

/**
 * Item Table
 *
 * @since  4.0.12
 */
class ItemTable extends Table
{
	/**
	 * Class constructor.
	 *
	 * @param   \JDatabaseDriver  $db  \JDatabaseDriver object.
	 *
	 * @since   4.0.12
	 */
	public function __construct(\JDatabaseDriver $db)
	{
		$this->typeAlias = 'com_minitekwall.group';

		parent::__construct('#__minitek_source_items', 'id', $db);

		$this->setColumnAlias('published', 'state');
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_minitekwall.item.' . (int) $this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}

	protected function _getAssetParentId(Table $table = null, $id = null)
	{
		$assetId = null;

		// This is an item under a group.
		if ($this->groupid)
		{
			// Build the query to get the asset id for the parent group.
			$query = $this->_db->getQuery(true)
				->select($this->_db->quoteName('asset_id'))
				->from($this->_db->quoteName('#__minitek_source_groups'))
				->where($this->_db->quoteName('id') . ' = ' . (int) $this->groupid);

			// Get the asset id from the database.
			$this->_db->setQuery($query);

			if ($result = $this->_db->loadResult())
			{
				$assetId = (int) $result;
			}
		}

		// Return the asset id.
		if ($assetId)
		{
			return $assetId;
		}
		else
		{
			return parent::_getAssetParentId($table, $id);
		}
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['tags']) && is_array($array['tags']))
		{
			$registry = new Registry;
			$registry->loadArray($array['tags']);
			$array['tags'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to perform sanity checks on the JTable instance properties to ensure
	 * they are safe to store in the database.  Child classes should override this
	 * method to make sure the data they are storing in the database is safe and
	 * as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @link    https://docs.joomla.org/Special:MyLanguage/JTable/check
	 * @since   4.0.12
	 */
	public function check()
	{
		if (trim($this->title) == '')
		{
			$this->setError(JText::_('COM_MINITEKWALL_ITEMS_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		if (trim(str_replace('&nbsp;', '', $this->description)) == '')
		{
			$this->description = '';
		}

		// Set publish_up to null if not set
		if (!$this->publish_up)
		{
			$this->publish_up = null;
		}

		// Set publish_down to null if not set
		if (!$this->publish_down)
		{
			$this->publish_down = null;
		}

		// Check the publish down date is not earlier than publish up.
		if (!is_null($this->publish_up) && !is_null($this->publish_down) && $this->publish_down < $this->publish_up)
		{
			// Swap the dates.
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}

		return true;
	}

	/**
	 * Stores an item.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   4.0.12
	 */
	public function store($updateNulls = false)
	{
		$date = Factory::getDate()->toSql();
		$user = Factory::getUser();

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date;
		}

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $user->get('id');
			$this->modified    = $date;
		}
		else
		{
			// Field created_by can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}

			// Set modified to created date if not set
			if (!(int) $this->modified)
			{
				$this->modified = $this->created;
			}

			// Set modified_by to created_by user if not set
			if (empty($this->modified_by))
			{
				$this->modified_by = $this->created_by;
			}
		}

		return parent::store($updateNulls);
	}
}
