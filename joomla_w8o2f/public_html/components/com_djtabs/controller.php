<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */
 
defined('_JEXEC') or die;

class DJTabsController extends JControllerLegacy
{
	public function display($cachable = true, $urlparams = false)
	{
		$document = JFactory::getDocument();
		$par = JComponentHelper::getParams('com_djtabs');
		
		$document->addScript('components/com_djtabs/assets/js/script.js', array('version' => 'auto'), array('defer' => 'defer'));
		if($par->get('wcag_script', '1')){
			$document->addScript('components/com_djtabs/assets/js/wcag.js', array('version' => 'auto'), array('defer' => 'defer'));
		}
		$document->addStyleSheet('components/com_djtabs/assets/css/style.css', array('version' => 'auto'));
		
		if($par->get('cache', '') != ''){
			$cachable = $par->get('cache');
		}

		return parent::display($cachable, $urlparams);
	}
}

