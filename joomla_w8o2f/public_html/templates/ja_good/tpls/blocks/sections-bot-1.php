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
?>

<!-- SECTION BOTTOM -->
<?php if ($this->countModules('section-bot-1')): ?>
<section class="t3-sections t3-sections-bot wrap clearfix">
	<div class="container">
  	<jdoc:include type="modules" name="<?php $this->_p('section-bot-1') ?>" style="T3Section" />
  </div>
</section>
<?php endif ?>
<!-- SECTION BOTTOM -->
