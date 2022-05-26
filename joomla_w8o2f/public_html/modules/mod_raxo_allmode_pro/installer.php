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

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;


class mod_raxo_allmode_proInstallerScript
{

	protected $min_joomla = '4.0';
	protected $migrate = false;

	protected $mod = [
		'3x' => ['name' => 'mod_raxo_allmode'],
		'4x' => ['name' => 'mod_raxo_allmode_pro']
	];

	protected $del = [
		'files' => [
			'/language/de-DE/de-DE.mod_raxo_allmode.ini',
			'/language/de-DE/de-DE.mod_raxo_allmode.sys.ini',
			'/language/en-GB/en-GB.mod_raxo_allmode.ini',
			'/language/en-GB/en-GB.mod_raxo_allmode.sys.ini',
			'/language/es-ES/es-ES.mod_raxo_allmode.ini',
			'/language/es-ES/es-ES.mod_raxo_allmode.sys.ini',
			'/language/fr-FR/fr-FR.mod_raxo_allmode.ini',
			'/language/fr-FR/fr-FR.mod_raxo_allmode.sys.ini',
			'/language/it-IT/it-IT.mod_raxo_allmode.ini',
			'/language/it-IT/it-IT.mod_raxo_allmode.sys.ini',
			'/language/ru-RU/ru-RU.mod_raxo_allmode.ini',
			'/language/ru-RU/ru-RU.mod_raxo_allmode.sys.ini'
		],
		'folders' => [
			'/cache/mod_raxo_allmode',
			'/images/raxo_thumbs/amp',
			'/modules/mod_raxo_allmode'
		]
	];


	/**
	 * Function to act prior to installation process begins
	 *
	 * @param	string		$action		Which action is happening (install|uninstall|discover_install|update)
	 * @param	Installer	$installer	The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($action, $installer)
	{
		if ($action === 'uninstall')
		{
			return true;
		}

		// Checking the minimum required Joomla version
		if (version_compare(JVERSION, $this->min_joomla, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->min_joomla), Log::WARNING, 'jerror');

			return false;
		}

		// Checking for an outdated module version
		foreach($this->mod as $j => $m)
		{
			$this->mod[$j]['path'] = JPATH_ROOT .'/modules/'. $m['name'];
			$this->mod[$j]['xml']  = $this->mod[$j]['path'] .'/'. $m['name'] .'.xml';
			$this->mod[$j]['tmpl'] = $this->mod[$j]['path'] .'/tmpl';
		}

		if (file_exists($this->mod['3x']['xml'])) {
			$this->migrate = true;

			$msg = 'An outdated version of the module has been detected.<br>The update process has started.';
			Log::add($msg, Log::WARNING, 'jerror');

			// Backup folder of previous module version
			if (!Folder::copy($this->mod['3x']['path'], $this->mod['4x']['path'] .'/backup', '', true))
			{
				$msg = 'Step 1 - Backup for the outdated module folder not created.';
				Log::add($msg, Log::WARNING, 'jerror');

				return false;
			}
			$msg = 'Step 1 - Backup for the outdated module folder created.';
			Log::add($msg, Log::NOTICE, 'jerror');

			// Copy existing module layouts
			if (!Folder::copy($this->mod['3x']['tmpl'], $this->mod['4x']['tmpl'], '', true))
			{
				$msg = 'Step 2 - Existing module layouts not copied to new location.';
				Log::add($msg, Log::WARNING, 'jerror');

				return false;
			}
			$msg = 'Step 2 - Existing module layouts copied to new location.';
			Log::add($msg, Log::NOTICE, 'jerror');

			// Update database module configurations
			if ($this->databaseModules())
			{
				$msg = 'Step 3 - Database module configuration updated.';
				Log::add($msg, Log::NOTICE, 'jerror');
			}
		}

		return true;
	}


	/**
	 * Called after any type of action
	 *
	 * @param	string		$action		Which action is happening (install|uninstall|discover_install|update)
	 * @param	Installer	$installer	The class calling this method
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($action, $installer)
	{
		if ($action === 'uninstall')
		{
			return true;
		}

		// Create the thumbnail cache folder
		$mod_thumbnails = JPATH_ROOT .'/images/thumbnails/raxo/ramp';
		if (!Folder::exists($mod_thumbnails) && Folder::create($mod_thumbnails))
		{
			File::write($mod_thumbnails .'/index.html', '<!DOCTYPE html><title></title>');
			$msg  = $this->migrate ? 'Step 4 - ' : '';
			$msg .= 'Thumbnail cache folder created.';
			Log::add($msg, Log::NOTICE, 'jerror');
		}

		if ($this->migrate)
		{
			// Update database extensions table
			$this->databaseExtensions();

			// Remove unnececary modules files and folders
			if ($this->moduleClenup())
			{
				$msg = 'Step 5 - Unnecessary module files removed.';
				Log::add($msg, Log::NOTICE, 'jerror');
			}
		}

		return true;
	}



	private function databaseModules()
	{
		$db = Factory::getDbo();

		// Get module instances
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))->select($db->quoteName('params'))
			->from($db->quoteName('#__modules'))
			->where($db->quoteName('module') .' = '. $db->quote($this->mod['3x']['name']));
		$db->setQuery($query);

		$mod_instances = [];
		try {
			$mod_instances = $db->loadObjectList();
		} catch (Exception $e) {
			echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';
			return false;
		}

		foreach ($mod_instances as $mod_instance)
		{
			// Updating module configuration
			$mod_params = $this->moduleConfig($mod_instance->params);

			$query = $db->getQuery(true)
				->update($db->quoteName('#__modules'))
				->set($db->quoteName('module') .' = '. $db->quote($this->mod['4x']['name']))
				->set($db->quoteName('params') .' = '. $db->quote($mod_params))
				->where($db->quoteName('id') .' = '. $db->quote($mod_instance->id));
			try {
				$db->setQuery($query)->execute();
			} catch (Exception $e) {
				echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';
				return false;
			}

		}

		return true;
	}


	private function databaseExtensions()
	{
		$db = Factory::getDbo();

		// Get extension ID
		$query = $db->getQuery(true)
			->select($db->quoteName('extension_id'))
			->from($db->quoteName('#__extensions'))
			->where($db->quoteName('type') .' = '. $db->quote('module'))
			->where($db->quoteName('element') .' = '. $db->quote($this->mod['3x']['name']));
		$db->setQuery($query);

		$mod_extension = '';
		try {
			$mod_extension = $db->loadResult();
		} catch (Exception $e) {
			echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';
			return false;
		}

		if ($mod_extension)
		{
			// Delete outdated extention
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__extensions'))
	            ->where($db->quoteName('extension_id') .' = '. $db->quote($mod_extension));
			$db->setQuery($query);

			if ($db->execute())
			{
				$query->clear()
				->update($db->quoteName('#__extensions'))
				->set($db->quoteName('extension_id') .' = '. $db->quote($mod_extension))
				->where($db->quoteName('type') .' = '. $db->quote('module'))
				->where($db->quoteName('element') .' = '. $db->quote($this->mod['4x']['name']));
				try {
					$db->setQuery($query)->execute();
				} catch (Exception $e) {
					echo Text::sprintf('JLIB_DATABASE_ERROR_FUNCTION_FAILED', $e->getCode(), $e->getMessage()) . '<br>';
					return;
				}
			}
		}

		return true;
	}


	private function moduleConfig($params)
	{
		// normal items
		$params = str_replace('"reg"', '"nor"', $params);
		$params = json_decode($params, true);

		// Checking for a previous version
		if (isset($params['count'])) { return false; }

		// items count
		$params['count']['top'] = (int) $params['count_top'];
		$params['count']['nor'] = (int) $params['count_regular'];
		unset($params['count_top'], $params['count_regular']);

		// date filtering
		if ($params['date_filtering'] == 'range' || $params['date_filtering'] == 'relative' )
		{
			$params['date_range']['start']		= $params['date_range_start'];
			$params['date_range']['end']		= $params['date_range_end'];
			$params['date_relative']['from']	= (int) $params['date_range_from'][0];
			$params['date_relative']['to']		= (int) $params['date_range_to'][0];
		}
		unset($params['date_range_start'], $params['date_range_end'], $params['date_range_from'], $params['date_range_to']);

		// text limits
		$params['limit_title']['top']	= $params['limit_title'][0];
		$params['limit_title']['nor']	= $params['limit_title'][1];
		$params['limit_text']['top']	= $params['limit_text'][0];
		$params['limit_text']['nor']	= $params['limit_text'][1];
		unset($params['limit_title'][0], $params['limit_title'][1], $params['limit_text'][0], $params['limit_text'][1]);

		// read more
		$params['read_more']['top']		= $params['read_more'][0];
		$params['read_more']['nor']		= $params['read_more'][1];
		unset($params['read_more'][0], $params['read_more'][1]);

		// image settings
		$params['image_top']['width']	= (int) $params['image_width'][0];
		$params['image_top']['height']	= (int) $params['image_height'][0];
		$params['image_nor']['width']	= (int) $params['image_width'][1];
		$params['image_nor']['height']	= (int) $params['image_height'][1];
		unset($params['image_width'], $params['image_height']);

		// date format
		$params['date_format']['top']	= $params['date_format'][0];
		$params['date_format']['nor']	= $params['date_format'][1];
		unset($params['date_format'][0], $params['date_format'][1]);

		return json_encode($params);
	}


	private function moduleClenup()
	{
		// Delete obsolete module files
		foreach ($this->del['files'] as $f)
		{
			$f = JPATH_ROOT . $f;
			if (File::exists($f))
			{
				File::delete($f);
			}
		}

		// Delete obsolete module folders
		foreach ($this->del['folders'] as $f)
		{
			$f = JPATH_ROOT . $f;
			if (Folder::exists($f))
			{
				Folder::delete($f);
			}
		}

		return true;
	}

}
