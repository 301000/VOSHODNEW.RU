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

/**
 * Mainbody 2 columns: content - sidebar
 */
?>
<div id="t3-mainbody" class="container container-large t3-mainbody">
	<div class="row row-flex row-large">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12 col-sm-8 col-md-8">
			<?php if($this->hasMessage()) : ?>
			<jdoc:include type="message" />
			<?php endif ?>
			<?php if ($this->countModules('mast-content')): ?>
			<div class="t3-mast-content">
				<jdoc:include type="modules" name="<?php $this->_p('mast-content') ?>" style="T3Section" />
			</div>
			<?php endif ?>
			<jdoc:include type="component" />
		</div>
		<!-- //MAIN CONTENT -->

		<!-- SIDEBAR RIGHT -->
		<div class="t3-sidebar t3-sidebar-right col-xs-12 col-sm-4 col-md-4 <?php $this->_c($vars['sidebar']) ?>">
			<jdoc:include type="modules" name="<?php $this->_p($vars['sidebar']) ?>" style="T3Xhtml" />
		</div>
		<!-- //SIDEBAR RIGHT -->

	</div>
</div> 
