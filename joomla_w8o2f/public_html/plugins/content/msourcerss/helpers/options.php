<?php
/**
* @title				Minitek Source RSS
* @copyright   	Copyright (C) 2011-2019 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

class MSourceRSSOptions
{
	public function getDisplayOptions($widgetID, $items, $detailBoxParams, $hoverBoxParams, $navParams = false, $component)
	{
		if ($component == 'com_minitekwall')
    {
      $this->utilities = new MinitekWallLibUtilities;
    }
    else if ($component == 'com_minitekslider')
    {
      $this->utilities = new MinitekSliderLibUtilities;
    }

		$source = $this->utilities->getSourceParams($widgetID);

		foreach ($items as &$item)
		{
			// Content type
			$item->itemType = JText::_('PLG_CONTENT_MSOURCERSS_'.$source['rss_title']);

			// Image
			if ($detailBoxParams['images'])
			{
				$item->itemImageRaw = false;

				if ($item->image)
				{
					$item->itemImageRaw = $item->image;
				}
				else
				{
					$introtext_temp = strip_tags($item->description, '<img>');
					preg_match('/<img[^>]+>/i', $introtext_temp, $new_image);

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
					{
						$item->itemImageRaw = $src;
					}
				}

				// Fallback image
				if (!$item->itemImageRaw && $detailBoxParams['fallback_image'])
				{
					$item->itemImageRaw = JURI::root().''.$detailBoxParams['fallback_image'];
				}

				// Final image
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

			// Link
			$item->itemLink = $item->link;

			// Introtext
			$item->itemIntrotext = $item->description;
			$item->itemIntrotext = preg_replace('/\{.*\}/', '', $item->itemIntrotext);
			$item->hover_itemIntrotext = $item->description;
			$item->hover_itemIntrotext = preg_replace('/\{.*\}/', '', $item->hover_itemIntrotext);

			if ($detailBoxParams['detailBoxStripTags'])
			{
				$item->itemIntrotext = preg_replace('/\[.*\]/', '', $item->itemIntrotext);
				$item->itemIntrotext = $this->utilities->wordLimit($item->itemIntrotext, $detailBoxParams['detailBoxIntrotextLimit']);
			}

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxStripTags'])
			{
				$item->hover_itemIntrotext = preg_replace('/\[.*\]/', '', $item->hover_itemIntrotext);
				$item->hover_itemIntrotext = $this->utilities->wordLimit($item->hover_itemIntrotext, $hoverBoxParams['hoverBoxIntrotextLimit']);
			}

			// Date
			$item->itemDate = JHTML::_('date', $item->created, $detailBoxParams['detailBoxDateFormat']);

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
			{
				$item->hover_itemDate = JHTML::_('date', $item->created, $hoverBoxParams['hoverBoxDateFormat']);
			}

			$item->itemDateRaw = $item->created;

			// Author
			$author = $item->author;
			$item->itemAuthorRaw = $author;
			$item->itemAuthor = $item->itemAuthorRaw;

			// Category
			$item->itemCategoryRaw = $item->category;
			$item->itemCategory = $item->itemCategoryRaw;
		}

		return $items;
	}
}
