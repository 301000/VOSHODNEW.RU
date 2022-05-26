<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


	$lang = JFactory::getLanguage();
	$extrafields = new JRegistry($row->attribs);

	$viewType = $extrafields->get('type-article');
?>

<?php if(!$viewType) :?>
	<div class="pagenav-article container">
		<div class="row">
			<div class="col-sm-6">
				<div class="previous">

					<?php if($row->prev) :?>
					<a class="hasTooltip btn btn-primary btn-lg btn-block" title="<?php echo htmlspecialchars($rows[$location-1]->title); ?>" aria-label="<?php echo JText::sprintf('JPREVIOUS_TITLE', htmlspecialchars($rows[$location-1]->title)); ?>" href="<?php echo $row->prev; ?>" rel="prev">
					<?php else : ?>
						<a class="btn btn-primary btn-lg btn-block disabled" href="">
					<?php endif ;?>

						<span class="previous-inner">
							<span class="icon ion-md-arrow-back"></span> <?php echo Jtext::_('TPL_JPREVIOUS_TITLE') ;?>
						</span>
					</a>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="next">
					<?php if($row->next) :?>
					<a class="hasTooltip btn btn-primary btn-lg btn-block" title="<?php echo htmlspecialchars($rows[$location+1]->title); ?>" aria-label="<?php echo JText::sprintf('JNEXT_TITLE', htmlspecialchars($rows[$location+1]->title)); ?>" href="<?php echo $row->next; ?>" rel="next">
					<?php else : ?>
						<a class="btn btn-primary btn-lg btn-block disabled" href="">
					<?php endif ;?>

					<span class="forward-inner">
						<?php echo Jtext::_('TPL_JNEXT_TITLE') ;?> <span class="icon ion-md-arrow-forward"></span>
					</span>
					</a>
				</div>
			</div>
		</div>
	</div>
<?php else :?>
	<div class="pagenav-model row">
		<div class="col-sm-6">
			<div class="previous <?php echo($row->prev) ? 'active' : 'no-article' ;?>">

				<?php if($row->prev) :?>
				<a class="hasTooltip" title="<?php echo htmlspecialchars($rows[$location-1]->title); ?>" aria-label="<?php echo JText::sprintf('JPREVIOUS_TITLE', htmlspecialchars($rows[$location-1]->title)); ?>" href="<?php echo $row->prev; ?>" rel="prev">
				<?php endif ;?>

					<span class="previous-inner">
						<span class="icon ion-md-arrow-back"></span> <?php echo Jtext::_('TPL_PREVIOUS_MODEL') ;?>
					</span>

				<?php if($row->prev) :?>
				</a>
				<?php endif ;?>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="next <?php echo($row->next) ? 'active' : 'no-article' ;?>">
				<?php if($row->next) :?>
				<a class="hasTooltip" title="<?php echo htmlspecialchars($rows[$location+1]->title); ?>" aria-label="<?php echo JText::sprintf('JNEXT_TITLE', htmlspecialchars($rows[$location+1]->title)); ?>" href="<?php echo $row->next; ?>" rel="next">
				<?php endif; ?>

				<span class="forward-inner">
					<?php echo Jtext::_('TPL_NEXT_MODEL') ;?> <span class="icon ion-md-arrow-forward"></span>
				</span>

				<?php if($row->next) :?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif ;?>

