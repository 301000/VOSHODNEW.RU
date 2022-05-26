<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class DjTabsHelper
{	
	private static $modules = null;
	
	public static function addThemeCSS(&$params)
	{	
		$document= JFactory::getDocument();
		$db = JFactory::getDBO();
		
		$theme_id = $params->get('theme',0);

		if ($theme_id==0) //random theme
			$query = 'SELECT * FROM #__djtabs_themes '
					.'WHERE id!='.$theme_id.' and published=1 AND random=1 ORDER BY RAND() LIMIT 1';
		elseif ($theme_id>0)
        	$query = 'SELECT * FROM #__djtabs_themes ' 
        			.'WHERE id = '.$theme_id;
		
		if ($theme_id>=0){
	        $db -> setQuery($query);
	        $theme = $db -> loadObject();
			
			$css_params = new JRegistry();
			$css_params->loadString($theme->params);
				
		}
		
		if ($theme_id==0) 
			$theme_id = $theme->id;
		
		if($theme_id==-1){	//default-theme	
			$theme_title = 'default-theme';
			$file = 'components/com_djtabs/assets/css/default/'.$theme_title.'.css';
			$document->addStyleSheet($file);
		}
		elseif($theme->custom==0){ //solid theme
			$theme_title = str_replace(' ','-',$theme->title);
			$file1 = 'components/com_djtabs/assets/css/default/solid-theme.css';
			$file2 = 'components/com_djtabs/assets/css/default/'.$theme_title.'.css';
			$document->addStyleSheet($file1);
			$document->addStyleSheet($file2);
			$theme_title = 'solid-theme '.$theme_title;
		}
		else{
			$theme_title = str_replace(' ','-',$theme->title);
			$file = 'components/com_djtabs/assets/css/'.$theme_title.'.css';
			$path = JPATH_ROOT.'/'.$file;
			if(file_exists($path)){
				$document->addStyleSheet($file);
			}		
			else {
				self::generateThemeCSS($theme_id);
				$document->addStyleSheet($file);
			}
		}
		
		$params->set('class_theme_title',$theme_title);

	}

	public static function generateThemeCSS($theme_id)
	{	
		$db = JFactory::getDBO();
		
    	$query = 'SELECT * FROM #__djtabs_themes ' 
    			.'WHERE id = '.$theme_id;
					
        $db -> setQuery($query);
        $theme = $db -> loadObject();
		
		$css_params = new JRegistry();
		$css_params->loadString($theme->params);
		$theme_title = str_replace(' ','-',$theme->title);
		if (!$theme_title) $theme_title = 'default-theme';
		
		$file = JPATH_ROOT.'/components/com_djtabs/assets/css/'.$theme_title.'.css';
		$path = JPATH_ROOT.'/components/com_djtabs/assets/custom.css.php';
		
		ob_start();		
			include($path);		
		$buffer = ob_get_clean();
		
		JFile::write($file, $buffer);
		
	}
		
	public static function loadModules($position, $style = 'xhtml')
	{
		if (!isset(self::$modules[$position])) {
			self::$modules[$position] = '';
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('module');
			$modules	= JModuleHelper::getModules($position);
			$params		= array('style' => $style);
			ob_start();
			
			foreach ($modules as $module) {
				echo $renderer->render($module, $params);
			}
	
			self::$modules[$position] = ob_get_clean();
		}
		return self::$modules[$position];
	}
	
	public static function addTabsScriptDeclaration($layout, $params, $mod = false, $prfx = '')
	{
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();

		$opts = array();
		$opts['duration'] = $params->get('transition_speed', '500');
		if($params->get('tabs_trigger','1') == '2' && !JBrowser::getInstance()->isMobile()){
			$opts['trigger'] = 'mouseenter';
		}

		if(!$mod && $app->input->get('tab', '1') > 1){
			$opts['tab_no'] = $app->input->get('tab', '1');
		}elseif($params->get('tab', '1') > 1){
			$opts['tab_no'] = $params->get('tab', '1');
		}

		if($params->get('accordion_display', '1') == '2'){
			$opts['acc_display'] = 'all_in';
		}
		if($params->get('scroll_to_accordion', '0') != '0'){
			if($params->get('scroll_to_accordion') == '1'){
				$opts['acc_scroll'] = 'main';
			}elseif($params->get('scroll_to_accordion') == '2'){
				$opts['acc_scroll'] = 'cat';
			}elseif($params->get('scroll_to_accordion') == '3'){
				$opts['acc_scroll'] = 'all';
			}
		}
		if($params->get('video_autopause', '2') != '2'){
			$opts['vid_auto'] = $params->get('video_autopause') == '3' ? '' : 'pauseplay';
		}
		//echo '<pre>'; print_r($opts); echo '</pre>'; die();

		JHTML::_('jquery.framework');
		$document->addScriptDeclaration("jQuery(function($){ djTabsInit('".$prfx."djtabs', '".($layout == 'default' ? 'tabs' : 'accordion')."', ".($opts ? json_encode($opts) : '{}')."); });");
	}
	
}

?>