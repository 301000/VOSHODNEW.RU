<?php
/**
 * =============================================================
 * @package		RAXO Bricks Module Layout
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2021 RAXO Group
 * @link		https://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;


// Layout - CSS
JHtml::stylesheet("modules/$module->module/tmpl/raxo-bricks/raxo-bricks.css");

// Layout - JS
JHtml::script("modules/$module->module/tmpl/raxo-bricks/raxo-bricks.js");


// Layout - Options
$columns_number = ['sm' => 2, 'md' => 3, 'lg' => 4, 'xl' => 6];

preg_match_all('/nor-(sm|md|lg|xl)-col(\d)/i', $module_class, $columns_manual);
if (isset($columns_manual[0]))
{
	$columns_manual = array_combine($columns_manual[1], $columns_manual[2]);
	$columns_number = array_merge($columns_number, $columns_manual);
}
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

	<?php
	// Layout - Block Button
	if ($block->button) : ?>
	<div class="raxo-block-button"><?php echo $block->button; ?></div>
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
			<div class="raxo-image">

				<?php echo $item->image; ?>

				<?php if ($item->category) : ?>
					<div class="raxo-category"><?php echo $item->category; ?></div>
				<?php endif; ?>

			</div>
			<?php endif; ?>


			<?php if ($item->title || $item->text || $item->date || $item->author || $item->readmore) : ?>
			<div class="raxo-content">

				<?php if ($item->date || $item->author) : ?>
				<div class="raxo-info">

					<?php if ($item->date) : ?>
					<span class="raxo-date"><?php echo $item->date; ?></span>
					<?php endif; ?>

					<?php if ($item->author) : ?>
					<span class="raxo-author"><?php echo $item->author; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>


				<?php if ($item->title) : ?>
				<h3 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h3>
				<?php endif; ?>


				<?php if ($item->hits || $item->rating_value || $item->comments_count) : ?>
				<div class="raxo-details">

					<?php if ($item->hits) : ?>
					<span class="raxo-hits" title="Hits: <?php echo $item->hits; ?>"><?php echo $item->hits; ?></span>
					<?php endif; ?>

					<?php if ($item->rating_value) : ?>
					<span class="raxo-rating" title="Rating: <?php echo $item->rating_value; ?>"><?php echo number_format ($item->rating_value, 1); ?></span>
					<?php endif; ?>

					<?php if ($item->comments_count) : ?>
					<span class="raxo-comments" title="Comments: <?php echo $item->comments_count; ?>"><?php echo $item->comments_count; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>


				<?php if ($item->text) : ?>
				<div class="raxo-text"><?php echo $item->text; ?></div>
				<?php endif; ?>


				<?php if ($item->readmore) : ?>
				<div class="raxo-readmore"><?php echo $item->readmore; ?></div>
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
			<div class="raxo-image">

				<?php echo $item->image; ?>

				<?php if ($item->category) : ?>
					<div class="raxo-category"><?php echo $item->category; ?></div>
				<?php endif; ?>

			</div>
			<?php endif; ?>


			<?php if ($item->title || $item->text || $item->date || $item->author) : ?>
			<div class="raxo-content">

				<?php if ($item->title) : ?>
				<h4 class="raxo-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
				<?php endif; ?>

				<?php if ($item->date || $item->author) : ?>
				<div class="raxo-info">

					<?php if ($item->date) : ?>
					<span class="raxo-date"><?php echo $item->date; ?></span>
					<?php endif; ?>

					<?php if ($item->author) : ?>
					<span class="raxo-author"><?php echo $item->author; ?></span>
					<?php endif; ?>

				</div>
				<?php endif; ?>

				<?php if ($item->text) : ?>
				<div class="raxo-text"><?php echo $item->text; ?></div>
				<?php endif; ?>

			</div>
			<?php endif; ?>


			<?php if ($item->hits || $item->rating_value || $item->comments_count || $item->readmore) : ?>
			<div class="raxo-details">

				<?php if ($item->hits) : ?>
				<span class="raxo-hits" title="Hits: <?php echo $item->hits; ?>"><?php echo $item->hits; ?></span>
				<?php endif; ?>

				<?php if ($item->rating_value) : ?>
				<span class="raxo-rating" title="Rating: <?php echo $item->rating_value; ?>"><?php echo number_format ($item->rating_value, 1); ?></span>
				<?php endif; ?>

				<?php if ($item->comments_count) : ?>
				<span class="raxo-comments" title="Comments: <?php echo $item->comments_count; ?>"><?php echo $item->comments_count; ?></span>
				<?php endif; ?>

				<?php if ($item->readmore) : ?>
				<span class="raxo-readmore"><?php echo $item->readmore; ?></span>
				<?php endif; ?>

			</div>
			<?php endif; ?>

		</div></article>
		<?php endforeach; ?>

	</div>

	<script>
	var raxoBricks = Macy({
		container: '#raxo-module-id<?php echo $module->id; ?> .raxo-normal',
		useContainerForBreakpoints: true,
		// trueOrder: true,
		mobileFirst: true,
		columns: 1,
		margin: { x: 24, y: 32 },
		breakAt: {
			480: <?php echo $columns_number['sm']; ?>,
			720: <?php echo $columns_number['md']; ?>,
			1080: <?php echo $columns_number['lg']; ?>,
			1440: <?php echo $columns_number['xl']; ?>
		}
	});
	</script>
	<?php endif; ?>

</div>
