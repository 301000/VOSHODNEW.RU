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

<!-- BACK TOP TOP BUTTON -->
<div id="back-to-top" data-spy="affix" data-offset-top="200" class="back-to-top hidden-xs hidden-sm affix-top">
  <button class="btn btn-primary" title="Back to Top"><span class="fa fa-angle-up"></span></button>
</div>

<script type="text/javascript">
(function($) {
  // Back to top
  $('#back-to-top').on('click', function(){
    $("html, body").animate({scrollTop: 0}, 500);
    return false;
  });
})(jQuery);
</script>
<!-- BACK TO TOP BUTTON -->

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">

	<!-- MAST BOTTOM -->
	<?php if ($this->countModules('banner-footer')): ?>
	<section class="t3-banner-footer wrap clearfix">
	  <div class="container">
	  	<div class="container-inner">
	    	<jdoc:include type="modules" name="<?php $this->_p('banner-footer') ?>" style="raw" />
	    </div>
	  </div>
	</section>
	<?php endif ?>
	<!-- MAST BOTTOM -->


	<?php if ($this->checkSpotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6')) : ?>
		<!-- FOOT NAVIGATION -->
		<div class="container">
			<?php $this->spotlight('footnav', 'footer-1, footer-2, footer-3, footer-4, footer-5, footer-6') ?>
		</div>
		<!-- //FOOT NAVIGATION -->
	<?php endif ?>

	<section class="t3-copyright">
		<div class="container">
			<div class="container-inner">
				<div class="row">
					<div class="<?php echo $this->getParam('t3-rmvlogo', 1) ? 'col-md-8' : 'col-md-12' ?> copyright <?php $this->_c('footer') ?>">
						<jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
					</div>
					<?php if ($this->getParam('t3-rmvlogo', 1)): ?>
						<div class="col-md-4 poweredby text-hide">
							<a class="t3-logo t3-logo-dark t3-logo-small" href="http://t3-framework.org" title="<?php echo JText::_('T3_POWER_BY_TEXT') ?>"
							   target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>><?php echo JText::_('T3_POWER_BY_HTML') ?></a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

</footer>
<!-- //FOOTER -->