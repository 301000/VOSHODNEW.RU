<?php
/*
 * ================================================================
   RAXO All-mode PRO J2.5 - Template
 * ----------------------------------------------------------------
 * @package		RAXO All-mode PRO
 * @subpackage	All-mode Carousel Template
 * @copyright	Copyright (C) 2009-2012 RAXO Group
 * @license		Copyrighted Commercial Software
 * 				This file is forbidden for redistribution
 * @link		http://raxo.org
 * ================================================================
*/

// no direct access
defined('_JEXEC') or die;

// add CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-carousel/allmode-carousel.css');

// add JS
JHtml::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
JHtml::script('modules/mod_raxo_allmode/tmpl/allmode-carousel/allmode-carousel.js');
?>


<div class="allmode_itemsbox">
	<div class="allmode_left"><a class="allmode_prev allmode<?php echo $module->id; ?>_prev"></a></div>
	<div class="allmode_center">


<div class="allmode_slider">
	<div id="allmode<?php echo $module->id; ?>" class="allmode_items">

<?php										// ALL-mode Items Output

$img_width	= $params->get('image_width', array());
$img_space 	= ($img_width[1]) ? $img_width[1] + 8 : 144;
$img_space = 'style="width: '.$img_space.'px"';

foreach ($list as $item) {
?>

	<div class="allmode_item" <?php echo @$img_space; ?>>

		<?php if ($item->image || $item->section || $item->category) { ?>
		<?php if ($item->image) { ?><div class="allmode_show"><?php } ?>
			<div class="allmode_img">
				<?php echo $item->image; ?>

				<?php if ($item->category) { ?>
				<div class="allmode_info">
					<span class="allmode_category"><?php echo $item->category; ?></span>
				</div>
				<?php } ?>

			</div>
		<?php if ($item->image) { ?></div><?php } ?>
		<?php } ?>

		<?php if ($item->title) { ?>
		<h4 class="allmode_title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
		<?php } ?>

		<?php if ($item->text) { ?>
		<div class="allmode_text"><?php echo $item->text; ?>
			<?php if ($item->readmore) { ?>
			&nbsp;<span class="allmode_readmore"><?php echo $item->readmore; ?></span>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($item->date || $item->author || $item->hits) { ?>
		<div class="allmode_details">
			<?php if ($item->date) { ?>
			<span class="allmode_date"><?php echo $item->date; ?></span>
			<?php } ?>

			<?php if ($item->author) { ?>
			<span class="allmode_author" title="Author"><?php echo $item->author; ?></span>
			<?php } ?>

			<?php if ($item->hits) { ?>
			<span class="allmode_hits" title="Hits">Hits: <?php echo $item->hits; ?></span>
			<?php } ?>
		</div>
		<?php } ?>

	</div>

<?php } ?>
	</div>
</div>


	</div>
	<div class="allmode_right"><a class="allmode_next allmode<?php echo $module->id; ?>_next"></a></div>
</div>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
	$('#allmode<?php echo $module->id; ?>').carouFredSel({
//		auto	: false,
		height	: 'auto',
		scroll	: {
			items			: 1,
			duration		: 600,
			pauseDuration	: 4800
		},
		prev	: '.allmode<?php echo $module->id; ?>_prev',
		next	: '.allmode<?php echo $module->id; ?>_next'
	});
});
</script>