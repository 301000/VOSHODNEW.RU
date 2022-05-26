<?php
/*
 * ================================================================
   RAXO All-mode PRO J2.5 - Template
 * ----------------------------------------------------------------
 * @package		RAXO All-mode PRO
 * @subpackage	All-mode Pagination Template
 * @copyright	Copyright (C) 2009-2012 RAXO Group
 * @license		Copyrighted Commercial Software
 * 				This file is forbidden for redistribution
 * @link		http://raxo.org
 * ================================================================
*/


// no direct access
defined('_JEXEC') or die;

// add CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-pagination/allmode-pagination.css');

// add JS
JHtml::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
JHtml::script('modules/mod_raxo_allmode/tmpl/allmode-pagination/allmode-pagination.js');
?>

<div id="allmode<?php echo $module->id; ?>" class="allmode_itemsbox">
<ul class="allmode_items">
<?php										// ALL-mode Items Output
foreach ($list as $item) {

$img_space = intval($params->get('image_width'));
	if ($item->image && $img_space) {
		$img_space = $img_space + 14;
		$img_space = 'style="padding-left: '.$img_space.'px"';
	} else {
		$img_space = null;
	}
?>

	<li class="allmode_item">

		<?php if ($item->image) { ?>
		<div class="allmode_img"><?php echo $item->image; ?></div>
		<?php } ?>

		<div class="allmode_right" <?php echo @$img_space; ?>>

			<?php if ($item->date || $item->category) { ?>
			<div class="allmode_info">
				<?php if ($item->date) { ?>
				<span class="allmode_date"><?php echo $item->date; ?></span>
				<?php } ?>

				<?php if ($item->category) { ?>
				<span class="allmode_category"><?php echo $item->category; ?></span>
				<?php } ?>
			</div>
			<?php } ?>

			<?php if ($item->title) { ?>
			<h4 class="allmode_title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
			<?php } ?>

			<?php if ($item->text) { ?>
			<div class="allmode_text"><?php echo $item->text; ?>
				<?php if ($item->readmore) { ?>
				<span class="allmode_readmore"><?php echo $item->readmore; ?></span>
				<?php } ?>
			</div>
			<?php } ?>

			<?php if ($item->author || $item->hits) { ?>
			<div class="allmode_details">
				<?php if ($item->author) { ?>
				<span class="allmode_author" title="Author"><?php echo $item->author; ?></span>
				<?php } ?>

				<?php if ($item->hits) { ?>
				<span class="allmode_hits" title="Hits"><?php echo $item->hits; ?></span>
				<?php } ?>
			</div>
			<?php } ?>

		</div>

	</li>
<?php } ?>
</ul>
<?php if (count($list) > 5) { ?>
<div class="allmode_pagenav"></div>
<?php } ?>
</div>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
	$('#allmode<?php echo $module->id; ?>').pajinate({
		items_per_page : 5,
		nav_label_prev : 'PREV',
		nav_label_next : 'NEXT'
	});
});
</script>