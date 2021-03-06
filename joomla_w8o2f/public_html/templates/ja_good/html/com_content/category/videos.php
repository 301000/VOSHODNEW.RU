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
use Joomla\CMS\Language\Text;

require_once(dirname(dirname(__FILE__)).'../../../helper.php');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::addIncludePath(T3_PATH.'/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));
if(version_compare(JVERSION, '4','lt')){
  JHtml::_('behavior.caption');
}

$mainframe = JFactory::getApplication();
$jinput = $mainframe->input;


$numresult = JATemplateHelper::getArticleContentNumber('video');
$app = JFactory::getApplication('site');
$mergedParams = $app->getParams();
$menuParams = new \Joomla\Registry\Registry;
// print_r($mergedParams);die();
$ordering=[];
$ordering["orderby_pri"] = $mergedParams->get('orderby_pri', 'order');
$ordering["orderby_sec"] = $mergedParams->get('orderby_sec', 'rdate');
$ordering["order_date"] = $mergedParams->get('order_date', 'published');
// print_r($ordering);die();
$result = JATemplateHelper::getArticleContent('video','',$ordering);

if ($menu = $app->getMenu()->getActive())
{
	$menuParams_tmp = version_compare(JVERSION,'4', 'ge') ? $menu->getParams() : $menu->params;
	$menuParams->loadString($menuParams_tmp);
}

$mergedParams = clone $menuParams;
$mergedParams->merge($mergedParams);

// Set limit for query. If list, use parameter. If blog, add blog parameters for limit.
if (($app->input->get('layout') == 'blog') || $mergedParams->get('layout_type') == 'blog')
{
	$limit = $mergedParams->get('num_leading_articles') + $mergedParams->get('num_intro_articles') + $mergedParams->get('num_links');
}
else
{
	$limit = $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.limit', 'limit', $mergedParams->get('display_num'), 'uint');
}
$limitstart = $jinput->get('limitstart', 0);
// In case limit has been changed, adjust it
$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
$this->columns = !empty($this->columns) ? $this->columns : $this->params->get('num_columns');

$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

//var_dump($result);
//var_dump($ordering);die();
?>
<div class="blog<?php echo $this->pageclass_sfx;?>" itemscope itemtype="https://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header clearfix">
		<h1 class="page-title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
  	<div class="page-subheader clearfix">
  		<<?php echo $htag; ?> class="page-subtitle"><?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')) : ?>
			<small class="subheading-category"><?php echo $this->category->title;?></small>
			<?php endif; ?>
  		</<?php echo $htag; ?>>
	</div>
	<?php endif; ?>
	
	<?php if ($this->params->get('show_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.tags', $this->category->tags->itemTags); ?>
	<?php endif; ?>
	
	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category-desc clearfix">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if (empty($result)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
		$introcount = (count($result));
		$counter = 0;
	?>

	<?php if (!empty($result)) : ?>
	<div class="ja-videos-list-wrap">
		<div id="ja-main-player" class="embed-responsive embed-responsive-16by9">
			<div id="videoplayer">
				<?php echo JLayoutHelper::render('joomla.content.video_play', array('item' => $result[0], 'context' => 'featured')); ?>
			</div>
		</div>

		<script type="text/javascript">

			(function($){
				$(document).ready(function(){
					$('#ja-main-player').find('iframe.ja-video, video, .jp-video, .jp-jplayer').each(function(){
						var container = $('#ja-main-player');
						var width = container.outerWidth(true);
						var height = container.outerHeight(true);

						$(this).removeAttr('width').removeAttr('height');
						$(this).css({width: width, height: height});
					});
				});
			})(jQuery);
		</script>
	</div>
	<?php endif; ?>

	<?php if (!empty($result)) : ?>
    <div id="item-container">
	<?php foreach ($result as $key => &$item) : ?>
		<?php $rowcount = ((int) $counter % (int) $this->columns) + 1; ?>
		<?php if ($rowcount == 1) : ?>
			<?php $row = $counter / $this->columns; ?>
		<div class="items-row cols-<?php echo (int) $this->columns;?>"><div class="equal-height equal-height-child <?php echo 'row-'.$row; ?> row">
		<?php endif; ?>
			<div class="item col col-sm-<?php echo round((12 / $this->columns));?> <?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>			
		</div></div><!-- end row -->
			<?php endif; ?>
	<?php endforeach; ?>
    </div>
	<?php endif; ?>
	
	<?php if ($mergedParams->get('num_links')) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>
	
	<?php 
  $this->pagination = new JPagination($numresult, $limitstart, $limit);
  $pagesTotal = isset($this->pagination->pagesTotal) ? $this->pagination->pagesTotal : $this->pagination->get('pages.total');
  if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($pagesTotal > 1)) : ?>
	<div class="pagination-wrap">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<div class="counter"> <?php echo $this->pagination->getPagesCounter(); ?></div>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>

	<!-- Show load more use infinitive-scroll -->
  <?php
    JFactory::getDocument()->addScript (T3_TEMPLATE_URL . '/js/infinitive-paging.js');
    JFactory::getDocument()->addScript (T3_TEMPLATE_URL . '/js/jquery.infinitescroll.js');
    $mode = 'manual';

    if($pagesTotal > 1 ) : ?>
        <script>
            jQuery(".pagination-wrap").css('display','none');
        </script>
        <div class="infinity-wrap">
        	<div id="infinity-next" class="btn btn-primary hide" data-limit="<?php echo $this->pagination->limit; ?>" data-url="<?php echo JUri::current(); ?>" data-mode="<?php echo $mode ?>" data-pages="<?php echo $this->pagination->get('pages.total') ?>" data-finishedmsg="<?php echo Text::_('TPL_INFINITY_NO_MORE_ARTICLE');?>"><?php echo Text::_('TPL_INFINITY_MORE_ARTICLE')?></div>
        </div>
    <?php else:  ?>
    		<div class="infinity-wrap">
        	<div id="infinity-next" class="btn btn-primary btn-block disabled" data-pages="1"><?php echo Text::_('TPL_INFINITY_NO_MORE_ARTICLE');?></div>
      	</div>
    <?php endif;
  ?>
	<?php  endif; ?>
</div>
