<?php 
/*
* Pixel Point Creative - Tile Menu Module
* License: GNU General Public License version
* See: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
*/
// No direct access.
defined('_JEXEC') or die;
$sizeoflevel1 = 0;
if ( isset($menus) && count($menus) ) {
	for( $i = 0; $i < count($menus); $i++ ) {
		if ($menus[$i]->level == 1) {
			$sizeoflevel1++;
		}
	}
}

$span = ''; //' span' . floor(12/$sizeoflevel1);


jimport( 'joomla.html.parameter' );
$document = JFactory::getDocument();
ob_start();
include   "css" . DIRECTORY_SEPARATOR . 'style.php';
$style = ob_get_contents();
ob_end_clean();
$document->addStyleDeclaration( $style );


ob_start();
?>

<div class="tilemenu_<?php echo $module->id;?> tilemenu">
	<?php if(isset($menus) && count($menus)){
		$countUlOpened = 0;
		$level = 1;
		$idx = 1;
		for($i = 0; $i < count($menus); $i++){
			if($i == 0){?>
			<!--<ul class="item_tile_menu" id="item_tile_menu_<?php echo $module->id;?>">-->
			<?php	$countUlOpened++;
			}
			$class = "";
			if($menus[$i]->id == $itemID){
				$class.= 'current';
			}
			$class .= ' level' . $menus[$i]->level;
			
			if ($menus[$i]->level == 1) {
				$class .= ' style' . $idx;
				$idx++;
			}
			
			if ($menus[$i]->level == 1) {
				$li = "<div class='". $class . $span ."'>";
			} else {
				$li = "<li class='". $class ."'>";
			}
			$style = "";
			$target = "";
			switch ($menus[$i]->browserNav) :
				
				case 1:
					$target=" target='_blank' ";
					break;
				case 2:
					$target = " onclick=\"window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;\"";
					break;
			endswitch;
			$icon_menu = ($menus[$i]->menu_image != '') ? '<img src="' . JURI::base() . $menus[$i]->menu_image . '">' : '';
			if ($menus[$i]->level == 1) {
				$divLink = "<label><a ".$target." href='".$menus[$i]->flink."'>".$menus[$i]->title."</a></label>" . $icon_menu;
			} else {
				$divLink = "<a ".$target." href='".$menus[$i]->flink."'>".$menus[$i]->title."</a>";
			}
			 
			$li .= $divLink;
			echo $li;
			if($i < count($menus)-1  && $menus[$i+1]->level > $menus[$i]->level ){
				echo "<ul>";
				$countUlOpened++;
				$level++;
			}
			if($i < count($menus)-1 && $menus[$i+1]->level < $menus[$i]->level ){
				if ($menus[$i]->level == 2) {
					echo "</li></ul></div>";
				} else {
					echo "</li></ul></li>";
				}
				
				$countUlOpened--;
				$level--;
				for($j = 1; $j < $menus[$i]->level - $menus[$i+1]->level; $j++){
					echo "</ul></li>";
					$countUlOpened--;
					$level--;
				}
			}
			if( $i < count($menus)-1 && $menus[$i+1]->level == $menus[$i]->level){
				if ($menus[$i]->level == 1) {
					echo "</div>";
				} else {
					echo "</li>";
				}
			}
		}
		/*for($i=0; $i< $countUlOpened - 1; $i++){
			echo "</li></ul>";
		}
		echo "</li></ul>";*/
	}
?>
</div>

<script language="javascript">
jQuery(document).ready(function () {
	jQuery(".tilemenu_<?php echo $module->id;?> .level1").metnav2({
		
	});
});
</script>
<?php
$content = ob_get_contents();
ob_end_clean();

echo TileMenuHelper::closetags($content); 

//echo "<pre>" . htmlspecialchars($content) . "</pre>"; die;
?>

