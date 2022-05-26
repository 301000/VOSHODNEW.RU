<?php
/*
 * ================================================================
   RAXO All-mode PRO J2.5 - Template
 * ----------------------------------------------------------------
 * @package		RAXO All-mode PRO
 * @subpackage	All-mode Newsbreak Template
 * @copyright	Copyright (C) 2009-2012 RAXO Group
 * @license		Copyrighted Commercial Software
 * 				This file is forbidden for redistribution
 * @link		http://raxo.org
 * ================================================================
*/

// no direct access
defined('_JEXEC') or die;

// add CSS
JHtml::stylesheet('modules/mod_raxo_allmode/tmpl/allmode-newsbreak/allmode-newsbreak.css');


$i = 0;
if ($toplist) {
?>
<div class="allmode_topbox">
<?php										// ALL-mode TOP Items Output
foreach ($toplist as $item) {
?>
	<?php if ($item->image) { ?>
	<div class="allmode_img"><?php echo $item->image; ?></div>
	<?php } ?>

	<div class="allmode_topitem <?php if ($i % 2) {echo 'even';} else {echo 'odd';} ?>">

		<?php if ($item->title) { ?>
		<h3 class="allmode_title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h3>
		<?php } ?>

		<?php if ($item->date || $item->category || $item->author || $item->hits) { ?>
		<div class="allmode_info">
			<?php if ($item->date) { ?>
			<span class="allmode_date"><?php echo $item->date; ?></span>
			<?php } ?>
			<?php if ($item->category) { ?>
			<span class="allmode_category"><?php echo $item->category; ?></span>
			<?php } ?>
			<?php if ($item->author) { ?>
			<span class="allmode_author"><?php echo $item->author; ?></span>
			<?php } ?>
			<?php if ($item->hits) { ?>
			<span class="allmode_hits"><?php echo $item->hits; ?>&nbsp;Hits</span>
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

	</div><div class="allmode_clear"></div>
<?php $i++; } ?>
</div>
<?php } ?>


<div class="allmode_itemsbox">
<?php										// ALL-mode Items Output
foreach ($list as $item) {
?>
	<?php if ($item->date) { ?>
	<span class="allmode_date"><?php echo $item->date; ?></span>
	<?php } ?>

	<div class="allmode_item">

		<div class="allmode_right <?php if ($i % 2) {echo 'even';} else {echo 'odd';} ?>">
			<?php if ($item->title) { ?>
			<h4 class="allmode_title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
			<?php } ?>

			<?php if ($item->category || $item->author || $item->hits) { ?>
			<div class="allmode_info">
				<?php if ($item->category) { ?>
				<span class="allmode_category"><?php echo $item->category; ?></span>
				<?php } ?>
				<?php if ($item->author) { ?>
				<span class="allmode_author"><?php echo $item->author; ?></span>
				<?php } ?>
				<?php if ($item->hits) { ?>
				<span class="allmode_hits"><?php echo $item->hits; ?>&nbsp;Hits</span>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	</div><div class="allmode_clear"></div>
<?php $i++; } ?>
</div>