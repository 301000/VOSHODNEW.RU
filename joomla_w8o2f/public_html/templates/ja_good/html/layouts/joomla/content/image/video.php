<?php
/**
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

$item  		     = is_array($displayData) ? $displayData['item'] : $displayData;
$params  		   = new JRegistry($item->attribs);
$thumbnail 		 = $params->get('ctm_thumbnail');
$desc 			   = $params->get('ctm_description');
$ctm_source    = $params->get('ctm_source', 'youtube');

if(!$thumbnail) {
  $images = json_decode($item->images);
  $thumbnail = @$images->image_intro;
}

$data = array();
$data['item'] = $item;
if (is_array($displayData) && isset($displayData['img-size'])) $data['size'] = $displayData['img-size'];

if (isset($thumbnail) && !empty($thumbnail)) {
  $data['image'] = $thumbnail;
  $data['alt'] = $item->title;
  $data['caption'] = $desc;
}
?>
<?php if (isset($thumbnail) && !empty($thumbnail)) : ?>
  <div class="item-image ja-video-list"
     data-url="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid)); ?>"
     data-title="<?php echo htmlspecialchars($item->title); ?>"
     data-video="<?php echo htmlspecialchars(JLayoutHelper::render('joomla.content.video_play_list', array('item' => $item, 'context' => 'list'))); ?>">
    <a class="btn-play" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid)); ?>">
      <span class="fa fa-play"></span>
      <span class="element-invisible">play</span>
    </a>
    <?php echo JLayoutHelper::render('joomla.content.image.image', $data); ?>
  </div>
<?php endif; ?>