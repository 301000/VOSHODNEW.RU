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

// positions configuration
$sidebar = 'sections-top-sidebar-2';

$sidebar = $this->countModules($sidebar) ? $sidebar : false;
?>

<!-- SECTION TOP -->
<?php if ($this->countModules('section-top-2') || $this->countModules('section-top-full-2')): ?>
<section class="t3-sections t3-sections-top wrap clearfix">
  <div class="container container-large">
    <?php if ($sidebar): ?>
  		<div class="row row-flex row-large">
  			<div class="col-md-8">
  	<?php endif ?>
    			<jdoc:include type="modules" name="<?php $this->_p('section-top-2') ?>" style="T3Section" />
    <?php if ($sidebar): ?>
    		</div>
    		<div class="col-md-4 t3-sidebar">
    			<jdoc:include type="modules" name="<?php $this->_p('sections-top-sidebar-2') ?>" style="T3Xhtml" />
    		</div>
    	</div>
    <?php endif ?>
  </div>

  <?php if ($this->countModules('section-top-full-2')): ?>
    <div class="section-full">
      <jdoc:include type="modules" name="<?php $this->_p('section-top-full-2') ?>" style="raw" />
    </div>
  <?php endif ?>
</section>
<?php endif ?>
<!-- SECTION TOP -->
