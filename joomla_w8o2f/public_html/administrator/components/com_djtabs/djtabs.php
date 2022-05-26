<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_COMPONENT.'/lib/djlicense.php';
require_once JPATH_COMPONENT.'/helpers/helper.php';

$app = JFactory::getApplication();
$document = JFactory::getDocument();

$db = JFactory::getDBO();
$db->setQuery("SELECT manifest_cache FROM #__extensions WHERE element='com_djtabs' LIMIT 1");
$version = json_decode($db->loadResult());
$version = $version->version;

//define('DJTABSFOOTER', '<div style="text-align: center; margin: 10px 0; clear:both; ">DJ-Tabs (version '.$version.'), &copy; 2012-'.JFactory::getDate()->format('Y').' Copyright by <a target="_blank" href="http://dj-extensions.com">DJ-Extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djtabs/assets/logo.png" alt="dj-extensions.com" style="margin: 20px 0 0;" /></a></div>');
define('DJTABSFOOTER', '<div class="djc-footer"><a class="djc-logo" target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djtabs/assets/images/logo.png" alt="DJ-Extensions.com" /></a><ul class="djc-links"><li><a href="https://extensions.joomla.org/extension/dj-tabs/" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/feedback.svg" alt="Feedback" /><span>Leave feedback on JED</span></a></li><li><a href="https://dj-extensions.com/support-center" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/support.svg" alt="Support" /><span>Contact support</span></a></li><li><a href="https://dj-extensions.com/blog/general/join-our-affiliate-program-and-make-money-sending-people-to-dj-extensions" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/affiliate.svg" alt="Affiliate" /><span>Become an Affiliate</span></a></li><li><a href="http://feedback.dj-extensions.com/forums/301899-general?category_id=118263" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/help.svg" alt="Help" /><span>Help us to improve DJ-Tabs</span></a></li></ul><p>&copy; 2009-'.date('Y').' by <a target="_blank" href="https://dj-extensions.com">DJ-Extensions.com</a> | All rights reserved.</p><ul class="djc-social"><li><a href="https://twitter.com/DJExtensions" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/follow.svg" alt="Follow" /><span>Follow</span></a></li><li><a href="https://www.facebook.com/djextensions/" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/like.svg" alt="Like" /><span>Like</span></a></li><li><a href="https://www.youtube.com/channel/UCii84LGLNgDiGcsWR1u546g" target="_blank"><img src="'.JURI::base().'components/com_djtabs/assets/images/subscribe.svg" alt="Subscribe" /><span>Subscribe</span></a></li></ul></div>');

$controller = JControllerLegacy::getInstance('djtabs');

if ($document->getType() == 'html') {
	$document->addStyleSheet(JURI::base(true).'/components/com_djtabs/assets/style.css', array('version' => 'auto'));
}

$controller->execute($app->input->get('task'));
$controller->redirect();

?>