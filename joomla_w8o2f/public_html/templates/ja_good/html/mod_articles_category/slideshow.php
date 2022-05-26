<?php
/*
 * ------------------------------------------------------------------------
 * JA Good Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

?>
<?php if ($grouped) : ?>
  <div class="alert alert-warning"><?php echo JText::_('TPL_LAYOUT_NOT_SUPPORT_GROUP') ;?></div>
<?php else : ?>
<div class="category-module article-slideshow">
  <!-- Swiper -->
  <div class="swiper-container gallery-top">
    <div class="swiper-wrapper">
      <!-- Wrapper for slides -->

        <?php foreach ($list as $item) : ?>
          <?php
            $displayData['item'] = $item;
            $displayData['params'] = $item->params;
          ?>
          <div class="swiper-slide">
            <?php echo JLayoutHelper::render('joomla.content.fulltext_image', array('item' => $item, 'params' => $params)); ?>
            <div class="container article-container">

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

              <header class="article-header clearfix">
                <h1 class="article-title">
                  <?php if ($params->get('link_titles') == 1) : ?>
                  <a title="<?php echo $item->title; ?>"  href="<?php echo $item->link; ?>">
                  <?php endif; ?>
                    <?php echo $item->title; ?>
                  <?php if ($params->get('link_titles') == 1) : ?>
                  </a>
                  <?php endif; ?>
                </h1>
              </header>
      
              <?php if ($params->get('show_introtext')) : ?>
                <section class="article-intro clearfix">
                  <?php echo $item->displayIntrotext; ?>
                </section>
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

              <?php if ($params->get('show_readmore')) : ?>
                <p class="slideshow-readmore">
                  <a class="btn btn-default <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
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
        <?php endforeach; ?>
    </div>
    <!-- Add Arrows -->
    <div class="swiper-button-next swiper-button-white"></div>
    <div class="swiper-button-prev swiper-button-white"></div>
  </div>
  <div class="swiper-thumbs-wraper hidden-xs hidden-sm">
    <div class="container"><div class="row">
      <div class="swiper-container gallery-thumbs">
        <div class="swiper-wrapper">
          <!-- Wrapper for slides -->
          <?php foreach ($list as $item) : ?>
            <?php
              $displayData['item'] = $item;
              $displayData['params'] = $item->params;
            ?>
            <div class="swiper-slide">
              <div class="article-thumbs">
                <div class="intro-image-wrap">
                  <?php echo JLayoutHelper::render('joomla.content.intro_image', $item); ?>
                </div>

                <div class="article-thumbs-ct">
                  <header class="article-header">
                    <h3 class="article-title"><?php echo $item->title; ?></h3>
                  </header>

                  <?php if ($item->displayDate) : ?>
                    <aside class="article-aside clearfix">
                      <?php echo $item->displayDate; ?>
                    </aside>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
      </div>
    </div></div>
  </div>

</div>
<?php endif; ?>
<!-- Initialize Swiper -->
<script>
jQuery(document).ready(function() {
  var galleryTop = new Swiper('.gallery-top', {
      nextButton: '.swiper-button-next',
      prevButton: '.swiper-button-prev',
      spaceBetween: 0,
      effect: 'fade',
      pagination: '.swiper-pagination',
      loop: true,
      paginationClickable: true,
      loopedSlides: 4,
  });
  var galleryThumbs = new Swiper('.gallery-thumbs', {
      spaceBetween: 0,
      slidesPerView: 4,
      touchRatio: 0.2,
      loop: true,
      loopedSlides: 4,
      slideToClickedSlide: true,
  });
  galleryTop.params.control = galleryThumbs;
  galleryThumbs.params.control = galleryTop;
  
  // re-load when images loaded
  jQuery('.t3-wrapper').imagesLoaded(function(){
    galleryTop.params.control = galleryThumbs;
    galleryThumbs.params.control = galleryTop;
  });
});
</script>
