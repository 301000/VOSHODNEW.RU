<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="row ja-news ja-news-1 <?php echo $params->get ('moduleclass_sfx') ?>">
	
	<?php if ($grouped) : ?>
		<?php $count = 1;foreach ($list as $group_name => $group) : ?>
		<div class="ja-news-group">
			<div class="col-xs-12">
				<div class="mod-articles-category-group"><?php echo JText::_($group_name); ?></div>
			</div>
			<div class="news-normal-wrap row-flex">
			<?php foreach ($group as $item) : ?>
				<div class="news-normal col-md-3 clearfix">
					<div class="article-content">
						<?php if ($item->displayCategoryTitle) : ?>
						<aside class="article-aside aside-before-title">
							<dl class="article-info muted">
	<dt class="article-info-term">
		<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
	</dt>
								<dd class="mod-articles-category-category">
									<?php echo $item->displayCategoryTitle; ?>
								</dd>
							</dl>
						</aside>
						<?php endif; ?>

						<?php if ($params->get('link_titles') == 1) : ?>
							<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
						<?php else : ?>
							<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><?php echo $item->title; ?></h4>
						<?php endif; ?>

						<?php if ($params->get('show_introtext')) : ?>
							<p class="mod-articles-category-introtext">
								<?php echo $item->displayIntrotext; ?>
							</p>
						<?php endif; ?>

						<?php if ($params->get('show_author') || $item->displayDate || $item->displayHits) : ?>
						<aside class="article-aside aside-after-title">
							<dl class="article-info muted">
	<dt class="article-info-term">
		<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
	</dt>
								<?php if ($params->get('show_author')) : ?>
									<dd class="mod-articles-category-writtenby">
										<?php echo $item->displayAuthorName; ?>
									</dd>
								<?php endif; ?>

								<?php if ($item->displayDate) : ?>
									<dd class="mod-articles-category-date">
										<?php echo $item->displayDate; ?>
									</dd>
								<?php endif; ?>

								<?php if ($item->displayHits) : ?>
									<dd class="mod-articles-category-hits">
										<?php echo Jtext::_('hits') ;?>: <?php echo $item->displayHits; ?>
									</dd>
								<?php endif; ?>
							</dl>
						</aside>
						<?php endif; ?>

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

				<?php if($count!=1 && $count%4==0): ?>
				</div>
				<div class="news-normal-wrap row-flex">
				<?php endif; ?>
			<?php $count++; endforeach; ?>
			</div>
		</div>
		<?php endforeach; ?>
	<?php else : $count = 1; ?>
		<div class="news-normal-wrap row-flex">
		<?php foreach ($list as $item) : ?>
			<div class="news-normal col-md-3 clearfix">
				<div class="article-content">
					<?php if ($item->displayCategoryTitle) : ?>
					<aside class="article-aside aside-before-title">
						<dl class="article-info muted">
							<dt class="article-info-term">
								<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
							</dt>
							<dd class="mod-articles-category-category">
								<?php echo $item->displayCategoryTitle; ?>
							</dd>
						</dl>
					</aside>
					<?php endif; ?>

					<?php if ($params->get('link_titles') == 1) : ?>
						<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
					<?php else : ?>
						<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><?php echo $item->title; ?></h4>
					<?php endif; ?>

					<?php if ($params->get('show_introtext')) : ?>
						<p class="mod-articles-category-introtext">
							<?php echo $item->displayIntrotext; ?>
						</p>
					<?php endif; ?>

					<?php if ($params->get('show_author') || $item->displayDate || $item->displayHits) : ?>
					<aside class="article-aside aside-after-title">
						<dl class="article-info muted">
	<dt class="article-info-term">
		<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
	</dt>
							<?php if ($params->get('show_author')) : ?>
								<dd class="mod-articles-category-writtenby">
									<?php echo $item->displayAuthorName; ?>
								</dd>
							<?php endif; ?>

							<?php if ($item->displayDate) : ?>
								<dd class="mod-articles-category-date">
									<?php echo $item->displayDate; ?>
								</dd>
							<?php endif; ?>

							<?php if ($item->displayHits) : ?>
								<dd class="mod-articles-category-hits">
									<?php echo Jtext::_('hits') ;?>: <?php echo $item->displayHits; ?>
								</dd>
							<?php endif; ?>
						</dl>
					</aside>
					<?php endif; ?>

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

			<?php if($count!=1 && $count%4==0): ?>
			</div>
			<div class="news-normal-wrap row-flex">
			<?php endif; ?>
		<?php $count++; endforeach; ?>
		</div>
	<?php endif; ?>
</div>
