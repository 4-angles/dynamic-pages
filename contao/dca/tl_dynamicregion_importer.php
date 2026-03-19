<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Database;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\News;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\FilesModel;
use FourAngles\DynamicPages\Models\DynamicregionImporterModel;
use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use FourAngles\DynamicPages\Models\DynamicregionItemModel;



$GLOBALS['TL_DCA']['tl_dynamicregion_importer'] = array
(
	// Config
	'config' => array
	(
		'dataContainer' => DC_Table::class,
		'switchToEdit' => true,
		'markAsCopy' => 'title',
		'oncreate_callback' => array
		(
			array('tl_dynamicregion_importer', 'adjustPermissions')
		),
		'oncopy_callback' => array
		(
			array('tl_dynamicregion_importer', 'adjustPermissions')
		),
		'onsubmit_callback' => array
		(
			array('tl_dynamicregion_importer', 'beforeSubmit')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'tstamp' => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode' => DataContainer::MODE_SORTED,
			'fields' => array('title'),
			'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
			'panelLayout' => 'filter;search,limit',
			'defaultSearchField' => 'title'
		),
		'label' => array
		(
			'fields' => array('title'),
			'format' => '%s'
		),
		'operations' => array
		(
			'edit',
			'delete',
		)
	),

	// Palettes
	'palettes' => array
	(
		'default' => '{title_legend},title;{file_legend},singleSRC,dynamicregionarchive,uploadcsv'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql' => "int(10) unsigned NOT NULL default 0"
		),
		'title' => array
		(
			'search' => true,
			'inputType' => 'text',
			'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
			'sql' => "varchar(255) NOT NULL default ''"
		),
		'singleSRC' => [
			'exclude' => true,
			'inputType' => 'fileTree',
			'eval' => [
				'filesOnly' => true,
				'fieldType' => 'radio',
				'extensions' => 'csv',
				'mandatory' => true
			],
			'sql' => [
				'type' => 'binary',
				'length' => 16,
				'fixed' => true,
				'notnull' => false,
			],
		],
		'uploadcsv' => array
		(
			'toggle' => true,
			'inputType' => 'checkbox',
			'eval' => array('doNotCopy' => true),
			'sql' => array('type' => 'boolean', 'default' => false)
		),
		'dynamicregionarchive' => [
			'inputType' => 'picker',
			'sql' => [
				'type' => 'integer',
				'unsigned' => true,
				'default' => 0,
			],
			'relation' => [
				'type' => 'hasOne',
				'load' => 'lazy',
				'table' => 'tl_dynamicregion_archive',
			],
		],
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @property News $News
 *
 * @internal
 */
class tl_dynamicregion_importer extends Backend
{
	/**
	 * Add the new archive to the permissions
	 *
	 * @param string|int $insertId
	 */
	public function adjustPermissions($insertId)
	{
		// The oncreate_callback passes $insertId as second argument
		if (func_num_args() == 4) {
			$insertId = func_get_arg(1);
		}

		$user = BackendUser::getInstance();

		if ($user->isAdmin) {
			return;
		}

		// Set root IDs
		if (empty($user->news) || !is_array($user->news)) {
			$root = array(0);
		} else {
			$root = $user->news;
		}

		// The archive is enabled already
		if (in_array($insertId, $root)) {
			return;
		}

		$db = Database::getInstance();

		$objSessionBag = System::getContainer()->get('request_stack')->getSession()->getBag('contao_backend');
		$arrNew = $objSessionBag->get('new_records');

		if (is_array($arrNew['tl_dynamicregion_importer']) && in_array($insertId, $arrNew['tl_dynamicregion_importer'])) {
			// Add the permissions on group level
			if ($user->inherit != 'custom') {
				$objGroup = $db->execute("SELECT id, news, newp FROM tl_user_group WHERE id IN(" . implode(',', array_map('\intval', $user->groups)) . ")");

				while ($objGroup->next()) {
					$arrNewp = StringUtil::deserialize($objGroup->newp);

					if (is_array($arrNewp) && in_array('create', $arrNewp)) {
						$arrNews = StringUtil::deserialize($objGroup->news, true);
						$arrNews[] = $insertId;

						$db->prepare("UPDATE tl_user_group SET news=? WHERE id=?")->execute(serialize($arrNews), $objGroup->id);
					}
				}
			}

			// Add the permissions on user level
			if ($user->inherit != 'group') {
				$objUser = $db
					->prepare("SELECT news, newp FROM tl_user WHERE id=?")
					->limit(1)
					->execute($user->id);

				$arrNewp = StringUtil::deserialize($objUser->newp);

				if (is_array($arrNewp) && in_array('create', $arrNewp)) {
					$arrNews = StringUtil::deserialize($objUser->news, true);
					$arrNews[] = $insertId;

					$db->prepare("UPDATE tl_user SET news=? WHERE id=?")->execute(serialize($arrNews), $user->id);
				}
			}

			// Add the new element to the user object
			$root[] = $insertId;
			$user->news = $root;
		}
	}

	public function beforeSubmit(DataContainer $dc)
	{



		$importerObj = DynamicregionImporterModel::findById($dc->id);

		if(!$importerObj->uploadcsv){
			return 0;
		}

		$file = StringUtil::binToUuid($importerObj->singleSRC);
		$file = FilesModel::findById($file);


		$uploader = $this->convertCSVtoDB($file->path, $importerObj->dynamicregionarchive, $dc);

		if($uploader){
			$db = Database::getInstance()->prepare("UPDATE `tl_dynamicregion_importer` SET `uploadcsv`='0' WHERE `id` = ?")
			->execute($dc->id);
		}

	}


	public function convertCSVtoDB($csvFile, $archiveId, DataContainer $dc)
	{
		// Open the CSV file for reading
		if (($handle = fopen($csvFile, 'r')) !== FALSE) {

			$values = []; // Array to store all values from the CSV

			// Read each row of the CSV file
			while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
				// Since each line contains only one city, add it to the array
				$values[] = $data[0];
			}

			fclose($handle);

			foreach ($values as $value) {
				$alias = $this->generateAlias($value, $archiveId, $dc);
				
				$this->uploadToDatabase($value, $alias, $archiveId);

			}


		}

		return 1;
	}

	/**
     * Auto-generate the news alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, $archiveId, DataContainer $dc)
    {

        $aliasExists = static function (string $varValue) use ($dc, $archiveId): bool {
            $result = Database::getInstance()
                ->prepare("SELECT id FROM tl_dynamicregion_item WHERE alias=? AND pid=?")
                ->execute($varValue, $archiveId);
            return $result->numRows > 0;
        };
        $archive = DynamicregionArchiveModel::findById($archiveId);
        $varValue = System::getContainer()->get('contao.slug')->generate($varValue, $archive->jumpTo, $aliasExists);

        return $varValue;
    }
    /**
     * Upload the alias and title to DB
     *
     */
    public function uploadToDatabase($title, $alias, $archiveId)
    {
        if (!DynamicregionItemModel::findBy(['alias=?', 'pid=?'], [$alias, $archiveId])) {
            $db = Database::getInstance();
            $aliasInsert = $db->prepare("INSERT INTO `tl_dynamicregion_item`(`published`, `pid`, `tstamp`, `headline`, `alias`) VALUES (?,?,?,?,?)")->execute(1, $archiveId, \time(), $title, $alias);
            return $aliasInsert;
        }
    }




}
