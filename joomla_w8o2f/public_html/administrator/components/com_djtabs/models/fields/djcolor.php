<?php
/**
 * @package DJ-Tabs
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email: contact@dj-extensions.com
 */

defined('JPATH_BASE') or die;

class JFormFieldDjcolor extends JFormField
{
	protected $type = 'djcolor';

	protected function getInput()
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::base().'/components/com_djtabs/models/fields/djcolor/jscolor.js');

		$class = $this->element['class'] ? (string) $this->element['class'] : 'color';
		$value = '';
		$value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES), ENT_QUOTES);
        $background = ' style="background-color: '.$value.'"';

		return '<input type="text" name="'.$this->name.'" id="'.$this->id.'" '.$background.' class="'.$class.'" value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'">';
	}
}