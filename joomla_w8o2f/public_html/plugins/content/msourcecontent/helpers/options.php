<?php
/**
* @title		Minitek Source Content
* @copyright	Copyright (C) 2011-2022 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Categories\Categories;
\JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

class MSourceContentOptions
{
	// Get Joomla parent categories
  	public static function getJoomlaParentCats($catid, $maxlevel, $level = 0, $cats = array())
	{
		$categories = Categories::getInstance('Content');
		$cat = $categories->get($catid);

		if ($cat->getParent() && $cat->getParent()->id != 'root')
		{
			$parent = $cat->getParent();
			$level++;

			if ($level <= $maxlevel)
			{
				array_push(
					$cats, array(
						'id' => $parent->id, 
						'title' => $parent->title,
						'date' => $parent->created_time,
						'ordering' => $parent->rgt
					)
				);
				$cats = self::getJoomlaParentCats($parent->id, $maxlevel, $level, $cats);
			}
		}

		return $cats;
	}

	public function getDisplayOptions($widgetID, $items, $detailBoxParams, $hoverBoxParams, $navParams = false, $component = 'com_minitekwall')
	{
		$lang = \JFactory::getLanguage();
		$lang->load('plg_content_msourcecontent.sys', JPATH_ADMINISTRATOR);

		if ($component == 'com_minitekwall')
			$this->utilities = new MinitekWallLibUtilities;
		else if ($component == 'com_minitekslider')
			$this->utilities = new MinitekSliderLibUtilities;

		$source = $this->utilities->getSourceParams($widgetID);
		$joomla_mode = $source['joomla_mode'];
		$nav_images = $navParams && $navParams['navigator'] && $navParams['navigator_images'];

		if (!$items)
			return false;

		foreach ($items as &$item)
		{
			if ($joomla_mode == 'ja')
			{
				$item->itemID = $item->id;
				$item->itemOrdering = $item->ordering;
				$item->itemFOrdering = $item->fordering;
				$item->itemAlias = $item->alias;
				$item->itemModified = $item->modified;
				$item->itemStart = $item->publish_up;
				$item->itemFinish = $item->publish_down;

				// Image
				if ($detailBoxParams['images'] || $nav_images)
				{
					$item->itemImageRaw = false;
					$images = json_decode($item->images, true);

					if ($source['ja_image_type'] == 'introtext' && isset($images['image_intro']))
					{
						$item->itemImageRaw = $images['image_intro'];
					}
					else if ($source['ja_image_type'] == 'fulltext' && isset($images['image_fulltext']))
					{
						$item->itemImageRaw = $images['image_fulltext'];
					}
					else if ($source['ja_image_type'] == 'inline')
					{
						$introtext_temp = strip_tags($item->introtext, '<img>');
						preg_match('/<img[^>]+>/i', $introtext_temp, $new_image);

						if (!$new_image) 
						{
							$introtext_temp = strip_tags($item->fulltext, '<img>');
							preg_match('/<img[^>]+>/i', $introtext_temp, $new_image);
						}

						$src = false;

						if ($new_image && function_exists('mb_convert_encoding'))
						{
							$new_image[0] = mb_convert_encoding($new_image[0], 'HTML-ENTITIES', "UTF-8");
							$doc = new DOMDocument();
							$doc->loadHTML($new_image[0]);
							$xpath = new DOMXPath($doc);
							$src = $xpath->evaluate("string(//img/@src)");
						}

						if ($src)
							$item->itemImageRaw = $src;
						else if (isset($images['image_intro']))
							$item->itemImageRaw = $images['image_intro'];
					}

					// Image fallback
					if (!$item->itemImageRaw)
					{
						if (array_key_exists('fallback_image', $detailBoxParams) && $detailBoxParams['fallback_image'])
							$item->itemImageRaw = JURI::root().''.$detailBoxParams['fallback_image'];
					}

					$item->itemImage =  $item->itemImageRaw;

          			// Slider image
					if (
						$detailBoxParams['crop_images'] &&
						$item->itemImage &&
						$image = $this->utilities->cropImages($item->itemImage, $detailBoxParams['image_width'], $detailBoxParams['image_height'])
					)
					{
						$item->itemImage = $image;
					}

					// Navigator image
					if (isset($navParams['navigator_images']) && $navParams['navigator_images'])
					{
						$nav_width = $navParams['navigator_width'] - (2 * $navParams['navigator_border']);
						$nav_height = $navParams['navigator_height'] - (2 * $navParams['navigator_border']);
	
						if ($item->itemImageRaw &&
							$thumb = $this->utilities->cropImages($item->itemImageRaw, $nav_width, $nav_height)
						)
						{
							$item->navImage = $thumb;
						}
					}

					// Experimental - Make sure that we don't have a relative image path
					if (isset($item->navImage) && $item->navImage && substr( $item->navImage, 0, 4 ) !== "http" && substr( $item->navImage, 0, 1 ) !== "/")
						$item->navImage = JURI::root().''.$item->navImage;
					
					if ($item->itemImage && substr( $item->itemImage, 0, 4 ) !== "http" && substr( $item->itemImage, 0, 1 ) !== "/")
						$item->itemImage = JURI::root().''.$item->itemImage;
				}

				// Title
				$item->itemTitle = $this->utilities->wordLimit($item->title, $detailBoxParams['detailBoxTitleLimit']);
				$item->itemTitleRaw = $item->title;

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxTitle'])
				{
					$item->hover_itemTitle = $this->utilities->wordLimit($item->title, $hoverBoxParams['hoverBoxTitleLimit']);
				}

				// Links
				$item->slug = $item->id.':'.$item->alias;
				$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;
				$item->itemLink = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
				$item->itemCategoryLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));

				// Introtext
				if ($detailBoxParams['detailBoxStripTags'])
				{
					$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->introtext);
					$item->itemIntrotext = preg_replace('/\[.*\]/', '', $item->itemIntrotext);
					$item->itemIntrotext = $this->utilities->wordLimit($item->itemIntrotext, $detailBoxParams['detailBoxIntrotextLimit']);
				}
				else
				{
					$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->introtext);

				}

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxStripTags'])
				{
					$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->introtext);
					$item->hover_itemIntrotext = preg_replace('/\[.*\]/', '', $item->hover_itemIntrotext);
					$item->hover_itemIntrotext = $this->utilities->wordLimit($item->hover_itemIntrotext, $hoverBoxParams['hoverBoxIntrotextLimit']);
				}
				else
				{
					$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->introtext);
				}

				// Date
				$ja_date_field = $source['ja_date_field'];
				$item->itemDate = JHTML::_('date', $item->$ja_date_field, $detailBoxParams['detailBoxDateFormat']);

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
				{
					$item->hover_itemDate = JHTML::_('date', $item->$ja_date_field, $hoverBoxParams['hoverBoxDateFormat']);
				}

				$item->itemDateRaw = $item->created;

				// Category
				// itemCategory: html that includes category/categories links
				// itemCategoriesRaw: array for dynamic filters creation
				$item->itemCategoriesRaw = array(
					array(
						'id' => $item->catid, 
						'title' => $item->category_title,
						'date' => $item->category_date,
						'ordering' => $item->category_order
					)
				);
				$maxlevel = isset($source['ja_filter_levels']) ? (int)$source['ja_filter_levels'] : 0;
				$parents = self::getJoomlaParentCats($item->catid, $maxlevel);

				if ($parents)
				{
					foreach ($parents as $parent)
					{
						array_push(
							$item->itemCategoriesRaw, 
							array(
								'id' => $parent['id'], 
								'title' => $parent['title'],
								'date' => $parent['date'],
								'ordering' => $parent['ordering']
							)
						);
					}
				}

				$item->itemCategory = '';
				
				if (isset($detailBoxParams['detailBoxCategoryLink']) && $detailBoxParams['detailBoxCategoryLink'])
					$item->itemCategory .= '<a href="'.$item->itemCategoryLink.'">';
					
				$item->itemCategory .= $item->category_title;
				
				if (isset($detailBoxParams['detailBoxCategoryLink']) && $detailBoxParams['detailBoxCategoryLink'])
					$item->itemCategory .= '</a>';

				// Author
				$item->itemAuthorRaw = $item->author;
				$item->itemAuthor = $item->itemAuthorRaw;

				// Hits
				$item->itemHits = $item->hits;

				// Tags
				$item_tags = new JHelperTags;
				$item->itemTags = $item_tags->getItemTags('com_content.article', $item->id);
				$item->tagLayout = new JLayoutFile('joomla.content.tags');
				$item->itemTagsLayout = $item->tagLayout->render($item->itemTags);

				// Tag filters 
				$item->itemTagsRaw = array();

				if ($item->itemTags)
				{
					foreach ($item->itemTags as $tag)
					{
						($item->itemTagsRaw)[$tag->id] = array(
							'id' => $tag->id,
							'title' => $tag->title,
							'date' => $tag->created_time,
							'ordering' => $tag->rgt
						);
					}
				}
			}
			else if ($joomla_mode == 'jc')
			{
				$item->itemID = $item->id;

				// Image
				if ($detailBoxParams['images'] || $nav_images)
				{
					$cat_params = json_decode($item->params, true);
					$item->itemImageRaw = isset($cat_params['image']) ? $cat_params['image'] : false;

					// Image fallback
					if (!$item->itemImageRaw)
					{
						if (array_key_exists('fallback_image', $detailBoxParams) && $detailBoxParams['fallback_image'])
						{
							$item->itemImageRaw = JURI::root().''.$detailBoxParams['fallback_image'];
						}
					}

					$item->itemImage =  $item->itemImageRaw;

					// Slider image
					if (
						$detailBoxParams['crop_images'] &&
						$item->itemImage &&
						$image = $this->utilities->cropImages($item->itemImage, $detailBoxParams['image_width'], $detailBoxParams['image_height'])
					)
					{
						$item->itemImage = $image;
					}

					// Navigator image
					if (isset($navParams['navigator_images']) &&
						$navParams['navigator_images'] &&
						$item->itemImageRaw &&
						$thumb = $this->utilities->cropImages($item->itemImageRaw, $navParams['navigator_width'], $navParams['navigator_height'])
					)
					{
						$item->navImage = $thumb;
					}

					// Experimental - Make sure that we don't have a relative image path
					if (isset($item->navImage) && $item->navImage && substr( $item->navImage, 0, 4 ) !== "http" && substr( $item->navImage, 0, 1 ) !== "/")
					{
						$item->navImage = JURI::root().''.$item->navImage;
					}
					if ($item->itemImage && substr( $item->itemImage, 0, 4 ) !== "http" && substr( $item->itemImage, 0, 1 ) !== "/")
					{
						$item->itemImage = JURI::root().''.$item->itemImage;
					}
				}

				// Title
				$item->itemTitle = $this->utilities->wordLimit($item->title, $detailBoxParams['detailBoxTitleLimit']);
				$item->itemTitleRaw = $item->title;

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxTitle'])
				{
					$item->hover_itemTitle = $this->utilities->wordLimit($item->title, $hoverBoxParams['hoverBoxTitleLimit']);
				}

				// Links
				$item->itemLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));

				// Introtext
				if ($detailBoxParams['detailBoxStripTags'])
				{
					$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->description);
					$item->itemIntrotext = preg_replace('/\[.*\]/', '', $item->itemIntrotext);
					$item->itemIntrotext = $this->utilities->wordLimit($item->itemIntrotext, $detailBoxParams['detailBoxIntrotextLimit']);
				}
				else
				{
					$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->description);
				}

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxStripTags'])
				{
					$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->description);
					$item->hover_itemIntrotext = preg_replace('/\[.*\]/', '', $item->hover_itemIntrotext);
					$item->hover_itemIntrotext = $this->utilities->wordLimit($item->hover_itemIntrotext, $hoverBoxParams['hoverBoxIntrotextLimit']);
				}
				else
				{
					$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->description);
				}

				// Date
				$item->itemDate = JHTML::_('date', $item->created_time, $detailBoxParams['detailBoxDateFormat']);

				if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
				{
					$item->hover_itemDate = JHTML::_('date', $item->created_time, $hoverBoxParams['hoverBoxDateFormat']);
				}

				$item->itemDateRaw = $item->created_time;

				// Category		
				$maxlevel = 1;		
				$item->itemCategoriesRaw = $this->getJoomlaParentCats($item->id, $maxlevel);

				if (isset($item->itemCategoriesRaw[0]))
				{
					$item->itemCategoryLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->itemCategoriesRaw[0]['id']));
					$item->itemCategory = '';

					if (isset($detailBoxParams['detailBoxCategoryLink']) && $detailBoxParams['detailBoxCategoryLink'])
						$item->itemCategory .= '<a href="'.$item->itemCategoryLink.'">';
						
					$item->itemCategory .= $item->itemCategoriesRaw[0]['title'];
					
					if (isset($detailBoxParams['detailBoxCategoryLink']) && $detailBoxParams['detailBoxCategoryLink'])
						$item->itemCategory .= '</a>';
				}
				
				// Author
				$item->itemAuthorRaw = $item->created_user_id;
				$item->itemAuthor = JFactory::getUser($item->itemAuthorRaw)->name;

				// Count
				$options = array();
				$options['countItems'] = true;
				$categories = Categories::getInstance('Content', $options);
				$cat = $categories->get($item->id);
				$item->itemCount = $cat->numitems;

				if ($item->itemCount == 1)
				{
					$item->itemCount = $item->itemCount.'&nbsp;'.JText::_('PLG_CONTENT_MSOURCECONTENT_ARTICLE');
				}
				else
				{
					$item->itemCount = $item->itemCount.'&nbsp;'.JText::_('PLG_CONTENT_MSOURCECONTENT_ARTICLES');
				}

				// Hits
				$item->itemHits = $item->hits;

				// Tags
				$item_tags = new JHelperTags;
				$item->itemTags = $item_tags->getItemTags('com_content.category', $item->id);
				$item->tagLayout = new JLayoutFile('joomla.content.tags');
				$item->itemTagsLayout = $item->tagLayout->render($item->itemTags);

				// Tag filters 
				$item->itemTagsRaw = array();

				if ($item->itemTags)
				{
					foreach ($item->itemTags as $tag)
					{
						($item->itemTagsRaw)[$tag->id] = array(
							'id' => $tag->id,
							'title' => $tag->title,
							'date' => $tag->created_time,
							'ordering' => $tag->rgt
						);
					}
				}
			}
		}

		return $items;
	}
}
