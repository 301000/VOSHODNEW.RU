<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */
 
defined ('_JEXEC') or die('Restricted access');

?>

<?php
	$params = $this->params;
	$tabs = $this->tabs;
?>

<img id="djtabs_loading" class="djtabs-loading" src="components/com_djtabs/assets/images/ajax-loader.gif" alt="loading..." />

<?php if($params->get('show_page_heading', 1)) : ?>
	<h1 class="componentheading<?php echo $params->get( 'pageclass_sfx' ) ?>">
		<?php $active = JFactory::getApplication()->getMenu()->getActive(); ?>
		<?php $title_overwrite = $this->escape($params->get('page_heading')); ?>
		<?php echo $title_overwrite ? $title_overwrite : $active->title; ?>
	</h1>
<?php endif; ?>

<?php $modules_djtabs = JModuleHelper::getModules('djtabs-top'); ?>
<?php if(count($modules_djtabs)>0){ ?>
	<div class="djclear">
		<?php
		foreach (array_keys($modules_djtabs) as $m){
			echo JModuleHelper::renderModule($modules_djtabs[$m]);
		}
		?>
	</div>
<?php } ?>

<div class="djtabs-container<?php echo $params->get( 'pageclass_sfx' ) ?>">
	<div id="djtabs" class="djtabs <?php echo ($params->get('class_theme_title')); ?> accordion<?php echo $params->get('truncate_titles','1')=='0' ? ' full-titles' : ''; ?>">
	
	<?php $tab_i = 0; ?>
	<?php foreach($tabs  as $tab){
		$tab_i++; ?>
		<div class="djtabs-title-wrapper">
			<div id="djtab<?php echo $tab_i; ?>" class="djtabs-title djtabs-accordion" data-tab-no="<?php echo $tab_i;?>" tabindex="0">
			<?php
				$tab_title = new JLayoutFile('tabtitle');
				echo $tab_title->render($tab);
			?>
			</div>
		</div>
		<div class="djclear"></div>
		<div class="djtabs-in-border" data-no="<?php echo $tab_i;?>"> 
			<div class="djtabs-in">
				<div class="djtabs-body accordion-body djclear <?php echo $tab->type_cl; ?>" data-tab-no="<?php echo $tab_i;?>" tabindex="0">
				<?php if($tab->type==1 || $tab->type==5){ // article category or k2 category ?>
				<div id="djtabs_accordion<?php echo $tab_i; ?>" class="<?php echo $tab->inner_acc_cl; ?> djtabs-body-in">
				<?php $art_i = 0; ?>
				<?php foreach($tab->content as $con){ ?>
				<?php $art_i++; ?>
				<div tabindex="0" class="djtabs-article-group<?php echo ($tab->params->get('articles_display','1') == 3 ? ' djtabs-article-out' : '');?>"<?php echo !empty($tab->art_width) ? ' style="'.$tab->art_width.($art_i%$tab->art_per_row ? $tab->art_space : '').'"' : ''; ?>>
						<div id="inner_accordion_panel<?php echo $art_i;?>_<?php echo $tab_i;?>" class="djtabs-panel">
						<?php
							$art_title = new JLayoutFile('articletitle');
							echo $art_title->render(array($tab, $con));
						?>
							<?php if($tab->params->get('articles_display', '1') != '3'){ ?>
								<span class="djtabs-panel-toggler"></span>
							<?php } ?>
						</div>
						<div data-tab-no="<?php echo $tab_i;?>" data-no="<?php echo $art_i;?>" class="djtabs-article-body">
						<?php
							$art_content = new JLayoutFile('articlecontent');
							echo $art_content->render(array($tab, $con));
						?>
						</div>
					</div>  
				<?php } ?>
				</div>
				<?php }else if($tab->type==2 || $tab->type==6){ // article or k2 item ?>
				<?php $con = $tab->content; ?>
				<div class="djtabs-body-in djtabs-article-body-in">
					<div class="djtabs-article-group djtabs-group-active">
						<?php if($tab->content->params->get('show_create_date','1')=='1' || $tab->content->params->get('show_title','1')=='1'){ ?>
						<div class="djtabs-panel djtabs-panel-active djtabs-panel-article">
						<?php
							$art_title = new JLayoutFile('articletitle');
							echo $art_title->render(array($tab, $con));
						?>
						</div>
						<?php } ?>
						<?php
							$art_content = new JLayoutFile('articlecontent');
							echo $art_content->render(array($tab, $con));
						?>
					</div>
				</div>
				<?php }else if($tab->type==3){ //module ?>
				<div class="djtabs-body-in djtabs-module">
					<?php echo DjTabsHelper::loadModules($tab->mod_pos); ?>
				</div>
				<?php }else if($tab->type==4){ //video ?>
					<?php if (!$tab->video_link){
						echo JText::_('COM_DJTABS_VIDEO_UNSUPPORTED');
					}else { ?>
					<div class="djVideoWrapper">
						<iframe src="<?php echo $tab->video_link; ?>" title="<?php echo $tab->name; ?>" allowfullscreen></iframe>
					</div>                      
					<?php } ?>
				<?php } else if($tab->type==7){ //custom html ?>
						<div class="djtabs-body-in custom-text">
							<?php echo $tab->content; ?>
						</div>
				<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
</div>