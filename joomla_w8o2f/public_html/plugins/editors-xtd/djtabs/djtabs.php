<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

class plgButtonDJTabs extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of (imageName, textToInsert)
	 */
	public function onDisplay($name)
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		if($app->isClient('site')){
			return;
		}

		JHTML::_('jquery.framework');
		$js = "function jInsertDJTabs(groupid, themeid, layoutname, title) {
			var tag = '<div><img src=\"' + 'administrator/components/com_djtabs/assets/icon-90-editor.png' + '\" style=\"background: #f5f5f5 10px center no-repeat; display: block; max-width: 100%; max-height: 300px; margin: 10px auto; padding: 10px 50px 10px 50px; border: 1px solid #ddd; \" alt=\"djtabs:' + groupid +','+themeid+','+layoutname+ '\" title=\"' + title + '\"></div>';
			Joomla.editors.instances['".$name."'].replaceSelection(tag);
			if(typeof SqueezeBox !== 'undefined'){
				SqueezeBox.close();
			}
			jQuery('.joomla-modal').modal('hide');
		}";
		$doc->addScriptDeclaration($js);
		$doc->addStyleDeclaration('
			.button2-left .djtabs a {background: url("'.JURI::base(true).'/components/com_djtabs/assets/icon-16.png") 100% 50% no-repeat; margin: 0 4px 0 0; padding: 0 22px 0 6px;}
			body .icon-djtabs { height: 16px; width: 16px; background: url("'.JURI::base(true).'/components/com_djtabs/assets/icon-16.png") 0 0 no-repeat; margin: 0 0 -3px; }
		');
		
		//$link = 'index.php?option=com_djmediatools&amp;view=categories&amp;layout=modal&amp;tmpl=component&amp;f_name=jInsertDJMedia';
		$link = 'index.php?option=com_djtabs&amp;view=modal&amp;tmpl=component&amp;f_name=jInsertDJTabs';
		
		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = JText::_('PLG_EDITORSXTD_DJTABS_BUTTON');
		$button->name = 'djtabs';
		//$button->options = '{handler: \'iframe\', size: {x: \'100%\', y: \'100%\'}, onOpen: function() { window.addEvent(\'resize\', function(){ this.resize({x: window.getSize().x - 100, y: window.getSize().y - 100}, true); }.bind(this) ); window.fireEvent(\'resize\'); }}';

		// $x = '280';
		// $y = '280'; 
		// $button->options = '{handler: \'iframe\', size: {x: \'100%\', y: \'100%\'}, onOpen: function() { jQuery(window).on(\'resize\', function(){ this.resize({x: '.$x.', y: '.$y.'}, true); }.bind(this) ); jQuery(window).trigger(\'resize\'); }}';

		return $button;
	}
}

