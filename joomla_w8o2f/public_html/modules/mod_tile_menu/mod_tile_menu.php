<?php 
/*
* Pixel Point Creative - Tile Menu Module
* License: GNU General Public License version
* See: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
*/

?>
<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
$moduleURI = JURI::base()."modules/mod_tile_menu/";
$imagesURI = $moduleURI."tmpl/images";

$menu_direction = $params->get("menu_direction");
$menuType = $params->get("menutype");
$startLevel = $params->get("startlevel", 1);
$endLevel = $params->get("endlevel", "all");
$showSub = $params->get("showsub", "true");
$jquery = $params->get('jquery');
$menu_color1 = $params->get('menu_color1', '#FF8F32');
$menu_color2 = $params->get('menu_color2', '#FF8F32');
$menu_color3 = $params->get('menu_color3', '#FF8F32');
$menu_color4 = $params->get('menu_color4', '#FF8F32');
$menu_color5 = $params->get('menu_color5', '#FF8F32');
$menu_color6 = $params->get('menu_color6', '#FF8F32');

if( !defined('SMART_JQUERY') && $jquery ){
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
} 

if( !defined('TILEMENU')  ){
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery.metnav2.v1.1.js');
	JHtml::stylesheet('modules/'.$module->module.'/tmpl/css/style.css');

	define('TILEMENU', 1);
}	


if (!class_exists('TileMenuHelper')) {
    include   "core" . DIRECTORY_SEPARATOR . 'helper.php';       
}

$menus	= TileMenuHelper::getList($menuType, $startLevel, $endLevel, $showSub);

$input = JFactory::getApplication()->input;
$itemID  = $input->getInt('Itemid'); 


if(count($menus)) {
    require JModuleHelper::getLayoutPath('mod_tile_menu');
}
?>