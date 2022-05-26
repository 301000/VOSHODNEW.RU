<?php
/*
 * ================================================================
   RAXO All-mode PRO J2.5 - Template
 * ----------------------------------------------------------------
 * @package		RAXO All-mode PRO
 * @subpackage	All-mode Grid Template
 * @copyright	Copyright (C) 2009-2012 RAXO Group
 * @license		Copyrighted Commercial Software
 * 				This file is forbidden for redistribution
 * @link		http://raxo.org
 * ================================================================
*/


// no direct access
defined('_JEXEC') or die;

// add CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-grid/allmode-grid.css');

// add JS
JHtml::script('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
JHtml::script('modules/mod_raxo_allmode/tmpl/allmode-grid/allmode-grid.js');

$img_width = $params->get('image_width', array());
$img_space = ($img_width[1]) ? $img_width[1] + 16 : 160;


if ($list) { ?>

<div id="allmode<?php echo $module->id; ?>">

	<div class="allmode_switch">
		<span class="allmode_sw_grid">GRID</span>
		<span class="allmode_sw_list active">LIST</span>
	</div>

	<div class="allmode_itemsbox allmode_items_grid">

<?php										// ALL-mode Items Output
foreach ($list as $item) { ?>

		<div class="allmode_item">

			<?php if ($item->image) { ?>
			<div class="allmode_img">
				<?php echo $item->image; ?>
				<?php if ($item->category) { ?>
					<div class="allmode_category"><div><?php echo $item->category; ?></div><span></span></div>
				<?php } ?>
			</div>
			<?php } ?>

			<div class="allmode_info">

				<?php if ($item->title) { ?>
				<h4 class="allmode_title">

					<?php if ($item->comments_count) { ?>
					<div class="allmode_comments" title="<?php echo @$item->comments_count; ?> comments"><?php echo $item->comments_count; ?></div>
					<?php } ?>

					<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>

				</h4>
				<?php } ?>

				<?php if ($item->author || $item->date || $item->hits || $item->rating) { ?>
				<div class="allmode_details">

					<?php if ($item->author) { ?>
					<span class="allmode_author"><?php echo $item->author; ?></span>
					<?php } ?>

					<?php if ($item->date) { ?>
					<span class="allmode_date"><?php echo $item->date; ?></span>
					<?php } ?>

					<?php if ($item->hits) { ?>
					<span class="allmode_hits">Hits: <?php echo $item->hits; ?></span>
					<?php } ?>

					<?php if ($item->rating) { ?>
					<span class="allmode_rating" title="<?php echo @$item->rating_value; ?>"><?php echo $item->rating; ?></span>
					<?php } ?>

				</div>
				<?php } ?>

				<?php if ($item->text) { ?>
				<div class="allmode_text"><?php echo $item->text; ?>
					<?php if ($item->readmore) { ?>
					<span class="allmode_readmore"><?php echo $item->readmore; ?></span>
					<?php } ?>
				</div>
				<?php } ?>

			</div>

		</div>
<?php } ?>

	</div>

</div>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
	$('#allmode<?php echo $module->id; ?>').layoutSwitch({
		default_layout	: 'grid',						// 'grid' or 'list'
		column_width	: '<?php echo $img_space; ?>',	// grid column width in px
		use_cookies		: true							// true or false
	});
});
</script>

<?php } ?>