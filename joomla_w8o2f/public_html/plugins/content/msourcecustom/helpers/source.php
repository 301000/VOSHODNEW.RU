<?php
/**
* @title				Minitek Source Custom
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Access\Access;
use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

class MSourceCustomSource
{
	public function getItemsOrdering($source_params)
	{
		switch ($source_params['custom_ordering'])
		{
			case 'created':
				$ordering = 'date';
				break;

			case 'publish_up':
				$ordering = 'start';
				break;

			case 'publish_down':
				$ordering = 'finish';
				break;

			default:
				$ordering = $source_params['custom_ordering'];
				break;
		}

		return $ordering;
	}

	public function getItemsDirection($source_params)
	{
		$direction = $source_params['custom_ordering_direction'];

		return $direction;
	}

	public function getItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		if ($isCount)
		{
			$result = self::getCustomItems($isCount, $source_params, $startLimit, false, false);
		}
		else
		{
			$result = self::getCustomItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit);
		}

		if (isset($result))
			return $result;
	}

	// Get Custom Items
	public static function getCustomItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		$app = Factory::getApplication();
		$user = Factory::getUser();
		$db = Factory::getDBO();

		// Set the list start limit
		$page = $app->input->get('page', '', 'INT');
		if (!$page || $page == 1) {
			$limit	= $startLimit;
			$start = 0;
		} else {
			$start_limit = $startLimit;
			$limit = $pageLimit;
			$start = $start_limit + (($page - 2) * $limit);

			$pagination = $app->input->get('pagination');
			if ($app->input->get('filters') == 'filters' && ($pagination == 1 || $pagination == '4')) { // Pagination: Append / Infinite
				$start = 0;
				$limit = $start_limit + (($page - 1) * $limit);
			}

			if ($start < $globalLimit) {
				if ($start + $pageLimit >= $globalLimit) {
					$limit = $globalLimit - $start;
				}
			} else {
				$limit = 0;
			}
		}

		// Query
		$query = 'SELECT * FROM ' . $db->quoteName( '#__minitek_source_items');

		// Filter by state
		$query .= ' WHERE '.$db->quoteName('state').' = '.$db->quote('1');

		// Filter by group id
		$query.= ' AND '.$db->quoteName('groupid').' = '.$db->quote($source_params['custom_groupid']);

		// Filter by start and end dates
		$nullDate	= $db->quote($db->getNullDate());
		$nowDate	= $db->quote(Factory::getDate()->toSql());

		$query .= ' AND ('.$db->quoteName('publish_up').' = '.$nullDate.' OR '.$db->quoteName('publish_up').' <= '.$nowDate.')';
		$query .= ' AND ('.$db->quoteName('publish_down').' = '.$nullDate.' OR '.$db->quoteName('publish_down').' >= '.$nowDate.')';

		// Filter by access level
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$query .= ' AND access IN ('.$groups.')';

		// Add the list ordering clause.
		$ordering = ''.$source_params['custom_ordering'].' '.$source_params['custom_ordering_direction'].', id '.$source_params['custom_ordering_direction'].'';
		$query .= ' ORDER BY '.$ordering;

		$db->setQuery( $query, $start, $limit );

		if (!$isCount)
		{
			$result	= $db->loadObjectList();

			if (!$result)
			{
				$result = array();
			}

			return $result;
		}
		else
		{
			$db->execute();
			$itemCount = $db->getNumRows();

			return $itemCount;
		}
	}
}
