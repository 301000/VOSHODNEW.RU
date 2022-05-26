<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class DJTabsControllerItems extends JControllerAdmin
{
	public function getModel($name = 'Item', $prefix = 'DJTabsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function purgeThumbs() {
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		
		if (!$user->authorise('core.admin', 'com_djtabs')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
		
		$files = JFolder::files(JPATH_ROOT.'/components/com_djtabs/thumbs', '.', true, true, array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX')); 
		$errors = array();
		if (count($files) > 0) {
			foreach ($files as $file) {
				if (!JFile::delete($file)){
					$errors[] = $file;
				}
			}
		}
		
		if (count($errors) > 0) {
			$this->setRedirect( 'index.php?option=com_djtabs&view=items', JText::_('COM_DJTABS_THUMBS_NOT_DELETED'), 'error');
		} else {
			$this->setRedirect( 'index.php?option=com_djtabs&view=items', count($files).' '.JText::_('COM_DJTABS_THUMBS_DELETED'), 'notice');
		}

	}
	
	public function resmushThumbs() {
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		
		$url = 'http://www.resmush.it/ws.php';
		
		if (!$user->authorise('core.admin', 'com_djtabs')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
		
		$files = JFolder::files(JPATH_ROOT.'/components/com_djtabs/thumbs', '.', true, true, array('index.html', '.svn', 'CVS', '.DS_Store', '__MACOSX')); 
		$errors = array();
		if (count($files) > 0) {
			foreach ($files as $file) {
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, array('files' => '@' . $file));
				$data = curl_exec($ch);
				curl_close($ch);
				$json = json_decode($data);
				
				// download and write file only if image size is smaller
				if($json->src_size > $json->dest_size) {

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $json->dest);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
					$image = curl_exec($ch);
					curl_close($ch);
					
					if (!JFile::write($file, $image)){
						$errors[] = $file;
					}
					
				}
		
			}
		}
		
		if (count($errors) > 0) {
			$this->setRedirect( 'index.php?option=com_djtabs&view=items', JText::_('COM_DJTABS_THUMBS_NOT_OPTIMISED'), 'error');
		} else {
			$this->setRedirect( 'index.php?option=com_djtabs&view=items', count($files).' '.JText::_('COM_DJTABS_THUMBS_OPTIMISED'), 'notice');
		}

	}
	
	
}