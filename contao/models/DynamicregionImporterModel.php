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
 * @method static DynamicregionImporterModel|null findById($id, $opt=array())
 * @method static DynamicregionImporterModel|null findByPk($id, array $opt=array())
 * @method static DynamicregionImporterModel|null findByIdOrAlias($val, array $opt=array())
 * @method static DynamicregionImporterModel|null findOneBy($col, $val, array $opt=array())
 * @method static DynamicregionImporterModel|null findOneByPid($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneBySorting($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByTstamp($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByQuestion($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAlias($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAuthor($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAnswer($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByPageTitle($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByRobots($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByDescription($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAddImage($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByOverwriteMeta($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneBySingleSRC($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAlt($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByImageTitle($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneBySize($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByImagemargin($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByImageUrl($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByFullsize($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByCaption($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByFloating($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByEnclosure($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByNoComments($val, $opt=array())
 * @method static DynamicregionImporterModel|null findOneByPublished($val, $opt=array())
 *
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByPid($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findBySorting($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByTstamp($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByQuestion($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAlias($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAuthor($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAnswer($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByPageTitle($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByRobots($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByDescription($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAddImage($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByOverwriteMeta($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findBySingleSRC($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAlt($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByImageTitle($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findBySize($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByImagemargin($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByImageUrl($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByFullsize($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByCaption($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByFloating($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByAddEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByNoComments($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findByPublished($val, $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findMultipleByIds($val, array $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findBy($col, $val, array $opt=array())
 * @method static Collection|DynamicregionImporterModel[]|DynamicregionImporterModel|null findAll(array $opt=array())
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
class DynamicregionImporterModel extends Model
{
	use ModelMetadataTrait;

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_dynamicregion_importer';

	


}

class_alias(DynamicregionImporterModel::class, 'DynamicregionImporterModel');
