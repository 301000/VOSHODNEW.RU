<?php 
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('_JEXEC') or die;

?>
.<?php echo $theme_title; ?>.djtabs{	
	margin-bottom:15px;
}
.<?php echo $theme_title; ?> .djtabs-in{	
	margin: 10px 15px 10px 15px;
}
.<?php echo $theme_title; ?> .djtabs-title{
	float:<?php echo $css_params->get('tb-dir','left'); ?>;
	width:<?php echo (is_numeric($css_params->get('tb-wdth','134')) ? $css_params->get('tb-wdth','134').'px' : $css_params->get('tb-wdth')); ?>;
	height:<?php echo (is_numeric($css_params->get('tb-hght','47')) ? $css_params->get('tb-hght','47').'px' : $css_params->get('tb-hght')); ?>;
	text-align:center;
	cursor:pointer;
	font-family:<?php echo $css_params->get('tb-fnt-fml','arial, sans-serif'); ?>;
	font-weight:bold;
	font-size:<?php echo $css_params->get('tb-fnt-sz','12'); ?>px;
	background-color:<?php echo $css_params->get('tb-nctv-bck-clr','#f5f5f5'); ?>;
	color:<?php echo $css_params->get('tb-nctv-ttl-clr','#000000'); ?>;
	white-space:nowrap;
	overflow:hidden;
	padding:0px <?php echo $css_params->get('tb-pddng-lft-rght','5'); ?>px 0 <?php echo $css_params->get('tb-pddng-lft-rght','5'); ?>px;
	text-transform:<?php echo $css_params->get('tb-txt-trnsfrm','uppercase'); ?>;
	border-<?php echo $css_params->get('tb-dir','left'); ?>-width:1px;
	border-<?php echo $css_params->get('tb-dir','left'); ?>-style:<?php echo $css_params->get('tb-brdr-stl','dotted'); ?>;
	border-<?php echo $css_params->get('tb-dir','left'); ?>-color:<?php echo $css_params->get('tb-brdr-clr','#585452'); ?>;
	border-top-left-radius:<?php echo $css_params->get('tb-brdr-rds','3'); ?>px;
	border-top-right-radius:<?php echo $css_params->get('tb-brdr-rds','3'); ?>px;
	border-bottom-left-radius:<?php echo $css_params->get('tb-brdr-rds-bttm','0'); ?>px;
	border-bottom-right-radius:<?php echo $css_params->get('tb-brdr-rds-bttm','0'); ?>px;
	line-height: <?php echo (is_numeric($css_params->get('tb-hght','47')) ? $css_params->get('tb-hght','47').'px' : $css_params->get('tb-hght')); ?>;
	text-overflow:ellipsis;
	margin-<?php echo $css_params->get('tb-dir','left') == 'left' ? 'right' : 'left'; ?>:<?php echo $css_params->get('tb-spc','0'); ?>px;
}
.<?php echo $theme_title; ?> .djtabs-accordion{
	margin-bottom:1px; 
	height:<?php echo (is_numeric($css_params->get('tb-hght','31')) ? $css_params->get('tb-hght','31').'px' : $css_params->get('tb-hght')); ?>; 
	width:100%;
	background-size:100% <?php echo (is_numeric($css_params->get('tb-hght','31')) ? $css_params->get('tb-hght','31').'px' : $css_params->get('tb-hght')); ?>;
	padding: 0;
	line-height:<?php echo (is_numeric($css_params->get('tb-hght','31')) ? $css_params->get('tb-hght','31').'px' : $css_params->get('tb-hght')); ?>;
	border-radius:<?php echo $css_params->get('tb-brdr-rds','3'); ?>px;
}
.<?php echo $theme_title; ?> .djclear{
	clear: both;
}
.<?php echo $theme_title; ?> .djtabs-active{
	color:<?php echo $css_params->get('tb-ctv-ttl-clr','#ffffff'); ?>;
	background-color:<?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
	border-<?php echo $css_params->get('tb-dir','left'); ?>:1px solid <?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}
<?php if(!$css_params->get('tb-brdr-rds') && !$css_params->get('tb-spc')): ?>
.<?php echo $theme_title; ?> .djtabs-active-wrapper + div .djtabs-title{
	border-<?php echo $css_params->get('tb-dir','left'); ?>:1px solid <?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}
<?php endif; ?>
.<?php echo $theme_title; ?> .djtabs-in-border{
	border-color:<?php echo $css_params->get('tb-ln-clr') ? $css_params->get('tb-ln-clr') : $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
	border-width:<?php echo $css_params->get('tb-ln-width','4'); ?>px;
	border-top-style:solid;
}
<?php if($css_params->get('cntnt-fll-brdr','0')){ ?>
.<?php echo $theme_title; ?>:not(.accordion) .djtabs-in-border{
	border-style:solid;
	border-radius:<?php echo $css_params->get('tb-brdr-rds','3'); ?>px;
    border-top-<?php echo $css_params->get('tb-dir','left'); ?>-radius:0;
}
<?php } ?>
.<?php echo $theme_title; ?> .djtabs-panel{
	overflow:hidden;
    white-space: nowrap;
	height:39px;
	border-width:1px;
	border-color:<?php echo $css_params->get('pnl-nctv-brdrs-clr','#ffffff'); ?>;
	padding-left:5px;
	border-left-style:none;
	border-right-style:none;
	border-top-style:<?php echo $css_params->get('pnl-nctv-brdrs-stl','solid'); ?>;
	border-bottom-style:<?php echo $css_params->get('pnl-nctv-brdrs-stl','solid'); ?>;
	background-color:<?php echo $css_params->get('pnl-nctv-clr','#f5f5f5'); ?>;
	position:relative;
	border-radius:<?php echo $css_params->get('pnl-brdr-rds','3'); ?>px;
}
.<?php echo $theme_title; ?> .djtabs-panel-date{
    float:left;
	color:<?php echo $css_params->get('pnl-dt-clr','#666666'); ?>;
	margin-right:15px;
}
.<?php echo $theme_title; ?> .djtabs-panel-title{
	overflow:hidden;
	display:inline-block;
    white-space: nowrap;
	font-family:<?php echo $css_params->get('pnl-fnt-fml','arial, sans-serif'); ?>;
	font-weight:bold;
	font-size:<?php echo $css_params->get('pnl-fnt-sz','14'); ?>px;
	color:<?php echo $css_params->get('pnl-nctv-ttl-clr','#000000'); ?>;
	margin-left:15px;
	text-transform:uppercase;
	text-overflow:ellipsis;
	float:left;
}
.<?php echo $theme_title; ?> .djtabs-panel > span.djtabs-panel-toggler{
    display:inline-block;
	background:url('../images/custom/arrow-down.png') no-repeat <?php echo $css_params->get('tgglr-nctv-bck-clr','#f5f5f5'); ?> center;
	width:19px;
	height:19px;
	margin-right:10px;
	border-radius:<?php echo $css_params->get('tgglr-brdr-rds','2'); ?>px;
	float:right;	
	margin-top:10px;
}
.<?php echo $theme_title; ?> .djtabs-panel-active{
	border-top-style:<?php echo $css_params->get('pnl-ctv-brdrs-stl','solid'); ?>;
	border-bottom-style:<?php echo $css_params->get('pnl-ctv-brdrs-stl','solid'); ?>;
	border-color:<?php echo $css_params->get('pnl-ctv-brdrs-clr','#ffffff'); ?>;
	background-color:<?php echo $css_params->get('pnl-ctv-clr','#000000'); ?>;
}

.<?php echo $theme_title; ?> .djtabs-panel-active > span.djtabs-panel-toggler{
	background:url('../images/custom/arrow-up.png') no-repeat <?php echo $css_params->get('tgglr-ctv-bck-clr','#000000'); ?> center;	
}

.<?php echo $theme_title; ?> .djtabs-panel-active > .djtabs-panel-title{
	color:<?php echo $css_params->get('pnl-ctv-ttl-clr','#ffffff'); ?>;
}
.<?php echo $theme_title; ?> .djtabs-article-content{
	font-family:<?php echo $css_params->get('cntnt-fnt-fml','arial, sans-serif'); ?>;
	font-weight:normal;
	font-size:<?php echo $css_params->get('cntnt-fnt-sz','13'); ?>px;
	color:<?php echo $css_params->get('cntnt-clr','#666666'); ?>;
	margin:10px 15px 7px 15px;
	line-height:1.5; 
}
.<?php echo $theme_title; ?> .djtabs-article-category, .<?php echo $theme_title; ?> .djtabs-article-author{
	font-family:<?php echo $css_params->get('cntnt-fnt-fml','arial, sans-serif'); ?>;
	font-weight:normal;
	font-size:<?php echo $css_params->get('cntnt-fnt-sz','13'); ?>px;
	color:#666666;
	margin:2px 1px 5px 5px;
}
.<?php echo $theme_title; ?> .djtabs-article-category{
	float:right;
}
.<?php echo $theme_title; ?> .djtabs-article-category > a:visited, .<?php echo $theme_title; ?> .djtabs-article-category > a:link{
	float:right;
	color:<?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
	font-weight:bold;
}
.<?php echo $theme_title; ?> .djtabs-article-author{
	float:right;
	font-style:italic;
}
.<?php echo $theme_title; ?> span.djtabs-readmore a:visited, .<?php echo $theme_title; ?> span.djtabs-readmore a:link{
	font-style:italic;
	color:<?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}
.<?php echo $theme_title; ?> .accordion-body .djtabs-panel-title, .<?php echo $theme_title; ?> .accordion-body .djtabs-panel-date{
	line-height: 26px;
}
.<?php echo $theme_title; ?> .djtabs-panel-title, .<?php echo $theme_title; ?> .djtabs-panel-date{
	line-height: 39px;
}
.<?php echo $theme_title; ?> .accordion-body .djtabs-panel {
	height: 26px;
	background-size: 100% 26px;
}
.<?php echo $theme_title; ?> .accordion-body .djtabs-panel > span.djtabs-panel-toggler{
	margin-top:3px;
}
.<?php echo $theme_title; ?> .djtabs-article-content a:visited, .<?php echo $theme_title; ?> .djtabs-article-content a:link {
	color:<?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}
.<?php echo $theme_title; ?> .accordion-body{
	overflow: hidden;
}
.djVideoWrapper {
	position: relative;
	padding-bottom: 56.25%; /* 16:9 */
	padding-top: 25px;
	height: 0;
}
.djVideoWrapper iframe {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.<?php echo $theme_title; ?> .djtabs-article-body-in .djtabs-panel{
	margin-bottom: 10px;
}
.<?php echo $theme_title; ?> .djtabs-article-body-in .djtabs-article-content{
	margin-top: 0;
}
.<?php echo $theme_title; ?> .djtabs-accordion .djtab-text{
	margin-left: 5px;
}
.<?php echo $theme_title; ?> div.djtabs-title-wrapper:first-child .djtabs-title{
	border-<?php echo $css_params->get('tb-dir','left'); ?>: 0px;
}

/* 1.0.5 */
.<?php echo $theme_title; ?> .djtabs-title.tabsBlock{
	border-left: 1px;
}
.<?php echo $theme_title; ?> .djtabs-title.tabsBlock:not(:first-child) {
	border-top-left-radius:0px;
	border-top-right-radius:0px;
}
.<?php echo $theme_title; ?> .tabsBlock{
	width: 100%;
  	-webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
	border-left: 1px;
	border-bottom: solid 1px <?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}

/* 1.0.6 */
.<?php echo $theme_title; ?> .djtabs-article-group{
	float:left; 
	width: 100%;	 
}
.<?php echo $theme_title; ?> .no-accordion .djtabs-article-group{
	margin-bottom:5px;	 
}
@media (max-width: 480px){
	.<?php echo $theme_title; ?> .djtabs-article-group{
		width: 100% !important;	 
	}
}
.<?php echo $theme_title; ?> .accordion_all_in .djtabs-panel,
.<?php echo $theme_title; ?> .accordion_first_out .djtabs-panel{
	cursor: pointer;
	margin-top:-1px;
}
.<?php echo $theme_title; ?> .accordion_all_in .djtabs-article-group:first-child,
.<?php echo $theme_title; ?> .accordion_first_out .djtabs-article-group:first-child {
	margin-top: 1px;
}
.<?php echo $theme_title; ?> .djtabs-article-img{
	margin-bottom: 5px;
	border-radius:<?php echo $css_params->get('mg-brdr-rds','2'); ?>px;
}
.<?php echo $theme_title; ?> .djtabs-article-img.dj-img-left{
	float:left;
	margin-right:10px;
}
.<?php echo $theme_title; ?> .djtabs-article-img.dj-img-right{
	float:right;
	margin-left:10px;
}
.<?php echo $theme_title; ?> .djtabs-article-img.dj-img-top{
	display:block;
	margin-left:auto;
	margin-right:auto;
}
.<?php echo $theme_title; ?> .djtabs-date-in{
	display: inline-block;
	margin-bottom: 5px;
	font-size: <?php echo $css_params->get('cntnt-dt-fnt-sz','12'); ?>px;
	color: #999999;
}
.<?php echo $theme_title; ?> .djtabs-title.djtabs-accordion{
	border-<?php echo $css_params->get('tb-dir','left'); ?>:none;
}

/* 1.0.6.2 */
.<?php echo $theme_title; ?> .tabs-wrapper.tabs-hidden{
	display:none;
}

/* 1.0.6.3 */
.<?php echo $theme_title; ?> .djtab-custom-html p{
	display: inline;
	margin: 0;
}
/* 1.0.6.15 */
.<?php echo $theme_title; ?> .djtabs-title.n-row,
.<?php echo $theme_title; ?> .djtabs-title.first-row{
	border-bottom: solid 1px <?php echo $css_params->get('tb-ctv-bck-clr','#000000'); ?>;
}
.<?php echo $theme_title; ?> .djtabs-title.n-row,
.<?php echo $theme_title; ?> .djtabs-title.last-row
{
	border-top-left-radius:0px;
	border-top-right-radius:0px;
}
/* 1.2.0 */
.<?php echo $theme_title; ?>.rows div.djtabs-title-wrapper:first-child .djtabs-title {
	border-left: 1px solid transparent;
}
/* 1.3.1 */
.<?php echo $theme_title; ?> .djtabs-title-wrapper .djtabs-title{
    outline: none;
}