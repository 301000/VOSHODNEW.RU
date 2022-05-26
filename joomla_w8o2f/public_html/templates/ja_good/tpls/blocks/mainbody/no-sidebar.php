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
 * Mainbody 1 columns, content only
 */
?>

<div id="t3-mainbody" class="container container-large t3-mainbody">
	<div class="row row-flex row-large">

		<!-- MAIN CONTENT -->
		<div id="t3-content" class="t3-content col-xs-12">
			<?php if($this->hasMessage()) : ?>
			<jdoc:include type="message" />
			<?php endif ?>

			<?php if ($this->countModules('mast-content')): ?>
			<div class="t3-mast-content">
				<jdoc:include type="modules" name="<?php $this->_p('mast-content') ?>" style="T3Xhtml" />
			</div>
			<?php endif ?>

			<jdoc:include type="component" />
		</div>
		<!-- //MAIN CONTENT -->

	</div>
</div> 