<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidator');

$bs_cl = $this->bs_classes;
?>

<script>
    if(typeof Joomla !== 'undefined') Joomla.submitbutton = function(task)
    {
		if (task == 'theme.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_djtabs&view=theme&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php echo JHTML::_($bs_cl->tab.'.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHTML::_($bs_cl->tab.'.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_DJTABS_NEW') : JText::_('COM_DJTABS_EDIT')); ?>
				<div class="<?php echo $bs_cl->row; ?>">
					<div class="<?php echo $bs_cl->col ?>6">
						<?php echo $this->form->renderField('title'); ?>
						<?php 
							foreach($this->form->getFieldset('basic') as $field){
								echo $field->renderField();
							}
						?>
					</div>
					<div class="<?php echo $bs_cl->col ?>3"></div>
					<div class="<?php echo $bs_cl->col ?>3">
						<fieldset class="form-vertical">
							<?php echo $this->form->renderField('published'); ?>
							<?php echo $this->form->renderField('random'); ?>
							<?php echo $this->form->renderField('id'); ?>
						</fieldset>
					</div>
				</div>
			<?php echo JHTML::_($bs_cl->tab.'.endTab'); ?>

		<?php echo JHTML::_($bs_cl->tab.'.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php echo DJTABSFOOTER; ?>