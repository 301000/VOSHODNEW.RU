<?php
/**
 * @package Sj Currency Rates
 * @version 1.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
JHtml::stylesheet('modules/mod_sj_currency_rates/assets/css/mod_sj_currency_rates.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/mod_sj_currency_rates/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/mod_sj_currency_rates/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
$w = $params->get('width_module');
$timezone = $params->get('timezone');
$nb = $params->get('nb');
$mc = $params->get('mc');
$c1 = $params->get('c1');
$c2 = $params->get('c2');
$c3 = $params->get('c3');
$c4 = $params->get('c4');
$c5 = $params->get('c5');
$c6 = $params->get('c6');
$c7 = $params->get('c7');
$c8 = $params->get('c8');
$c9 = $params->get('c9');
$c10 = $params->get('c10');
$bc = $params->get('bc');
$tag_id = 'sj_curentcy_rates_'.$module->id;
echo"
<link rel=\"stylesheet\" type=\"text/css\" href=\"http://www.foreignexchange.org.uk/widget/FE-FERT2-css.php?w=$w&nb=$nb\" media=\"screen\" />";
?>
<?php if( $params->get('pretext') != ""){?>
	<div class="intro_text"><?php echo $params->get('pretext'); ?></div>
<?php }?>
<div id="<?php echo $tag_id;?>" class="sj-curentcy-rates">
	<div id="fefert2-widget"></div>
</div>
<?php if( $params->get('posttext')!= "" ){?>
	<div class="footer_text"><?php echo $params->get('posttext'); ?></div>
<?php }?>
<script type="text/javascript">
	var w = '500';
	var c1 = '<?php echo $c1;?>';
	var c2 = '<?php echo $c2;?>';
	var c3 = '<?php echo $c3;?>';
	var c4 = '<?php echo $c4;?>';
	var c5 = '<?php echo $c5;?>';
	var c6 = '<?php echo $c6;?>';
	var c7 = '<?php echo $c7;?>';
	var c8 = '<?php echo $c8;?>';
	var c9 = '<?php echo $c9;?>';
	var c10 = '<?php echo $c10;?>';
	var nb = '<?php echo $nb;?>';
	var mc = '<?php echo $mc;?>';
	var bc = '<?php echo $bc;?>';
	var tz = '<?php echo $timezone;?>';
	var widget = document.getElementById('<?php echo $tag_id; ?>');
	function widgetcheck(){
		if (!widget){
			return false;
		}else{
			return true;
		}
	}
	function widgetshow(){
		var prefix = window.parent.document.location.protocol;
		var vt = new Date();
		os = vt.getTimezoneOffset()/60;
		var userhr = vt.getHours();
		var ws = document.location.href;
		if (nb==10){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&c6=' +c6 + '&c7=' +c7 + '&c8=' +c8 + '&c9=' +c9 + '&c10=' +c10 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==9){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&c6=' +c6 + '&c7=' +c7 + '&c8=' +c8 + '&c9=' +c9 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==8){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&c6=' +c6 + '&c7=' +c7 + '&c8=' +c8 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==7){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&c6=' +c6 + '&c7=' +c7 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==6){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&c6=' +c6 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==5){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&c5=' +c5 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==4){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&c4=' +c4 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==3){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&c3=' +c3 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==2){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&c2=' +c2 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}else if (nb==1){
			var widgetiframe = '<iframe src="' + prefix + '//www.foreignexchange.org.uk/widget/FE-FERT2-2.php?ws=' + ws + '&os=' + os + '&bc=' + bc + '&mc=' + mc + '&c1=' +c1 + '&t=' + '&w=' + w + '&tz=' + tz + '&userhr=' + userhr + '" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>';
		}
		var widgetid = '<?php echo $tag_id;?>';
		document.getElementById('fefert2-widget').innerHTML = widgetiframe;
	}
	if (widgetcheck()){
		widgetshow();
	}else{
		document.getElementById('<?php echo $tag_id;?>').innerHTML = "Please check widget code!";
	}
</script>
