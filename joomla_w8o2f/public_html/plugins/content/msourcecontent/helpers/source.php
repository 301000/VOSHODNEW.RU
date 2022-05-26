<?php
/**
* @title		Minitek Source Content
* @copyright	Copyright (C) 2011-2022 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Access\Access;
use Joomla\Utilities\ArrayHelper;
use Joomla\Component\Content\Site\Model\ArticlesModel;
use Joomla\CMS\Categories\Categories;
use Joomla\String\StringHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Helper\TagsHelper;

class MSourceContentSource
{
	public function getStaticCategories($source_params)
	{
		$cat_array = array();
		$filtering_type = $source_params['ja_category_filtering_type'];
		$options = array();
		$options['countItems'] = true;
		$categories = Categories::getInstance('Content', $options);

		if (isset($source_params['ja_catid']))
		{
			$cat_ids = $source_params['ja_catid'];

			// Include categories
			if ($filtering_type)
			{
				foreach ($cat_ids as $id)
				{
					$category = $categories->get($id);

					$cat_array[$id] = array(
						'id' => $category->id,
						'title' => $category->title,
						'date' => $category->created_time,
						'ordering' => $category->rgt
					);
				}
			}
			// Exclude categories
			else 
			{
				$root = $categories->get('root');
				$items = $root->getChildren(true);

				if ($items)
				{
					foreach ($items as $category)
					{
						if (!in_array($category->id, $cat_ids) && $category->numitems)
						{
							$cat_array[$category->id] = array(
								'id' => $category->id,
								'title' => $category->title,
								'date' => $category->created_time,
								'ordering' => $category->rgt
							);
						}
					}
				}
			}

			return $cat_array;
		}
		else 
		{
			$root = $categories->get('root');
			$items = $root->getChildren(true);

			if ($items)
			{
				foreach ($items as $category)
				{
					if ($category->numitems)
					{
						$cat_array[$category->id] = array(
							'id' => $category->id,
							'title' => $category->title,
							'date' => $category->created_time,
							'ordering' => $category->rgt
						);
					}
				}
			}
		}

		return $cat_array;
	}

	public function getStaticTags($source_params)
	{
		$tag_array = array();
		$filtering_type = $source_params['ja_tag_filtering_type'];
		$tagsHelper = new TagsHelper;
		$tag = Table::getInstance('Tag', 'TagsTable');

		if (isset($source_params['ja_tag_id']))
		{
			$tag_ids = $source_params['ja_tag_id'];

			// Include tags
			if ($filtering_type)
			{
				foreach ($tag_ids as $id)
				{
					$tag->load($id, true);
					
					$tag_array[$id] = array(
						'id' => $tag->id,
						'title' => $tag->title,
						'date' => $tag->created_time,
						'ordering' => $tag->rgt
					);
				}
			}
			// Exclude tags
			else 
			{
				$tagsTree = $tagsHelper->getTagTreeArray(1);

				if ($tagsTree)
				{
					foreach ($tagsTree as $id)
					{
						if (!in_array($id, $tag_ids) && $id != 1)
						{
							$tag->load($id, true);

							$tag_array[$id] = array(
								'id' => $tag->id,
								'title' => $tag->title,
								'date' => $tag->created_time,
								'ordering' => $tag->rgt
							);
						}
					}
				}
			}
		}
		else 
		{
			$tagsTree = $tagsHelper->getTagTreeArray(1);

			if ($tagsTree)
			{
				foreach ($tagsTree as $id)
				{
					if ($id != 1)
					{
						$tag->load($id, true);

						$tag_array[$id] = array(
							'id' => $tag->id,
							'title' => $tag->title,
							'date' => $tag->created_time,
							'ordering' => $tag->rgt
						);
					}
				}
			}
		}

		return $tag_array;
	}

	public function getStaticDates($source_params)
	{
		$mode = $source_params['joomla_mode'];

		if ($mode == 'ja')
		{
			$start_field = 'ja_start_date_range';
			$end_field = 'ja_end_date_range';
		}
		elseif ($mode == 'jc')
		{
			$start_field = 'jc_start_date_range';
			$end_field = 'jc_end_date_range';
		}

		$date_array = array();

		if (isset($source_params[$start_field]) && isset($source_params[$end_field]))
		{
			$start = (new Date($source_params[$start_field]))->modify('first day of this month');
			$end = (new Date($source_params[$end_field]))->modify('first day of this month');
			$interval = DateInterval::createFromDateString('1 month');
			$period = new DatePeriod($start, $interval, $end);

			foreach ($period as $dt) 
			{
				$date_array[] = $dt->format("Y-m");
			}
		}

		return $date_array;
	}

	// Convert data source ordering to sorting ordering
	public function getItemsOrdering($source_params)
	{
		$ordering = 'title';

		if ($source_params['joomla_mode'] == 'ja')
		{
			$ordering = $source_params['ja_article_ordering'];

			if (($pos = strpos($ordering, '.')) !== FALSE) 
			{
				if ($ordering == 'fp.ordering')
					$ordering = 'fordering';
				else
					$ordering = substr($ordering, strrpos($ordering, '.') + 1);
			}

			if ($ordering == 'created')
				$ordering = 'date';
			else if ($ordering == 'publish_up')
				$ordering = 'start';
			else if ($ordering == 'publish_down')
				$ordering = 'finish';
		}
		else if ($source_params['joomla_mode'] == 'jc')
		{
			$ordering = $source_params['jc_ordering'];

			if ($ordering == 'alpha')
				$ordering = 'title';
		}

		return $ordering;
	}

	// Convert sorting ordering to data source ordering
	public static function convertOrdering($mode, $ordering)
	{
		if ($mode == 'ja')
		{
			if ($ordering == 'date')
				$ordering = 'created';
		}
		else if ($mode == 'jc')
		{
			if ($ordering == 'alpha')
				$ordering = 'title';

			if ($ordering == 'date')
				$ordering = 'created_time';
		}

		return $ordering;
	}

	public function getItemsDirection($source_params)
	{
		$direction = 'DESC';

		if ($source_params['joomla_mode'] == 'ja')
			$direction = $source_params['ja_article_ordering_direction'];
		else if ($source_params['joomla_mode'] == 'jc')
		{
			if (array_key_exists('jc_ordering_direction', $source_params))
				$direction = $source_params['jc_ordering_direction'];
		}

		return $direction;
	}

	public function getItems($isCount, $source_params, $startLimit, $pageLimit, $globalLimit, $filters = false)
	{
		$joomla_mode = $source_params['joomla_mode'];

		// Joomla Articles
		if ($joomla_mode == 'ja')
		{
			if ($isCount)
				$result = self::getJoomlaArticles($isCount, $source_params, $startLimit, false, false, $filters);
			else
				$result = self::getJoomlaArticles($isCount, $source_params, $startLimit, $pageLimit, $globalLimit, $filters);
		}

		// Joomla Categories
		if ($joomla_mode == 'jc')
		{
			if ($isCount)
				$result = self::getJoomlaCategories($isCount, $source_params, $startLimit, false, false, $filters);
			else
				$result = self::getJoomlaCategories($isCount, $source_params, $startLimit, $pageLimit, $globalLimit, $filters);
		}

		if (isset($result))
			return $result;
	}

	// Get Joomla Categories
	public static function getJoomlaCategories($isCount, $source_params, $startLimit, $pageLimit, $globalLimit, $filters)
	{
		$db = Factory::getDbo();
		$app = Factory::getApplication();
		$user = Factory::getUser();

		// Set the list start limit
		$page = $app->input->get('page', false, 'INT');

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
			$view = $app->input->get('view', '', 'WORD');
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

		$query = $db->getQuery(true);

		$query->select('*');
		$query->from('#__categories AS a');

		// Filter by extension (com_content)
		$query->where('a.extension = ' . $db->quote('com_content'));

		// Filter by published state
		$query->where('a.published = ' . $db->quote(1));

		// Filter by access level
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$query->where('a.access IN (' . $groups . ')');

		// Filter by categories
		$category_filter = !empty($filters['category']) && $filters['category'][0];
		
		if ($category_filter)
			$catids = $filters['category'];
		else 
		{
			if (array_key_exists('jc_catid', $source_params))
				$catids = $source_params['jc_catid'];
			else
				$catids = array();
		}

		if (count($catids) > 0)
		{
			if ($source_params['jc_include_categories_children'])
			{
				// Get an instance of the generic categories model
				$categories = Categories::getInstance('Content', array());
				$children = array();

				foreach ($catids as $id)
				{
					$recursive = true;
					$category = $categories->get($id);
					$items = $category->getChildren($recursive);

					if ($items)
					{
						foreach ($items as $child)
						{
							$children[] = $child->id;
						}
					}
				}

				$catids = array_unique(array_merge($catids, $children));
			}

			$catids = ArrayHelper::toInteger($catids);
			$categoryIds = implode(',', $catids);
			$categoryIds = trim($categoryIds, ',');

			if (!empty($categoryIds))
			{
				$query->where('a.id IN (' . $categoryIds . ')');
			}
		}

		// Filter by tags
		$tag_filter = !empty($filters['tag']) && $filters['tag'][0];
		
		if ($tag_filter)
			$tagids = $filters['tag'];
		else 
		{
			if (array_key_exists('jc_tagid', $source_params))
				$tagids = $source_params['jc_tagid'];
			else
				$tagids = array();
		}

		if (count($tagids) > 0)
		{
			$tagids = ArrayHelper::toInteger($tagids);
			$tagIds = implode(',', $tagids);
			$tagIds = trim($tagIds, ',');

			if (!empty($tagIds))
			{
				$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_content.category')
				);
				$query->where('tagmap.tag_id IN (' . $tagIds . ')');
			}
		}

		// Filter by date range
		$date_filter = isset($filters['date']) && $filters['date'] ? $filters['date'] : false;

		if ($date_filter)
		{
			$date_filter = (new Date($date_filter))->modify('first day of this month');
			$date_filter = $date_filter->format("Y-m-d");

			$query->where(
				'(a.created_time >= ' . $db->quote($date_filter) . ' AND a.created_time < ' . $db->quote($date_filter) . ' + INTERVAL 1 MONTH)'
			);
		}
		else 
		{
			if (isset($source_params['jc_date_range']) && $source_params['jc_date_range']
				&& ($source_params['jc_start_date_range'] || $source_params['jc_end_date_range'])
			)
			{
				$start_where = '';
				$end_where = '';
				$date_where = '(';

				// Start date 
				if ($source_params['jc_start_date_range'])
				{
					$start_where = 'a.created_time >= ' . $db->quote($source_params['jc_start_date_range']);
					$date_where .= $start_where;
				}
				
				// AND
				if ($source_params['jc_start_date_range'] && $source_params['jc_end_date_range'])
					$date_where .= ' AND ';

				// End date 
				if ($source_params['jc_end_date_range'])
				{
					$end_where = 'a.created_time <= ' . $db->quote($source_params['jc_end_date_range']);
					$date_where .= $end_where;
				}

				$date_where .= ')';
				
				if ($start_where || $end_where)
					$query->where($date_where);
			}
		}

		// Group results
		$query->group('a.id');

		// Set ordering
		$ordering_dir = isset($filters['direction']) && $filters['direction'] ? $filters['direction'] : $source_params['jc_ordering_direction'];
		$ordering = isset($filters['ordering']) && $filters['ordering'] 
			? 'a.' . self::convertOrdering('jc', $filters['ordering']) 
			: 'a.' . self::convertOrdering('jc', $source_params['jc_ordering']) . ' ' . $ordering_dir.', a.id ' . $ordering_dir.'';
		$query->order($ordering);

		$db->setQuery($query, $start + (int) $source_params['joomla_offset'], $limit);

		if (!$isCount)
		{
			// Load the results as a list of stdClass objects (see later for more options on retrieving data).
			$items = $db->loadObjectList();

			return $items;
		}
		else
		{
			$db->execute();
			$itemCount = $db->getNumRows();

			return $itemCount;
		}
	}

	// Get Joomla Articles
	public static function getJoomlaArticles($isCount, $source_params, $startLimit, $pageLimit, $globalLimit, $filters)
	{
		$db = Factory::getDbo();
		$user = Factory::getUser();
		$model = new ArticlesModel(array('ignore_request' => true));
		$app = Factory::getApplication();
		$offset = isset($source_params['joomla_offset']) ? $source_params['joomla_offset'] : 0;
		
		// Set the list start limit
		$page = $app->input->get('page', false, 'INT');

		if (!$page || $page == 1) 
		{
			$articles_limit = $startLimit;
			$start = 0;
		} 
		else 
		{
			$start_limit = $startLimit;
			$articles_limit = $pageLimit;
			$start = $start_limit + (($page - 2) * $articles_limit);
			$view = $app->input->get('view', '', 'WORD');
			$pagination = $app->input->get('pagination', '', 'INT');

			// Pagination: Append / Infinite
			if ($app->input->get('filters') == 'filters' && ($pagination == 1 || $pagination == '4')) 
			{
				$start = 0;
				$articles_limit = $start_limit + (($page - 1) * $articles_limit);
			}

			if ($start < $globalLimit) 
			{
				if ($start + $pageLimit >= $globalLimit) 
					$articles_limit = $globalLimit - $start;
			} 
			else 
				$articles_limit = 0;
		}

		// Set the filters based on the module params
		$model->setState('list.start', $start + (int) $offset);
		$model->setState('list.limit', $articles_limit);
		$model->setState('filter.published', 1);

		// Access filter
		$access = !ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels($user->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$category_filter = !empty($filters['category']) && $filters['category'][0];
		
		if ($category_filter)
			$catids = $filters['category'];
		else 
		{
			if (array_key_exists('ja_catid', $source_params))
				$catids = $source_params['ja_catid'];
			else
				$catids = '';
		}
		
		$model->setState('filter.category_id.include', (bool) $source_params['ja_category_filtering_type']);

		if ($catids)
		{
			if ($source_params['ja_show_child_category_articles'] && (int) $source_params['ja_levels'] > 0)
			{
				// Get an instance of the generic categories model
				$categories = Categories::getInstance('Content', array());
				$levels = $source_params['ja_levels'] ? $source_params['ja_levels'] : 9999;
				$additional_catids = array();

				foreach ($catids as $catid)
				{
					$recursive = true;
					$category = $categories->get($catid);
					$items = $category->getChildren($recursive);

					if ($items)
					{
						foreach ($items as $child)
						{
							$condition = (($child->level - $category->level) <= $levels);

							if ($condition)
								$additional_catids[] = $child->id;
						}
					}
				}

				$catids = array_unique(array_merge($catids, $additional_catids));
			}
			
			$model->setState('filter.category_id', $catids);
		}

		// Tag filter
		$tag_filter = !empty($filters['tag']) && $filters['tag'][0];
		
		if ($tag_filter)
			$tagIds = $filters['tag'];
		else 
		{
			if (array_key_exists('ja_tag_id', $source_params))
				$tagIds = $source_params['ja_tag_id'];
			else
				$tagIds = '';
		}

		if ($tagIds)
		{
			$includeTagChildren = $source_params['ja_tag_include_children'];

			if ($includeTagChildren)
			{
				$tagTreeArray = array();
				$tag_helper = new \JHelperTags;

				foreach ($tagIds as $tag)
				{
					$tag_helper->getTagTreeArray($tag, $tagTreeArray);
				}

				$tagIds = array_unique(array_merge($tagIds, $tagTreeArray));
			}

			$tagIds = implode(',', $tagIds);
			$model->setState('filter.tag_id', $tagIds);
			$model->setState('filter.tag_id.include', (bool) $source_params['ja_tag_filtering_type']);
		}

		// Ordering
		$model->setState(
			'list.ordering', 
			isset($filters['ordering']) && $filters['ordering'] 
				? self::convertOrdering('ja', $filters['ordering']) 
				: $source_params['ja_article_ordering']
		);
		$model->setState(
			'list.direction', 
			isset($filters['direction']) && $filters['direction'] 
				? $filters['direction'] 
				: $source_params['ja_article_ordering_direction']
		);

		// New Parameters
		$model->setState('filter.featured', $source_params['ja_show_front']);

		if (array_key_exists('ja_created_by', $source_params))
			$model->setState('filter.author_id', $source_params['ja_created_by']);

		if (isset($source_params['ja_author_filtering_type']))
			$model->setState('filter.author_id.include', $source_params['ja_author_filtering_type']);

		$excluded_articles = false;

		if (array_key_exists('ja_excluded_articles', $source_params))
			$excluded_articles = $source_params['ja_excluded_articles'];

		if ($excluded_articles)
		{
			$excluded_articles = explode("\r\n", $excluded_articles);
			$model->setState('filter.article_id', $excluded_articles);
			$model->setState('filter.article_id.include', false); // Exclude
		}

		$date_filtering = $source_params['ja_date_filtering'];

		if ($date_filtering !== 'off')
		{
			$model->setState('filter.date_filtering', $date_filtering);
			$model->setState('filter.date_field', $source_params['ja_date_field']);
			$model->setState('filter.start_date_range', $source_params['ja_start_date_range']);
			$model->setState('filter.end_date_range', $source_params['ja_end_date_range']);
			$model->setState('filter.relative_date', $source_params['ja_relative_date']);
		}

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$model->getState(
				'list.select',
				'a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
					'a.checked_out, a.checked_out_time, a.ordering, fp.ordering as fordering, ' .
					'a.catid, a.created, a.created_by, a.created_by_alias, ' .
					// Use created if modified is 0
					'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
					'a.modified_by, uam.name as modified_by_name,' .
					// Use created if publish_up is 0
					'CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
					'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
					'a.hits, a.featured,' . ' ' . $query->length('a.fulltext') . ' AS readmore'
			)
		);

		// Process an Archived Article layout
		if ($model->getState('filter.published') == 2)
		{
			// If badcats is not null, this means that the article is inside an archived category
			// In this case, the state is set to 2 to indicate Archived (even if the article state is Published)
			$query->select($model->getState('list.select', 'CASE WHEN badcats.id is null THEN a.state ELSE 2 END AS state'));
		}
		else
		{
			/*
			Process non-archived layout
			If badcats is not null, this means that the article is inside an unpublished category
			In this case, the state is set to 0 to indicate Unpublished (even if the article state is Published)
			*/
			$query->select($model->getState('list.select', 'CASE WHEN badcats.id is not null THEN 0 ELSE a.state END AS state'));
		}

		$query->from('#__content AS a');

		// Join over the frontpage articles.
		$query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');

		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, ' .
			'c.alias AS category_alias, c.rgt as category_order, c.created_time as category_date')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author and modified_by names.
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author")
			->select("ua.email AS author_email")
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by')
			->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
			->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

		// Join to check for category published state in parent categories up the tree
		$query->select('c.published, CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
		$subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_content');

		if ($model->getState('filter.published') == 2)
		{
			// Find any up-path categories that are archived
			// If any up-path categories are archived, include all children in archived layout
			$subquery .= ' AND parent.published = 2 GROUP BY cat.id ';

			// Set effective state to archived if up-path category is archived
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 2 END';
		}
		else
		{
			// Find any up-path categories that are not published
			// If all categories are published, badcats.id will be null, and we just use the article state
			$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';

			// Select state to unpublished if up-path category is unpublished
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.state ELSE 0 END';
		}

		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

		// Filter by access level.
		if ($access = $model->getState('filter.access'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')')
				->where('c.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $model->getState('filter.published');

		if (is_numeric($published))
		{
			// Use article state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			ArrayHelper::toInteger($published);
			$published = implode(',', $published);

			// Use article state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' IN (' . $published . ')');
		}

		// Filter by featured state
		$featured = $model->getState('filter.featured');

		switch ($featured)
		{
			case 'hide':
				$query->where('a.featured = 0');
				break;

			case 'only':
				$query->where('a.featured = 1');
				break;

			case 'show':
			default:
				// Normally we do not discriminate
				// between featured/unfeatured items.
				break;
		}

		// Filter by a single or group of articles.
		$articleId = $model->getState('filter.article_id');

		if (is_numeric($articleId))
		{
			$type = $model->getState('filter.article_id.include', true) ? '= ' : '<> ';
			$query->where('a.id ' . $type . (int) $articleId);
		}
		elseif (is_array($articleId))
		{
			ArrayHelper::toInteger($articleId);
			$articleId = implode(',', $articleId);
			$type = $model->getState('filter.article_id.include', true) ? 'IN' : 'NOT IN';
			$query->where('a.id ' . $type . ' (' . $articleId . ')');
		}

		// Filter by tags
		if ($tagIds)
		{
			if ($tag_filter)
				$tagtype = 'IN';
			else
				$tagtype = $model->getState('filter.tag_id.include', true) ? 'IN' : 'NOT IN';

			$includeChildren = $source_params['ja_tag_include_children'];

			$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
				. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
				. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_content.article')
			);
			$query->where('tagmap.tag_id ' . $tagtype . ' (' . $tagIds . ')');
		}

		// Filter by a single or group of categories
		$categoryId = $model->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

			// Add subcategory check
			$includeSubcategories = $model->getState('filter.subcategories', false);
			$categoryEquals = 'a.catid ' . $type . (int) $categoryId;

			if ($includeSubcategories)
			{
				$levels = (int) $model->getState('filter.max_category_levels', '1');

				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true)
					->select('sub.id')
					->from('#__categories as sub')
					->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
					->where('this.id = ' . (int) $categoryId);

				if ($levels >= 0)
					$subQuery->where('sub.level <= this.level + ' . $levels);

				// Add the subquery to the main query
				$query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
			}
			else
				$query->where($categoryEquals);
		}
		elseif (is_array($categoryId) && (count($categoryId) > 0))
		{
			ArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$categoryId = trim($categoryId, ',');

			if (!empty($categoryId))
			{
				if ($category_filter)
					$type = 'IN';
				else 
					$type = $model->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';

				$query->where('a.catid ' . $type . ' (' . $categoryId . ')');
			}
		}

		// Filter by author
		$authorId = $model->getState('filter.author_id');
		$authorWhere = '';

		if (is_numeric($authorId) && $authorId > 0)
		{
			$type = $model->getState('filter.author_id.include', true) ? '= ' : '<> ';
			$authorWhere = 'a.created_by ' . $type . (int) $authorId;
		}
		elseif (is_array($authorId))
		{
			ArrayHelper::toInteger($authorId);
			$authorId = implode(',', $authorId);

			if ($authorId)
			{
				$type = $model->getState('filter.author_id.include', true) ? 'IN' : 'NOT IN';
				$authorWhere = 'a.created_by ' . $type . ' (' . $authorId . ')';
			}
		}

		// Filter by author alias
		$authorAlias = $model->getState('filter.author_alias');
		$authorAliasWhere = '';

		if (is_string($authorAlias) && $authorAlias)
		{
			$type = $model->getState('filter.author_alias.include', true) ? '= ' : '<> ';
			$authorAliasWhere = 'a.created_by_alias ' . $type . $db->quote($authorAlias);
		}
		elseif (is_array($authorAlias))
		{
			$first = current($authorAlias);

			if (!empty($first))
			{
				ArrayHelper::toString($authorAlias);

				foreach ($authorAlias as $key => $alias)
				{
					$authorAlias[$key] = $db->quote($alias);
				}

				$authorAlias = implode(',', $authorAlias);

				if ($authorAlias)
				{
					$type = $model->getState('filter.author_alias.include', true) ? 'IN' : 'NOT IN';
					$authorAliasWhere = 'a.created_by_alias ' . $type . ' (' . $authorAlias . ')';
				}
			}
		}

		if (!empty($authorWhere) && !empty($authorAliasWhere))
			$query->where('(' . $authorWhere . ' OR ' . $authorAliasWhere . ')');
		elseif (empty($authorWhere) && empty($authorAliasWhere))
		{
			// If both are empty we don't want to add to the query
		}
		else
		{
			// One of these is empty, the other is not so we just add both
			$query->where($authorWhere . $authorAliasWhere);
		}

		// Define null and now dates
		$nullDate = $db->quote($db->getNullDate());
		$nowDate = $db->quote(Factory::getDate()->toSql());

		// Filter by start and end dates.
		if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
		{
			$query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')')
				->where('(a.publish_down = '.$nullDate.' or a.publish_down IS NULL OR a.publish_down >= '.$nowDate.')');
		}

		// Date filters
		$date_filter = isset($filters['date']) && $filters['date'] ? $filters['date'] : false;

		if ($date_filter)
		{
			$dateField = $model->getState('filter.date_field', 'a.created');
			$date_filter = (new Date($date_filter))->modify('first day of this month');
			$date_filter = $date_filter->format("Y-m-d");

			$query->where(
				'(' . $dateField . ' >= ' . $db->quote($date_filter) . ' AND ' . $dateField .
					' < ' . $db->quote($date_filter) . ' + INTERVAL 1 MONTH)'
			);
		}
		else 
		{
			// Filter by Date Range or Relative Date
			$dateFiltering = $model->getState('filter.date_filtering', 'off');
			$dateField = $model->getState('filter.date_field', 'a.created');

			switch ($dateFiltering)
			{
				case 'range':
					$startDateRange = $db->quote($model->getState('filter.start_date_range', $nullDate));
					$endDateRange = $db->quote($model->getState('filter.end_date_range', $nullDate));
					$query->where(
						'(' . $dateField . ' >= ' . $startDateRange . ' AND ' . $dateField .
							' <= ' . $endDateRange . ')'
					);
					break;

				case 'relative':
					$relativeDate = (int) $model->getState('filter.relative_date', 0);
					$query->where(
						$dateField . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' .
							$relativeDate . ' DAY)'
					);
					break;

				case 'off':
				default:
					break;
			}
		}

		// Process the filter for list views with user-entered filters
		$params = $model->getState('params');

		if ((is_object($params)) && ($params->get('filter_field') != 'hide') && ($filter = $model->getState('list.filter')))
		{
			// Clean filter variable
			$filter = StringHelper::strtolower($filter);
			$hitsFilter = (int) $filter;
			$filter = $db->quote('%' . $db->escape($filter, true) . '%', false);

			switch ($params->get('filter_field'))
			{
				case 'author':
					$query->where(
						'LOWER( CASE WHEN a.created_by_alias > ' . $db->quote(' ') .
							' THEN a.created_by_alias ELSE ua.name END ) LIKE ' . $filter . ' '
					);
					break;

				case 'hits':
					$query->where('a.hits >= ' . $hitsFilter . ' ');
					break;

				case 'title':
				default:
					// Default to 'title' if parameter is not valid
					$query->where('LOWER( a.title ) LIKE ' . $filter);
					break;
			}
		}

		// Filter by language
		if ($model->getState('filter.language'))
			$query->where('a.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

		// Group by
		$groupBy = array(
			'a.id',
			'a.title',
			'a.alias',
			'a.introtext',
			'a.fulltext',
			'a.checked_out',
			'a.checked_out_time',
			'a.ordering',
			'fp.ordering',
			'a.catid',
			'a.created',
			'a.created_by',
			'a.created_by_alias',
			'a.modified',
			'a.modified_by',
			'uam.name',
			'a.publish_up',
			'a.publish_down',
			'a.images',
			'a.urls',
			'a.attribs',
			'a.metadata',
			'a.metakey',
			'a.metadesc',
			'a.access',
			'a.state',
			'a.hits',
			'a.featured',
			'badcats.id',
			'c.title',
			'c.path',
			'c.access',
			'c.alias',
			'c.rgt',
			'ua.name',
			'ua.email',
			'parent.title',
			'parent.id',
			'parent.path',
			'parent.alias',
			'c.published',
		);

		$query->group($db->quoteName($groupBy));

		// Add the list ordering clause.
		$ordering = '' . $model->getState('list.ordering', 'a.ordering') . ' ' . $model->getState('list.direction', 'ASC').', a.id ' . $model->getState('list.direction', 'ASC').'';
		$query->order($ordering);

		$db->setQuery($query, $model->setState('list.start'), $model->setState('list.limit'));

		if (!$isCount)
		{
			// Load the results as a list of stdClass objects (see later for more options on retrieving data).
			$items = $db->loadObjectList();

			return $items;
		}
		else
		{
			$db->execute();
			$itemCount = $db->getNumRows();

			return $itemCount;
		}
	}
}