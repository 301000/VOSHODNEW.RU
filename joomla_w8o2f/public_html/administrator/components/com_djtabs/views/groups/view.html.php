<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */
 
defined('_JEXEC') or die( 'Restricted access' );

//require_once(JPATH_ROOT.'/administrator/components/com_djtabs/helpers/helper.php');

class DJTabsViewGroups extends JViewLegacy
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
		//DJTabsAdminHelper::addToolbar();

		if (class_exists('JHtmlSidebar') && version_compare(JVERSION, '4', '<')){
			$this->sidebar = JHtmlSidebar::render();
		}

		// $this->bs_classes = DJTabsAdminHelper::getBSClasses();

		parent::display($tpl);
	}
	
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJTABS').' · '.JText::_('COM_DJTABS_GROUPS'), 'category');
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-category { background-image: url(components/com_djtabs/assets/icon-48-category.png); }');

        JToolBarHelper::addNew('group.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('group.edit','JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('', 'groups.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::custom('groups.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('groups.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djtabs', 550, 500);
		
	}
	
}