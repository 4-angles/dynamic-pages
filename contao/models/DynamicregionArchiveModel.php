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
 * @method static DynamicregionArchiveModel|null findById($id, $opt=array())
 * @method static DynamicregionArchiveModel|null findByPk($id, array $opt=array())
 * @method static DynamicregionArchiveModel|null findByIdOrAlias($val, array $opt=array())
 * @method static DynamicregionArchiveModel|null findOneBy($col, $val, array $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByPid($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneBySorting($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByTstamp($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByQuestion($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAlias($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAuthor($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAnswer($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByPageTitle($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByRobots($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByDescription($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAddImage($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByOverwriteMeta($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneBySingleSRC($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAlt($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByImageTitle($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneBySize($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByImagemargin($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByImageUrl($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByFullsize($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByCaption($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByFloating($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByAddEnclosure($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByEnclosure($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByNoComments($val, $opt=array())
 * @method static DynamicregionArchiveModel|null findOneByPublished($val, $opt=array())
 *
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByPid($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findBySorting($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByTstamp($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByQuestion($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAlias($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAuthor($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAnswer($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByPageTitle($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByRobots($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByDescription($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAddImage($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByOverwriteMeta($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findBySingleSRC($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAlt($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByImageTitle($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findBySize($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByImagemargin($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByImageUrl($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByFullsize($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByCaption($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByFloating($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByAddEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByEnclosure($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByNoComments($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findByPublished($val, $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findMultipleByIds($val, array $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findBy($col, $val, array $opt=array())
 * @method static Collection|DynamicregionArchiveModel[]|DynamicregionArchiveModel|null findAll(array $opt=array())
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
class DynamicregionArchiveModel extends Model
{
	use ModelMetadataTrait;

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_dynamicregion_archive';



}

class_alias(DynamicregionArchiveModel::class, 'DynamicregionArchiveModel');
