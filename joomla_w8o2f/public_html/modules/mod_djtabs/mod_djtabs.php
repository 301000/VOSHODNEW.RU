<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined ('_JEXEC') or die('Restricted access');

require_once (JPATH_BASE.'/components/com_djtabs/helpers/helper.php');
require_once (JPATH_BASE.'/components/com_djtabs/models/tabs.php');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

$app = JFactory::getApplication();
$document = JFactory::getDocument();
$par = JComponentHelper::getParams('com_djtabs');
$lang = JFactory::getLanguage();

$lang->load('com_djtabs', JPATH_SITE, 'en-GB', false, false);
$lang->load('com_djtabs', JPATH_SITE.'/components/com_djtabs', 'en-GB', false, false);
$lang->load('com_djtabs', JPATH_SITE, null, true, false);
$lang->load('com_djtabs', JPATH_SITE.'/components/com_djtabs', null, true, false);

foreach($params as $key => $value){ // setting global params
	if($value == '' && $par->get($key) != ''){
		$params[$key] = $par->get($key);
	}
}

if(($params->get('enable_mobile','1')=='0' && JBrowser::getInstance()->isMobile()) || ($params->get('enable_mobile','1')=='2' && !JBrowser::getInstance()->isMobile())){
	return;
}

//$params->set('truncate_titles', '0'); // backward compatibility
if($app->input->getInt('theme')){
	$params->set('theme', $app->input->getInt('theme'));
}

$groupid = $params->get('group_id',0);
if(!$groupid){
    return false;
}

$tabs = DJTabsModelTabs::getTabs($groupid);

$document->addScript('components/com_djtabs/assets/js/script.js', array('version' => 'auto'), array('defer' => 'defer'));
if($par->get('wcag_script', '1')){
	$document->addScript('components/com_djtabs/assets/js/wcag.js', array('version' => 'auto'), array('defer' => 'defer'));
}
$document->addStyleSheet('components/com_djtabs/assets/css/style.css', array('version' => 'auto'));

DjTabsHelper::addThemeCSS($params);
$layout = $params->get('layout', 'default');
$layout = $layout == 'tabs' ? 'default' : $layout; // backward compatibility

if (isset($module)){
	$prfx = 'mod'.$module->id.'_';
}else{
	$prfx = $params->get('prefix', 'modArt').'_';
}

DjTabsHelper::addTabsScriptDeclaration($layout, $params, true, $prfx);

require(JModuleHelper::getLayoutPath('mod_djtabs',$layout));
 
?>
