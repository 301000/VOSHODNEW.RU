<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */
 
defined ('_JEXEC') or die('Restricted access');

$tab = $displayData;

?>

<?php if($tab->params->get('tab_custom_html','')){ ?>
	<div class="djtab-custom-html">
		<?php echo $tab->params->get('tab_custom_html'); ?>
	</div>
<?php }else{ ?>
	<span class="djtab-text" title="<?php echo $tab->name; ?>">
		<?php if($tab->params->get('tab_icon','')){ ?>
			<i class="<?php echo $tab->params->get('tab_icon'); ?> "></i>
		<?php } ?>
		<?php echo $tab->name; ?>
	</span>
<?php } ?>