<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$input = Factory::getApplication()->input;
?>

<div class="gridEditor">
	<div class="gridOptions">
		<form action="<?php echo Route::_('index.php?option=com_minitekwall&view=grid&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
			<div class="row-fluid">
				<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'grid')); ?>
					<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'grid', Text::_('COM_MINITEKWALL_FIELDSET_GRID', true)); ?>
						<fieldset class="form-vertical form-no-margin">
							<?php echo $this->form->renderField('name'); ?>
							<div class="control-group">
								<div class="control-label">
									<?php echo $this->form->getLabel('columns'); ?>
								</div>
								<div class="controls">
									<?php echo $this->form->getInput('columns'); ?>
								</div>
							</div>
							<div class="text-center mb-3">
								<a href="#" class="btn btn-success" id="update-columns">
									<i class="icon icon-refresh"></i>&nbsp;
									<?php echo Text::_('COM_MINITEKWALL_GRID_UPDATE'); ?>
								</a>
							</div>
							<div class="hidden">
							<?php echo $this->form->renderField('elements'); ?>
							</div>
							<div class="gridNewItem well p-3">
								<h4><?php echo Text::_('COM_MINITEKWALL_GRID_ADD_NEW_ITEM'); ?></h4>
								<div class="control-group">
									<div class="control-label">
										<label for="item-size"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_ITEM_SIZE_LABEL'); ?></label>
									</div>
									<div class="controls">
										<select id="item-size">
											<option value="S"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_SMALL'); ?></option>
											<option value="L"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_LANDSCAPE'); ?></option>
											<option value="P"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_PORTRAIT'); ?></option>
											<option value="B"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_BIG'); ?></option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="item-height"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_CELL_HEIGHT_MULTIPLIER_LABEL'); ?></label>
									</div>
									<div class="controls">
										<input type="number" id="item-height" step="1" min="1" max="10" value="1" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="item-width"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_COLUMNS_LABEL'); ?></label>
									</div>
									<div class="controls">
										<input type="number" id="item-width" step="1" min="1" max="10" value="1" />
									</div>
								</div>
								<div class="text-center">
									<a href="#" class="btn btn-success" id="add-item">
										<i class="icon icon-plus"></i>&nbsp;
										<?php echo Text::_('COM_MINITEKWALL_GRID_ADD'); ?>
									</a>
								</div>
							</div>
						</fieldset>
					<?php echo HTMLHelper::_('uitab.endTab'); ?>

					<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'items', Text::_('COM_MINITEKWALL_FIELDSET_ITEMS', true)); ?>
						<div class="edit-item-info"><p><?php echo Text::_('COM_MINITEKWALL_GRID_SELECT_AN_ITEM_FROM_THE_GRID'); ?></p></div>
						<div class="gridEditItem well p-3" style="display: none;">
							<fieldset class="form-vertical form-no-margin">
								<h4><?php echo Text::_('COM_MINITEKWALL_GRID_EDIT_ITEM'); ?> <span class="edit-index"></span></h4>
								<div class="control-group">
									<div class="control-label">
										<label for="edit-size"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_ITEM_SIZE_LABEL'); ?></label>
									</div>
									<div class="controls">
										<select id="edit-size">
											<option value="S"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_SMALL'); ?></option>
											<option value="L"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_LANDSCAPE'); ?></option>
											<option value="P"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_PORTRAIT'); ?></option>
											<option value="B"><?php echo Text::_('COM_MINITEKWALL_FIELD_OPTION_BIG'); ?></option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="edit-height"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_CELL_HEIGHT_MULTIPLIER_LABEL'); ?></label>
									</div>
									<div class="controls">
										<input type="number" id="edit-height" step="1" min="1" max="10" value="1" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="edit-width"><?php echo Text::_('COM_MINITEKWALL_FIELD_GRID_COLUMNS_LABEL'); ?></label>
									</div>
									<div class="controls">
										<input type="number" id="edit-width" step="1" min="1" max="10" value="1" />
									</div>
								</div>
								<div class="text-center">
									<a href="#" class="btn btn-success" id="edit-item">
										<i class="icon icon-plus"></i>&nbsp;
										<?php echo Text::_('COM_MINITEKWALL_GRID_UPDATE'); ?>
									</a>
								</div>
							</fieldset>
						</div>
					<?php echo HTMLHelper::_('uitab.endTab'); ?>

				<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
			</div>

			<?php if (!$this->item->id) { ?>
				<input type="hidden" name="jform[state]" value="1" />
			<?php } ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	</div>

	<div class="gridDesigner">
		<?php if (!$this->item->id) {
			$columns = 4;
		}
		else
		{
			$columns = (int)$this->item->columns;
		} ?>
		<div class="gridContainer gridP-col-<?php echo $columns; ?>" data-columns="<?php echo $columns; ?>">
			<div class="gridP"> </div>
		</div>
	</div>
</div>
