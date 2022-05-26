<?php
/**
 * =============================================================
 * @package		RAXO List Module Layout
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2021 RAXO Group
 * @link		https://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;


// Layout - CSS
JHtml::stylesheet("modules/$module->module/tmpl/raxo-list/raxo-list.css");
?>


<?php
// Layout - Block Name
if ($block->name) : ?>
<h3 class="raxo-block-name"><?php echo $block->name; ?></h3>
<?php endif; ?>

<?php
// Layout - Block Button
if ($block->button) : ?>
<div class="raxo-block-button"><?php echo $block->button; ?></div>
<?php endif; ?>

<?php
// Layout - Block Intro
if ($block->intro) : ?>
<div class="raxo-block-intro"><?php echo $block->intro; ?></div>
<?php endif; ?>


<div class="raxo-container">

	<?php
	// =============================================================
	// Layout - TOP Items
	foreach ($toplist as $item) : ?>
	<article class="raxo-item-top<?php
		if ($item->category_id) echo " raxo-category-id$item->category_id";
		if ($item->featured) echo " raxo-featured";
	?>"><div class="raxo-wrap">

		<div class="raxo-content">

			<?php if ($item->date || $item->category || $item->author || $item->hits || $item->comments_count || $item->rating_value) : ?>
			<div class="raxo-info">

				<?php if ($item->date) : ?>
				<span class="raxo-date"><?php echo $item->date; ?></span>
				<?php endif; ?>

				<?php if ($item->category) : ?>
				<span class="raxo-category"><?php echo $item->category; ?></span>
				<?php endif; ?>

				<?php if ($item->author) : ?>
				<span class="raxo-author"><?php echo $item->author; ?></span>
				<?php endif; ?>

				<?php if ($item->hits) : ?>
				<span class="raxo-hits"><?php echo $item->hits; ?></span>
				<?php endif; ?>

				<?php if ($item->comments_count) : ?>
				<span class="raxo-comments"><?php echo $item->comments_count; ?></span>
				<?php endif; ?>

				<?php if ($item->rating_value) : ?>
				<span class="raxo-rating"><?php echo $item->rating_value; ?></span>
				<?php endif; ?>

			</div>
			<?php endif; ?>

			<?php if ($item->title) : ?>
			<h3 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h3>
			<?php endif; ?>

			<?php if ($item->text) : ?>
			<div class="raxo-text"><?php echo $item->text; ?></div>
			<?php endif; ?>

		</div>

		<?php if ($item->image) : ?>
		<div class="raxo-image"><?php echo $item->image; ?></div>
		<?php endif; ?>

		<a href="<?php echo $item->link; ?>" class="raxo-readmore"></a>

	</div></article>
	<?php endforeach; ?>


	<?php
	// =============================================================
	// Layout - Normal Items
	foreach ($list as $item) : ?>
	<article class="raxo-item-nor<?php
		if ($item->category_id) echo " raxo-category-id$item->category_id";
		if ($item->featured) echo " raxo-featured";
	?>"><div class="raxo-wrap">

		<div class="raxo-content">

			<?php if ($item->date) : ?>
			<div class="raxo-date"><?php echo $item->date; ?></div>
			<?php endif; ?>

			<div class="raxo-right">

				<?php if ($item->title) : ?>
				<h4 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
				<?php endif; ?>

				<?php if ($item->category || $item->author || $item->hits || $item->comments_count || $item->rating_value) : ?>
				<div class="raxo-info">

					<?php if ($item->category) : ?>
					<span class="raxo-category"><?php echo $item->category; ?></span>
					<?php endif; ?>

					<?php if ($item->author) : ?>
					<span class="raxo-author"><?php echo $item->author; ?></span>
					<?php endif; ?>

					<?php if ($item->hits) : ?>
					<span class="raxo-hits"><?php echo $item->hits; ?></span>
					<?php endif; ?>

					<?php if ($item->comments_count) : ?>
					<span class="raxo-comments"><?php echo $item->comments_count; ?></span>
					<?php endif; ?>

					<?php if ($item->rating_value) : ?>
					<span class="raxo-rating"><?php echo $item->rating_value; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->text) : ?>
				<div class="raxo-text"><?php echo $item->text; ?></div>
				<?php endif; ?>

			</div>
		</div>

		<?php if ($item->image) : ?>
		<div class="raxo-image"><?php echo $item->image; ?></div>
		<?php endif; ?>

		<a href="<?php echo $item->link; ?>" class="raxo-readmore"></a>

	</div></article>
	<?php endforeach; ?>

</div>
