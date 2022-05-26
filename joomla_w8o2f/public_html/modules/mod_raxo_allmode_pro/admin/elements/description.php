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

class JFormFieldDescription extends JFormField
{
	protected $type = 'Description';

	protected function getInput()
	{
		return null;
	}

	protected function getLabel()
	{
		return '<div class="description">'. JText::_('MOD_RAXO_RAMP_DESCRIPTION') .'</div>';
	}
}
