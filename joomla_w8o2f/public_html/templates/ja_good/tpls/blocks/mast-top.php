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

<!-- MAST BOTTOM -->
<?php if ($this->countModules('mast-top')): ?>
<section class="t3-mast t3-mast-top wrap clearfix">
  <div class="container container-lg">
    <jdoc:include type="modules" name="<?php $this->_p('mast-top') ?>" style="raw" />
  </div>
</section>
<?php endif ?>
<!-- MAST BOTTOM -->
