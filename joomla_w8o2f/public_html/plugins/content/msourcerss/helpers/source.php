<?php
/**
* @title				Minitek Source RSS
* @copyright   	Copyright (C) 2011-2019 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Joomla\String\StringHelper;

class MSourceRSSSource
{
	public function getItemsOrdering($source_params)
	{
		$ordering = 'created';

		if ($ordering == 'created')
		{
			$ordering = 'date';
		}

		return $ordering;
	}

	public function getItemsDirection($source_params)
	{
		$direction = 'DESC';

		return $direction;
	}

	public function getItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		if ($isCount)
		{
			$result = self::getRSSItemsCount($source_params, $startLimit);
		}
		else
		{
			$result = self::getRSSItems($source_params, $startLimit, $pageLimit, $globalLimit);
		}

		if (isset($result))
			return $result;
	}

	// Get Items count from RSS feed
	public static function getRSSItemsCount($source_params, $globalLimit)
	{
		$file_or_url = $source_params['rss_file_or_url'];
		$file_or_url = self::resolveFile($file_or_url);

		if (!($temp = simplexml_load_file($file_or_url)))
      return 0;

		$count = count($temp->channel->item);

		if ($count > $globalLimit)
		{
			$count = $globalLimit;
		}

		return $count;
	}

	// Get Items from RSS feed
	public static function getRSSItems($source_params, $startLimit, $pageLimit, $globalLimit)
	{
		$file_or_url = $source_params['rss_file_or_url'];
		$file_or_url = self::resolveFile($file_or_url);

		if (!($temp = simplexml_load_file($file_or_url)))
			return;

		$items = array();

		foreach ($temp->channel->item as $item)
    {
			$rss_item				= new stdClass();
			$rss_item->title 		= (string) $item->title;
			$rss_item->link  		= (string) $item->link;
			$rss_item->description	= (string) $item->description;
			$rss_item->author		= (string) $item->author;
			$rss_item->category		= (string) $item->category;
			$temp_date 				= (string) $item->pubDate;
			$rss_item->created  	= date('Y-m-d H:i:s', strtotime($temp_date));
			$rss_item->image		= (string) $item->thumbnail;

			$items[] = $rss_item;
		}

		// Set the list start limit
		$app = Factory::getApplication();

		$page = $app->input->get('page', false, 'INT');

		if (!$page || $page == 1)
		{
			$limit	= $startLimit;
			$start = 0;
		}
		else
		{
			$start_limit = $startLimit;
			$limit = $pageLimit;
			$start = $start_limit + (($page - 2) * $limit);

			$view = $app->input->get('view', '', 'WORD');
			$pagination = $app->input->get('pagination', '', 'INT');

			if ($app->input->get('filters') == 'filters' && ($pagination == 1 || $pagination == '4'))
			{
				$start = 0;
				$limit = $start_limit + (($page - 1) * $limit);
			}

			if ($start < $globalLimit)
			{
				if ($start + $pageLimit >= $globalLimit)
				{
					$limit = $globalLimit - $start;
				}
			}
			else
			{
				$limit = 0;
			}
		}

		// Limit items according to pagination
		$items = array_slice($items, $start, $limit);

		return $items;
	}

	private static function resolveFile($file_or_url)
	{
    if (!preg_match('|^https?:|', $file_or_url))
		{
      $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/'. $file_or_url;
		}
		else
		{
      $feed_uri = $file_or_url;
		}

    return $feed_uri;
  }
}
