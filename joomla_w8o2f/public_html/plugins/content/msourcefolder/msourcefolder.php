<?php
/**
* @title				Minitek Source Folder
* @copyright   	Copyright (C) 2011-2019 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

/**
 * Minitek Source Plugin
 *
 * @since  1.0.0
 */
class PlgContentMSourceFolder extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var boolean
	 *
	 * @since 4.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Prepare source type and image.
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	function onWidgetPrepareSource()
	{
		$source = array();
		$source['type'] = 'folder';
		$source['title'] = JText::_('PLG_CONTENT_MSOURCEFOLDER_SOURCE_TITLE');
		$source['image'] = JURI::root(true).'/plugins/content/msourcefolder/images/icon.png';

		return $source;
	}

	/**
	 * Prepare form and add my field.
	 *
	 * @param   JForm  $form  The form to be altered.
	 * @param   mixed  $data  The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   4.0.0
	 */
	function onContentPrepareForm($form, $data)
	{
		$app    = JFactory::getApplication();
		$option = $app->input->get('option');
		$view = $app->input->get('view');

		if (($option == 'com_minitekwall' || $option == 'com_minitekslider') && $view == 'widget')
		{
			if ($app->isClient('administrator'))
			{
				JForm::addFormPath(__DIR__ . '/forms');
				$form->loadFile('folder', false);
			}

			return true;
		}

		return true;
	}
}
