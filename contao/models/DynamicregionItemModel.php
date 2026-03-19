<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace FourAngles\DynamicPages\Models;

use Contao\CoreBundle\File\ModelMetadataTrait;
use Contao\Model\Collection;
use Contao\Model;
use Contao\Date;
use Contao\StringUtil;

/**
 * Reads and writes FAQs
 *
 * @property string|integer $id
 * @property string|integer $pid
 * @property string|integer $sorting
 * @property string|integer $tstamp
 * @property string         $question
 * @property string         $alias
 * @property string|integer $author
 * @property string|null    $answer
 * @property string         $pageTitle
 * @property string         $robots
 * @property string|null    $description
 * @property string|boolean $addImage
 * @property string|boolean $overwriteMeta
 * @property string|null    $singleSRC
 * @property string         $alt
 * @property string         $imageTitle
 * @property string|integer $size
 * @property string|array   $imagemargin
 * @property string         $imageUrl
 * @property string|boolean $fullsize
 * @property string         $caption
 * @property string         $floating
 * @property string|boolean $addEnclosure
 * @property string|null    $enclosure
 * @property string|boolean $noComments
 * @property string|boolean $published
 *
 * @method static DynamicregionItemModel|null findById($id, $opt=array())
 * @method static DynamicregionItemModel|null findByPk($id, array $opt=array())
 * @method static DynamicregionItemModel|null findByIdOrAlias($val, array $opt=array())
 * @method static DynamicregionItemModel|null findOneBy($col, $val, array $opt=array())
 * @method static DynamicregionItemModel|null findOneByPid($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneBySorting($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByTstamp($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByQuestion($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAlias($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAuthor($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAnswer($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByPageTitle($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByRobots($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByDescription($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAddImage($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByOverwriteMeta($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneBySingleSRC($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAlt($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByImageTitle($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneBySize($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByImagemargin($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByImageUrl($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByFullsize($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByCaption($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByFloating($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByEnclosure($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByNoComments($val, $opt=array())
 * @method static DynamicregionItemModel|null findOneByPublished($val, $opt=array())
 *
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByPid($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findBySorting($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByTstamp($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByQuestion($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAlias($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAuthor($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAnswer($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByPageTitle($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByRobots($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByDescription($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAddImage($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByOverwriteMeta($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findBySingleSRC($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAlt($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByImageTitle($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findBySize($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByImagemargin($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByImageUrl($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByFullsize($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByCaption($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByFloating($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByAddEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByNoComments($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findByPublished($val, $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findMultipleByIds($val, array $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findBy($col, $val, array $opt=array())
 * @method static Collection|DynamicregionItemModel[]|DynamicregionItemModel|null findAll(array $opt=array())
 *
 * @method static integer countById($id, $opt=array())
 * @method static integer countByPid($val, $opt=array())
 * @method static integer countBySorting($val, $opt=array())
 * @method static integer countByTstamp($val, $opt=array())
 * @method static integer countByQuestion($val, $opt=array())
 * @method static integer countByAlias($val, $opt=array())
 * @method static integer countByAuthor($val, $opt=array())
 * @method static integer countByAnswer($val, $opt=array())
 * @method static integer countByPageTitle($val, $opt=array())
 * @method static integer countByRobots($val, $opt=array())
 * @method static integer countByDescription($val, $opt=array())
 * @method static integer countByAddImage($val, $opt=array())
 * @method static integer countByOverwriteMeta($val, $opt=array())
 * @method static integer countBySingleSRC($val, $opt=array())
 * @method static integer countByAlt($val, $opt=array())
 * @method static integer countByImageTitle($val, $opt=array())
 * @method static integer countBySize($val, $opt=array())
 * @method static integer countByImagemargin($val, $opt=array())
 * @method static integer countByImageUrl($val, $opt=array())
 * @method static integer countByFullsize($val, $opt=array())
 * @method static integer countByCaption($val, $opt=array())
 * @method static integer countByFloating($val, $opt=array())
 * @method static integer countByAddEnclosure($val, $opt=array())
 * @method static integer countByEnclosure($val, $opt=array())
 * @method static integer countByNoComments($val, $opt=array())
 * @method static integer countByPublished($val, $opt=array())
 */
class DynamicregionItemModel extends Model
{
	use ModelMetadataTrait;

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_dynamicregion_item';

		/**
	 * Find a published news item from one or more news archives by its ID or alias
	 *
	 * @param mixed $varId      The numeric ID or alias name
	 * @param array $arrPids    An array of parent IDs
	 * @param array $arrOptions An optional options array
	 *
	 * @return DynamicregionItemModel|null The model or null if there are no news
	 */
	public static function findPublishedByParentAndIdOrAlias($varId, $arrPids, array $arrOptions=array())
	{
		$arrPids = StringUtil::deserialize($arrPids);


		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = !preg_match('/^[1-9]\d*$/', $varId) ? array("BINARY $t.alias=?") : array("$t.id=?");
		$arrColumns[] = "$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")";
		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}

		return static::findOneBy($arrColumns, array($varId), $arrOptions);
	}

	/**
	 * Find published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param integer $intLimit    An optional limit
	 * @param integer $intOffset   An optional offset
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return Collection<DynamicregionItemModel>|DynamicregionItemModel[]|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPids($arrPids, $blnFeatured=null, $intLimit=0, $intOffset=0, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");


		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}


		return static::findBy($arrColumns, null, $arrOptions);
	}

	/**
	 * Count published news items by their parent ID
	 *
	 * @param array   $arrPids     An array of news archive IDs
	 * @param boolean $blnFeatured If true, return only featured news, if false, return only unfeatured news
	 * @param array   $arrOptions  An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedByPids($arrPids, $blnFeatured=null, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return 0;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");

		if ($blnFeatured === true)
		{
			$arrColumns[] = "$t.featured=1";
		}
		elseif ($blnFeatured === false)
		{
			$arrColumns[] = "$t.featured=0";
		}

		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}

		return static::countBy($arrColumns, null, $arrOptions);
	}

	/**
	 * Find published news items with the default redirect target by their parent ID
	 *
	 * @param integer $intPid     The news archive ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return Collection<DynamicregionItemModel>|DynamicregionItemModel[]|null A collection of models or null if there are no news
	 */
	public static function findPublishedDefaultByPid($intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.source='default'");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}


		return static::findBy($arrColumns, array($intPid), $arrOptions);
	}

	/**
	 * Find published news items by their parent ID
	 *
	 * @param integer $intId      The news archive ID
	 * @param integer $intLimit   An optional limit
	 * @param array   $arrOptions An optional options array
	 *
	 * @return Collection<DynamicregionItemModel>|DynamicregionItemModel[]|null A collection of models or null if there are no news
	 */
	public static function findPublishedByPid($intId, $intLimit=0, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}


		if ($intLimit > 0)
		{
			$arrOptions['limit'] = $intLimit;
		}

		return static::findBy($arrColumns, array($intId), $arrOptions);
	}


	/**
	 * Count all published news items of a certain period of time by their parent ID
	 *
	 * @param integer $intFrom    The start date as Unix timestamp
	 * @param integer $intTo      The end date as Unix timestamp
	 * @param array   $arrPids    An array of news archive IDs
	 * @param array   $arrOptions An optional options array
	 *
	 * @return integer The number of news items
	 */
	public static function countPublishedFromToByPids($intFrom, $intTo, $arrPids, array $arrOptions=array())
	{
		if (empty($arrPids) || !\is_array($arrPids))
		{
			return null;
		}

		$t = static::$strTable;
		$arrColumns = array("$t.date>=? AND $t.date<=? AND $t.pid IN(" . implode(',', array_map('\intval', $arrPids)) . ")");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = Date::floorToMinute();
			$arrColumns[] = "$t.published=1 AND ($t.start='' OR $t.start<=$time) AND ($t.stop='' OR $t.stop>$time)";
		}

		return static::countBy($arrColumns, array($intFrom, $intTo), $arrOptions);
	}


}

class_alias(DynamicregionItemModel::class, 'DynamicregionItemModel');
