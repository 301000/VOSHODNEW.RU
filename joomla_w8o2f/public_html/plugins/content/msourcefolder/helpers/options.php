<?php
/**
* @title				Minitek Source Folder
* @copyright   	Copyright (C) 2011-2019 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

class MSourceFolderOptions
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
			$item->itemType = JText::_('PLG_CONTENT_MSOURCEFOLDER_'.$source['fold_title']);

			// Image
			if ($detailBoxParams['images'])
			{
				$item->itemImageRaw = $item->path;
				$item->itemImage = $item->itemImageRaw;

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

			// Date
			$item->itemDate = JHTML::_('date', $item->created, $detailBoxParams['detailBoxDateFormat']);

			if ($hoverBoxParams['hoverBox'] && $hoverBoxParams['hoverBoxDate'])
			{
				$item->hover_itemDate = JHTML::_('date', $item->created, $hoverBoxParams['hoverBoxDateFormat']);
			}

			$item->itemDateRaw = $item->created;
		}

		return $items;
	}
}
