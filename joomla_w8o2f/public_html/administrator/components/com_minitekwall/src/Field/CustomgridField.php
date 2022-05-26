<?php
/**
* @title				Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\Field;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

\JFormHelper::loadFieldClass('list');

class CustomGridField extends \JFormFieldList
{
  protected $type = 'CustomGrid';

  protected function getOptions()
  {
    $db = Factory::getDBO();

    $query = 'SELECT g.id as value, g.name as text FROM #__minitek_wall_grids g ';
    $query .= 'WHERE state = 1 ORDER BY g.name';

    $db->setQuery($query);
		$grids = $db->loadObjectList();
		$options = array();

    foreach ($grids as $grid)
		{
			$options[] = HTMLHelper::_('select.option', $grid->value, $grid->text);
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
  }
}
