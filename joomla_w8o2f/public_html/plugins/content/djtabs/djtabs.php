<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class plgContentDJTabs extends JPlugin
{
	protected static $tabs = array();
	/**
	 * Plugin that loads DJ-Tabs within content
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0){
		
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'djtabs') === false) {
			return true;
		}

		// expression to search for (positions)
		$regex		= '/{djtabs\s*(\d*)\s*(\-?\d*)\s*(\w*)}/i';
		$regex2		= '/<img [^>]*alt="djtabs:(\d*),(\-?\d*),(\w*)"[^>]*>/i';
		//$style		= $this->params->def('style', 'none');
		
		// replace the image placeholder with plugin code
		$article->text = preg_replace($regex2, '{djtabs $1 $2 $3}', $article->text);

		// Find all instances of plugin and put in $matches for djmedia code
		// $matches[0] is full pattern match, $matches[1] is the album ID
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {
				$output = '';
				// Chceck if group ID is set.
				if (isset($match[1]) && (int)$match[1] > 0) {
					$output = $this->_load($match[1],$match[2],$match[3]);
				}
				// We should replace only first occurrence in order to allow the same category to regenerate their content:
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
			}
		}
	}

	protected function _load($groupid, $themeid, $layout)
	{
		$tab_instance_id = $groupid.$themeid.$layout;
		if(isset(self::$tabs[$tab_instance_id])){
			return;
		}
		
		self::$tabs[$tab_instance_id] = '';
		
		require_once (JPATH_BASE.'/components/com_djtabs/helpers/helper.php');
		require_once (JPATH_BASE.'/components/com_djtabs/models/tabs.php');
			
		$document = JFactory::getDocument();
		$par = JComponentHelper::getParams('com_djtabs');
		$lang = JFactory::getLanguage();

		$lang->load('com_djtabs', JPATH_SITE, 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_SITE.'/components/com_djtabs', 'en-GB', false, false);
		$lang->load('com_djtabs', JPATH_SITE, null, true, false);
		$lang->load('com_djtabs', JPATH_SITE.'/components/com_djtabs', null, true, false);

		$document->addScript('components/com_djtabs/assets/js/script.js', array('version' => 'auto'), array('defer' => 'defer'));
		if($par->get('wcag_script', '1')){
			$document->addScript('components/com_djtabs/assets/js/wcag.js', array('version' => 'auto'), array('defer' => 'defer'));
		}
	 	$document->addStyleSheet('components/com_djtabs/assets/css/style.css', array('version' => 'auto'));
		
		$lang = JFactory::getLanguage();
		$lang->load('com_djtabs', JPATH_SITE . '/components/com_djtabs');
		
		$params = JComponentHelper::getParams('com_djtabs');
		$params->set('theme',$themeid);
		$params->set('prefix','p'.$tab_instance_id);
		//$params->set('truncate_titles', '0'); // backward compatibility
		
		$tabs = DJTabsModelTabs::getTabs($groupid);
		
		$layout = $layout == 'tabs' ? 'default' : $layout; // backward compatibility
		
		DjTabsHelper::addThemeCSS($params); 
		DjTabsHelper::addTabsScriptDeclaration($layout, $params, true, 'p'.$tab_instance_id.'_');
		
		ob_start();

		require(JModuleHelper::getLayoutPath('mod_djtabs',$layout));

		self::$tabs[$tab_instance_id] = ob_get_clean();
		
		return self::$tabs[$tab_instance_id];
	}
	
}
