<?php 
/*
* Pixel Point Creative - Tile Menu Module
* License: GNU General Public License version
* See: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
*/
?>
<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
.tilemenu_<?php echo $module->id;?> .level1{ width: <?php echo 94/$sizeoflevel1?>%;}

.tilemenu_<?php echo $module->id;?> .style1, .style1 > ul
{
    background-color: <?php echo $menu_color1?>;
}

.tilemenu_<?php echo $module->id;?> .style2, .style2 > ul
{
    background-color: <?php echo $menu_color2?>;
}

.tilemenu_<?php echo $module->id;?> .style3, .style3 > ul
{
    background-color: <?php echo $menu_color3?>;
}

.tilemenu_<?php echo $module->id;?> .style4, .style4 > ul
{
    background-color: <?php echo $menu_color4?>;
}

.tilemenu_<?php echo $module->id;?> .style5, .style5 > ul
{
    background-color: <?php echo $menu_color5?>;
}

.tilemenu_<?php echo $module->id;?> .style6, .style6 > ul
{
    background-color: <?php echo $menu_color6?>;
}

@media all and (max-width: 640px) {
	.tilemenu_<?php echo $module->id;?> .level1{ margin-bottom:5px;width: 100% !important;}
}