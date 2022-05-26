<?php
/**
 * =============================================================
 * @package		RAXO All-mode PRO J4.x
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2021 RAXO Group
 * @link		https://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;

use Joomla\Module\RaxoAllmodePro\Site\Helper\RaxoAllmodeProHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;


// Check the page type
if ($params->get('hide_option', 0))
{
	$input = $app->input;
	if ($input->get('option') === 'com_content' && $input->get('view') === 'article')
	{
		return;
	}
}


// Module cache parameters
$cacheparams				= (object) [];
$cacheparams->cachemode		= 'itemid';
if ($params->get('ordering') === 'random' || !$params->get('current_item', 0))
{
	$cacheparams->cachemode	= 'safeuri';
}
$cacheparams->class			= RaxoAllmodeProHelper::class;
$cacheparams->method		= 'getList';
$cacheparams->methodparams	= $params;
$cacheparams->modeparams	= array('id' => 'int', 'Itemid' => 'int');


// Get module data
$list = ModuleHelper::moduleCache($module, $params, $cacheparams);
if (empty($list))
{
	return;
}

$count			= $params->get('count');
$toplist		= [];
if ($count->top)
{
	$toplist	= array_slice($list, 0, $count->top);
	$list		= array_slice($list, $count->top);
}


// Module block details
$block = (object) [];

$block->name_text = $block->name = trim($params->get('block_name'));
$block->name_link = trim($params->get('block_name_link'));
if ($block->name_text && $block->name_link)
{
	$block->name = '<a href="'. $block->name_link .'">'. $block->name_text .'</a>';
}

$block->intro = $params->get('block_intro');

$block->button_text = $block->button = trim($params->get('block_button'));
$block->button_link = trim($params->get('block_button_link'));
if ($block->button_text && $block->button_link)
{
	$block->button = '<a href="'. $block->button_link .'">'. $block->button_text .'</a>';
}


// Displaying the module layout
$module_layout	= $params->get('layout', '_:raxo-default');
$layout_name	= substr(strrchr($module_layout, ':'), 1);
$layout_path	= ModuleHelper::getLayoutPath('mod_raxo_allmode_pro', $module_layout);
$module_class	= htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$module_class	= trim($module->name .' '. $layout_name .' '. $module_class);
?>

<div id="raxo-module-id<?php echo $module->id; ?>" class="<?php echo $module_class; ?>">
<?php
if (file_exists($layout_path))
{
	require($layout_path);
}
else
{
	echo Text::_('MOD_RAXO_RAMP_ERROR_LAYOUT');
}
?>
</div>
