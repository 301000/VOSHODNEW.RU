<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$count = 1;
?>

<?php if ($grouped) : ?>
	<div class="alert alert-warning"><?php echo JText::_('TPL_LAYOUT_NOT_SUPPORT_GROUP') ;?></div>
<?php else : ?>

<div class="slide-layout slide-3 clearfix <?php echo $params->get ('moduleclass_sfx') ?>">
	<div class="label-slide">
		<?php echo Jtext::_('TPL_TREND_NEWS') ;?>
	</div>
	<div class="owl-carousel owl-theme">
		<?php foreach ($list as $item) : ?>
			<div class="slide-article slice-small item">
				<div class="article-content">
					<div class="top-info">
						<?php if ($params->get('link_titles') == 1) : ?>
							<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
						<?php else : ?>
							<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><?php echo $item->title; ?></h4>
						<?php endif; ?>

						<?php if ($item->displayDate) : ?>
							<span class="mod-articles-category-date">
								<?php echo $item->displayDate; ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="bottom-info">
						<?php if ($params->get('show_introtext')) : ?>
							<p class="mod-articles-category-introtext">
								<?php echo $item->displayIntrotext; ?>
							</p>
						<?php endif; ?>

						<?php if($item->displayCategoryTitle || $params->get('show_author') || $item->displayHits) :?>
						<aside class="article-aside clearfix">
							<dl class="article-info muted">
								<dt class="article-info-term">
									<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
								</dt>
								<?php if ($item->displayCategoryTitle) : ?>
									<dd class="mod-articles-category-category">
										<?php echo $item->displayCategoryTitle; ?>
									</dd>
								<?php endif; ?>

								<?php if ($params->get('show_author')) : ?>
									<dd class="mod-articles-category-writtenby">
										<?php echo $item->displayAuthorName; ?>
									</dd>
								<?php endif; ?>

								<?php if ($item->displayHits) : ?>
									<dd class="mod-articles-category-hits">
										<?php echo Jtext::_('hits') ;?>: <?php echo $item->displayHits; ?>
									</dd>
								<?php endif; ?>
							</dl>
						</aside>
						<?php endif ;?>

						<?php if ($params->get('show_tags', 0) && $item->tags->itemTags) : ?>
							<div class="mod-articles-category-tags">
								<?php echo JLayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?>
							</div>
						<?php endif; ?>

						<?php if ($params->get('show_readmore')) : ?>
							<p class="mod-articles-category-readmore">
								<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
									<?php if ($item->params->get('access-view') == false) : ?>
										<?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
									<?php elseif ($readmore = $item->alternative_readmore) : ?>
										<?php echo $readmore; ?>
										<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
									<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
										<?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
									<?php else : ?>
										<?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
										<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
									<?php endif; ?>
								</a>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php $count++; endforeach; ?>
	</div>
</div>
<?php endif; ?>

<script>
// Carousel - Articles Slide
jQuery(document).ready(function($) {
	$('.slide-3 .owl-carousel').owlCarousel({
		items: 1,
    loop: true,
    autoplay: true,
    animateOut: 'fadeOut',
    margin: 0,
    nav: false,
    dots: false
	})
});
</script>