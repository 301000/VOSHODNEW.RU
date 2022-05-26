<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die();

class JFormFieldDJGroup extends JFormField {
	
	protected $type = 'DJGroup';
	
	protected function getInput()
	{
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$lang = JFactory::getLanguage();

		$lang->load('com_djtabs', JPATH_ADMINISTRATOR, 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR.'/components/com_djtabs', 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR, null, true, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR.'/components/com_djtabs', null, true, false);

        $query = "SELECT id, title FROM #__djtabs_groups WHERE published=1 ORDER BY title";		
        $db->setQuery($query);
        $groups = $db->loadObjectList();
		
		$groups_array = array();
		foreach($groups as $group){
			$groups_array[] = JHTML::_('select.option',$group->id,$group->title);
		}	

		$html = JHTML::_('select.genericlist', $groups_array, $this->name, '', 'value', 'text', $this->value);
		//$html .= '&nbsp;<a id="items_link" href="index.php?option=com_djtabs&view=items&filter_group=" target="_blank"><span id="group_name" title="'.JText::_('COM_DJTABS_GROUP_ITEMS_LINK_TITLE').'"></span></a>';
		$html .= '&nbsp;<a id="items_link" href="index.php?option=com_djtabs&view=items&filter_group=" target="_blank" title="'.JText::_('COM_DJTABS_GROUP_ITEMS_LINK_TITLE').'" class="hasTooltip" rel=" ">'.JText::_('COM_DJTABS_GROUP_ITEMS_LINK').'&nbsp;<span class="icon-new-tab"> </span></a>';
		

		JHTML::_('jquery.framework');
		JHtml::_('bootstrap.tooltip');
		$document->addScriptDeclaration("
		jQuery(function($){
			updateLink();
			$('#jformparamsgroup_id').change(function(){
				updateLink();
			});
			function updateLink()
			{
				//$('#group_name').text($('#jformparamsgroup_id option:selected').text());
				$('#items_link').attr('href', 'index.php?option=com_djtabs&view=items&filter_group='+$('#jformparamsgroup_id').val());
			}
		});
		");
		
		return $html;
	}
}
?>