<?php
/**
* @title		Minitek Source EasyBlog
* @copyright	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\URI\URI;

\JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

class MSourceEasyBlogOptions
{
	public function getDisplayOptions($widgetID, $items, $detailBoxParams, $hoverBoxParams, $navParams = false, $component = 'com_minitekwall')
	{
		if ($component == 'com_minitekwall')
			$this->utilities = new MinitekWallLibUtilities;
		else if ($component == 'com_minitekslider')
			$this->utilities = new MinitekSliderLibUtilities;

		$source = $this->utilities->getSourceParams($widgetID);
		$items = EB::formatter('list', $items);

		foreach ($items as &$item)
		{
			$item->itemID = $item->id;
			$item->itemOrdering = $item->ordering;
			$item->itemAlias = $item->permalink;
			$item->itemModified = $item->modified;
			$item->itemStart = $item->publish_up;
			$item->itemFinish = $item->publish_down;

			// Image
			if ($detailBoxParams['images'])
			{
				$item->itemImageRaw = false;

				if ($source['eb_image_type'] == 'article' && $item->hasImage()) 
					$item->itemImageRaw = $item->getImage('large');
				else
				{
					if ($item->intro) 
					{
						$introtext_temp = strip_tags($item->intro, '<img>');
						preg_match('/<img[^>]+>/i', $introtext_temp, $new_image);

						if (!$new_image) 
						{
							$introtext_temp = strip_tags($item->content, '<img>');
							preg_match('/<img[^>]+>/i', $introtext_temp, $new_image);
						}
					} 
					else 
					{
						$introtext_temp = strip_tags($item->content, '<img>');
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
				}
				
				// Remove host and base path from image path
				$uri = URI::getInstance();
				$host = $uri->getHost();
				$root_string = '//'.$host.URI::root(true).'/';
				
				if (substr($item->itemImageRaw, 0, strlen($root_string)) === $root_string)
					$item->itemImageRaw = str_replace($root_string, '', $item->itemImageRaw);

				// Image fallback
				if (!$item->itemImageRaw)
				{
					if (array_key_exists('fallback_image', $detailBoxParams) && $detailBoxParams['fallback_image'])
						$item->itemImageRaw = URI::root().''.$detailBoxParams['fallback_image'];
				}
				
				// Main image
				if (
					$detailBoxParams['crop_images'] &&
					$item->itemImageRaw &&
					$image = $this->utilities->cropImages($item->itemImageRaw, $detailBoxParams['image_width'], $detailBoxParams['image_height'])
				)
				{
					$item->itemImage = $image;
				}
				else
					$item->itemImage =  $item->itemImageRaw;
				
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
					$item->navImage = URI::root().''.$item->navImage;

				if ($item->itemImage && substr( $item->itemImage, 0, 4 ) !== "http" && substr( $item->itemImage, 0, 1 ) !== "/")
					$item->itemImage = URI::root().''.$item->itemImage;
			}

			// Title
			$item->itemTitle = $this->utilities->wordLimit($item->title, $detailBoxParams['detailBoxTitleLimit']);
			$item->itemTitleRaw = $item->title;

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxTitle'])
				$item->hover_itemTitle = $this->utilities->wordLimit($item->title, $hoverBoxParams['hoverBoxTitleLimit']);

			// Link
			$item->itemLink = $item->getPermalink();

			// Introtext
			$item->itemIntrotext = $item->getIntro(true);
			$item->hover_itemIntrotext = $item->getIntro(true);

			if ($detailBoxParams['detailBoxStripTags'])
			{
				$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->itemIntrotext);
				$item->itemIntrotext = preg_replace('/\[.*\]/', '', $item->itemIntrotext);
				$item->itemIntrotext = $this->utilities->wordLimit($item->itemIntrotext, $detailBoxParams['detailBoxIntrotextLimit']);
			}
			else
				$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->itemIntrotext);

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxStripTags'])
			{
				$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->hover_itemIntrotext);
				$item->hover_itemIntrotext = preg_replace('/\[.*\]/', '', $item->hover_itemIntrotext);
				$item->hover_itemIntrotext = $this->utilities->wordLimit($item->hover_itemIntrotext, $hoverBoxParams['hoverBoxIntrotextLimit']);
			}
			else
				$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->hover_itemIntrotext);

			// Date
			$item->itemDate = JHTML::_('date', $item->created, $detailBoxParams['detailBoxDateFormat']);

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
				$item->hover_itemDate = JHTML::_('date', $item->created, $hoverBoxParams['hoverBoxDateFormat']);

			$item->itemDateRaw = $item->created;

			// Categories
			// itemCategory: html that includes category/categories links
			// itemCategoryRaw: string for data-category attribute (for sorting)
			// itemCategoriesRaw: array for filters creation
			$item->itemCategoriesArray = $item->categories;
			$item->itemCategoriesRaw = [];

			foreach ($item->itemCategoriesArray as $category)
			{
				$item->itemCategoriesRaw[] = array(
					'id' => $category->id, 
					'category_name' => $category->getTitle()
				);
			}

			$item->itemCategory = '';
			$item->itemCategoryRaw = '';

			foreach ($item->categories as $key => $category) 
			{
				$item->itemCategory .= '<a href="' . $category->getPermalink() . '">';
				$item->itemCategory .= $category->getTitle();
				$item->itemCategory .= '</a>';
				$item->itemCategoryRaw .= $this->utilities->cleanName($category->getTitle()) . ' ';

				if ($key < count($item->itemCategoriesArray) - 1) 
					$item->itemCategory .= ',&nbsp;';
			}

			// Author
			$author = $item->getAuthor();
			$item->itemAuthorRaw = $author->getName();
			$item->itemAuthorLink = $author->getPermalink();
			$item->itemAuthor = '<a href="' . $item->itemAuthorLink . '">' . $item->itemAuthorRaw . '</a>';

			// Hits
			$item->itemHits = $item->hits;

			// Tags
			$item->itemTags = $item->getTags();

			if ($item->itemTags)
			{
				$item->itemTagsLayout = '<ul class="tags list-inline">';

				foreach ($item->itemTags as $tag)
				{
					$item->itemTagsLayout .= '<li class="list-inline-item '.$tag->alias.' tag-list0" itemprop="keywords">';
					$item->itemTagsLayout .= '<a href="'.$tag->getPermalink().'" class="btn btn-sm btn-info">';
					$item->itemTagsLayout .= $tag->title;
					$item->itemTagsLayout .= '</a>';
					$item->itemTagsLayout .= '</li>';
				}

				$item->itemTagsLayout .= '</ul>';
			}
		}

		return $items;
	}
}
