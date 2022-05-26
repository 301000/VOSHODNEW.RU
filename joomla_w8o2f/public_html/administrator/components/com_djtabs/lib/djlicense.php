<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

class DJLicense
{	
	public static function getSubscription($name)
	{
		$license_file = self::getLicenseFile($name);
		$license = JFile::exists($license_file) ? file_get_contents($license_file) : '';

		self::setUpdateServer($name, $license);

		$update = '';
		$update .= '<div class="djlic_box">';
		$update .= '<div class="djlic_logo_wrapper">';
		$update .= '<div class="djlic_logo"><img alt="DJ-'.$name.'" width="68" src="'.JURI::base().'components/com_djtabs/assets/images/dj-tabs.svg" /></div>';
		$update .= '<div class="djlic_title">DJ-'.$name.'</div>';
		$update .= '</div>';

		$can_check_xml = true;
		if(!in_array('SimpleXML', get_loaded_extensions())){
			$update .= self::renderAlert(JText::_('COM_DJTABS_DJLIC_SIMPLEXML_NOT_INSTALLED'), 'error');
			$can_check_xml = false;
		}
		if(!ini_get('allow_url_fopen')){
			$update .= self::renderAlert(JText::_('COM_DJTABS_DJLIC_ALLOW_URL_FOPEN_DISABLED'), 'warning');
			$can_check_xml = false;
		}
		if($can_check_xml){
			$update .= self::getDJVersion($name, $license);
		}

		$update .= self::getDJLicense($name, $license);
		$update .= '</div>';
		
		return $update;
	}

	static function getLicenseFile($name)
	{
		$config = JFactory::getConfig();
		$secret_file = JFile::makeSafe('license_'.$config->get('secret').'.txt');
		$license_file = JPath::clean(JPATH_ROOT.'/administrator/components/com_dj'.strtolower($name).'/'.$secret_file);
		return $license_file;
	}
	
	public static function renderAlert($msg, $type = '', $title = '') {
	
		$html = 	'<div class="alert alert-'.$type.'">'
				.		(!empty($title) ? '<h3>'.$title.'</h3>' : '')
				.		'<div class="alert-body">'.$msg.'</div>'
						.	'</div>';
	
		return $html;
	}
	
	public static function setUpdateServer($name, $license = null)
	{
		if(!$license){
			$license_file = self::getLicenseFile($name);
			$license = JFile::exists($license_file) ? file_get_contents($license_file) : '';
		}

		$db = JFactory::getDBO();
		$query = "SELECT extension_id, manifest_cache FROM #__extensions WHERE element='pkg_dj-".strtolower($name)."' AND type='package' ";
		$db->setQuery($query);
		$pkg = $db->loadObject();
			
		if($pkg) {
			$mc = json_decode($pkg->manifest_cache);
			$version = $mc->version;

			$extra_query = 'dlid='.$license.'&v='.$version.'&site='.JURI::root();
				
			$db->setQuery("SELECT COUNT(*) FROM #__update_sites WHERE name='DJ-".$name."' AND type='extension'");
			$exists = $db->loadResult();
			if($exists){
				$db->setQuery("UPDATE #__update_sites SET extra_query='".addslashes($extra_query)."' WHERE name='DJ-".$name."' AND type='extension'");
				$db->execute();
			}else{
				$db->setQuery("INSERT INTO #__update_sites (`name`, `type`, `location`, `enabled`, `extra_query`) VALUES ('DJ-".$name."', 'extension', 'https://dj-extensions.com/index.php?option=com_ars&view=update&task=stream&format=xml&id=10', 1, '".addslashes($extra_query)."')");
				$db->execute();
					
				$update_site_id = $db->insertid();
				$db->setQuery("INSERT INTO #__update_sites_extensions (`update_site_id`, `extension_id`) VALUES (".$update_site_id.", ".$pkg->extension_id.")");
				$db->execute();
			}
		}
	}

	static function storeLicense($license, $name)
	{
		self::setUpdateServer($name, $license);
		
		$license_file = self::getLicenseFile($name);
		$license_before = JFile::exists($license_file) ? file_get_contents($license_file) : '';
		JFile::write($license_file, $license);
		$license_after = JFile::exists($license_file) ? file_get_contents($license_file) : '';

		if(!$license_before && !$license && !$license_after){
			return self::renderAlert(JText::_('DJUPDATER_PROVIDE_KEY'), 'warning');
		}elseif($license_before == $license && $license_after){
			return self::renderAlert(JText::_('DJUPDATER_KEY_ALREADY_STORED'), 'warning');
		}elseif($license && $license_after){
			return self::renderAlert(JText::_('DJUPDATER_KEY_STORED'), 'success');
		}elseif(!$license && !$license_after){
			return self::renderAlert(JText::_('DJUPDATER_KEY_REMOVED'), 'success');
		}else{
			return self::renderAlert(JText::_('DJUPDATER_KEY_STORE_ERROR'), 'error');
		}
	}

	static function getDJVersion($name, $license)
	{		
		$db = JFactory::getDBO();
		$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".'pkg_dj-'.strtolower($name)."'";
		$db->setQuery($query);
		$result = $db->loadResult();
		
		$ext = 'com_dj'.strtolower($name);
		
		if(!$result){ // in case package wasn't installed check the version of the component
			$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$ext."'";
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		
		$mc = json_decode($result);
		$version = $mc->version;

		$djext = simplexml_load_file('https://dj-extensions.com/index.php?option=com_ars&view=update&task=stream&format=xml&id=10');
		if(empty($djext->update)){
			$download_url = '';
			$update_available = false;
		}else{
			$new_ver = $djext->update->version;
			$download_url = $djext->update->downloads->downloadurl;
			$update_available = version_compare($version, $new_ver, '<');
		}

		if(empty($djext->update)){
			$icon_class = 'djlic_icon_warning';
			$version_message = 'COM_DJTABS_DJLIC_VERSION_UNKNOWN';
			$component_version = 'COM_DJTABS_DJLIC_YOUR_VERSION';
			$latest_version = '';
		}elseif($update_available){
			$icon_class = 'djlic_icon_invalid';
			$version_message = 'COM_DJTABS_DJLIC_VERSION_OLD';
			$component_version = 'COM_DJTABS_DJLIC_YOUR_VERSION';
			$latest_version = '<span class="djlic_latest_version">'.JText::_('COM_DJTABS_DJLIC_NEW_VERSION').' <span>'.$new_ver.'</span></span>';
		}else{
			$icon_class = 'djlic_icon_valid';
			$version_message = 'COM_DJTABS_DJLIC_VERSION_LATEST';
			$component_version = 'COM_DJTABS_DJLIC_VERSION';
			$latest_version = '';
		}

		$update = '';

		$update .= '<div class="djlic_version">';
		$update .= '<span class="djlic_icon '.$icon_class.'"></span>';
		$update .= '<div class="djlic_info">';
		$update .= '<p>'.JText::_($version_message).'</p>';
		$update .= '<span class="djlic_current_version">'.JText::_($component_version).' <span>'.$version.'</span></span>';
		$update .= $latest_version;
		$update .= $update_available && $download_url ? '<a href="'.$download_url.'" target="_blank" title="'.JText::_('COM_DJTABS_DJLIC_UPDATE_DOWNLOAD').'"><span class="icon-download"> </span></a><span></span>' : '';
		$update .= '<a href="https://dj-extensions.com/support/changelogs/dj-tabs/" target="_blank">'.JText::_('COM_DJTABS_DJLIC_CHANGELOG').'</a>';
		// if($update_available){
		// 	$update .= '<div class="djlic_update update"><button id="update" class="btn btn-djex"> '.JText::_('DJUPDATER_UPDATE').'</button></div>';
		// }
		$update .= '</div>';
		$update .= '</div>';

		/*
		$js = "
			jQuery(document).ready(function(){

				var button = jQuery('#update');
				var loader = jQuery('<i class=\"icon-refresh djspin\" />');

				if(button.length) {
					button.click(function(e){

						e.preventDefault();
						
						if(confirm('".DJClassifiedsTheme::sanitizePopupText(JText::sprintf('DJUPDATER_CONFIRM_UPDATE_MESSAGE', 'DJ-'.$name))."')) {
				
							button.prop('disabled', true);
							button.prepend(loader);
					
							jQuery.ajax({
								url: 'index.php',
								method: 'post',
								data: {
									option: 'com_installer',
									view: 'install',
									task: 'install.install',
									installtype: 'url',
									'".JSession::getFormToken()."': 1,
									install_url: 'https://dj-extensions.com/index.php?option=com_djsubscriptions&view=getUpdate&license=$license&ext=$ext&v=".$version."'
								}
							}).done(function(data) {
								var hidden = jQuery('<div class=\"hidden\">'+data+'</div>');
								jQuery(document.body).append(hidden);
			
								var message = hidden.find('.alert');
								if(!message.length) message = data;
								button.closest('.update').prepend(message);
								setTimeout(function(){ location.reload(); }, 1000);
			
								hidden.remove();
							})
							.fail(function() {
								alert( 'connection error' );
								button.prop('disabled', false);
								loader.detach();
							});
						}
					});
				}
			});
		";

		JFactory::getDocument()->addScriptDeclaration($js);
		*/

		return $update;
	}

	static function getDJLicense($name, $license)
	{
		JHtml::_('bootstrap.tooltip');

		$update = '';

		$update .= '<div class="djlic_invalid">';
		$update .= '<span class="djlic_icon '.($license ? 'djlic_icon_valid' : 'djlic_icon_invalid').'"></span>';
		$update .= '<div class="djlic_info">';
		$update .= '<p>'.JText::_('COM_DJTABS_DJLIC_ENTER_LICENSE_CODE').' <a href="//'.JUri::getInstance()->getHost().'" target="_blank">'.JUri::getInstance()->getHost().'</a></p>';
		$update .= '<input id="license" type="text" name="license" class="input input-large" value="'.$license.'" placeholder="'. JText::_('DJUPDATER_PASTE_KEY').'" /><br />';
		$update .= '<button id="register" class="btn btn-djex" href="#">'.JText::_('DJUPDATER_REGISTER_KEY').'</button> ';
		$update .= '<a class="btn btn-link" target="_blank" href="https://dj-extensions.com/pricing">'.JText::_('DJUPDATER_BUY_LICENSE').'</a>';
		$update .= '</div>';
		$update .= '</div>';

		$js = "
			jQuery(document).ready(function(){

				var button = jQuery('#register');
				var loader = jQuery('<i class=\"icon-refresh djspin\" />');

				jQuery('#license').keyup(function(){
					jQuery(this).addClass('active');
				});
		
				button.click(function(e){
					button.prop('disabled', true);
					button.prepend(loader);
					e.preventDefault();
		
					jQuery.ajax({
						data: {
							option: '".'com_dj'.strtolower($name)."',
							task: 'license.save',
							extension: '".$name."',
							license: jQuery('#license').val()
						}
					}).done(function(data) {
						button.closest('.djlic_valid, .djlic_invalid, .djlic_expired').prepend(jQuery(data));
						setTimeout(function(){ location.reload(); }, 1000);
					})
					.fail(function() {
						alert( 'connection error' );
						button.prop('disabled', false);
						loader.detach();
					});
				});
			});
		";
		
		JFactory::getDocument()->addScriptDeclaration($js);

		return $update;
	}




}