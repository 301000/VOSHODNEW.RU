<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesCategory\Site\Helper\ArticlesCategoryHelper;
use Joomla\CMS\Factory;
defined('_JEXEC') or die;
$colNews = $params->get('show_col');
$nCol 	 = 12/$colNews;
$countG  = 0;
?>
<div class="ja-news ja-news-6 <?php echo $params->get ('moduleclass_sfx') ?>">
	<div class="row row-flex">
	<?php if ($grouped) : ?>
		<?php
			if (!empty($idbase)) { 
				$sql = '
				SELECT DISTINCT sub.*
				FROM #__categories as sub
				INNER JOIN #__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt
				WHERE this.id IN ('.implode(',', $idbase).')';
				$db = JFactory::getDbo();
				$db->setQuery($sql);
				$results = $db->loadObjectList();
				}

				foreach ($results AS $res) {
				$res->displayCategoryLink  = JRoute::_(ContentHelperRoute::getCategoryRoute($res->id));
				?>
				<div class="col-sm-<?php echo $colNews ;?> <?php if($countG%$nCol==0) echo 'clear'; ?>">

					<!-- Cagegory -->
					<div class="module-title"><?php echo $res->title; ?></div>
					<!-- // Category -->

					<!-- Load Article -->
					<div class="news-normal-group">
						<?php 
						$params->set('catid', [$res->id]);
						$params->set('show_child_category_articles', 0);
						
						// check version
						if (version_compare(JVERSION, '4.0', 'ge')) {
							$cacheid = md5(serialize(array ($idbase, $module->module, $module->id)));

							$cacheparams               = new stdClass;
							$cacheparams->cachemode    = 'id';
							$cacheparams->class        = 'Joomla\Module\ArticlesCategory\Site\Helper\ArticlesCategoryHelper';
							$cacheparams->method       = 'getList';
							$cacheparams->methodparams = $params;
							$cacheparams->modeparams   = $cacheid;

							$list = ModuleHelper::moduleCache($module, $params, $cacheparams);
						} else {
							$cacheid = md5(serialize(array ([$res->id], $module->module, $module->id)));

							$cacheparams               = new stdClass;
							$cacheparams->cachemode    = 'id';
							$cacheparams->class        = 'ModArticlesCategoryHelper';
							$cacheparams->method       = 'getList';
							$cacheparams->methodparams = $params;
							$cacheparams->modeparams   = $cacheid;

							$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
						}
						$i = 0;


						foreach ($list AS $l) { $i++; ?>
							

							<div class="<?php echo ($i==1) ? 'news-big' : 'news-normal';?>">
								<div class="intro-image-wrap">
									<?php echo JLayoutHelper::render('joomla.content.intro_image', $l); ?>
								</div>

								<div class="article-content">
									<?php if ($l->displayCategoryTitle) : ?>
									<aside class="article-aside aside-before-title">
										<dl class="article-info muted">
											<dt class="article-info-term">
												<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
											</dt>
											<dd class="mod-articles-category-category">
												<?php echo $l->displayCategoryTitle; ?>
											</dd>
										</dl>
									</aside>
									<?php endif; ?>

									<?php if ($params->get('link_titles') == 1) : ?>
										<h4 class="mod-articles-category-title <?php echo $l->active; ?>"><a href="<?php echo $l->link; ?>"><?php echo $l->title; ?></a></h4>
									<?php else : ?>
										<h4 class="mod-articles-category-title <?php echo $l->active; ?>"><?php echo $l->title; ?></h4>
									<?php endif; ?>

									<?php if ($params->get('show_introtext') && $i==1) : ?>
										<p class="mod-articles-category-introtext">
											<?php echo $l->displayIntrotext; ?>
										</p>
									<?php endif; ?>

									<?php if ($params->get('show_author') || $l->displayDate || $l->displayHits) : ?>
									<aside class="article-aside aside-after-title">
										<dl class="article-info muted">
											<dt class="article-info-term">
												<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
											</dt>
											<?php if ($params->get('show_author')) : ?>
												<dd class="mod-articles-category-writtenby">
													<?php echo $l->displayAuthorName; ?>
												</dd>
											<?php endif; ?>

											<?php if ($l->displayDate) : ?>
												<dd class="mod-articles-category-date">
													<?php echo $l->displayDate; ?>
												</dd>
											<?php endif; ?>

											<?php if ($l->displayHits) : ?>
												<dd class="mod-articles-category-hits">
													<?php echo Jtext::_('hits') ;?>: <?php echo $l->displayHits; ?>
												</dd>
											<?php endif; ?>
										</dl>
									</aside>
									<?php endif; ?>

									<?php if ($params->get('show_tags', 0) && $l->tags->itemTags) : ?>
										<div class="mod-articles-category-tags">
											<?php echo JLayoutHelper::render('joomla.content.tags', $l->tags->itemTags); ?>
										</div>
									<?php endif; ?>

									<?php if ($params->get('show_readmore')) : ?>
										<p class="mod-articles-category-readmore">
											<a class="<?php echo $l->active; ?>" href="<?php echo $l->link; ?>">
												<?php if ($l->params->get('access-view') == false) : ?>
													<?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
												<?php elseif ($readmore = $l->alternative_readmore) : ?>
													<?php echo $readmore; ?>
													<?php echo JHtml::_('string.truncate', $l->title, $params->get('readmore_limit')); ?>
												<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
													<?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
												<?php else : ?>
													<?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
													<?php echo JHtml::_('string.truncate', $l->title, $params->get('readmore_limit')); ?>
												<?php endif; ?>
											</a>
										</p>
									<?php endif; ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<!-- End Load Article -->
				</div>
		<?php	$countG++; } ?>
	<?php else : ?>
		<?php $i = 1; foreach ($list as $item) : ?>
			<div class="<?php echo ($i==1) ? 'news-big col-xs-12' : 'news-normal col-xs-12';?>">
				<?php if($i==1) :?>
				<?php echo JLayoutHelper::render('joomla.content.intro_image', $item); ?>
				<?php endif ;?>
				<div class="article-content">
					<aside class="article-aside aside-before-title">
						<dl class="article-info muted">
	<dt class="article-info-term">
		<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
	</dt>
							<?php if ($params->get('show_author')) : ?>
								<dd class="mod-articles-category-writtenby">
									<?php echo $item->displayAuthorName; ?>
								</dd>
							<?php endif; ?>

							<?php if ($item->displayCategoryTitle) : ?>
								<dd class="mod-articles-category-category">
									<?php echo $item->displayCategoryTitle; ?>
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
					<?php if ($params->get('link_titles') == 1) : ?>
						<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h4>
					<?php else : ?>
						<h4 class="mod-articles-category-title <?php echo $item->active; ?>"><?php echo $item->title; ?></h4>
					<?php endif; ?>

					<?php if ($params->get('show_introtext') && $i==1) : ?>
						<p class="mod-articles-category-introtext">
							<?php echo $item->displayIntrotext; ?>
						</p>
					<?php endif; ?>

					<?php if ($params->get('show_tags', 0) && $item->tags->itemTags) : ?>
						<div class="mod-articles-category-tags">
							<?php echo JLayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?>
						</div>
					<?php endif; ?>

					<?php if ($params->get('show_readmore')) : ?>
						<p class="mod-articles-category-readmore">
							<a class="<?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
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
		<?php $i++; endforeach; ?>
	<?php endif; ?>
</div>
</div>
