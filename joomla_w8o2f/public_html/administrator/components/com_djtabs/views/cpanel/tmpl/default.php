<?php 
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>
	<div class="djc_control_panel clearfix">
		<div class="cpanel-left">
			<div class="cpanel clearfix">
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;view=items">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_ITEMS'); ?>"
						src="components/com_djtabs/assets/icon-48-items.png" />
						<span><?php echo JText::_('COM_DJTABS_ITEMS'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;task=item.add">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_NEW_ITEM'); ?>"
						src="components/com_djtabs/assets/icon-48-item-add.png" />
						<span><?php echo JText::_('COM_DJTABS_NEW_ITEM'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;view=groups">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_GROUPS'); ?>"
						src="components/com_djtabs/assets/icon-48-category.png" />
						<span><?php echo JText::_('COM_DJTABS_GROUPS'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;task=group.add">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_NEW_GROUP'); ?>"
						src="components/com_djtabs/assets/icon-48-category-add.png" />
						<span><?php echo JText::_('COM_DJTABS_NEW_GROUP'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;view=themes">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_THEMES'); ?>"
						src="components/com_djtabs/assets/icon-48-themes.png" />
						<span><?php echo JText::_('COM_DJTABS_THEMES'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_djtabs&amp;task=theme.add">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_NEW_THEME'); ?>"
						src="components/com_djtabs/assets/icon-48-theme-add.png" />
						<span><?php echo JText::_('COM_DJTABS_NEW_THEME'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="http://dj-extensions.com/documentation" target="_blank">
					<span>
						<img
						alt="<?php echo JText::_('COM_DJTABS_DOCUMENTATION'); ?>"
						src="components/com_djtabs/assets/icon-48-help.png" />
						<span><?php echo JText::_('COM_DJTABS_DOCUMENTATION'); ?> </span>
					</span>
					</a>
				</div>
				<div class="icon">
					<a href="index.php?option=com_config&view=component&component=com_djtabs&return=<?php echo base64_encode(JUri::getInstance()->toString()); ?>"> 
					<span>
					<img alt="<?php echo JText::_('JOPTIONS'); ?>"
						src="components/com_djtabs/assets/icon-48-config.png" />
						<span><?php echo JText::_('JOPTIONS'); ?> </span>
					</span>
					</a>
				</div>
			</div>
		</div>

		<div class="cpanel-right">
			<div class="cpanel">
					<?php echo DJLicense::getSubscription('Tabs'); ?>
			</div>
		</div>
	</div>
	<div>
		<input type="hidden" name="option" value="com_djtabs" />
		<input type="hidden" name="c" value="cpanel" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="cpanel" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</div>
</div>
</form>

<?php echo DJTABSFOOTER; ?>