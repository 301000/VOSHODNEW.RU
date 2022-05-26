<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\View\Grids;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\GenericDataException;

/**
 * Grids view class for Minitek Wall.
 *
 * @since  4.0.12
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 *
	 * @since  4.0.12
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  \Joomla\CMS\Pagination\Pagination
	 *
	 * @since  4.0.12
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  mixed
	 *
	 * @since  4.0.12
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var    \JForm
	 * @since  4.0.12
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var    array
	 * @since  4.0.12
	 */
	public $activeFilters;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

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
	 * @since   4.0.12
	 */
	protected function addToolbar()
	{
		$canDo = ContentHelper::getActions('com_minitekwall');
		$user  = Factory::getUser();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(\JText::_('COM_MINITEKWALL_CUSTOM_GRIDS_TITLE'), 'grid-2');

		if ($canDo->get('core.create'))
		{
			$toolbar->addNew('grid.add');
		}

		if ($canDo->get('core.edit.state'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fas fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			$childBar->publish('grids.publish')->listCheck(true);
			$childBar->unpublish('grids.unpublish')->listCheck(true);
			$childBar->archive('grids.archive')->listCheck(true);
			$childBar->checkin('grids.checkin')->listCheck(true);
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			$toolbar->delete('grids.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}
		elseif ($canDo->get('core.edit.state'))
		{
			$childBar->trash('grids.trash')->listCheck(true);
		}

		if ($user->authorise('core.admin', 'com_minitekwall') || $user->authorise('core.options', 'com_minitekwall'))
		{
			$toolbar->preferences('com_minitekwall');
		}

		\JHtmlSidebar::setAction('index.php?option=com_minitekwall&view=grids');
	}
}
