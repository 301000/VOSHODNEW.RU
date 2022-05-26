<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die( 'Restricted access' );

use Joomla\Utilities\ArrayHelper;

$content_helper_route = JPATH_BASE . '/components/com_content/helpers/route.php';
if(file_exists($content_helper_route)){
	include_once $content_helper_route;
}
require_once (JPATH_BASE . '/components/com_djtabs/helpers/helper.php');
require_once (JPATH_BASE . '/administrator/components/com_djtabs/lib/djimage.php');
JModelLegacy::addIncludePath(JPATH_BASE . '/components/com_content/models', 'ContentModel');

class DJTabsModelTabs extends JModelList
{
	 public static function getTabs($groupid)
	 {
		$app = JFactory::getApplication();
        $db = JFactory::getDBO();
		$user = JFactory::getUser();
		
        $query = 'SELECT * FROM #__djtabs_items ' 
        		.'WHERE group_id = '.$groupid.' AND published=1 '
				.'AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') '
        		.'ORDER BY ordering ';
        $db->setQuery($query);
        $tabs = $db->loadObjectList();

        foreach ($tabs as $tab) {
			$tab->params = self::getMergedParams($tab->params);

            if ($tab->type == 1){ //article category
				$tab->content = self::getArticleCategory($tab->params);
				$tab->type_cl = 'type-article-category';
				if($tab->params->get('articles_display', '1') == '3'){
					self::artSpaceCount($tab);
				}
            }else if ($tab->type == 2){ //article
				$tab->content = self::getArticle($tab->params);
				$tab->type_cl = 'type-article';
            }else if ($tab->type == 3){ //module
				$tab->mod_pos = $tab->params->get('module_position');
				$tab->type_cl = 'type-module';
            }else if ($tab->type == 4){ //video link
				$tab->video_link = self::convertVideoLink($tab->params->get('video_link'));
				$tab->type_cl = 'type-video';
            }else if ($tab->type == 5){ //K2 category
				$tab->content = self::getK2Category($tab->params);
				$tab->type_cl = 'type-k2-category';
				if($tab->params->get('articles_display', '1') == '3'){
					self::artSpaceCount($tab);
				}
            }else if ($tab->type == 6){ //K2 item
				$tab->content = self::getK2Item($tab->params);
				$tab->type_cl = 'type-k2-item';
			}else if ($tab->type == 7){ //custom html
				$tab->content = JHTML::_('content.prepare', $tab->params->get('custom'));
				$tab->type_cl = 'type-custom';
			}
			
			if($tab->type == 1 || $tab->type == 5){
				if($tab->params->get('articles_display', '1') == '1'){
					$tab->inner_acc_cl = 'accordion-in accordion_first_out';
				}elseif($tab->params->get('articles_display', '1') == '2'){
					$tab->inner_acc_cl = 'accordion-in accordion_all_in';
				}else{
					$tab->inner_acc_cl = '';
				}
			}

			
        }

        return $tabs;
	}
	
	static function artSpaceCount(&$tab)
	{
		$art_per_row = $tab->params->get('articles_per_row', '1');
		$art_per_row = is_numeric($art_per_row) && $art_per_row ? $art_per_row : '1';

		if($art_per_row != '1'){
			$art_space = $tab->params->get('articles_space', '0');
			$art_space = is_numeric($art_space) ? $art_space : '0';	
			$art_width = (100-(($art_per_row-1) * $art_space))/$art_per_row;
			$art_width = "width:".$art_width."%;";
			$art_space = "margin-right:".$art_space."%;";
		}else{
			$art_width = '';
			$art_space = '';
		}

		$tab->art_per_row = $art_per_row;
		$tab->art_width = $art_width;
		$tab->art_space = $art_space;
	}

	static function getArticle($tab_params)
	{
        $app = JFactory::getApplication();
		$db = JFactory::getDBO();
		
		$now = new JDate();
		$now = $now->toSQL();

        $model_article = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
        $model_article->setState('params', $app->getParams('com_content'));//merging specific article params into global article params

        $article_id = $tab_params->get('article_id');
        
		$query = "SELECT * "
		."FROM #__content "
		."WHERE id=$article_id AND state=1 AND ".$db->quote($now)." >= publish_up and (".$db->quote($now)." <= publish_down OR IFNULL(publish_down, '0000-00-00 00:00:00') = '0000-00-00 00:00:00') "
		."AND access IN (".implode(',', JFactory::getUser()->getAuthorisedViewLevels()).")";
		$db->setQuery($query);
		$exists = $db->loadObject();
		
		if(!$exists){ //setting empty params if article not available to avoid tmpl errors
		
			return self::getEmptyObject();
		
		}else{
        	
			$item = $model_article->getItem($article_id);

            $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));			
            $item->cat_link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
			$item->slug = $item->id.':'.$item->alias;

			self::manageImage($tab_params, $item);
			self::cleanText($item->introtext);
			self::formatText($item->introtext, $tab_params);
			self::parseContentPlugins($item);
			
			$item->params = ($item->params ? $item->params : $app->getParams('com_content'));//in case of not loading article params
			self::mergeArticleParams($tab_params, $item->params);

        	return $item;
        }

    }

	static function getArticleCategory($tab_params)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		
		$now = new JDate();
		$now = $now->toSQL();

		$model_article = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
        $model_article->setState('params', $app->getParams('com_content')); // merging specific article params into global article params
		
		$cat_ids = $tab_params->get('category_id', 'NULL');
		$tag_ids = $tab_params->get('articles_tags', array());
		$art_limit = $tab_params->get('article_limit', '');
		$art_order = $tab_params->get('articles_ordering','ordering');
		$art_order_dir = $tab_params->get('articles_ordering_direction','');
		$art_min_date = $tab_params->get('articles_min_date','');
		$art_max_date = $tab_params->get('articles_max_date','');
		$max_cat_lvl = $tab_params->get('max_category_levels','1');
		$art_from_last_x_days = $tab_params->get('articles_from_last_x_days','');
		$art_from_last_x_days = is_numeric($art_from_last_x_days) ? $art_from_last_x_days : '';

		$cat_ids = is_array($cat_ids) ? implode(',',$cat_ids) : $cat_ids;
		
		$tagsWhere = '';
		if(!is_array($tag_ids)) $tag_ids = array($tag_ids);
		if(count($tag_ids) > 0) {
			
			$tag_ids = implode(',', ArrayHelper::toInteger($tag_ids));
			
			$subQuery = $db->getQuery(true)
			->select('DISTINCT content_item_id')
			->from($db->quoteName('#__contentitem_tag_map'))
			->where('tag_id IN (' . $tag_ids . ')')
			->where('type_alias = ' . $db->quote('com_content.article'));
			
			$tagsWhere = 'AND i.id IN ('.implode(',',$db->setQuery($subQuery)->loadColumn()).') ';
		}

		$featuredWhere = '';
		if($tab_params->get('articles_featured_only','0')){
			$featuredWhere = 'AND i.featured=1 ';
		}
		
		if($art_order == "random"){
			$art_order = "RAND()";
		}else{
			$art_order = "i.".$art_order." ".($art_order_dir == '-1' ? 'DESC' : 'ASC');
		}
		
		$query="SELECT i.*, node.title as category_title, u.name as author ".
				"FROM #__content AS i LEFT JOIN #__users u ON i.created_by=u.id, ".
				"#__categories AS node, ".
				"#__categories AS parent ".
				"WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.id=i.catid ".
				//"AND i.state=1 AND ".$db->quote($now)." >= i.publish_up and ( ".$db->quote($now)." <= i.publish_down or i.publish_down < i.publish_up) ".
				"AND i.state=1 AND ".$db->quote($now)." >= i.publish_up and ( ".$db->quote($now)." <= i.publish_down or IFNULL(i.publish_down, '0000-00-00 00:00:00') = '0000-00-00 00:00:00') ".
					($art_min_date ? " AND i.created>=".$db->quote($art_min_date) : "").
					($art_max_date ? " AND i.created<=".$db->quote($art_max_date) : "").
					($art_from_last_x_days ? " AND i.created>=(".$db->quote($now)." - INTERVAL ".$db->quote($art_from_last_x_days)." DAY)" : "")." ".
				"AND node.level-parent.level<".$max_cat_lvl." AND parent.id IN (".$cat_ids.") ".
				"AND i.access IN (".implode(',', JFactory::getUser()->getAuthorisedViewLevels()).") ".
				$tagsWhere.
				$featuredWhere.
				"ORDER BY ".$art_order.", i.title ".($art_limit ? "LIMIT ".$art_limit : "");

		$db->setQuery((string)$query);

		$items = $db->loadObjectList();

		foreach($items as $item){
			
			$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
			$item->cat_link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
			$item->slug = $item->id.':'.$item->alias;
			$item->params = $model_article->getItem($item->id)->params;

			self::manageImage($tab_params, $item);
			self::cleanText($item->introtext);
			self::formatText($item->introtext, $tab_params);
			self::parseContentPlugins($item);
			
			self::setArticleCategoryParams($tab_params, $item);
		}        

     return $items;

    }

	static function getK2Item($tab_params)
	{
		$k2_helper_route = JPATH_SITE . '/components/com_k2/helpers/route.php';
		
		if(JFile::exists($k2_helper_route)){
			
			require_once($k2_helper_route);
			
	        $app = JFactory::getApplication();
			$db = JFactory::getDBO();
			
			$now = new JDate();
			$now = $now->toSQL();
	
	        $item_id = $tab_params->get('k2_item_id');
	        
			$query ="SELECT a.*, b.userName author, c.name category_title, c.alias c_alias 
					FROM #__k2_items a 
					LEFT JOIN #__k2_users b ON a.created_by=b.userID 
					LEFT JOIN #__k2_categories c ON a.catid=c.id 
					WHERE a.id=$item_id AND a.trash=0 AND a.published=1 AND ".$db->quote($now)." >= a.publish_up 
					AND (".$db->quote($now)." <= a.publish_down OR a.publish_down < a.publish_up)";
			$db->setQuery($query);
			$item=$db->loadObject();
			
			if(!$item){ //setting empty params if article not available to avoid tmpl errors
			
				return self::getEmptyObject();
			
			}else{

	            $item->state = 1;
	            
	            $item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->c_alias))));
		        $item->cat_link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->c_alias))));
	
				self::cleanText($item->introtext);
				self::formatText($item->introtext, $tab_params);
				self::parseContentPlugins($item);
				
				$item->params = $app->getParams('com_content');
					
				self::mergeArticleParams($tab_params, $item->params);

				if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg'))
				{
					$item->image_url = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
				}else{
					$item->image_url = '';
				}
				
	        	return $item;
	        }
	
		}
	}

	static function getK2Category($tab_params)
	{
		$k2_helper_route = JPATH_SITE . '/components/com_k2/helpers/route.php';
		
		if(JFile::exists($k2_helper_route)){
			
			require_once($k2_helper_route);
			
	        $app = JFactory::getApplication();
			$db = JFactory::getDbo();
			
			$now = new JDate();
			$now = $now->toSQL();
	
			$cat_ids = $tab_params->get('k2_category_id', 'NULL');
			$art_limit = $tab_params->get('article_limit', '');
			$art_order = $tab_params->get('articles_ordering','ordering');
			$art_order_dir = $tab_params->get('articles_ordering_direction','');
			$art_min_date = $tab_params->get('articles_min_date','');
			$art_max_date = $tab_params->get('articles_max_date','');
			//$max_cat_lvl = $tab_params->get('max_category_levels','1');
			$art_from_last_x_days = $tab_params->get('articles_from_last_x_days','');
			$art_from_last_x_days = is_numeric($art_from_last_x_days) ? $art_from_last_x_days : '';
	
			$cat_ids = is_array($cat_ids) ? implode(',',$cat_ids) : $cat_ids;
	
			if($art_order == "random") $art_order = "RAND()";
			else if($art_order == "ordering") $art_order = "i.".$art_order;
			else $art_order = "i.".$art_order." ".($art_order_dir == '-1' ? 'DESC' : 'ASC');
			
			$query="SELECT i.*, c.name category_title, c.alias c_alias, u.userName author ".
					"FROM #__k2_items i ".
					"LEFT JOIN #__k2_users u ON i.created_by=u.userID ".
					"LEFT JOIN #__k2_categories c ON i.catid=c.id ".
					"WHERE i.trash=0 AND i.published=1 AND ".$db->quote($now)." >= i.publish_up ".
					"AND ( ".$db->quote($now)." <= i.publish_down or i.publish_down < i.publish_up) ".
						($art_min_date ? " AND i.created>=".$db->quote($art_min_date) : "").
						($art_max_date ? " AND i.created<=".$db->quote($art_max_date) : "").
						($art_from_last_x_days ? " AND i.created>=(".$db->quote($now)." - INTERVAL ".$db->quote($art_from_last_x_days)." DAY)" : "")." ".
					"AND i.catid IN (".$cat_ids.") ".
					"ORDER BY ".$art_order.", i.title ".($art_limit ? "LIMIT ".$art_limit : "");
	
			$db->setQuery((string)$query);
			$items = $db->loadObjectList();
	
			foreach($items as $item){
				
				$item->state = 1;
	            
	            $item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->c_alias))));
		        $item->cat_link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->catid.':'.urlencode($item->c_alias))));
	
				self::cleanText($item->introtext);
				self::formatText($item->introtext, $tab_params);
				self::parseContentPlugins($item);
				
				$item->params = $app->getParams('com_content');
					
				self::setArticleCategoryParams($tab_params, $item);

				if (JFile::exists(JPATH_SITE.'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg'))
				{
					$item->image_url = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
				}else{
					$item->image_url = '';
				}

			}        
	
	     return $items;
	
		}
	}

	static function getMergedParams($params_string)
	{
		$app = JFactory::getApplication();
		$par = $app->getParams('com_djtabs');

		$tab_params = new JRegistry();
		$tab_params->loadString($params_string);

		//$tab->params = $params->merge($tab_params);
		foreach($tab_params->toArray() as $key => $value){
			if($value == '' && $par->get($key) != ''){
				$tab_params->set($key, $par->get($key));
			}
		}

		$merged_params = new JRegistry();
		$merged_params->loadArray(array_merge($par->toArray(), $tab_params->toArray()));

		return $merged_params;
	}

	static function mergeArticleParams($tab_params, &$article_params)
	{
		if ($tab_params->get('author')!='')
			$article_params->set('show_author',$tab_params->get('author'));
		if ($tab_params->get('title')!='')
			$article_params->set('show_title',$tab_params->get('title'));
		if ($tab_params->get('title_link')!='')
			$article_params->set('link_titles',$tab_params->get('title_link'));
		if ($tab_params->get('category')!='')
			$article_params->set('show_category',$tab_params->get('category'));
		if ($tab_params->get('category_link')!='')
			$article_params->set('link_category',$tab_params->get('category_link'));
		if ($tab_params->get('date')!='')
			$article_params->set('show_create_date',$tab_params->get('date'));
		if ($tab_params->get('readmore_button')!='')
			$article_params->set('show_readmore',$tab_params->get('readmore_button'));

	}
	
	static function setArticleCategoryParams($tab_params, &$item)
	{
		$app = JFactory::getApplication();
        $article_params = $app->getParams('com_content');
		
		$item->show_author = ($tab_params->get('author')!='') ? $tab_params->get('author') : $article_params->get('show_author',0);
		$item->show_title = ($tab_params->get('title')!='') ? $tab_params->get('title') : $article_params->get('show_title',0);
		$item->link_titles = ($tab_params->get('title_link')!='') ? $tab_params->get('title_link') : $article_params->get('link_titles',0);
		$item->show_category = ($tab_params->get('category')!='') ? $tab_params->get('category') : $article_params->get('show_category',0);
		$item->link_category = ($tab_params->get('category_link')!='') ? $tab_params->get('category_link') : $article_params->get('link_category',0);
		$item->show_create_date = ($tab_params->get('date')!='') ? $tab_params->get('date') : $article_params->get('show_create_date',0);
		$item->show_readmore = ($tab_params->get('readmore_button')!='') ? $tab_params->get('readmore_button') : $article_params->get('show_readmore',0);

	}

	static function cleanText(&$text)
	{
		//$text = preg_replace('/{loadposition\s+(.*?)}/i', '', $text);
		//$text = preg_replace('/{loadmodule\s+(.*?)}/i', '', $text);
		//$text = preg_replace('/{djmedia\s*(\d*)}/i', '', $text);
		$text = preg_replace('/{djsuggester\s+(.*?)}/i', '', $text);
		$text = preg_replace('/{djtabs\s*(\d*)\s*(\-?\d*)\s*(\w*)}/i', '', $text);
		$text = preg_replace('/<img [^>]*alt="djtabs:(\d*),(\-?\d*),(\w*)"[^>]*>/i', '<div style="text-align:center;color:white;background:red;border-radius:3px;">&nbsp;DJ-Tabs articles within DJ-Tabs not allowed&nbsp;</div>', $text);
	}

	static function formatText(&$text, $params)
	{
		if ($params->get('HTML_in_description',0)){
			$intro_desc = $text;
		}else if ($params->get('description_char_limit')==''){
			$intro_desc = strip_tags($text);
		}else{
			$desc = strip_tags($text);
			$limit = $params->get('description_char_limit');
			if($limit && $limit - strlen($desc) < 0) {
				$desc = substr($desc, 0, $limit);
			// don't cut in the middle of the word unless it's longer than 20 chars
			if($pos = strrpos($desc, ' ')) {
				$limit = ($limit - $pos > 20) ? $limit : $pos;
				$desc = substr($desc, 0, $limit);
			}
			// cut text and add dots
			if(preg_match('/[a-zA-Z0-9]$/', $desc)) $desc.='&hellip;';
				$desc = '<p>'.nl2br(trim($desc)).'</p>';
			}
			$intro_desc = $desc;
		}

		$text = $intro_desc;
	}

	/*
	function getParams()
	{
		if (!isset($this->_params)) {
			$app = JFactory::getApplication();
			$mparams = $app->getParams(); 
			$this->_params = $mparams;
		}

		return $this->_params;
	}
	*/
	
	static function getEmptyObject()
	{
			$app = JFactory::getApplication();

			$object = new stdClass();
			$params = $app->getParams('com_content');
			$object->params = $params;
			$object->title = '';
			$object->images = '';
			$object->introtext = '';
			$object->author = '';
			$object->cat_link = '';
			$object->category_title = '';
			$object->state = 0;
			
			return $object;
	}
	
	static function convertVideoLink($link)
	{
		if($_link=stristr($link,'youtube')){
			$_link = '//www.youtube.com/embed/'.str_replace('youtube.com/watch?v=','',$_link).'?wmode=opaque&amp;rel=0&amp;enablejsapi=1';
		}
		else if($_link=stristr($link,'youtu.be')){
			$_link = '//www.youtube.com/embed/'.str_replace('youtu.be/','',$_link).'?wmode=opaque&amp;rel=0&amp;enablejsapi=1';
		}
		else if($_link=stristr($link,'vimeo')){
			$_link = '//player.vimeo.com/video/'.str_replace('vimeo.com/','',$_link);
		}
		
		return $_link;
	}
	
	static function manageImage($params, &$item)
	{
		$app_params = JComponentHelper::getParams('com_djtabs');

		if($params->get('image','0')){
			if($params->get('image','0') == '1' || $params->get('image','0') == '2'){
				if($params->get('image','0') == '1'){
					$image_type = 'image_intro';
				}else if($params->get('image','0') == '2'){
					$image_type = 'image_fulltext';
				}
	            $images = new JRegistry();
				$images->loadString($item->images);
				$old_path = $images->get($image_type);
				$item->image_alt = $images->get($image_type.'_alt');
				$item->image_caption = $images->get($image_type.'_caption');
			}else if($params->get('image','0') == '3'){
				// $xpath = new DOMXPath(@DOMDocument::loadHTML($item->fulltext));
				// $old_path = $xpath->evaluate("string(//img/@src)");
				if(preg_match("/<img [^>]*src=\"([^\"]*)\"[^>]*>/", $item->fulltext, $matches)){
					$old_path = $matches[1];
				}else if(preg_match("/<img [^>]*src=\"([^\"]*)\"[^>]*>/", $item->introtext, $matches)){
					$old_path = $matches[1];
				}
				$item->image_alt = '';
				$item->image_caption = '';
			}

			if(isset($old_path) && $app_params->get('thumbnails','0')=='1' && ($params->get('image_width',0) || $params->get('image_height',0))){
				$old_path_parts = pathinfo($old_path);
				$thumb_name = str_replace('/','__',$old_path_parts['dirname']).'__'.$old_path_parts['filename'].'__'.$params->get('image_width','0').'x'.$params->get('image_height','0').'.'.$old_path_parts['extension'];
				$new_path = 'components/com_djtabs/thumbs/'.$thumb_name;
				if(!file_exists($new_path)){
					DJTabsImage::makeThumb($old_path, $new_path, $params->get('image_width',0), $params->get('image_height',0));
				}
				$item->image_url = $new_path;
			}else{
				$item->image_url = isset($old_path) ? $old_path : '';
			}
		}else{
			$item->image_url = '';
		}
	}

	static function parseContentPlugins(&$article)
	{
		$app = JFactory::getApplication();

		$article->introtext = JHTML::_('content.prepare', $article->introtext);
		$params = new JObject;
		$article->text = $article->introtext;

		JPluginHelper::importPlugin('content');
		$app->triggerEvent('onContentPrepare', array('com_content.article', &$article, &$params, 0));
	}

}
