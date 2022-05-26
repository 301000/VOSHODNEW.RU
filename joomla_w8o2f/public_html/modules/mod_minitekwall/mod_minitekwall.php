<?php
/**
* @title		Minitek Wall
* @copyright	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license		GNU General Public License version 3 or later.
* @author url	https://www.minitek.gr/
* @developers	Minitek.gr
*/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Component\MinitekWall\Site\Controller\DisplayController;

$app = Factory::getApplication();

// Abort if this is a content form page
$input = $app->input;

if ($input->get('option') == 'com_content' && $input->get('view') == 'form' && $input->get('layout') == 'edit')
	return;

// Add component registry file (for assets)
$document = $app->getDocument();
$wa = $document->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_minitekwall');

// Store original page vars
$option = $input->getCmd('option', NULL);
$view = $input->getCmd('view', NULL);
$layout = $input->getCmd('layout', NULL);
$task = $input->getCmd('task', NULL);

// Get widget id
$widget_id = $params->get('widget_id', 0, 'INT');

// Change to Minitek Wall view
$input->set('option', 'com_minitekwall');
$input->set('view', 'masonry');
$input->set('widget_id', $widget_id);
$input->set('layout', '');
$input->set('task', 'display');

// Load language
$lang = Factory::getLanguage();
$lang->load('com_minitekwall', JPATH_SITE);

// Load controller
$config = array(
	'base_path'		=> JPATH_SITE .'/components/com_minitekwall',
	'view_path'		=> JPATH_SITE .'/components/com_minitekwall/src/Module/views',
	'model_path'	=> JPATH_SITE .'/components/com_minitekwall/src/Module/models',
	'name'			=> 'Module', // view prefix
	'model_prefix'	=> 'ModuleModel', // model prefix
);

$controller = new DisplayController($config);
$controller->execute('display');

// Revert back to original page vars
if ($option != null)
{
	$input->set('option', $option);
}

if ($view != null)
{
	$input->set('view', $view);
}

if ($layout != null)
{
	$input->set('layout', $layout);
}

if ($task != null)
{
	$input->set('task', $task);
}
