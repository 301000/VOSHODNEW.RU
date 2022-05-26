<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

//require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'groups.php');

class DJTabsViewItems extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		//$groups = new DJTabsModelGroups();
		$this->group_options = $this->get('Groups');

		$this->addToolbar();
		if (class_exists('JHtmlSidebar') && version_compare(JVERSION, '4', '<')){
			$this->sidebar = JHtmlSidebar::render();
		}	
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$doc = JFactory::getDocument();
		$par = JComponentHelper::getParams('com_djtabs');
		
		$doc->addStyleDeclaration('.icon-48-items { background-image: url(components/com_djtabs/assets/icon-48-items.png); }');
			
		JToolBarHelper::title(JText::_('COM_DJTABS').' · '.JText::_('COM_DJTABS_ITEMS'), 'items');

		JToolBarHelper::addNew('item.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('item.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djtabs', 550, 500);

		if($par->get('thumbnails',0)){
			JToolBarHelper::divider();
			JToolBarHelper::custom('items.purgeThumbs', 'trash', 'trash','COM_DJTABS_PURGE_THUMBS', false);
			JToolBarHelper::custom('items.resmushThumbs', 'wand', 'wand','COM_DJTABS_RESMUSH_THUMBS', false);
		}	
	}
}