<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

class DjTabsViewgroup extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		$this->addToolbar();
		
		$this->bs_classes = DJTabsAdminHelper::getBSClasses();
		
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
	
		$isNew		= ($this->item->id == 0);

		$text = $isNew ? JText::_( 'COM_DJTABS_NEW' ) : JText::_( 'COM_DJTABS_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_DJTABS_GROUP' ).': <small><small>[ ' . $text.' ]</small></small>', 'generic.png' );

		JToolBarHelper::apply('group.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('group.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('group.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		JToolBarHelper::custom('group.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		JToolBarHelper::cancel('group.cancel', 'JTOOLBAR_CANCEL');
	}

}


