<?php 
/*
* Pixel Point Creative - Cinch Menu
* License: GNU General Public License version
* See: http://www.gnu.org/copyleft/gpl.html
* Copyright (c) Pixel Point Creative LLC.
* More info at http://www.pixelpointcreative.com
* Last Updated: 6/26/21
*/

defined('_JEXEC') or die('Restricted access');

class JFormFieldUpgradecheck extends JFormField {
	
	var   $_name = 'Upgradecheck';
	
	protected function getInput()
	{
		return ' ';
	}	
	
	protected function getLabel()
	{
		//check for cURL support before we do anyting esle.
		if(!function_exists("curl_init")) return 'cURL is not supported by your server. Please contact your hosting provider to enable this capability.';
		//If cURL is supported, check the current version available.
		else 
				$version = 1.2;
				$target = 'http://www.pixelpointcreative.com/upgradecheck/shapeview/index.txt';
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $target);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				$str = curl_exec($curl);
				curl_close($curl);
				
						
				$message = '
		
	<div style="text-align:center;max-width:100%; min-width:300px; border: 1px solid rgba(0,0,0,0.125);  border-radius: .25rem;padding:10px;margin-bottom:25px;">
		<img width="250" height="170" border="0" src="../modules/mod_cinch_menu/elements/cinchmenu.jpg" title="Cinch Menu" alt="Cinch Menu"></br/>
		</div>';
			
	
		return 
				$message;
		
		
			
				
											
	  }
}