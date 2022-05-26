<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */
 
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.'/controller.php');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

$controller = new DJTabsController();
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();

?>