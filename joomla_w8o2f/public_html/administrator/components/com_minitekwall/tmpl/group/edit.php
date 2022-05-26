<?php

/**
 * @title         Minitek Wall
 * @copyright     Copyright (C) 2011-2021 Minitek, All rights reserved.
 * @license       GNU General Public License version 3 or later.
 * @author url    https://www.minitek.gr/
 * @developers    Minitek.gr
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
?>

<form action="<?php echo Route::_('index.php?option=com_minitekwall&view=group&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="widget-form" class="form-validate">

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_MINITEKWALL_FIELDSET_GENERAL')); ?>
		<div class="row">
			<div class="col-lg-9">
				<div>
					<div class="card-body">
						<fieldset class="adminform">
							<?php echo $this->form->renderField('description'); ?>
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="bg-white px-3">
					<?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
				</div>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value="">

		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>