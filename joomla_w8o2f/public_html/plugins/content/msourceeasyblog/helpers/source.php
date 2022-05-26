<?php
/**
* @title		Minitek Source EasyBlog
* @copyright	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Access\Access;
use Joomla\Utilities\ArrayHelper;

require_once(JPATH_ADMINISTRATOR.'/components/com_easyblog/includes/easyblog.php');
Factory::getLanguage()->load('com_easyblog', JPATH_ROOT);

class MSourceEasyBlogSource
{
	// public function getCategoriesNames($cat_ids)
	// {
	// 	$cat_names = array();

	// 	return $cat_names;
	// }

	// public function getStaticCategories($source_params)
	// {
	// 	if (array_key_exists('eb_catids', $source_params))
	// 	{
	// 		$cat_ids = $source_params['eb_catids'];

	// 		return $cat_ids;
	// 	}

	// 	return false;
	// }

	// public function getTagsNames($tag_ids)
	// {
	// 	$tag_names = array();

	// 	return $tag_names;
	// }

	// public function getStaticTags($source_params)
	// {
	// 	if (array_key_exists('eb_tagids', $source_params))
	// 	{
	// 		$tag_ids = $source_params['eb_tagids'];

	// 		return $tag_ids;
	// 	}

	// 	return false;
	// }

	public function getItemsOrdering($source_params)
	{
		$ordering = $source_params['eb_sortby'];

		if ($ordering == 'latest')
			$ordering = 'date';
		else if ($ordering == 'published')
			$ordering = 'start';
		else if ($ordering == 'popular')
			$ordering = 'hits';
		else if ($ordering == 'alphabet')
			$ordering = 'title';

		return $ordering;
	}

	public function getItemsDirection($source_params)
	{
		$direction = $source_params['eb_sortDirection'];

		return $direction;
	}

	public function getItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		if ($isCount)
		{
			$pageLimit = false;
			$globalLimit = false;
		}

		$result = self::getEasyBlogArticles($isCount, $source_params, $startLimit, $pageLimit, $globalLimit);
		
		return $result;
	}

	// Get EasyBlog Articles
	public static function getEasyBlogArticles($isCount, $source_params, $startLimit, $pageLimit, $globalLimit)
	{
		$app = Factory::getApplication();

		// Set the list start limit
		$page = $app->input->get('page', '', 'INT');

		if (!$page || $page == 1) 
		{
			$limit = $startLimit;
			$start = 0;
		} 
		else 
		{
			$start_limit = $startLimit;
			$limit = $pageLimit;
			$start = $start_limit + (($page - 2) * $limit);

			$pagination = $app->input->get('pagination', '', 'INT');

			// Pagination: Append / Infinite
			if ($app->input->get('filters') == 'filters' && ($pagination == 1 || $pagination == '4')) 
			{
				$start = 0;
				$limit = $start_limit + (($page - 1) * $limit);
			}

			if ($start < $globalLimit) 
			{
				if ($start + $pageLimit >= $globalLimit) 
					$limit = $globalLimit - $start;
			} 
			else 
				$limit = 0;
		}

		$db = EB::db();
		$my	= Factory::getUser();
		$config	= EB::config();

		// Categories
		$category_filtering_type = $source_params['eb_category_filtering_type'] ? 'IN' : 'NOT IN';
		$categories_get_children = $source_params['eb_getChildren'];

		if (array_key_exists('eb_catids', $source_params))
			$categories	= EB::getCategoryInclusion( $source_params['eb_catids'] );
		else
			$categories	= false;

		$catIds = array();

		if (!empty($categories))
		{
			if (!is_array($categories))
				$categories	= array($categories);

			foreach ($categories as $item)
			{
				$category = new stdClass();
				$category->id = trim( $item );
				$catIds[] = $category->id;
			
				if ($categories_get_children)
				{
					$category->childs = null;
					EB::buildNestedCategories($category->id, $category, false, true );
					EB::accessNestedCategoriesId($category, $catIds);
				}
			}

			$catIds = array_unique ($catIds);
		}

		$cid = $catIds;

		if ($source_params['eb_category_filtering_type'])
		{
			ArrayHelper::toInteger($cid);
			$categoryIds = implode(',', $cid);
		} 
		else 
		{
			$categoryIds = array_merge( $cid, EB::getPrivateCategories() );
			$categoryIds = implode(',', $categoryIds);
		}

		// Tags
		$tag_filtering_type = $source_params['eb_tag_filtering_type'] ? 'IN' : 'NOT IN';

		if (array_key_exists('eb_tagids', $source_params))
		{
			$tagid = isset($source_params['eb_tagids']) ? $source_params['eb_tagids'] : array();
			ArrayHelper::toInteger($tagid);
			$tagIds = implode(',', $tagid);
		}
		else
			$tagIds = false;

		// Teams
		$team_filtering_type = $source_params['eb_team_filtering_type'] ? 'IN' : 'NOT IN';
		$teamBlogIds_temp = $source_params['eb_teamids']; // comma separated string
		
		if (preg_match('/^[0-9,]+$/i', $teamBlogIds_temp)) 
		{
		  $teamBlogIds = $teamBlogIds_temp;
		  $teamBlogIds = trim($teamBlogIds, ',');
		} 
		else 
		  $teamBlogIds = false;
		 
		// Authors
		$author_filtering_type = $source_params['eb_bloggerlisttype'] ? 'IN' : 'NOT IN';
		$bloggerIds_temp = $source_params['eb_bloggerlist']; // comma separated string

		if (preg_match('/^[0-9,]+$/i', $bloggerIds_temp)) 
		  $bloggerIds = $bloggerIds_temp;
		else 
		  $bloggerIds = false;
		 
		// Exclude items
		$excludeBlogs_temp = $source_params['eb_exclude_items']; // comma separated string

		if (preg_match('/^[0-9,]+$/i', $excludeBlogs_temp)) 
		{
		  $excludeBlogs = $excludeBlogs_temp;
		  $excludeBlogs = trim($excludeBlogs, ',');
		} 
		else 
		  $excludeBlogs = false;

		// Query variables
		$query = '';
		$queryWhere	= '';
		$queryOrder	= '';

		// Other variables
		$sort = $source_params['eb_sortby'];
		$sortDirection = $source_params['eb_sortDirection'];
		$frontpage = (int) $source_params['eb_showFrontpage'];
		$protected = true;
		$limitType = 'listlength';
		$usefeatured = (int) $source_params['eb_usefeatured'];

		//////////////
		// Start query
		//////////////

		// Show published
		$queryWhere	.= ' WHERE (a.`published` = 1) ';

		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(Factory::getDate()->toSql());

		$queryWhere	.= ' AND (a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.') ';
		$queryWhere	.= ' AND (a.publish_down = '.$nullDate.' or a.publish_down IS NULL OR a.publish_down >= '.$nowDate.')';

		$queryWhere	.= ' AND a.`state` = 0';

		// Categories
		if (!empty($categoryIds))
			$queryWhere	.= ' AND c.`category_id` '.$category_filtering_type.' ('.$categoryIds.')';

		// Tags
		if (!empty($tagIds))
			$queryWhere .= ' AND t.`tag_id` '.$tag_filtering_type.' ('.$tagIds.')';

		// Team blogs
		if (!empty($teamBlogIds))
			$queryWhere .= ' AND u.`team_id` '.$team_filtering_type.' ('.$teamBlogIds.')';

		// Authors
		if (!empty($bloggerIds))
			$queryWhere .= ' AND a.`created_by` '.$author_filtering_type.' ('.$bloggerIds.')';

		// Exclude Posts
		if (!empty($excludeBlogs))
			$queryWhere .= ' AND a.`id` NOT IN ('.$excludeBlogs.') ';

		// Show Frontpage posts
		if (!$frontpage)
			$queryWhere	.= ' AND a.`frontpage` = '. $db->Quote('0');
		else if( $frontpage == 2 )
			$queryWhere	.= ' AND a.`frontpage` = '. $db->Quote('1');

		// Filter by language
		if (EB::getJoomlaVersion() >= '1.6') 
		{
			// @rule: When language filter is enabled, we need to detect the appropriate contents
			$filterLanguage = Factory::getApplication()->getLanguageFilter();

			if ($filterLanguage)
			{
				$queryWhere	.= ' AND (';
				$queryWhere	.= ' a.`language`= '. $db->Quote( Factory::getLanguage()->getTag() );
				$queryWhere	.= ' OR a.`language`= '. $db->Quote( '' );
				$queryWhere	.= ' OR a.`language`= '. $db->Quote( '*' );
				$queryWhere	.= ' )';
			}
		}

		// Do not list out protected blog in rss
		if ($app->input->get('format', '') == 'feed')
		{
			if ($config->get('main_password_protect', true))
				$queryWhere	.= ' AND a.`blogpassword`="" ';
		}

		// Hide protected posts
		if ($protected == false)
			$queryWhere	.= ' AND a.`blogpassword` = ""';

		// Blog privacy setting
		if ($my->id == 0)
			$queryWhere .= ' AND a.`access` = '. $db->Quote(BLOG_PRIVACY_PUBLIC);

		// Get Posts table
		$query .= 'SELECT a.`id` AS key1, a.*, b.`id` as key2, b.`title` as `category`';

		if (!empty($teamBlogIds))
			$query .= ', u.`team_id` ';

		$query .= ' FROM `#__easyblog_post` AS a';

		// Join Categories
		$query .= ' LEFT JOIN `#__easyblog_category` AS b';
		$query .= ' ON a.category_id = b.id';

		// Join Featured
		if ($usefeatured)
		{
			$query .= ' INNER JOIN `#__easyblog_featured` AS f';
			$query .= ' ON a.`id` = f.`content_id` AND f.`type` = '. $db->Quote('post');
		}

		// Join Categories
		if (!empty($categoryIds))
			$query .= ' LEFT JOIN `#__easyblog_post_category` AS c ON a.`id` = c.`post_id`';

		// Join Tags
		if (!empty($tagIds))
			$query .= ' LEFT JOIN `#__easyblog_post_tag` AS t ON a.`id` = t.`post_id`';

		// Join Teams
		if (!empty($teamBlogIds))
			$query .= ' LEFT JOIN `#__easyblog_team_post` AS u ON a.id = u.post_id';

		// Get ordering
		$queryOrder .= ' ORDER BY ';

		switch ($sort)
		{
			case 'latest':
				$queryOrder	.= ' a.`created` '.$sortDirection.', a.`id` '.$sortDirection;
				break;
			case 'published':
				$queryOrder	.= ' a.`publish_up` '.$sortDirection.', a.`id` '.$sortDirection;
				break;
			case 'modified':
				$queryOrder	.= ' a.`modified` '.$sortDirection.', a.`id` '.$sortDirection;
				break;
			case 'popular':
				$queryOrder	.= ' a.`hits` '.$sortDirection.', a.`id` '.$sortDirection;
				break;
			case 'alphabet':
				$queryOrder	.= ' a.`title` '.$sortDirection.', a.`id` '.$sortDirection;
				break;
			case 'random':
				$queryOrder	.= ' RAND() ';
				break;
			default :
				break;
		}

		// Create query
		$query .= $queryWhere;
		$query .= ' GROUP BY a.id ';

		if (!empty($teamBlogIds))
			$query .= ', u.team_id ';

		$query .= $queryOrder;

		$offset = isset($source_params['eb_offset']) ? $source_params['eb_offset'] : 0;

		$query .= ' LIMIT '.($start + (int) $offset).', '.$limit.'';

		// Execute query
		$db->setQuery($query);

		if (!$isCount) 
		{
			$items = $db->loadObjectList();

			return $items;
		} 
		else 
		{
			$db->query();
			$result = $db->getNumRows();

			return $result;
		}
	}
}
