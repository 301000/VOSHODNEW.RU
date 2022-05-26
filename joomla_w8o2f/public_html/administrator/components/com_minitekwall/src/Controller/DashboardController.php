<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2020 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

namespace Joomla\Component\MinitekWall\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Component\MinitekWall\Administrator\Helper\MinitekWallHelper;

/**
 * Dashboard Controller
 *
 * @since  4.0.15
 */
class DashboardController extends BaseController
{
	/**
	 * Method to save the Download ID.
	 *
	 * @since   4.0.15
	 */
	public function saveDownloadId()
	{
		\JSession::checkToken('request') or jexit('Invalid token');

		$app = Factory::getApplication();
		$input = $app->input;
		
		$download_id = $input->get('downloadid', '', 'STRING');
		$id = $input->getInt('id');

		// Save Download ID
		if (MinitekWallHelper::saveDownloadId($id, $download_id))
		{
			$app->enqueueMessage(\JText::_('COM_MINITEKWALL_DASHBOARD_DOWNLOAD_ID_SAVED'), 'Message');
		}
		else 
		{
			$app->enqueueMessage(\JText::_('COM_MINITEKWALL_DASHBOARD_DOWNLOAD_ID_ERROR'), 'Error');
		}		

		// Redirect
		$app->redirect('index.php?option=com_minitekwall&view=dashboard');
	}
}
