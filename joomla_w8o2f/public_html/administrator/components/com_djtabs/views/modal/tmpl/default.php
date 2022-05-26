<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

JHTML::_('jquery.framework');
Jhtml::_('behavior.multiselect');

$app = JFactory::getApplication();
$function = $app->input->get('f_name');

?>

<form action="<?php echo JRoute::_('index.php?option=com_djtabs&view=modal&layout=default&tmpl=component&'.JSession::getFormToken().'=1'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="form-horizontal">
		<?php
			echo $this->form->renderField('group_id');echo $this->form->renderField('theme');
			echo $this->form->renderField('layout');
		?>
		<button 
			class="button btn btn-lg btn-success pointer" 
			onclick="if (window.parent) window.parent.<?php echo $function ?>(jQuery('#jformgroup_id').val(),jQuery('#jformtheme').val(),jQuery('#jform_layout').val(),jQuery('#jformgroup_id option:selected').text());"
		>
			<?php echo JText::_('COM_DJTABS_INSERT'); ?>
		</button>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="f_name" value="<?php echo $function; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>