<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Component\MinitekWall\Administrator\Helper\MinitekWallHelper;

/**
 * The item controller
 *
 * @since  4.0.12
 */

class ItemController extends FormController
{
	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 * @param   MVCFactoryInterface  $factory  The factory.
	 * @param   CmsApplication       $app      The JApplication for the dispatcher
	 * @param   \JInput              $input    Input
	 *
	 * @since   4.0.12
	 */
	public function __construct($config = array(), MVCFactoryInterface $factory = null, $app = null, $input = null)
	{
		parent::__construct($config, $factory, $app, $input);
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   4.0.12
	 */
	protected function allowAdd($data = array())
	{
		$allow = null;

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}

		return $allow;
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   4.0.12
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}
}
