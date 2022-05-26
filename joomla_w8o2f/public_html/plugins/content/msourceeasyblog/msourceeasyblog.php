<?php
/**
* @title		Minitek Source EasyBlog
* @copyright	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

/**
 * Minitek Source Plugin
 *
 * @since  1.0.0
 */
class PlgContentMSourceEasyBlog extends JPlugin
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
		$source['type'] = 'easyblog';
		$source['title'] = JText::_('PLG_CONTENT_MSOURCEEASYBLOG_SOURCE_TITLE');
		$source['image'] = JURI::root(true).'/plugins/content/msourceeasyblog/images/icon.png';

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
 				$form->loadFile('easyblog', false);
 			}
 		}

 		return true;
	}
}
