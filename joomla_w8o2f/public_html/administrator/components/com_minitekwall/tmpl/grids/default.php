<?php

/**
 * @title		Minitek Wall
 * @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
 * @license   	GNU General Public License version 3 or later.
 * @author url   https://www.minitek.gr/
 * @developers   Minitek.gr
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Button\PublishedButton;

HTMLHelper::_('behavior.multiselect');

$user = Factory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo Route::_('index.php?option=com_minitekwall&view=grids'); ?>" method="post" name="adminForm" id="adminForm">
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
					<table class="table" id="articleList">
						<caption class="visually-hidden">
							<?php echo Text::_('COM_MINITEKWALL_CUSTOM_GRIDS_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
						</caption>
						<thead>
							<tr>
								<td class="w-1 text-center">
									<?php echo HTMLHelper::_('grid.checkall'); ?>
								</td>
								<th scope="col" class="w-1 text-center">
									<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" style="min-width:100px">
									<?php echo HTMLHelper::_('searchtools.sort', 'COM_MINITEKWALL_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
								</th>
								<th scope="col" class="w-3 d-none d-lg-table-cell">
									<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->items as $i => $item) :
								$canEdit    = $user->authorise('core.edit',       'com_minitekwall');
								$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
								$canChange  = $user->authorise('core.edit.state', 'com_minitekwall.grid.' . $item->id) && $canCheckin;
							?>
								<tr class="row<?php echo $i % 2; ?>">
									<td class="text-center">
										<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
									</td>

									<td class="text-center">
										<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'grids.', $canChange, 'cb'); ?>
									</td>

									<th scope="row" class="has-context">
										<div class="break-word">
											<?php if ($item->checked_out) : ?>
												<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'grids.', $canCheckin); ?>
											<?php endif; ?>
											<?php if ($canEdit) : ?>
												<a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_minitekwall&task=grid.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape(addslashes($item->name)); ?>">
													<?php echo $this->escape($item->name); ?></a>
												</a>
											<?php else : ?>
												<span><?php echo $this->escape($item->name); ?></span>
											<?php endif; ?>
										</div>
									</th>

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