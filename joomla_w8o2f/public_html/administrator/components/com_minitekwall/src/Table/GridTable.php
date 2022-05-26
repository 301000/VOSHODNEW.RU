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

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

/**
 * Grid Table
 *
 * @since  4.0.12
 */
class GridTable extends Table
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
		$this->typeAlias = 'com_minitekwall.grid';

		parent::__construct('#__minitek_wall_grids', 'id', $db);

		$this->setColumnAlias('published', 'state');
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_minitekwall.grid.' . (int) $this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->name;
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
		// Check for valid name.
		if (trim($this->name) == '')
		{
			$this->setError(\JText::_('COM_MINITEKWALL_CUSTOM_GRIDS_WARNING_PROVIDE_VALID_NAME'));

			return false;
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
 		return parent::store($updateNulls);
 	}
}
