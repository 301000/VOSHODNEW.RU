<?php
/**
 * =============================================================
 * @package		RAXO Default Module Layout
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2021 RAXO Group
 * @link		https://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;


// Layout - CSS
JHtml::stylesheet("modules/$module->module/tmpl/raxo-default/raxo-default.css");
?>


<?php if ($block->name || $block->intro) : ?>
<div class="raxo-header">

	<?php
	// Layout - Block Name
	if ($block->name) : ?>
	<h3 class="raxo-block-name"><?php echo $block->name; ?></h3>
	<?php endif; ?>

	<?php
	// Layout - Block Intro
	if ($block->intro) : ?>
	<div class="raxo-block-intro"><?php echo $block->intro; ?></div>
	<?php endif; ?>

</div>
<?php endif; ?>


<div class="raxo-container">

	<?php
	// =============================================================
	// Layout - TOP Items
	if ($toplist) : ?>
	<div class="raxo-top">

		<?php foreach ($toplist as $item) : ?>
		<article class="raxo-item-top<?php
			if ($item->category_id) echo " raxo-category-id$item->category_id";
			if ($item->featured) echo " raxo-featured";
		?>"><div class="raxo-wrap">

			<?php if ($item->image) : ?>
			<div class="raxo-image"><?php echo $item->image; ?></div>
			<?php endif; ?>

			<?php if ($item->title || $item->text || $item->date || $item->category || $item->author) : ?>
			<div class="raxo-content">

				<?php if ($item->date || $item->hits || $item->comments_count || $item->rating) : ?>
				<div class="raxo-meta">

					<?php if ($item->date) : ?>
					<span class="raxo-date"><?php echo $item->date; ?></span>
					<?php endif; ?>

					<?php if ($item->hits) : ?>
					<span class="raxo-hits"><?php echo $item->hits; ?></span>
					<?php endif; ?>

					<?php if ($item->comments_count) : ?>
					<span class="raxo-comments"><a href="<?php echo $item->comments_link; ?>"><?php echo $item->comments_count; ?></a></span>
					<?php endif; ?>

					<?php if ($item->rating) : ?>
					<span class="raxo-rating" title="<?php echo @$item->rating_value; ?>"><?php echo $item->rating; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->title) : ?>
				<h3 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h3>
				<?php endif; ?>

				<?php if ($item->category || $item->author) : ?>
				<div class="raxo-info">

					<?php if ($item->category) : ?>
					<?php echo JText::_('JCATEGORY') .': '; ?><span class="raxo-category"><?php echo $item->category; ?></span>
					<?php endif; ?>

					<?php if ($item->author) : ?>
					<?php echo JText::_('JAUTHOR') .': '; ?><span class="raxo-author"><?php echo $item->author; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->text) : ?>
				<div class="raxo-text"><?php echo $item->text; ?>

					<?php if ($item->readmore) : ?>
					<span class="raxo-readmore"><?php echo $item->readmore; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div></article>
		<?php endforeach; ?>

	</div>
	<?php endif; ?>


	<?php
	// =============================================================
	// Layout - Normal Items
	if ($list) : ?>
	<div class="raxo-normal">

		<?php foreach ($list as $item) : ?>
		<article class="raxo-item-nor<?php
			if ($item->category_id) echo " raxo-category-id$item->category_id";
			if ($item->featured) echo " raxo-featured";
		?>"><div class="raxo-wrap">

			<?php if ($item->image) : ?>
			<div class="raxo-image"><?php echo $item->image; ?></div>
			<?php endif; ?>

			<?php if ($item->title || $item->text || $item->date || $item->category || $item->author) : ?>
			<div class="raxo-content">

				<?php if ($item->date || $item->hits || $item->comments_count || $item->rating) : ?>
				<div class="raxo-meta">

					<?php if ($item->date) : ?>
					<span class="raxo-date"><?php echo $item->date; ?></span>
					<?php endif; ?>

					<?php if ($item->hits) : ?>
					<span class="raxo-hits"><?php echo $item->hits; ?></span>
					<?php endif; ?>

					<?php if ($item->comments_count) : ?>
					<span class="raxo-comments"><a href="<?php echo $item->comments_link; ?>"><?php echo $item->comments_count; ?></a></span>
					<?php endif; ?>

					<?php if ($item->rating) : ?>
					<span class="raxo-rating" title="<?php echo @$item->rating_value; ?>"><?php echo $item->rating; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->title) : ?>
				<h4 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
				<?php endif; ?>

				<?php if ($item->category || $item->author) : ?>
				<div class="raxo-info">

					<?php if ($item->category) : ?>
					<span class="raxo-category"><?php echo $item->category; ?></span>
					<?php endif; ?>

					<?php if ($item->author) : ?>
					<span class="raxo-author"><?php echo $item->author; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->text) : ?>
				<div class="raxo-text"><?php echo $item->text; ?>

					<?php if ($item->readmore) : ?>
					<span class="raxo-readmore"><?php echo $item->readmore; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div></article>
		<?php endforeach; ?>

	</div>
	<?php endif; ?>

</div>


<?php
// Layout - Block Button
if ($block->button) : ?>
<div class="raxo-block-button"><?php echo $block->button; ?></div>
<?php endif; ?>
