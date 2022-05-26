<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die();

class JFormFieldDJType extends JFormField
{
	protected $type = 'DJType';
	
	protected function getInput()
	{
		$types[] = JHTML::_('select.option', 2, JText::_('COM_DJTABS_TYPE_SINGLE_ARTICLE'));
		$types[] = JHTML::_('select.option', 1, JText::_('COM_DJTABS_TYPE_ARTICLE_CATEGORY'));
		$types[] = JHTML::_('select.option', 3, JText::_('COM_DJTABS_TYPE_MODULE_POSITION'));
		$types[] = JHTML::_('select.option', 4, JText::_('COM_DJTABS_TYPE_VIDEO_LINK'));
		
		if(JComponentHelper::isInstalled('com_k2'))
		{
			$types[] = JHTML::_('select.option', 6, JText::_('COM_DJTABS_TYPE_K2_SINGLE_ARTICLE'));
			$types[] = JHTML::_('select.option', 5, JText::_('COM_DJTABS_TYPE_K2_ARTICLE_CATEGORY'));
		}

		$types[] = JHTML::_('select.option', 7, JText::_('COM_DJTABS_TYPE_CUSTOM'));

		$html = JHTML::_('select.genericlist', $types, $this->name, '', 'value', 'text', $this->value);
		
		return $html;
	}
	
}
?>