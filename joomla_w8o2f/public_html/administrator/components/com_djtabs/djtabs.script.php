<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class com_djtabsInstallerScript
{
	function postflight($type, $parent)
	{
		if($type == 'update') {

			$paths = array(
				JPATH_ROOT.'/components/com_djtabs/router.php',
				JPATH_ROOT.'/components/com_djtabs/assets/icons.css',
				JPATH_ROOT.'/components/com_djtabs/assets/script.js',
				JPATH_ROOT.'/components/com_djtabs/views/tabs/tmpl/inc/art_space_count.php'
			);
			$custom_css = glob(JPATH_ROOT.'/components/com_djtabs/assets/css/dj-custom*.css');
			$paths = array_merge($paths, $custom_css);

			foreach($paths as $path){
				if(JFile::exists($path)) {
					@unlink($path);
				}
			}
		
			require_once(JPath::clean(JPATH_BASE.'/components/com_djtabs/lib/djlicense.php'));
			DJLicense::setUpdateServer('Tabs');
		}
	}
}