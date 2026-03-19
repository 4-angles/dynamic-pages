<?php

use Contao\Backend;
use Contao\Database;
use Contao\BackendUser;
use Contao\System;
use Contao\DataContainer;

// Add palettes to tl_module
#$GLOBALS['TL_DCA']['tl_module']['palettes']['dynamicregion_list'] = '{title_legend},name,headline,type;{config_legend},item_categories,jumpTo;{protected_legend:hide},protected;{expert_legend:hide},cssID';
#$GLOBALS['TL_DCA']['tl_module']['palettes']['dynamicregion_reader'] = '{title_legend},name,headline,type;{config_legend},item_categories;{protected_legend:hide},protected;{expert_legend:hide},cssID';

$GLOBALS['TL_DCA']['tl_module']['palettes']['dynamicregion_list'] = '{title_legend},name,headline,type;{config_legend},item_categories;{protected_legend:hide},protected;{expert_legend:hide},cssID';
$GLOBALS['TL_DCA']['tl_module']['palettes']['dynamicregion_reader'] = '{title_legend},name,headline,type;{config_legend},item_categories;{protected_legend:hide},protected;{expert_legend:hide},cssID';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['item_categories'] = array
(
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_module_DynamicPages', 'getNewsArchives'),
	'eval'                    => array('multiple'=>true, 'mandatory'=>true),
	'sql'                     => "blob NULL"
);



/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @internal
 */
class tl_module_DynamicPages extends Backend
{
	/**
	 * Get all news archives and return them as array
	 *
	 * @return array
	 */
	public function getNewsArchives()
	{

		$arrArchives = array();
		$objArchives = Database::getInstance()->execute("SELECT id, title FROM tl_dynamicregion_archive ORDER BY title");
		while ($objArchives->next()) {
			$arrArchives[$objArchives->id] = $objArchives->title;
		}

		return $arrArchives;
	}

	/**
	 * Get all dynamicregion_reader modules and return them as array
	 *
	 * @return array
	 */
	public function getReaderModules()
	{
		$arrModules = array();
		$objModules = Database::getInstance()->execute("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE m.type='dynamicregion_reader' ORDER BY t.name, m.name");

		while ($objModules->next())
		{
			$arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
		}

		return $arrModules;
	}

	/**
	 * Return the sorting options
	 *
	 * @param DataContainer $dc
	 *
	 * @return array
	 */
	public function getSortingOptions(DataContainer $dc)
	{
		if ($dc->activeRecord && $dc->activeRecord->type == 'newsmenu')
		{
			return array('order_date_asc', 'order_date_desc');
		}

		return array('order_date_asc', 'order_date_desc', 'order_headline_asc', 'order_headline_desc', 'order_random');
	}
}