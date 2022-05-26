<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
//JHtml::_('formbehavior.chosen', 'select');
Jhtml::_('behavior.multiselect');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

?>
<form action="<?php echo JRoute::_('index.php?option=com_djtabs&view=themes');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>	

		<div id="filter-bar" class="btn-toolbar mb-3">
			<div class="filter-search btn-group pull-left m-1">
				<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left m-1">
				<button type="submit" class="btn btn-primary"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn btn-secondary" onclick="document.getElementById('filter_search').value='';jQuery('[name^=filter_]').prop('selectedIndex',0);this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>

			<div class="btn-group pull-right ml-auto">
				<div class="btn-group hidden-phone m-1">
					<select name="filter_published" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
						<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED')), 'value', 'text', $this->state->get('filter.published'), true);?>
					</select>
				</div>
				<div class="btn-group hidden-phone m-1">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
		</div>
		<div class="clearfix"> </div>

		<table class="table table-striped">
		<thead>
			<tr>	
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%"></th>
				<th>
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
				</th>
				<th width="12%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					<?php if ($listOrder == 'a.ordering') : ?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'themes.saveorder'); ?>
					<?php endif; ?>
				</th>

				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
				<tr class="row<?php echo $i % 2; ?>">
					<td>
						<?php 
							if($item->custom){
								echo JHtml::_('grid.id', $i, $item->id);
							}else{
								echo '<input type="checkbox" style="display:none" readonly>';
							}
						?>
					</td>
					<td class="center">
						<?php 		    
							$styling = new JRegistry();
							$styling->loadString($item->params);
							$color = $styling->get('tb-ctv-bck-clr','#000')
						?>
						<?php if($item->custom): ?>
							<i style="color:<?php echo $color; ?>" class="icon-palette"></i>
						<?php else: ?>
							<i style="color:<?php echo $color; ?>" class="icon-picture"></i>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($item->custom==1){ ?>
							<a href="<?php echo JRoute::_('index.php?option=com_djtabs&task=theme.edit&id='.(int) $item->id);?>">
								<?php echo $this->escape($item->title); ?>
							</a>
						<?php } else{ ?>
							<?php echo $this->escape($item->title); ?>
						<?php } ?>
					</td>
					<td align="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'themes.', true, 'cb'	); ?>
					</td>
					<td align="center">
						<?php            	
						if($listOrder=='a.ordering'){
							$ordering = 'true'; ?>
								<span><?php echo $this->pagination->orderUpIcon($i,true,'themes.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>								
								<span><?php echo $this->pagination->orderDownIcon($i, count($this->items), true, 'themes.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
								<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
								<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="input-mini text-area-order" />
							<?php	
						}else{
							echo $item->ordering;
						}?>					
					</td>
					<td>
						<?php echo $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</div>
</form>
<?php echo DJTABSFOOTER; ?>
