<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select.chosen');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidator');
JHTML::_('jquery.framework');

$app = JFactory::getApplication();
$bs_cl = $this->bs_classes;
$fields_to_move = array('tab_icon', 'tab_custom_html');

?>

<script>
	if(typeof Joomla !== 'undefined') Joomla.submitbutton = function(task)
    {
		if (task == 'item.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_djtabs&view=item&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php echo JHTML::_($bs_cl->tab.'.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHTML::_($bs_cl->tab.'.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_DJTABS_NEW') : JText::_('COM_DJTABS_EDIT')); ?>
				<div class="<?php echo $bs_cl->row; ?>">
					<div class="<?php echo $bs_cl->col ?>9">
						<?php 
							echo $this->form->renderField('name');
							echo $this->form->renderField('type');
						?>
						<?php 
							foreach ($this->form->getFieldset('basic') as $field){
								if(!in_array($field->fieldname, $fields_to_move)){
									echo $field->renderField();
								}
							} 
						?>
						<?php
							if(JComponentHelper::isInstalled('com_k2')){
								foreach ($this->form->getFieldset('k2_basic') as $field){
									echo $field->renderField();
								}
							}
						?>
					</div>
					<div class="<?php echo $bs_cl->col ?>3">
						<fieldset class="form-vertical">
							<?php
								echo $this->form->renderField('group_id');
								echo $this->form->renderField('published');
								echo $this->form->renderField('access');
								echo $this->form->renderField('id');
							?>
						</fieldset>
					</div>
				</div>
			<?php echo JHTML::_($bs_cl->tab.'.endTab'); ?>


			<?php echo JHTML::_($bs_cl->tab.'.addTab', 'myTab', 'basic_params', JText::_('COM_DJTABS_TAB_OPTIONS')); ?>
				<div class="<?php echo $bs_cl->row; ?>">
					<div class="<?php echo $bs_cl->col ?>6">
					<?php 
						foreach($this->form->getFieldset('basic') as $field){
							if(in_array($field->fieldname, $fields_to_move)){
								echo $field->renderField();
							}
						}
					?>
					</div>
					<div class="<?php echo $bs_cl->col ?>6"></div>
				</div>
			<?php echo JHTML::_($bs_cl->tab.'.endTab'); ?>

			<?php echo JHTML::_($bs_cl->tab.'.addTab', 'myTab', 'article_params', JText::_('COM_DJTABS_ARTICLE_OPTIONS')); ?>
			<?php 
				foreach($this->form->getFieldset('article') as $field){
					echo $field->renderField();
				}
			?>
			<?php echo JHTML::_($bs_cl->tab.'.endTab'); ?>

			<?php echo JHTML::_($bs_cl->tab.'.addTab', 'myTab', 'article_category_params', JText::_('COM_DJTABS_ARTICLE_CATEGORY_OPTIONS')); ?>
			<?php 
				foreach($this->form->getFieldset('article_category') as $field){
					echo $field->renderField();
				}
			?>
			<?php echo JHTML::_($bs_cl->tab.'.endTab'); ?>


		<?php echo JHTML::_($bs_cl->tab.'.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="tab_href" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php echo DJTABSFOOTER; ?>


<script>

	//document.addEventListener("DOMContentLoaded", function() {
	jQuery(document).ready(function($){

		//setTimeout(function(){
			paramsHide();
			jQuery('#jformtype').off().change(paramsHide);

			categoryParamsHide();
			jQuery('#jform_params_articles_display').off().change(categoryParamsHide);

			orderingDirectionHide();
			jQuery('#jform_params_articles_ordering').off().change(orderingDirectionHide);
		//}, 300);

		function categoryParamsHide()
		{
			var val = jQuery('#jform_params_articles_display').val();
			var art_per_row = jQuery('#jform_params_articles_per_row').closest('.control-group');
			var art_space = jQuery('#jform_params_articles_space').closest('.control-group');
			if (val=='3'){
			art_per_row.css('display','');
			art_space.css('display','');
			}
			else {
			art_per_row.css('display','none');
			art_space.css('display','none');
			}

		}

		function orderingDirectionHide()
		{
			var val = jQuery('#jform_params_articles_ordering').val();
			var art_order_dir = jQuery('jform_params_articles_ordering_direction').closest('.control-group');
			if(val=='random'){
			art_order_dir.css('display','none');
			}else{
			art_order_dir.css('display','');
			}
		}


		function paramsHide()
		{
			var val = jQuery('#jformtype').val();

			var art = jQuery('#article_params');
			var art_tab = jQuery('a[href="#article_params"]'); 
			if(val=='1' || val=='2' || val=='5' || val=='6'){
			art.css('visibility','visible');
			art_tab.css('display','');
			}else{
			art.css('visibility','hidden');
			art_tab.css('display','none');
			}

			var cat = jQuery('#article_category_params');
			var cat_tab = jQuery('a[href="#article_category_params"]');
			if(val!='1' && val!='5'){ //article category or k2 article category
			cat.css('visibility','hidden');
			cat_tab.css('display','none');
			}else{
			cat.css('visibility','visible');
			cat_tab.css('display','');
			}

			manageField(val);
			// ver.1.3
			var max_cat_field = jQuery('#jform_params_max_category_levels');
			if(val=='5'){
			max_cat_field.closest('.control-group').css('display','none');
			}else{
			max_cat_field.closest('.control-group').css('display','');
			}
		}

		function manageField(val)
		{
			var cat_field = jQuery('#jform_params_category_id');
			var art_field = jQuery('[name="jform[params][article_id]"]');
			var mod_field = jQuery('#jform_params_module_position');
			var vid_field = jQuery('#jform_params_video_link');
			var html_field = jQuery('#jform_params_custom');

			var k2_cat_field = jQuery('[name="jform[params][k2_category_id][]"]');
			if(!k2_cat_field.length){
				k2_cat_field = jQuery('[name="jform[params][k2_category_id]"]');
			}
			var k2_art_field = jQuery('[name="jform[params][k2_item_id]"]');

			cat_field.removeProp('required');
			cat_field.removeClass('required');
			art_field.removeProp('required');
			art_field.removeClass('required');
			mod_field.removeProp('required');
			mod_field.removeClass('required');
			vid_field.removeProp('required','required');
			vid_field.removeClass('required');

			k2_cat_field.removeProp('required');
			k2_cat_field.removeClass('required');
			k2_art_field.removeProp('required');
			k2_art_field.removeClass('required');

			cat_field.closest('.control-group').css('display','none');
			art_field.closest('.control-group').css('display','none');
			mod_field.closest('.control-group').css('display','none');
			vid_field.closest('.control-group').css('display','none');
			html_field.closest('.control-group').css('display','none');

			k2_cat_field.closest('.control-group').css('display','none');
			k2_art_field.closest('.control-group').css('display','none');

			if (val=='1'){
			cat_field.prop('required','required');
			cat_field.addClass('required');
			cat_field.closest('.control-group').css('display','');
			}else if (val=='2'){
			art_field.prop('required','required');
			art_field.addClass('required');
			art_field.closest('.control-group').css('display','');
			}else if (val=='3'){
			mod_field.prop('required','required');
			mod_field.addClass('required');
			mod_field.closest('.control-group').css('display','');
			}else if (val=='4'){
			vid_field.prop('required','required');
			vid_field.addClass('required');
			vid_field.closest('.control-group').css('display','');
			}else if (val=='5'){
			k2_cat_field.prop('required','required');
			k2_cat_field.addClass('required');
			k2_cat_field.closest('.control-group').css('display','');
			}else if (val=='6'){
			k2_art_field.prop('required','required');
			k2_art_field.addClass('required');
			k2_art_field.closest('.control-group').css('display','');
			}else if (val=='7'){
			html_field.closest('.control-group').css('display','');
			}
		}

		jQuery('.nav-tabs a').click(function(){
			jQuery('[name="tab_href"]').val(jQuery(this).attr('href'));
		});

		var tab_href = '<?php echo $app->getUserState('djtabs.tab_href'); ?>';
		if(tab_href){
			jQuery('.nav-tabs a[href="'+tab_href+'"]').trigger('click');
		}

	});

</script>