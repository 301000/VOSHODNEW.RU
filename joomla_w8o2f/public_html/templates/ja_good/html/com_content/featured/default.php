<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;

if(!class_exists('ContentHelperRoute')){
	if(version_compare(JVERSION, '4', 'ge')){
		abstract class ContentHelperRoute extends \Joomla\Component\content\Site\Helper\RouteHelper{};
	}else{
		JLoader::register('ContentHelperRoute', $com_path . '/helpers/route.php');
	}
}

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::addIncludePath(T3_PATH.'/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));
if (version_compare(JVERSION, '4', 'lt')) {
	JHtml::_('behavior.caption');
}
$this->columns = !empty($this->columns) ? $this->columns : $this->params->get('num_columns');

// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>
<div class="blog-featured<?php echo $this->pageclass_sfx;?>" itemscope itemtype="https://schema.org/Blog">
<?php if ($this->params->get('show_page_heading') != 0) : ?>
<div class="page-header">
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>
<?php endif; ?>

<?php $leadingcount = 0; ?>
<?php if (!empty($this->lead_items)) : ?>
<div class="blog-items items-leading clearfix <?php echo $this->params->get('blog_class_leading'); ?>">
	<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<?php
			$leadingcount++;
		?>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<?php
	$introcount = (count($this->intro_items));
	$counter = 0;
?>
<?php if (!empty($this->intro_items)) : ?>
<div id="item-container">
	<?php foreach ($this->intro_items as $key => &$item) : ?>

		<?php
		$key = ($key - $leadingcount) + 1;
		$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
		$row = $counter / $this->columns;

		if ($rowcount == 1) : ?>

		<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row">
		<?php endif; ?>
			<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?> col-sm-<?php echo round((12 / $this->columns));?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
			</div>
			<?php $counter++; ?>

			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>

		</div>
		<?php endif; ?>

	<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (!empty($this->link_items)) : ?>
	<section class="items-more">
		<h3><?php echo Text::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
		<?php echo $this->loadTemplate('links'); ?>
	</section>
<?php endif; ?>

<?php 
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
    
    if($this->pagination->get('pages.total') > 1 ) : ?>
        <script>
            jQuery(".pagination-wrap").css('display','none');
        </script>
        <div class="infinity-wrap">
        	<div id="infinity-next" class="btn btn-primary hide" data-limit="<?php echo $this->pagination->limit; ?>" data-url="<?php echo JUri::current(); ?>" data-mode="<?php echo $mode ?>" data-pages="<?php echo $this->pagination->get('pages.total') ?>" data-finishedmsg="<?php echo JText::_('TPL_INFINITY_NO_MORE_ARTICLE');?>"><?php echo Text::_('TPL_INFINITY_MORE_ARTICLE')?></div>
        </div>
    <?php else:  ?>
    		<div class="infinity-wrap">
        	<div id="infinity-next" class="btn btn-primary btn-block disabled" data-pages="1"><?php echo Text::_('TPL_INFINITY_NO_MORE_ARTICLE');?></div>
      	</div>
    <?php endif;
  ?>
	<?php  endif; ?>

</div>
