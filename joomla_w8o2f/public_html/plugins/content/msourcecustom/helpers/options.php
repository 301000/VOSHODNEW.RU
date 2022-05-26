<?php
/**
* @title				Minitek Source Custom
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\URI\URI;

class MSourceCustomOptions
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
			$item->itemID = $item->id;
			$item->itemOrdering = $item->ordering;
			$item->itemModified = $item->modified;
			$item->itemStart = $item->publish_up;
			$item->itemFinish = $item->publish_down;

			// Content type
			$item->itemType = Text::_('PLG_CONTENT_MSOURCECUSTOM_'.$source['custom_title']);

			// Image
			if ($detailBoxParams['images'])
			{
        $images = json_decode($item->images, true);
				$item->itemImageRaw = $images['image'];

        // Image fallback
				if (!$item->itemImageRaw)
				{
					if (array_key_exists('fallback_image', $detailBoxParams) && $detailBoxParams['fallback_image'])
					{
						$item->itemImageRaw = URI::root().''.$detailBoxParams['fallback_image'];
					}
				}

        $item->itemImage =  $item->itemImageRaw;

        // Main image
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
					$item->navImage = URI::root().''.$item->navImage;
				}
				if ($item->itemImage && substr( $item->itemImage, 0, 4 ) !== "http" && substr( $item->itemImage, 0, 1 ) !== "/")
				{
					$item->itemImage = URI::root().''.$item->itemImage;
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
      $urls = json_decode($item->urls, true);

      if ($urls['title_url'])
        $item->itemLink = $urls['title_url'];
      if ($urls['category_url'])
        $item->itemCategoryLink = $urls['category_url'];
      if ($urls['author_url'])
        $item->itemAuthorLink = $urls['author_url'];

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
      $item->itemDate = HTMLHelper::_('date', $item->created, $detailBoxParams['detailBoxDateFormat']);
			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
			{
				$item->hover_itemDate = HTMLHelper::_('date', $item->created, $hoverBoxParams['hoverBoxDateFormat']);
			}
			$item->itemDateRaw = $item->created;

			// Category
      if ($item->category != '')
			{
				$item->itemCategoryRaw = $item->category;
				$item->itemCategory = $item->itemCategoryRaw;
				if (isset($item->itemCategoryLink) && $item->itemCategoryLink)
				{
					$item->itemCategory = '<a href="'.$item->itemCategoryLink.'">'.$item->itemCategoryRaw.'</a>';
				}
			}

      // Author
			if ($item->author)
			{
				$item->itemAuthorRaw = $item->author;
			}
			else
			{
				$item->itemAuthorRaw = Factory::getUser($item->created_by)->name;
			}
			$item->itemAuthor = $item->itemAuthorRaw;
			if (isset($item->itemAuthorLink) && $item->itemAuthorLink)
			{
				$item->itemAuthor = '<a href="'.$item->itemAuthorLink.'">'.$item->itemAuthorRaw.'</a>';
			}

      // Tags
			$item->itemTags = json_decode($item->tags, false);
			foreach ($item->itemTags as $key => $tag)
			{
				if ($tag->title == '')
				{
					unset($item->itemTags->$key);
				}
			}
		}

		return $items;
	}
}
