<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

class DJTabsViewThemes extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
	
		$this->addToolbar();
		if (class_exists('JHtmlSidebar') && version_compare(JVERSION, '4', '<')){
			$this->sidebar = JHtmlSidebar::render();
		}
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJTABS').' · '.JText::_('COM_DJTABS_THEMES'), 'themes');
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-themes { background-image: url(components/com_djtabs/assets/icon-48-themes.png); }');
		
		JToolBarHelper::addNew('theme.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('theme.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'themes.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::custom('themes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('themes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djtabs', 550, 500);
		
	}
}