<?php
/**
 * ------------------------------------------------------------------------
 * JA Platon Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
if(version_compare(JVERSION, '4', 'ge')){
}

if(version_compare(JVERSION, '4', 'ge')) {
	//create tag router on Joomla 4
	class TagsHelperRoute extends \Joomla\Component\Tags\Site\Helper\RouteHelper{};

	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
	$wa = $this->document->getWebAssetManager();
	$wa->useScript('com_tags.tag-default');
}

HTMLHelper::_('behavior.core');


// Get the user object.
$user = Factory::getUser();
$db = JFactory::getDbo();

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);

// get attribute for tag content item
$allIDItem = [];
foreach ($items AS $it) {
    $allIDItem[] = $it->content_item_id;
}
$SQL = "SELECT id, attribs FROM #__content WHERE id IN (".implode(",", $allIDItem).")";
$db->setQuery($SQL);
$attrs = $db->loadAssocList('id', 'attribs');
foreach ($attrs AS $k => $att) {
    foreach ($items AS $ki => $it) {
        if ($it->content_item_id == $k) {
            $it->attribs = $att;
            $it->params = $it->core_params;
            $it->images = $it->core_images;
            $it->title = $it->core_title;
            $it->slug = $it->core_alias ? ($it->content_item_id . ':' . $it->core_alias) : $it->content_item_id;
            continue;
        }
    }
}
// get attribute for tag content item

JFactory::getDocument()->addScriptDeclaration("
		var resetFilter = function() {
		document.getElementById('filter-search').value = '';
	}
");

?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	<?php if ($this->params->get('show_headings') || $this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
		<fieldset class="filters d-flex justify-content-between mb-3">
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="input-group">
					<label class="filter-search-lbl sr-only" for="filter-search">
						<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="form-control" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>">
					<span class="input-group-btn">
						<button type="button" name="filter-search-button" title="<?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>" onclick="document.adminForm.submit();" class="btn btn-light">
							<span class="fa fa-search" aria-hidden="true"></span>
						</button>
						<button type="reset" name="filter-clear-button" title="<?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn btn-light" onclick="resetFilter(); document.adminForm.submit();">
							<span class="fa fa-times" aria-hidden="true"></span>
						</button>
					</span>
				</div>
			<?php endif; ?>
			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="btn-group pull-right">
					<label for="limit" class="sr-only">
						<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>

			<input type="hidden" name="filter_order" value="">
			<input type="hidden" name="filter_order_Dir" value="">
			<input type="hidden" name="limitstart" value="">
			<input type="hidden" name="task" value="">
		</fieldset>
	<?php endif; ?>

	<?php if ($this->items == false || $n == 0) : ?>
		<p> <?php echo Text::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>

	<ul class="category tags-list media-group list-unstyled equal-height equal-height-child row">
		<?php foreach ($items as $i => $item) : ?>
			<?php if ($item->core_state == 0) : ?>
				<li class="media col system-unpublished cat-list-row<?php echo $i % 2; ?>">
			<?php else: ?>
				<li class="media col cat-list-row<?php echo $i % 2; ?> col-md-6 clearfix" >
			<?php endif; ?>

					<div class="media-wrap">
						<?php echo $item->event->afterDisplayTitle; ?>
                        <?php echo JLayoutHelper::render('joomla.content.intro_image', $item); ?>
						<div class="media-body">
						<h4 class="title">
							<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
								<?php echo $this->escape($item->core_title); ?>
							</a>
						</h4>
						
						<?php echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?>

						</div>
					</div>
				</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
</form>

