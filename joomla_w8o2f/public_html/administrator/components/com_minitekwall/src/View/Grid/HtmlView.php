<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\View\Grid;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\URI\URI;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\GenericDataException;

/**
 * Grid view class for Minitek Wall.
 *
 * @since  4.0.12
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   4.0.12
	 */
	public function display($tpl = null)
	{
		$this->form = $this->get('Form');
		$this->masonryform = $this->get('MasonryForm');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = ContentHelper::getActions('com_minitekwall', 'grid', $this->item->id);

		// Add assets
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
		$wa->useScript('com_minitekwall.admin-grid');

		if (!$this->item->get('elements'))
			$this->item->set('elements', '""');
	
		// Add script options 
		Factory::getDocument()->addScriptOptions('com_minitekwall', array(
			'elements' => $this->item->get('elements')
		));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user       = Factory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;

		\JToolbarHelper::title(
			\JText::_('COM_MINITEKWALL_GRID_TITLE_' . ($checkedOut ? 'VIEW_GRID' : ($isNew ? 'NEW_GRID' : 'EDIT_GRID'))),
			'pencil-2 article-add'
		);

		// For new records, check the create permission.
		if ($canDo->get('core.create') && $isNew)
		{
			\JToolbarHelper::saveGroup(
				[
					['apply', 'grid.apply'],
					['save', 'grid.save'],
					['save2new', 'grid.save2new']
				],
				'btn-success'
			);

			\JToolbarHelper::cancel('grid.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit');

			$toolbarButtons = [];

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				$toolbarButtons[] = ['apply', 'grid.apply'];
				$toolbarButtons[] = ['save', 'grid.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create'))
				{
					$toolbarButtons[] = ['save2new', 'grid.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2copy', 'grid.save2copy'];
			}

			\JToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			\JToolbarHelper::cancel('grid.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
