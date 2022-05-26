<?php 
/*
* Pixel Point Creative - Tile Menu Module
* License: GNU General Public License version
* See: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
*/
defined( '_JEXEC' ) or die( 'Restricted access' ); 

abstract class TileMenuHelper
{
	public static function getMenus($menutype, $startLevel = 1, $endLevel = "all")
	{
		$arrMenus = array();
		
		TileMenuHelper::getSortedMenus($arrMenus, $menutype, null, $startLevel, $endLevel);
		return $arrMenus;
	}
	public static function getSortedMenus(& $arrMenus, $menutype, $parent_id = null , $startLevel, $endLevel){
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			$query->select('a.id, a.title, a.level, a.parent_id, a.link, a.params, a.type');
			$query->from('#__menu AS a');
			$query->where('a.published = 1');
			$where = "menutype=".$db->quote($menutype);

			if(isset($parent_id) && !isset($startLevel)){
				$where.=" AND a.parent_id = ".$parent_id;
			} 
			if(isset($startLevel) && !isset($parent_id)){
				$where .=" AND a.level = ".$startLevel;
			}
			if($endLevel != "all"){
				$where.= " AND a.level <= ".$endLevel; 
			}
			$query->where($where);
			$query->order('lft');
			$db->setQuery($query);
			$menus = $db->loadObjectList();
			
			if(count($menus)){
				foreach ($menus as $menu) {
					$arrMenus[] = $menu;
					TileMenuHelper::getSortedMenus($arrMenus, $menutype, $menu->id, null, $endLevel);
				}
			}
			
		
	}
	static function getList($menuType,$startLevel, $endLevel, $showSub)
	{
		$list		= array();
		$db		= JFactory::getDbo();
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		$active = ($menu->getActive()) ? $menu->getActive() : $menu->getDefault();
		$path		= $active->tree;
		
		$items 		= $menu->getItems('menutype',$menuType);
		$lastitem	= 0;
       	        //$instack = array();
		//$instack[] = $active->id;
		//$instack[] = $active->parent_id;
		
		if ($items) {
			foreach($items as $i => $item)
			{
				
				$itemParams = $item->getParams();
				
				if (($startLevel && $item->level < $startLevel)
					|| ($endLevel && $endLevel != "all" && $item->level > $endLevel  )
					|| ($showSub == "false" && $item->level > $startLevel)
				) {
					unset($items[$i]);
					continue;
				}
				
				
				
				
				if ($item->level > 1 && !in_array($item->parent_id, $path)) {
					unset($items[$i]);
					continue;
				}
				$path[] = $item->id;
				$item->parent = (boolean) $menu->getItems('parent_id', (int) $item->id, true);
				$lastitem			= $i;
				$item->active		= false;
				$item->flink = $item->link;
				
				switch ($item->type)
				{
					case 'separator':
						break;
					case 'url':
						if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
							$item->flink = $item->link.'&Itemid='.$item->id;
						}
						break;
					case 'alias':
						$item->flink = 'index.php?Itemid='.$itemParams->get('aliasoptions');
						break;
					default:
						$item->flink .= '&Itemid='.$item->id;
						break;
				}
				if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {
					$item->flink = JRoute::_($item->flink, true, $itemParams->get('secure'));					
				}
				else {
					$item->flink = JRoute::_($item->flink);
				}
				$item->menu_image = $itemParams->get('menu_image', '') ? htmlspecialchars($itemParams->get('menu_image', '')) : '';
			}
		}
		
		return array_values($items);
	}
	
	static function closetags($html) {
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);

		$closedtags = $result[1];
		$len_opened = count($openedtags);

		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}
}
