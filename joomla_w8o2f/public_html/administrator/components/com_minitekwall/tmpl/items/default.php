<?php

/**
 * @title        Minitek Wall
 * @copyright    Copyright (C) 2011-2021 Minitek, All rights reserved.
 * @license      GNU General Public License version 3 or later.
 * @author url   https://www.minitek.gr/
 * @developers   Minitek.gr
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Button\PublishedButton;

HTMLHelper::_('behavior.multiselect');

$user = Factory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if (strpos($listOrder, 'publish_up') !== false) {
	$orderingColumn = 'publish_up';
} elseif (strpos($listOrder, 'publish_down') !== false) {
	$orderingColumn = 'publish_down';
} else {
	$orderingColumn = 'created';
}

if ($saveOrder && !empty($this->items)) {
	$saveOrderingUrl = 'index.php?option=com_minitekwall&task=items.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
	HTMLHelper::_('draggablelist.draggable');
}
?>

<form action="<?php echo Route::_('index.php?option=com_minitekwall&view=items'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>
				<?php if (empty($this->items)) : ?>
					<div class="alert alert-info">
						<span class="fas fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
						<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>
					<table class="table itemList" id="articleList">
						<caption class="visually-hidden">
							<?php echo Text::_('COM_MINITEKWALL_CUSTOM_ITEMS_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
								<td class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" class="w-1 text-center d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
								</th>
								<th scope="col" class="w-1 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10 d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10 d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-10 d-none d-md-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-1 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody <?php if ($saveOrder) : ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true" <?php endif; ?>>
							<?php foreach ($this->items as $i => $item) :
								$canEdit    = $user->authorise('core.edit',       'com_minitekwall');
								$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
								$canEditOwn = $user->authorise('core.edit.own',   'com_minitekwall') && $item->created_by == $userId;
								$canChange  = $user->authorise('core.edit.state', 'com_minitekwall') && $canCheckin;
							?>
								<tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo (int) $item->groupid; ?>">

									<td class="text-center">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
									</td>

									<td class="text-center d-none d-md-table-cell">
										<?php
										$iconClass = '';
										if (!$canChange) {
											$iconClass = ' inactive';
										} elseif (!$saveOrder) {
											$iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::tooltipText('JORDERINGDISABLED');
										}
										?>
										<span class="sortable-handler<?php echo $iconClass ?>">
											<span class="fas fa-ellipsis-v" aria-hidden="true"></span>
										</span>
										<?php if ($canChange && $saveOrder) : ?>
											<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
										<?php endif; ?>
									</td>

									<td class="text-center">
										<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'items.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
									</td>

									<th scope="row" class="has-context question-title">
										<div class="break-word">
											<?php if ($item->checked_out) : ?>
												<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'items.', $canCheckin); ?>
											<?php endif; ?>
											<?php if ($canEdit) : ?>
												<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_minitekwall&task=item.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape(addslashes($item->title)); ?>">
													<?php echo $this->escape($item->title); ?></a>
												</a>
											<?php else : ?>
												<span><?php echo $this->escape($item->title); ?></span>
											<?php endif; ?>
											<div class="small">
												<?php echo Text::_('COM_MINITEKWALL_ITEMS_GROUP') . ": "; ?>
												<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_minitekwall&task=group.edit&id=' . $item->groupid); ?>" title="<?php echo Text::_('COM_MINITEKWALL_ITEMS_EDIT_GROUP'); ?>">
													<?php echo $this->escape($item->group_name); ?>
												</a>
											</div>
										</div>
									</th>

									<td class="small d-none d-md-table-cell">
										<?php echo $this->escape($item->access_level); ?>
									</td>

									<td class="small d-none d-md-table-cell">
										<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>" title="<?php echo Text::_('JAUTHOR'); ?>">
											<?php echo $this->escape($item->author_name); ?>
										</a>
									</td>

									<td class="small d-none d-md-table-cell text-center">
										<?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC4')); ?>
									</td>

									<td class="d-none d-lg-table-cell">
										<?php echo (int) $item->id; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->pagination->getListFooter(); ?>
				<?php endif; ?>

				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<?php echo HTMLHelper::_('form.token'); ?>

			</div>
		</div>
	</div>
</form>