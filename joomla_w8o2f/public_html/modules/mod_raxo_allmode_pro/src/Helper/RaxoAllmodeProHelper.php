<?php
/**
 * =============================================================
 * @package		RAXO All-mode PRO J4.x
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2021 RAXO Group
 * @link		https://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */

namespace Joomla\Module\RaxoAllmodePro\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\String\StringHelper;


// Helper for mod_raxo_allmode_pro
abstract class RaxoAllmodeProHelper
{
	public static function getList(&$params)
	{
		$app	= Factory::getApplication();
		$db		= Factory::getDbo();
		$query	= $db->getQuery(true);


		$component	= ($app->input->get('option') === 'com_content') ? 1 : '';
		if ($component)
		{
			$view	= $app->input->get('view');
			$curid	= $app->input->getInt('id');
		}


		// Source Selection
		$source_selection	= $params->get('source_selection', 'selected');
		$category_filter	= (bool) $params->get('category_filter', 1);
		switch ($source_selection)
		{
			case 'items':
				$selected_items = trim(preg_replace(array('/\s*/', '/,+/'), array('', ','), $params->get('selected_items')), ',');

				if (!empty($selected_items))
				{
					$query->where('a.id IN ('. $selected_items .')');
				}
				else
				{
					echo Text::_('MOD_RAXO_RAMP_ERROR_SOURCE');
					return;
				}
				break;

			case 'current':
				if ($component)
				{
					switch ($view)
					{
						case 'category':
						case 'categories':
							$current_category = $curid;
							break;

						case 'article':
							$current_category = $app->input->getInt('catid');
							$current_category = empty($current_category) ? self::getItemCategory($curid) : $current_category;
							break;

						default:
							return;
					}

					if ($category_filter)
					{
						$query->where('a.catid = '. $current_category);
					}
					else
					{
						$query->where('a.catid != '. $current_category);
					}
				}
				else
				{
					return;
				}
				break;

			case 'selected':
				$selected_categories = $params->get('selected_categories', array());

				if (!empty($selected_categories))
				{
					if ($category_filter)
					{
						$query->where('(a.catid = '. implode(' OR a.catid = ', $selected_categories) .')');
					}
					else
					{
						$query->where('a.catid NOT IN ('. implode(',', $selected_categories) .')');
					}
				}
				else
				{
					echo Text::_('MOD_RAXO_RAMP_ERROR_SOURCE');
					return;
				}
				break;

			case 'all':
			default:
				break;
		}


		// Exclude Items
		$exclude_items	= $params->get('exclude_items');
		$current_item	= (bool) $params->get('current_item', 0);
		$current_item	= (!$current_item && $component && $view === 'article') ? $curid : '';

		if ($exclude_items)
		{
			!empty($current_item) ? $exclude_items .= ','. $current_item : '';
			$exclude_items	= trim(preg_replace(array('/\s*/', '/,+/'), array('', ','), $exclude_items), ',');
			$exclude_items	= array_flip(array_flip(explode(',', $exclude_items)));

			$query->where('a.id NOT IN ('. implode(',', $exclude_items) .')');
		}
		elseif (!empty($current_item))
		{
			$query->where('a.id != '. $current_item);
		}


		// FILTERS
		$count_top			= $params->get('count')->top;
		$count_nor			= $params->get('count')->nor;
		$count_skip			= (int) $params->get('count_skip', 0);

		$tags				= $params->get('tags', array());
		$tags				= !empty($tags) ? implode(' OR t.tag_id = ', $tags) : NULL;

		$date_filtering		= $params->get('date_filtering', 'disabled');
		$featured_items		= $params->get('featured_items', 'show');

		$user				= Factory::getUser();
		$userID				= (int) $user->get('id');
		$userVL				= array_unique($user->getAuthorisedViewLevels());
		$access				= $params->get('not_public', 0) ? 0 : !ComponentHelper::getParams('com_content')->get('show_noauth');


		// TEXT
		$show_title			= (array) $params->get('show_title');
		$show_title_top		= in_array('top', $show_title) ? 1 : '';
		$show_title_nor		= in_array('nor', $show_title) ? 1 : '';
		$limit_title		= $params->get('limit_title');
		$limit_title_top	= $limit_title->top;
		$limit_title_nor	= $limit_title->nor;

		$show_text			= (array) $params->get('show_text');
		$show_text_top		= in_array('top', $show_text) && $count_top ? 1 : '';
		$show_text_nor		= in_array('nor', $show_text) && $count_nor ? 1 : '';
		$limit_text			= $params->get('limit_text');
		$limit_text_top		= $limit_text->top;
		$limit_text_nor		= $limit_text->nor;

		$read_more			= $params->get('read_more');
		$read_more_top		= $read_more->top;
		$read_more_nor		= $read_more->nor;

		$intro_clean		= $params->get('intro_clean', 1);
		$allowable_tags		= str_replace(' ', '', $params->get('allowable_tags'));
		$allowable_tags		= "<". str_replace(',', '><', $allowable_tags) .">";
		$plugin_support		= $params->get('plugin_support', 0);


		// INFO
		$show_date			= (array) $params->get('show_date');
		$show_date_top		= in_array('top', $show_date) ? 1 : '';
		$show_date_nor		= in_array('nor', $show_date) ? 1 : '';
		$date_type			= $params->get('date_type', 'created');
		$date_format		= $params->get('date_format');
		$date_format_top	= $date_format->top ?: 'F d, Y';
		$date_format_nor	= $date_format->nor ?: 'M d, Y';


		$show_category		= (array) $params->get('show_category');
		$show_category_top	= in_array('top', $show_category) && $count_top ? 1 : '';
		$show_category_nor	= in_array('nor', $show_category) && $count_nor ? 1 : '';
		$category_link		= $params->get('category_link', 0);

		$show_author		= (array) $params->get('show_author');
		$show_author_top	= in_array('top', $show_author) && $count_top ? 1 : '';
		$show_author_nor	= in_array('nor', $show_author) && $count_nor ? 1 : '';
		$show_author		= $show_author_top || $show_author_nor;

		$show_rating		= (array) $params->get('show_rating');
		$show_rating_top	= in_array('top', $show_rating) && $count_top ? 1 : '';
		$show_rating_nor	= in_array('nor', $show_rating) && $count_nor ? 1 : '';

		$show_hits			= (array) $params->get('show_hits');
		$show_hits_top		= in_array('top', $show_hits) && $count_top ? 1 : '';
		$show_hits_nor		= in_array('nor', $show_hits) && $count_nor ? 1 : '';

		$comment_system		= $params->get('comment_system', 0);
		$show_comments		= !empty($comment_system) ? (array) $params->get('show_comments') : array();
		$show_comments_top	= in_array('top', $show_comments) && $count_top ? 1 : '';
		$show_comments_nor	= in_array('nor', $show_comments) && $count_nor ? 1 : '';


		// IMAGES
		$show_image			= (array) $params->get('show_image');
		$show_image_top		= in_array('top', $show_image) && $count_top ? 1 : '';
		$show_image_nor		= in_array('nor', $show_image) && $count_nor ? 1 : '';

		$image_top			= $params->get('image_top');
		$image_nor			= $params->get('image_nor');

		$image_source		= $params->get('image_source', 'automatic');
		$image_crop			= $params->get('image_crop', 1);
		$image_link			= $params->get('image_link', 1);
		$image_title		= $params->get('image_title', 1);
		$image_default		= $params->get('image_default') !== '-1' ? 'modules/mod_raxo_allmode_pro/tools/'. $params->get('image_default') : NULL;


		// ORDERING
		$ordering			= $params->get('ordering', 'created_dsc');
		$ordermap			= array(
			'created_asc'		=> 'date ASC',
			'created_dsc'		=> 'date DESC',
			'title_az'			=> 'a.title ASC',
			'title_za'			=> 'a.title DESC',
			'popular_first'		=> 'a.hits DESC',
			'popular_last'		=> 'a.hits ASC',
			'rated_most'		=> 'rating_value DESC, r.rating_count DESC',
			'rated_least'		=> 'rating_value ASC, r.rating_count ASC',
			'commented_most'	=> $comment_system ? 'comments_count DESC, comments_date DESC' : 'date DESC',
			'commented_latest'	=> $comment_system ? 'comments_date DESC' : 'date DESC',
			'ordering_fwd'		=> $featured_items === 'only' ? 'f.ordering ASC' : 'a.ordering ASC',
			'ordering_rev'		=> $featured_items === 'only' ? 'f.ordering DESC' : 'a.ordering DESC',
			'id_asc'			=> 'a.id ASC',
			'id_dsc'			=> 'a.id DESC',
			'exact'				=> ($source_selection === 'items') ? 'FIELD(a.id, '. $selected_items .')' : 'a.id ASC',
			'random'			=> $query->Rand()
		);


		// SELECT ITEMS
		$query->select('a.id, a.title, a.alias, a.catid AS category_id, a.access, a.featured, a.language');

		// Select: Date
		if ($date_type !== 'created')
		{
			$query->select(' CASE WHEN a.'. $date_type .' = \'0000-00-00 00:00:00\' THEN a.created ELSE a.'. $date_type .' END AS date');
		}
		else
		{
			$query->select(' a.created AS date');
		}

		if ($show_text_top || $show_text_nor || (($show_image_top || $show_image_nor) && ($image_source === 'text' || $image_source === 'automatic')))
		{
			$query->select(' a.introtext, a.fulltext');
		}

		($show_image_top || $show_image_nor) && $image_source != 'text' ? $query->select(' a.images') : '';

		$show_hits_top || $show_hits_nor ? $query->select(' a.hits') : '';

		$show_category_top || $show_category_nor ? $query->select(' c.title AS category_name') : '';

		$query->from('#__content AS a')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join: Users
		if ($show_author_top || $show_author_nor)
		{
			$query->select(' CASE WHEN a.created_by_alias > \' \' THEN a.created_by_alias ELSE u.name END AS author')
				->join('LEFT', '#__users AS u ON u.id = a.created_by');
		}

		// Join: Rating
		if ($show_rating_top || $show_rating_nor || $ordering === 'rated_most' || $ordering === 'rated_least')
		{
			$query->select(' ROUND(r.rating_sum / r.rating_count, 2) AS rating_value, r.rating_count')
				->join('LEFT', '#__content_rating AS r ON r.content_id = a.id');
		}

		// Join: Comments
		if ($show_comments_top || $show_comments_nor || $ordering === 'commented_most' || $ordering === 'commented_latest')
		{
			switch ($comment_system)
			{
				case 'jcomments':
					$query->select(' COUNT(jc.id) AS comments_count, MAX(jc.date) AS comments_date')
						->join('LEFT', '#__jcomments AS jc ON jc.object_id = a.id AND jc.object_group = \'com_content\' AND jc.published = 1');
					$comments_link = "#comments";
					break;

				case 'jacomment':
					$query->select(' COUNT(jc.id) AS comments_count, MAX(jc.date) AS comments_date')
						->join('LEFT', '#__jacomment_items AS jc ON jc.contentid = a.id AND jc.option = \'com_content\' AND jc.type = 1');
					$comments_link = "#jac-wrapper";
					break;

				case 'jlexcomment':
					$query->select(' jc.cm_count_active AS comments_count, MAX(cm.created_time) AS comments_date')
						->join('LEFT', '#__jlexcomment_obj AS jc ON jc.com_id = a.id AND jc.com_name = \'content\'')
						->join('LEFT', '#__jlexcomment AS cm ON cm.obj_id = jc.id AND cm.published = 1');
					$comments_link = "#comment";
					break;

				case 'komento':
					$query->select(' COUNT(jc.id) AS comments_count, MAX(jc.created) AS comments_date')
						->join('LEFT', '#__komento_comments AS jc ON jc.cid = a.id AND jc.component = \'com_content\' AND jc.published = 1');
					$comments_link = "#section-kmt";
					break;

				case 'compojoom':
					$query->select(' COUNT(jc.id) AS comments_count, MAX(jc.date) AS comments_date')
						->join('LEFT', '#__comment AS jc ON jc.contentid = a.id AND jc.component = \'com_content\' AND jc.published = 1');
					$comments_link = "#ccomment-content-";
					break;

				case 'rscomments':
					$query->select(' COUNT(jc.IdComment) AS comments_count, MAX(jc.date) AS comments_date')
						->join('LEFT', '#__rscomments_comments AS jc ON jc.id = a.id AND jc.published = 1');
					$comments_link = "#rscomments_big_container";
					break;

				case 'slicomments':
					$query->select(' COUNT(jc.id) AS comments_count, MAX(jc.created) AS comments_date')
						->join('LEFT', '#__slicomments AS jc ON jc.article_id = a.id AND jc.status = 1');
					$comments_link = "#comments";
					break;

				case '0':
				default:
					break;
			}
		}


		// Filter: Published
		$query->where('c.published = 1 AND a.state = 1');

		// Filter: Access
		if ($access)
		{
			$user_access = (count($userVL) > 1) ? 'IN ('. implode(',', $userVL) .')' : '= '. $userVL[0];
			$query->where('c.access '. $user_access .' AND a.access '. $user_access);
		}

		// Filter: Featured
		if ($featured_items === 'only')
		{
			if ($ordering === 'ordering_fwd' || $ordering === 'ordering_rev')
			{
				$query->join('LEFT', '#__content_frontpage AS f ON f.content_id = a.id');
			}
			$query->where('a.featured = 1');
		}
		elseif ($featured_items === 'hide')
		{
			$query->where('a.featured = 0');
		}

		// Filter: Language
		if ($app->getLanguageFilter())
		{
			$query->where('a.language IN ('. $db->quote(Factory::getLanguage()->getTag()) .','. $db->quote('*') .')');
		}

		// Filter: Date
		$date		= Factory::getDate();
		$date_null	= $db->quote($db->getNullDate());
		// MySQL cache optimization (1 min)
		$date_now	= $db->quote($date->format('Y-m-d H:i:00'));


		$query->where([
			'('. $db->quoteName('a.publish_up') .' IS NULL OR '. $db->quoteName('a.publish_up') .' <= '. $date_now .')',
			'('. $db->quoteName('a.publish_down') .' IS NULL OR '. $db->quoteName('a.publish_down'). ' >= '. $date_now .')'
		]);

		// Time zone based on the server configuration
		if ($date_filtering !== 'disabled')
		{
			$date->setTimezone(new \DateTimeZone($app->get('offset')));
			$date->setTime(0, 0, 0);
		}

		switch ($date_filtering)
		{
			case 'today':
				$date_start	= $date->format('Y-m-d');
				break;

			case 'yesterday':
				$date->modify('-1 day');
				$date_start	= $date->format('Y-m-d');
				$date_end	= $date->setTime(23, 59, 59)->format('Y-m-d H:i:s');
				break;

			case 'this_week':
				$date->modify(-$date->format('N') + 1 .' days');
				$date_start	= $date->format('Y-m-d');
				break;

			case 'last_week':
				$date->modify(-$date->format('N') - 6 .' days');
				$date_start	= $date->format('Y-m-d');
				$date->modify('+6 days')->setTime(23, 59, 59);
				$date_end	= $date->format('Y-m-d H:i:s');
				break;

			case 'this_month':
				$date_start	= $date->format('Y-m-01');
				break;

			case 'last_month':
				$date->modify('-1 month');
				$date_start	= $date->format('Y-m-01');
				$date_end	= $date->format('Y-m-t');
				break;

			case 'this_year':
				$date_start	= $date->format('Y-01-01');
				break;

			case 'last_year':
				$date->modify('-1 year');
				$date_start	= $date->format('Y-01-01');
				$date_end	= $date->format('Y-12-31');
				break;

			case 'history_day':
				$date_find	= 'MONTH(a.'. $date_type .') = '. $date->format('m') .' AND DAY(a.'. $date_type .') = '. $date->format('j');
				break;

			case 'history_month':
				$date_find	= 'MONTH(a.'. $date_type .') = '. $date->format('m');
				break;

			case 'range':
				$date_start	= $params->get('date_range')->start ?: '1000-01-01 00:00:00';
				$date_end	= $params->get('date_range')->end ?: '9999-12-31 23:59:59';
				break;

			case 'relative':
				$date_temp	= clone $date;

				$date_start	= $params->get('date_relative')->from;
				$date_start	? $date->modify(-$date_start .' days') : $date->setDate(1000, 1, 1);
				$date_start	= $date->format('Y-m-d');

				$date_end	= $params->get('date_relative')->to;
				$date_end	? $date_temp->modify(-$date_end .' days') : $date_temp->setDate(9999, 12, 31);
				$date_end	= $date_temp->setTime(23, 59, 59)->format('Y-m-d H:i:s');
				break;

			case 'disabled':
			default:
				break;
		}

		if (isset($date_start))
		{
			$date_start	= $db->quote($date_start);
			$date_end	= (isset($date_end)) ? $db->quote($date_end) : $date_now;

			$date_extra	= ($date_type !== 'created') ? ' OR (a.'. $date_type .' = '. $date_null .' AND a.created BETWEEN '. $date_start .' AND '. $date_end .')' : '';
			$query->where('(a.'. $date_type .' BETWEEN '. $date_start .' AND '. $date_end . $date_extra .')');
		}

		if (isset($date_find))
		{
			$query->where('('. $date_find .')');
		}


		// Filter: Author
		switch ($params->get('authors'))
		{
			case 'selected':
				$author_id		= $params->get('author_id', array());
				$author_alias	= $params->get('author_alias', array());
				$authors		= array();

				if (!empty($author_id)) {
					$authors[] = !empty($author_id[1]) ? 'a.created_by IN ('. implode(',', $author_id) .')' : 'a.created_by = '. $author_id[0];
				}

				if (!empty($author_alias)) {
					foreach ($author_alias as $key => $alias) {
						$author_alias[$key] = $db->quote($alias);
					}
					$authors[] = !empty($author_alias[1]) ? 'a.created_by_alias IN ('. implode(',', $author_alias) .')' : 'a.created_by_alias = '. $author_alias[0];
				}

				!empty($authors) ? $query->where('('. implode(' OR ', $authors) .')') : '';
				break;

			case 'by_me':
				if ($userID) {
					$query->where('(a.created_by = '. $userID .' OR a.modified_by = '. $userID .')');
				} else {
					return;
				}
				break;

			case 'not_me':
				if ($userID) {
					$query->where('(a.created_by <> '. $userID .' AND a.modified_by <> '. $userID .')');
				}
				break;

			case 'all':
			default:
				break;
		}

		// Filter: Tags
		if (isset($tags))
		{
			$query->join('INNER', '#__contentitem_tag_map AS t ON t.content_item_id = a.id')
				->where('(t.tag_id = '. $tags .') AND t.type_id = 1');
		}

		(isset($tags) || isset($comments_link)) ? $query->group('a.id') : '';

		// Ordering
		$query->order($ordermap[$ordering]);

		$db->setQuery($query, $count_skip, $count_top + $count_nor);
		// echo $query->dump();


		// Retrieve Data
		$items	= $db->loadObjectList();
		$list	= array();

		if (!empty($items))
		{
			$empty = array('id', 'title', 'title_full', 'link', 'date', 'author',
						'image', 'image_thumbnail', 'image_original', 'image_alt', 'image_title', 'image_attributes',
						'category', 'category_id', 'category_name', 'category_link', 'featured',
						'text', 'readmore', 'hits', 'rating', 'rating_value', 'rating_count',
						'comments', 'comments_count', 'comments_link');
			$empty = (object) array_fill_keys($empty, '');

			foreach ($items as $i => &$item)
			{
				// TOP Items & Normal Items
				if ($i < $count_top)
				{
					$show_title		= $show_title_top ? 1 : '';
					$limit_title	= $limit_title_top;
					$show_text		= $show_text_top ? 1 : '';
					$limit_text		= $limit_text_top;
					$read_more		= $read_more_top;

					$show_date		= $show_date_top ? 1 : '';
					$date_format	= $date_format_top;
					$show_category	= $show_category_top ? 1 : '';
					$show_author	= $show_author_top ? 1 : '';
					$show_rating	= $show_rating_top ? 1 : '';
					$show_hits		= $show_hits_top ? 1 : '';
					$show_comments	= $show_comments_top ? 1 : '';

					$show_image		= $show_image_top ? 1 : '';
					$image_width	= $image_top->width;
					$image_height	= $image_top->height;
				}
				else
				{
					$show_title		= $show_title_nor ? 1 : '';
					$limit_title	= $limit_title_nor;
					$show_text		= $show_text_nor ? 1 : '';
					$limit_text		= $limit_text_nor;
					$read_more		= $read_more_nor;

					$show_date		= $show_date_nor ? 1 : '';
					$date_format	= $date_format_nor;
					$show_category	= $show_category_nor ? 1 : '';
					$show_author	= $show_author_nor ? 1 : '';
					$show_rating	= $show_rating_nor ? 1 : '';
					$show_hits		= $show_hits_nor ? 1 : '';
					$show_comments	= $show_comments_nor ? 1 : '';

					$show_image		= $show_image_nor ? 1 : '';
					$image_width	= $image_nor->width;
					$image_height	= $image_nor->height;
				}

				$list[$i]				= clone $empty;
				$list[$i]->id			= $item->id;
				$list[$i]->category_id	= $item->category_id;
				$list[$i]->featured		= $item->featured;

				// Item Link
				if ($access || in_array($item->access, $userVL))
				{
					$list[$i]->link = Route::_(RouteHelper::getArticleRoute($item->id .':'. $item->alias, $item->category_id, $item->language));
				}
				else
				{
					$link	= 'index.php?option=com_users&view=login';
					$menu	= $app->getMenu()->getItems('link', $link);
					$list[$i]->link = isset($menu[0]) ? Route::_($link .'&Itemid='. $menu[0]->id) : Route::_($link);
				}

				// Show Title
				if ($show_title)
				{
					$list[$i]->title = $list[$i]->title_full = trim($item->title);
					if ($limit_title)
					{
						$list[$i]->title = self::truncateHTML($list[$i]->title_full, $limit_title, '&hellip;', false, false);
					}
				}

				// Plugins Support
				$item->introtext = $plugin_support ? HTMLHelper::_('content.prepare', @$item->introtext) : preg_replace('/{[^{]+?{\/.+?}|{.+?}/', '', @$item->introtext);

				// Show Images
				if ($show_image)
				{
					// Retrieve Image
					$img	= array_fill_keys(array('src', 'alt', 'ttl'), '');
					$images = ($image_source != 'text') ? json_decode($item->images) : '';

					if (!empty($images->image_intro) && ($image_source === 'intro' || $image_source === 'automatic'))
					{
						$img['src'] = HTMLHelper::cleanImageURL($images->image_intro)->url;
						$img['alt'] = $images->image_intro_alt;
						$img['ttl'] = $images->image_intro_caption;
					}
					elseif (!empty($images->image_fulltext) && ($image_source === 'full' || $image_source === 'automatic'))
					{
						$img['src'] = HTMLHelper::cleanImageURL($images->image_fulltext)->url;
						$img['alt'] = $images->image_fulltext_alt;
						$img['ttl'] = $images->image_fulltext_caption;
					}
					elseif ($image_source === 'text' || $image_source === 'automatic')
					{
						$pattern = '/<img[^>]+>/i';
						!preg_match($pattern, $item->introtext, $img_tag) ? preg_match($pattern, $item->fulltext, $img_tag) : '';

						if (isset($img_tag[0]))
						{
							preg_match_all('/(alt|title|src)\s*=\s*(["\'])(.*?)\2/i', $img_tag[0], $img_attr);
							$img_attr = array_combine($img_attr[1], $img_attr[3]);

							if (isset($img_attr['src']))
							{
								$img['src'] = trim(rawurldecode($img_attr['src']));
								$img['alt'] = isset($img_attr['alt']) ? trim($img_attr['alt']) : '';
								$img['ttl'] = isset($img_attr['title']) ? trim($img_attr['title']) : '';

								$item->introtext = preg_replace($pattern, '', $item->introtext, 1);
							}
						}
					}

					// Default Image
					if (empty($img['src']) && isset($image_default))
					{
						$img['src'] = $image_default;
						$img['alt'] = Text::_('MOD_RAXO_RAMP_NOIMAGE');
					}

					// Process Image
					if ($img['src'])
					{
						$img_src = $img_attr = $tb_url = '';

						if (strncasecmp($img['src'], 'http', 4) !== 0)
						{
							$img['src'] = Uri::base(true) .'/'. $img['src'];
							$tb_url = '-int';
						}
						else
						{
							$tb_url = '-ext';
						}

						if ($image_width || $image_height)
						{
							$img_src .= Uri::base(true) .'/modules/mod_raxo_allmode_pro/tools/tb.php?src=';
							$img_src .= rawurlencode($img['src']);
							$tb_path = '/images/thumbnails/raxo/ramp/tb';

							if ($image_width)
							{
								$img_src .= '&amp;w='. $image_width;
								$tb_path .= '-w'. $image_width;
								$img_attr = !$image_height ? 'width="'. $image_width .'"' : '';
							}
							if ($image_height)
							{
								$img_src .= '&amp;h='. $image_height;
								$tb_path .= '-h'. $image_height;
								$img_attr = !$image_width ? 'height="'. $image_height .'"' : '';
							}
							if ($image_width && $image_height && $image_crop)
							{
								$img_src .= '&amp;zc=1';
								$tb_path .= '-crop';
								$img_attr = 'width="'. $image_width .'" height="'. $image_height .'"';
							}
							else
							{
								$tb_path .= '-fit';
							}

							$tb_path .= $tb_url .'-'. md5($img['src']) .'.jpg';
							$img_src = file_exists(JPATH_SITE . $tb_path) ? Uri::base(true) . $tb_path : $img_src;
						}

						$img['ttl']	= $image_title ? htmlspecialchars($list[$i]->title_full, ENT_COMPAT, 'UTF-8') : $img['ttl'];

						$list[$i]->image_thumbnail	= $img_src ?: $img['src'];
						$list[$i]->image_original	= $img['src'];
						$list[$i]->image_alt		= $img['alt'];
						$list[$i]->image_caption	= $img['ttl'];
						$list[$i]->image_attributes	= $img_attr .' alt="'. $img['alt'] .'"'. ($img['ttl'] ? ' title="'. $img['ttl'] .'"' : '');

						$list[$i]->image = '<img src="'. $list[$i]->image_thumbnail .'" '. $list[$i]->image_attributes .' />';
						$list[$i]->image = $image_link ? '<a href="'. $list[$i]->link .'">'. $list[$i]->image .'</a>' : $list[$i]->image;
					}
				}

				// Show Text
				if ($show_text)
				{
					// Clean XHTML
					if ($intro_clean)
					{
						$item->introtext = strip_tags($item->introtext, $allowable_tags);
						$item->introtext = str_replace('&nbsp;', ' ', $item->introtext);
						$item->introtext = preg_replace('/\s{2,}/u', ' ', trim($item->introtext));
					}
					// Limit Text
					$list[$i]->text = $limit_text ? self::truncateHtml($item->introtext, $limit_text, '&hellip;', false, true) : $item->introtext;
				}

				// Show Category
				if ($show_category)
				{
					$list[$i]->category = $list[$i]->category_name = trim($item->category_name);
					if ($category_link)
					{
						$list[$i]->category_link = Route::_(RouteHelper::getCategoryRoute($item->category_id));
						$list[$i]->category = '<a href="'. $list[$i]->category_link .'">'. $list[$i]->category_name .'</a>';
					}
				}

				// Show Author
				$list[$i]->author = $show_author ? $item->author : '';

				// Show Date
				$list[$i]->date = $show_date ? HTMLHelper::_('date', $item->date, $date_format) : '';

				// Show Readmore
				$list[$i]->readmore = $read_more ? '<a href="'. $list[$i]->link .'">'. $read_more .'</a>' : '';

				// Show Hits
				$list[$i]->hits = $show_hits ? $item->hits : '';

				// Show Rating
				if ($show_rating) {
					$rating_stars = $rating_proc = 0;
					if ($item->rating_count > 0) {
						$rating_stars = floor($item->rating_value);
						$rating_proc = ($item->rating_value - $rating_stars) * 100;
					}

					for ($star = 0; $star++<5;) {
						$list[$i]->rating .= '<span class="raxo-star">';
						if ($star <= $rating_stars) {
							$list[$i]->rating .= '<span></span>';
						} elseif ($rating_proc && $star == ceil($item->rating_value)) {
							$list[$i]->rating .= '<span style="width:'. $rating_proc .'%"></span>';
						}
						$list[$i]->rating .= '</span>';
					}

					$list[$i]->rating_value = $item->rating_value;
					$list[$i]->rating_count = $item->rating_count;
				}

				// Show Comments
				if ($show_comments)
				{
					$list[$i]->comments_count	= $item->comments_count;
					$list[$i]->comments_link	= $comment_system === 'compojoom' ? $list[$i]->link . $comments_link . $item->id : $list[$i]->link . $comments_link;
					$list[$i]->comments			= '<a href="'. $list[$i]->comments_link .'">'. $item->comments_count .'</a>';
				}

			}

		}

		return $list;
	}


	/**
	 * Get the keywords of the current item
	 *
	 * @param   integer   $curid   The item ID
	 *
	 * @return  integer
	 */
	public static function getItemCategory($curid)
	{
		$db		= Factory::getDbo();
		$query	= $db->getQuery(true)
			->select('catid')
			->from('#__content')
			->where('id = '. $curid);

		return $db->setQuery($query)->loadResult();
	}


	/**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 *
	 * @return string Trimmed string.
	 */
	public static function truncateHtml($text, $length = 320, $ending = '&hellip;', $exact = false, $considerHtml = true)
	{
		if ($considerHtml)
		{
			// if the plain text is shorter than the maximum length, return the whole text
			if (StringHelper::strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
			{
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$open_tags		= array();
			$total_length	= 0;
			$truncate		= '';

			foreach ($lines as $line_matchings)
			{
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1]))
				{
					// if it's an "empty element" with or without xhtml-conform closing slash
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]))
					{
						// do nothing
					}
					// if tag is a closing tag
					elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings))
					{
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false)
						{
							unset($open_tags[$pos]);
						}
					}
					// if tag is an opening tag
					elseif (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings))
					{
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = StringHelper::strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length + $content_length > $length)
				{
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
					{
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity)
						{
							if ($entity[1] + 1 - $entities_length <= $left)
							{
								$left--;
								$entities_length += StringHelper::strlen($entity[0]);
							}
							else
							{
								// no more characters left
								break;
							}
						}
					}
					$truncate .= StringHelper::substr($line_matchings[2], 0, $left + $entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				}
				else
				{
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if ($total_length >= $length)
				{
					break;
				}
			}
		}
		else
		{
			if (StringHelper::strlen($text) <= $length)
			{
				return $text;
			}
			else
			{
				$truncate = StringHelper::substr($text, 0, $length);
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact && $length > 10)
		{
			$spacepos = StringHelper::strrpos($truncate, ' ');
			if (isset($spacepos))
			{
				$truncate = StringHelper::substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		// close all unclosed html-tags
		if ($considerHtml)
		{
			foreach ($open_tags as $tag)
			{
				$truncate .= '</'. $tag .'>';
			}
		}

		return $truncate;
	}

}
