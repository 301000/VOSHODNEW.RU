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

<!-- SLIDESHOW -->
<?php if ($this->countModules('slideshow')): ?>
<section class="t3-slideshow wrap clearfix">
  <jdoc:include type="modules" name="<?php $this->_p('slideshow') ?>" style="raw" />
</section>
<?php endif ?>
<!-- SLIDESHOW -->
