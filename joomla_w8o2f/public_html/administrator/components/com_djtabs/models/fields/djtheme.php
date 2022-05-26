<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die();

class JFormFieldDJTheme extends JFormField
{
	protected $type = 'DJTheme';
	
	protected function getInput()
	{
		$db = JFactory::getDBO();
		$document = JFactory::getDocument();
		$lang = JFactory::getLanguage();

		$lang->load('com_djtabs', JPATH_ADMINISTRATOR, 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR.'/components/com_djtabs', 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR, null, true, false);
		$lang->load('com_djtabs', JPATH_ADMINISTRATOR.'/components/com_djtabs', null, true, false);

        $query = 'SELECT * FROM #__djtabs_themes WHERE published=1 ORDER BY ordering, id';
					
        $db->setQuery($query);
        $themes = $db->loadObjectList();
		
		$rows_num = count($themes);
		
		$i=0;
		$random_num=rand(1, $rows_num);
		
		$themes_array[] = JHTML::_('select.option',-1,'['.JText::_('COM_DJTABS_DEFAULT_THEME').']');
		
		foreach ($themes as $theme){
			
			$i++;
			
			if ($i==$random_num)
				$themes_array[] = JHTML::_('select.option',0,'»'.JText::_('COM_DJTABS_RANDOM_THEME').'«');
			
			$themes_array[] = JHTML::_('select.option',$theme->id,$theme->title);		
			
		}	

		$html = JHTML::_('select.genericlist', $themes_array, $this->name, '', 'value', 'text', $this->value);
		$html .= '&nbsp;<a id="theme_link" href="index.php?option=com_djtabs&view=theme&layout=edit&id=" target="_blank" title="'.JText::_('COM_DJTABS_THEME_LINK_TITLE').'" class="hasTooltip" rel=" ">'.JText::_('COM_DJTABS_THEME_LINK').'&nbsp;<span class="icon-new-tab"> </span></a>';

		JHTML::_('jquery.framework');
		JHtml::_('bootstrap.tooltip');
		$document->addScriptDeclaration("
		jQuery(function($){
			updateLink();
			$('#jformparamstheme').change(function(){
				updateLink();
			});
			function updateLink()
			{
				var theme_id = $('#jformparamstheme').val();
				if(theme_id != '0' && theme_id != '-1'){
					$('#theme_link').show().attr('href', 'index.php?option=com_djtabs&view=theme&layout=edit&id='+$('#jformparamstheme').val());
				}else{
					$('#theme_link').hide();
				}
			}
		});
		");

		return $html;
		
	}
	
}
?>