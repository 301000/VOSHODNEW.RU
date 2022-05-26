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

// jimport('joomla.html.html');
jimport('joomla.form.formfield');
use Joomla\CMS\Language\Text;


class JFormFieldCheckboxed extends JFormField
{
	protected $type = 'Checkboxed';
	protected $forceMultiple = true;


	protected function getInput()
	{
		$options = $this->getOptions();

		$this->value = is_string($this->value) && !empty($this->value) ? explode(',', $this->value) : $this->value;


		$html	= array();
		$html[]	= '<fieldset id="'. $this->id .'" class="'. trim('checkboxes raxo-checkbox-toggle '. (string) $this->element['class']) .'">';

		foreach ($options as $i => $option)
		{
			$checked	= (in_array((string)$option->value,(array)$this->value) ? ' checked="checked"' : '');
			$class		= !empty($option->class) ? ' class="'.$option->class.'"' : '';
			$disabled	= !empty($option->disable) ? ' disabled="disabled"' : '';
			$onclick	= !empty($option->onclick) ? ' onclick="'.$option->onclick.'"' : '';

			$fieldid	= $this->id . $i;

			$html[] = '<div class="form-check form-check-inline">';
			$html[] = '<input type="checkbox" id="'. $fieldid .'" name="'. $this->name .'" value="'.htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8').'"'.$checked.$class.$onclick.$disabled.'/>';
			$html[] = '<label for="'. $fieldid .'" class="form-check-label">'. Text::_($option->text) .'</label>';
			$html[] = '</div>';
		}

			$i = $i + 1;
			$html[] = '<input type="hidden" id="'. $this->id . $i .'" name="'. $this->name .'" value="fix" />';

		$html[] = '</fieldset>';

		return implode($html);
	}


	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{
			if ($option->getName() != 'option')
			{
				continue;
			}

			$tmp = JHtml::_('select.option', (string) $option['value'], trim((string) $option), 'value', 'text', ((string) $option['disabled']=='true'));
			$tmp->class = (string) $option['class'];
			$tmp->onclick = (string) $option['onclick'];

			$options[] = $tmp;
		}

		reset($options);
		return $options;
	}
}
