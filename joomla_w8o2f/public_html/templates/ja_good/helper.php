<?php
/**
 * ------------------------------------------------------------------------
 * JA Focus Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

if(version_compare(JVERSION, '4', 'ge')){
	class ContentHelperQuery extends \Joomla\Component\Content\Site\Helper\QueryHelper{};
}else{
	JLoader::register('ContentHelperQuery', JPATH_SITE . '/components/com_content/helpers/query.php');
}

class JATemplateHelper
{
	public static function getCustomFields($id, $context) {
		if ($context == 'article')
			$context = 'com_content.article';
		else if ($context == 'category')
			$context = 'com_content.categories';
		else if ($context == 'contact')
			$context = 'com_contact.contact';
		else if ($context == 'user')
			$context = 'com_users.user';
		$currentLanguage = JFactory::getLanguage();
		$currentTag = $currentLanguage->getTag();

		$sql = 'SELECT fv.value, fg.title AS gtitle, f.title AS ftitle, f.name
				FROM #__fields_values fv
				LEFT JOIN #__fields f ON fv.field_id = f.id
				LEFT JOIN #__fields_groups fg ON fg.id = f.group_id
				WHERE fv.item_id = '.$id.'
				AND f.context = "'.$context.'"
				AND f.language IN ("*", "'.$currentTag.'")
				AND f.access = 1
				';
			// echo $sql;
		$db = JFactory::getDbo();
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		$arr = array();
		foreach ($result AS $r) {
			$arr[$r->name] = $r->value;
		}

		return $arr;
	}

	/*Article Category*/
	public static function categoryGroup($idbase=array()) {
		if (empty($idbase)) return array();
		$IN = '';
		if (is_array($idbase))
			$IN = implode(',', $idbase);
		else
			$IN = $idbase;
		
		  $sql = '
			SELECT DISTINCT sub.*
			FROM #__categories AS sub
			INNER JOIN #__categories AS this ON sub.lft > this.lft AND sub.rgt < this.rgt
			WHERE this.id IN ('.$IN.')';
		$db = JFactory::getDbo();
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		$html = '<div class="category-menu"><ul>';
		$ct_showup = 6;
		$i=0;
		$show='';
		$hide='';
		foreach ($result AS $cat) {
			if ($cat->published!=1) continue;
			if ($cat->access!=1) continue;
			$cat->displayCategoryLink  = JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id));
			
			if (++$i>$ct_showup) {
				$hide .= '<li>
						<a href="'.$cat->displayCategoryLink.'">'.$cat->title.'</a>
					</li>';
			} else {
				$show .= '<li>
						<a href="'.$cat->displayCategoryLink.'">'.$cat->title.'</a>
					</li>';
			}
			
		}
		$html .= $show;
		if ($ct_showup<count($result)) {
			$html .= '<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">More <span class="fa fa-angle-down" aria-hidden="true"></span></a>
				<ul class="dropdown-menu">'.$hide.'</ul>
				</li>';
		}
		$html .= '</ul></div>';
		return $html;
	}

	/*** Custom Get List Article Content type ***/
  public static function getArticleContent($jatype='', $jawhere=array(), $jaorder=array(), $number=false) {
  	$mainframe = JFactory::getApplication();
  	$jinput = $mainframe->input;
  	$app = JFactory::getApplication('site');
	$mergedParams = $app->getParams();
	$menuParams = new \Joomla\Registry\Registry;

	if ($menu = $app->getMenu()->getActive())
	{
		$menuParams_tmp = version_compare(JVERSION,'4', 'ge') ? $menu->getParams() : $menu->params;
		$menuParams->loadString($menuParams_tmp);
	}

	$mergedParams = clone $menuParams;
	$mergedParams->merge($mergedParams);
	$itemid = $mainframe->input->get('id', 0, 'int') . ':' . $mainframe->input->get('Itemid', 0, 'int');
	// Set limit for query. If list, use parameter. If blog, add blog parameters for limit.
	if (($app->input->get('layout') == 'blog') || $mergedParams->get('layout_type') == 'blog')
	{
		$limit = $mergedParams->get('num_leading_articles') + $mergedParams->get('num_intro_articles') + $mergedParams->get('num_links');
	}
	else
	{
		$limit = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.limit', 'limit', $mergedParams->get('display_num'), 'uint');
	}
	if (empty($limit))
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	
	$limitstart = $jinput->get('limitstart', 0);

	// In case limit has been changed, adjust it
	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

	// Get the current user for authorisation checks
	$user = JFactory::getUser();
	
	// Create a new query object.
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
// 	if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
// 	JLoader::import('joomla.application.component.model');
// 	JLoader::import( 'Category', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_content' . DS . 'models' );
	jimport('joomla.application.component.model');
	JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
	if (version_compare(JVERSION, '4.0', 'ge')) {
		$model = new Joomla\Component\Content\Site\Model\ArticlesModel();
		$modelcat = new Joomla\Component\Content\Site\Model\CategoryModel();
	} else {
		$model = JModelLegacy::getInstance('Articles', 'ContentModel');
		$modelcat = JModelLegacy::getInstance('Category', 'ContentModel');
	}
	$category = $modelcat->getCategory();
	$model->setState('params', $mergedParams);
	$model->setState('filter.category_id', $category->id);
	$model->setState('list.start', $model->getState('list.start'));	
	$model->setState('list.limit', $limit);
	$model->setState('filter.subcategories', $mergedParams->get('show_subcategory_content'));
	$model->setState('filter.max_category_levels', $mergedParams->get('maxLevel', 4));	
	
	// Select the required fields from the table.
	$query->select(
		$model->getState(
			'list.select',
			'DISTINCT a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
			'a.checked_out, a.checked_out_time, ' .
			'a.catid, a.created, a.created_by, a.created_by_alias, ' .
			// Published/archived article in archive category is treats as archive article
			// If category is not published then force 0
			'CASE WHEN c.published = 2 AND a.state > 0 THEN 2 WHEN c.published != 1 THEN 0 ELSE a.state END as state,' .
			// Use created if modified is 0
			'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
			'a.modified_by, uam.name as modified_by_name,' .
			// Use created if publish_up is 0
			'CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
			'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
			'a.hits, a.featured, a.language, ' . ' ' . $query->length('a.fulltext') . ' AS readmore, a.ordering'
		)
	);

	$query->from('#__content AS a');

	$params      = $model->getState('params');
	$orderby_sec = $params->get('orderby_sec');

	// Join over the frontpage articles if required.
	if ($model->getState('filter.frontpage'))
	{
		if ($orderby_sec === 'front')
		{
			$query->select('fp.ordering');
			$query->join('INNER', '#__content_frontpage AS fp ON fp.content_id = a.id');
		}
		else
		{
			$query->where('a.featured = 1');
		}
	}
	elseif ($orderby_sec === 'front' || $model->getState('list.ordering') === 'fp.ordering')
	{
		$query->select('fp.ordering');
		$query->join('LEFT', '#__content_frontpage AS fp ON fp.content_id = a.id');
	}

	// Join over the categories.
	$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
		->select('c.published, c.published AS parents_published, c.lft')
		->join('LEFT', '#__categories AS c ON c.id = a.catid');

	// Join over the users for the author and modified_by names.
	$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author")
		->select('ua.email AS author_email')
		->join('LEFT', '#__users AS ua ON ua.id = a.created_by')
		->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

	// Join over the categories to get parent category titles
	$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
		->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

	if (JPluginHelper::isEnabled('content', 'vote'))
	{
		// Join on voting table
		$query->select('COALESCE(NULLIF(ROUND(v.rating_sum  / v.rating_count, 0), 0), 0) AS rating, 
						COALESCE(NULLIF(v.rating_count, 0), 0) as rating_count')
			->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');
	}

	// Filter by access level.
	if ($access = $model->getState('filter.access'))
	{
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$query->where('a.access IN (' . $groups . ')')
			->where('c.access IN (' . $groups . ')');
	}

	// Filter by published state
	$published = $model->getState('filter.published');

	if (is_numeric($published) && $published == 2)
	{
		// If category is archived then article has to be published or archived.
		// If categogy is published then article has to be archived.
		$query->where('((c.published = 2 AND a.state > 0) OR (c.published = 1 AND a.state = 2))');
	}
	elseif (is_numeric($published))
	{
		// Category has to be published
		$query->where('c.published = 1 AND a.state = ' . (int) $published);
	}
	elseif (is_array($published))
	{
		$published = ArrayHelper::toInteger($published);
		$published = implode(',', $published);

		// Category has to be published
		$query->where('c.published = 1 AND a.state IN (' . $published . ')');
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
		$articleId = ArrayHelper::toInteger($articleId);
		$articleId = implode(',', $articleId);
		$type 	   = $model->getState('filter.article_id.include', true) ? 'IN' : 'NOT IN';
		$query->where('a.id ' . $type . ' (' . $articleId . ')');
	}

	// Filter by a single or group of categories
	$categoryId = $model->getState('filter.category_id');

	if (is_numeric($categoryId))
	{
		$type = $model->getState('filter.category_id.include', true) ? '= ' : '<> ';

		// Add subcategory check
		$includeSubcategories = $model->getState('filter.subcategories', '-1');
		$categoryEquals		  = 'a.catid ' . $type . (int) $categoryId;

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
			{
				$subQuery->where('sub.level <= this.level + ' . $levels);
			}

			// Add the subquery to the main query
			$query->where('(' . $categoryEquals . ' OR a.catid IN (' . (string) $subQuery . '))');
		}
		else
		{
			$query->where($categoryEquals);
		}
	}
	elseif (is_array($categoryId) && (count($categoryId) > 0))
	{
		$categoryId = ArrayHelper::toInteger($categoryId);
		$categoryId = implode(',', $categoryId);

		if (!empty($categoryId))
		{
			$type = $model->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
			$query->where('a.catid ' . $type . ' (' . $categoryId . ')');
		}
	}

	// Filter by author
	$authorId    = $model->getState('filter.author_id');
	$authorWhere = '';

	if (is_numeric($authorId))
	{
		$type        = $model->getState('filter.author_id.include', true) ? '= ' : '<> ';
		$authorWhere = 'a.created_by ' . $type . (int) $authorId;
	}
	elseif (is_array($authorId))
	{
		$authorId = ArrayHelper::toInteger($authorId);
		$authorId = implode(',', $authorId);

		if ($authorId)
		{
			$type        = $model->getState('filter.author_id.include', true) ? 'IN' : 'NOT IN';
			$authorWhere = 'a.created_by ' . $type . ' (' . $authorId . ')';
		}
	}

	// Filter by author alias
	$authorAlias      = $model->getState('filter.author_alias');
	$authorAliasWhere = '';

	if (is_string($authorAlias))
	{
		$type             = $model->getState('filter.author_alias.include', true) ? '= ' : '<> ';
		$authorAliasWhere = 'a.created_by_alias ' . $type . $db->quote($authorAlias);
	}
	elseif (is_array($authorAlias))
	{
		$first = current($authorAlias);

		if (!empty($first))
		{
			foreach ($authorAlias as $key => $alias)
			{
				$authorAlias[$key] = $db->quote($alias);
			}

			$authorAlias = implode(',', $authorAlias);

			if ($authorAlias)
			{
				$type             = $model->getState('filter.author_alias.include', true) ? 'IN' : 'NOT IN';
				$authorAliasWhere = 'a.created_by_alias ' . $type . ' (' . $authorAlias .
					')';
			}
		}
	}

	if (!empty($authorWhere) && !empty($authorAliasWhere))
	{
		$query->where('(' . $authorWhere . ' OR ' . $authorAliasWhere . ')');
	}
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
	$nowDate  = $db->quote(JFactory::getDate()->toSql());

	// Filter by start and end dates.
	if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
	{
		$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
			->where('(a.publish_down IS NULL OR a.publish_down >= ' . $nowDate . ')');
	}

	// Filter by Date Range or Relative Date
	$dateFiltering = $model->getState('filter.date_filtering', 'off');
	$dateField     = $model->getState('filter.date_field', 'a.created');

	switch ($dateFiltering)
	{
		case 'range':
			$startDateRange = $db->quote($model->getState('filter.start_date_range', $nullDate));
			$endDateRange   = $db->quote($model->getState('filter.end_date_range', $nullDate));
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

	// Process the filter for list views with user-entered filters
	if (is_object($params) && ($params->get('filter_field') !== 'hide') && ($filter = $model->getState('list.filter')))
	{
		// Clean filter variable
		$filter     = StringHelper::strtolower($filter);
		$hitsFilter = (int) $filter;
		$filter     = $db->quote('%' . $db->escape($filter, true) . '%', false);

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
	{
		$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
	}

	// Filter by a single or group of tags.
	$hasTag = false;
	$tagId  = $model->getState('filter.tag');

	if (!empty($tagId) && is_numeric($tagId))
	{
		$hasTag = true;

		$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int) $tagId);
	}
	elseif (is_array($tagId))
	{
		ArrayHelper::toInteger($tagId);
		$tagId = implode(',', $tagId);
		if (!empty($tagId))
		{
			$hasTag = true;

			$query->where($db->quoteName('tagmap.tag_id') . ' IN (' . $tagId . ')');
		}
	}

	if ($hasTag)
	{
		$query->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
			. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
			. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_content.article')
		);
	}

	// Add the list ordering clause.
	
	if ($jatype != '') {
		$query->where('a.attribs REGEXP \'"ctm_content_type":"'.$jatype.'"\'');
	}

	if ($jawhere != false) {
		foreach ($jawhere AS $jw) {
			$query->where($jw);
		}
	}
    
	if ($jaorder) {
 		foreach ($jaorder AS $jo) {
 			$query->order(ContentHelperQuery::orderbySecondary($jo));
 		}
	} else {
		$query->order($model->getState('list.ordering', 'a.ordering') . ' ' . $model->getState('list.direction', 'ASC'));
	}

	if ($number == false)
		$db->setQuery($query, $limitstart, $limit);
	else
		$db->setQuery($query);

	$result = $db->loadObjectList();
	if ($number == true)
		return $result;
	
	$userId = $user->get('id');
	$guest = $user->get('guest');
	$groups = $user->getAuthorisedViewLevels();
	$input = JFactory::getApplication()->input;

	// Get the global params
	$globalParams = JComponentHelper::getParams('com_content', true);

	// Convert the parameter fields into objects.
	foreach ($result as &$item)
	{
		$articleParams = new \Joomla\Registry\Registry;
		$articleParams->loadString($item->attribs);

		// Unpack readmore and layout params
		$item->alternative_readmore = $articleParams->get('alternative_readmore');
		$item->layout = $articleParams->get('layout');

		$item->params = clone $model->getState('params');

		/*For blogs, article params override menu item params only if menu param = 'use_article'
		Otherwise, menu item params control the layout
		If menu item is 'use_article' and there is no article param, use global*/
		if (($input->getString('layout') == 'blog') || ($input->getString('view') == 'featured')
			|| ($model->getState('params')->get('layout_type') == 'blog'))
		{
			// Create an array of just the params set to 'use_article'
			$menuParamsArray = $model->getState('params')->toArray();
			$articleArray = array();

			foreach ($menuParamsArray as $key => $value)
			{
				if ($value === 'use_article')
				{
					// If the article has a value, use it
					if ($articleParams->get($key) != '')
					{
						// Get the value from the article
						$articleArray[$key] = $articleParams->get($key);
					}
					else
					{
						// Otherwise, use the global value
						$articleArray[$key] = $globalParams->get($key);
					}
				}
			}

			// Merge the selected article params
			if (count($articleArray) > 0)
			{
				$articleParams = new JRegistry;
				$articleParams->loadArray($articleArray);
				$item->params->merge($articleParams);
			}
		}
		else
		{
			// For non-blog layouts, merge all of the article params
			$item->params->merge($articleParams);
		}

		// Get display date
		switch ($item->params->get('list_show_date'))
		{
			case 'modified':
				$item->displayDate = $item->modified;
				break;

			case 'published':
				$item->displayDate = ($item->publish_up == 0) ? $item->created : $item->publish_up;
				break;

			default:
			case 'created':
				$item->displayDate = $item->created;
				break;
		}

		// Compute the asset access permissions.
		// Technically guest could edit an article, but lets not check that to improve performance a little.
		if (!$guest)
		{
			$asset = 'com_content.article.' . $item->id;

			// Check general edit permission first.
			if ($user->authorise('core.edit', $asset))
			{
				$item->params->set('access-edit', true);
			}

			// Now check if edit.own is available.
			elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
			{
				// Check for a valid user and that they are the owner.
				if ($userId == $item->created_by)
				{
					$item->params->set('access-edit', true);
				}
			}
		}

		$access = $model->getState('filter.access');

		if ($access)
		{
			// If the access filter has been set, we already have only the articles this user can view.
			$item->params->set('access-view', true);
		}
		else
		{
			// If no access filter is set, the layout takes some responsibility for display of limited information.
			if ($item->catid == 0 || $item->category_access === null)
			{
				$item->params->set('access-view', in_array($item->access, $groups));
			}
			else
			{
				$item->params->set('access-view', in_array($item->access, $groups) && in_array($item->category_access, $groups));
			}
		}

		// Get the tags
		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_content.article', $item->id);
		
		
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

		$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

		// No link for ROOT category
		if ($item->parent_alias == 'root')
		{
			$item->parent_slug = null;
		}

		$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		$item->event   = new stdClass;

		$dispatcher = JFactory::getApplication();

		// Old plugins: Ensure that text property is available
		if (!isset($item->text))
		{
			$item->text = $item->introtext;
		}

		JPluginHelper::importPlugin('content');
		$dispatcher->triggerEvent('onContentPrepare', array ('com_content.category', &$item, &$item->params, 0));

		// Old plugins: Use processed text as introtext
		$item->introtext = $item->text;

		$results = $dispatcher->triggerEvent('onContentAfterTitle', array('com_content.category', &$item, &$item->params, 0));
		$item->event->afterDisplayTitle = trim(implode("\n", $results));

		$results = $dispatcher->triggerEvent('onContentBeforeDisplay', array('com_content.category', &$item, &$item->params, 0));
		$item->event->beforeDisplayContent = trim(implode("\n", $results));

		$results = $dispatcher->triggerEvent('onContentAfterDisplay', array('com_content.category', &$item, &$item->params, 0));
		$item->event->afterDisplayContent = trim(implode("\n", $results));
	}
		
  	return $result;
  }
  
  public static function getArticleContentNumber($jatype='', $jawhere=array(), $jaorder=array()) {
  	return count(JATemplateHelper::getArticleContent($jatype, $jawhere, $jaorder, true));
  }
  /*** End Custom Get Article Content type ***/
}

?>