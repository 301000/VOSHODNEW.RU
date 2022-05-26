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

$link = '';
$item  = is_array($displayData) ? $displayData['item'] : $displayData; 

if (isset($item)) {
  $link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
}

$params  = new JRegistry($displayData);
$image = $params->get('image');
$alt = $params->get('alt');
$caption = $params->get('caption');
$size = $params->get('size');

require_once (JPATH_ROOT . '/plugins/system/jacontenttype/helpers/image.php');

$img = JAContentTypeImageHelper::getImage($image, $size);
if ($caption) $caption = 'class="caption"' . ' title="' . htmlspecialchars($caption) . '"';
?>

<?php if ($img) : ?>
  <a href="<?php echo $link; ?>">
   <img <?php echo $caption ?>	src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($alt); ?>"/>
  </a>
<?php endif; ?>
