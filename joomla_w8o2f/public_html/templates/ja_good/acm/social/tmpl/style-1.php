<?php 
/**
 * ------------------------------------------------------------------------
 * JA Good Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/
defined('_JEXEC') or die;
	$count = $helper->getRows('link');
?>

<div class="dropdown social-follow">
  <a href="javascript:void(0)" class="dropdown-toggle" id="socialDropdown<?php echo $module->id ;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <?php echo $module->title ;?>
    <span class="caret"></span>
  </a>

  <ul class="dropdown-menu">
  	<?php for ($i=0; $i<$count; $i++) : ?>
    	<li>
    		<a href="<?php echo $helper->get('link', $i) ;?>" title="<?php echo $helper->get('title', $i) ;?>">
    			<span class="<?php echo $helper->get('font-icon', $i) ;?>"></span>
    		</a>
    	</li>
    <?php endfor ?>
  </ul>
</div>
