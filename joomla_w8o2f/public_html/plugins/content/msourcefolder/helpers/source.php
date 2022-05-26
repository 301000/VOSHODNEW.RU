<?php
/**
* @title				Minitek Source Folder
* @copyright   	Copyright (C) 2011-2019 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

class MSourceFolderSource
{
	public function getItemsOrdering($source_params)
	{
		$ordering = $source_params['fold_ordering'];
		if ($ordering == 'created')
		{
			$ordering = 'date';
		}

		return $ordering;
	}

	public function getItemsDirection($source_params)
	{
		$direction = $source_params['fold_ordering_direction'];

		return $direction;
	}

	public function getItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		if ($isCount)
		{
			$result = self::getFolderImagesCount($source_params, $startLimit);
		}
		else
		{
			$result = self::getFolderImages($source_params, $startLimit, $pageLimit, $globalLimit);
		}

		if (isset($result))
			return $result;
	}

	// Get Images count from folder
	public static function getFolderImagesCount($source_params, $globalLimit)
	{
		$folder = trim($source_params['image_folder'], '/');
		$directory = $folder.'/';
		$images = glob($directory."*.{jpg,png}", GLOB_BRACE);
		if (!$images)
		{
			$images = array_merge(glob($directory."*.jpg"),glob($directory."*.png"));
		}
		$count = count($images);

		if ($count > $globalLimit)
		{
			$count = $globalLimit;
		}

		return $count;
	}

	// Get Images from folder
	public static function getFolderImages($source_params, $startLimit, $pageLimit, $globalLimit)
	{
		$folder = trim($source_params['image_folder'], '/');
		$directory = $folder.'/';
		$images = glob($directory."*.{jpg,png}", GLOB_BRACE);
		if (!$images)
		{
			$images = array_merge(glob($directory."*.jpg"),glob($directory."*.png"));
		}

		foreach ($images as $key => $value)
		{
			$images[$key] = new stdClass();
			$images[$key]->path = $value;
			$images[$key]->title = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($value));
			$images[$key]->created = date('Y-m-d H:i:s', filemtime($value));
		}

		// Ordering direction
		if ($source_params['fold_ordering_direction'] == 'ASC')
		{
			$dir = SORT_ASC;
		}
		else
		{
			$dir = SORT_DESC;
		}

		// Order by title
		if ($source_params['fold_ordering'] == 'title')
		{
			$title = array();
			foreach ($images as $key => $row)
			{
				$title[$key] = $row->title;
			}
			array_multisort($title, $dir, $images);
		}

		// Order by date created
		if ($source_params['fold_ordering'] == 'created')
		{
			$created = array();
			foreach ($images as $key => $row)
			{
				$created[$key] = $row->created;
			}
			array_multisort($created, $dir, $images);
		}

		// Random order
		if ($source_params['fold_ordering'] == 'random')
		{
			shuffle($images);
		}

		// Set the list start limit
		$app = Factory::getApplication();

		$page = $app->input->get('page', false, 'INT');
		if (!$page || $page == 1) {
			$limit	= $startLimit;
			$start = 0;
		} else {
			$start_limit = $startLimit;
			$limit = $pageLimit;
			$start = $start_limit + (($page - 2) * $limit);

			$view = $app->input->get('view', '', 'WORD');
			$pagination = $app->input->get('pagination', '', 'INT');
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

		// Limit items according to pagination
		$images = array_slice($images, $start, $limit);

		return $images;
	}
}
