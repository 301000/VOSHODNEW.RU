<?php
/*
 * ================================================================
   RAXO All-mode PRO J2.5 - Template
 * ----------------------------------------------------------------
 * @package		RAXO All-mode PRO
 * @subpackage	All-mode Rational Template
 * @copyright	Copyright (C) 2009-2012 RAXO Group
 * @license		GNU General Public License v2.0
				http://www.gnu.org/licenses/gpl-2.0.html
 * @link		http://raxo.org
 * ================================================================
*/

// no direct access
defined('_JEXEC') or die;

// add CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-rational/allmode-rational.css');

// add JS
JHtml::script('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
JHtml::script('modules/mod_raxo_allmode/tmpl/allmode-rational/allmode-rational.js');


// Template Settings
$img_width		= $params->get('image_width', array());
$column_size	= ($img_width[1]) ? $img_width[1] + 12 : 144; // if thumbnail width is not set, default column size is 144px
$column_space	= 12;
?>


<div id="allmode<?php echo $module->id; ?>" class="allmode_itemsbox">
<?php										// ALL-mode Items Output
foreach ($list as $item) { ?>

	<div class="allmode_item" style="width: <?php echo $column_size; ?>px">
		<?php if ($item->comments_count) { ?>
		<a href="<?php echo $item->comments_link; ?>" class="allmode_comments"><?php echo $item->comments_count; ?></a>
		<?php } ?>

		<?php if ($item->title) { ?>
		<h4 class="allmode_title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
		<?php } ?>

		<?php if ($item->image) { ?>
		<div class="allmode_img"><?php echo $item->image; ?></div>
		<?php } ?>

		<?php if ($item->category || $item->hits || $item->author) { ?>
		<div class="allmode_info">
			<?php if ($item->category) { ?>
			<span class="allmode_category"><?php echo $item->category; ?></span>
			<?php } ?>

			<?php if ($item->date) { ?>
			<span class="allmode_date"><?php echo $item->date; ?></span>
			<?php } ?>

			<?php if ($item->hits) { ?>
			<span class="allmode_hits">Hits: <?php echo $item->hits; ?></span>
			<?php } ?>

			<?php if ($item->author) { ?>
			<span class="allmode_author">Author: <?php echo $item->author; ?></span>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($item->text) { ?>
		<div class="allmode_text"><?php echo $item->text; ?></div>
		<?php } ?>

		<?php if ($item->rating) { ?>
		<span class="allmode_rating" title="<?php echo @$item->rating_value; ?>"><?php echo $item->rating; ?></span>
		<?php } ?>

		<?php if ($item->readmore) { ?>
		<span class="allmode_readmore"><?php echo $item->readmore; ?></span>
		<?php } ?>
	</div>
<?php } ?>
</div>

<script type="text/javascript">
jQuery.noConflict();
jQuery(window).load(function($){
	jQuery('#allmode<?php echo $module->id; ?>').masonry({
		singleMode: true,
		columnWidth: <?php echo $column_size+$column_space; ?>,
		animate: true
	});
});
</script>