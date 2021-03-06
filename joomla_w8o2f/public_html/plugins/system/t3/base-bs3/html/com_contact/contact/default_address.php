<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\String\PunycodeHelper;

/**
 * Marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<dl class="contact-address dl-horizontal" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<?php if (($this->params->get('address_check') > 0) &&
		($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
		<?php if ($this->params->get('address_check') > 0) : ?>
			<dt>
			<?php if (!$this->params->get('marker_address')) : ?>
				<span class="icon-address" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_ADDRESS'); ?></span>
			<?php else : ?>
				<span class="<?php echo $this->params->get('marker_class'); ?>" >
					<?php echo $this->params->get('marker_address'); ?>
				</span>
			<?php endif; ?>
			</dt>
		<?php endif; ?>

		<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
			<dd>
				<span class="contact-street" itemprop="streetAddress">
					<?php echo nl2br($this->contact->address); ?>
					<br />
				</span>
			</dd>
		<?php endif; ?>

		<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
			<dd>
				<span class="contact-suburb" itemprop="addressLocality">
					<?php echo $this->contact->suburb; ?>
					<br />
				</span>
			</dd>
		<?php endif; ?>
		<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
			<dd>
				<span class="contact-state" itemprop="addressRegion">
					<?php echo $this->contact->state; ?>
					<br />
				</span>
			</dd>
		<?php endif; ?>
		<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
			<dd>
				<span class="contact-postcode" itemprop="postalCode">
					<?php echo $this->contact->postcode; ?>
					<br />
				</span>
			</dd>
		<?php endif; ?>
		<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
		<dd>
			<span class="contact-country" itemprop="addressCountry">
				<?php echo $this->contact->country; ?>
				<br />
			</span>
		</dd>
		<?php endif; ?>
	<?php endif; ?>

<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>
	<dt>
		<?php if (!$this->params->get('marker_email')) : ?>
			<span class="icon-envelope" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_EMAIL'); ?>"></span>
		<?php else : ?>
		<span class="<?php echo $this->params->get('marker_class'); ?>" itemprop="email">
			<?php echo nl2br($this->params->get('marker_email')); ?>
		</span>
		<?php endif; ?>
	</dt>
	<dd>
		<span class="contact-emailto">
			<?php echo $this->contact->email_to; ?>
		</span>
	</dd>
<?php endif; ?>

<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
	<dt>
		<?php if (!$this->params->get('marker_telephone')) : ?>
				<span class="icon-phone" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_TELEPHONE'); ?></span>
		<?php else : ?>
		<span class="<?php echo $this->params->get('marker_class'); ?>">
			<?php echo $this->params->get('marker_telephone'); ?>
		</span>
		<?php endif; ?>
	</dt>
	<dd>
		<span class="contact-telephone" itemprop="telephone">
			<?php echo $this->contact->telephone; ?>
		</span>
	</dd>
<?php endif; ?>
<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
	<dt>
		<?php if (!$this->params->get('marker_fax')) : ?>
			<span class="icon-fax" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_FAX'); ?></span>
		<?php else : ?>
		<span class="<?php echo $this->params->get('marker_class'); ?>">
			<?php echo $this->params->get('marker_fax'); ?>
		</span>
		<?php endif; ?>
	</dt>
	<dd>
		<span class="contact-fax" itemprop="faxNumber">
		<?php echo $this->contact->fax; ?>
		</span>
	</dd>
<?php endif; ?>
<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
	<dt>
		<?php if (!$this->params->get('marker_mobile')) : ?>
			<span class="icon-mobile" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_MOBILE'); ?></span>
		<?php else : ?>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
			<?php echo $this->params->get('marker_mobile'); ?>
		</span>
		<?php endif; ?>
	</dt>
	<dd>
		<span class="contact-mobile" itemprop="telephone">
			<?php echo $this->contact->mobile; ?>
		</span>
	</dd>
<?php endif; ?>
<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
	<dt>
		<?php if (!$this->params->get('marker_webpage')) : ?>
			<span class="icon-home" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('COM_CONTACT_WEBPAGE'); ?></span>
		<?php else : ?>
		<span class="<?php echo $this->params->get('marker_class'); ?>" >
			<?php echo $this->params->get('marker_webpage'); ?>
		</span>
		<?php endif; ?>
	</dt>
	<dd>
		<span class="contact-webpage">
			<a href="<?php echo $this->contact->webpage; ?>" target="_blank" rel="noopener noreferrer" itemprop="url">
			<?php echo JStringPunycode::urlToUTF8($this->contact->webpage); ?></a>
		</span>
	</dd>
<?php endif; ?>
</dl>
