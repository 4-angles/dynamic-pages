<?php

use FourAngles\DynamicPages\Models\DynamicregionItemModel;
use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use FourAngles\DynamicPages\Models\DynamicregionImporterModel;


use FourAngles\DynamicPages\Modules\DynamicregionArchiveList;
use FourAngles\DynamicPages\Modules\DynamicregionItemReader;

$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['FourAngles\DynamicPages\InsertTag\DynamicRegionTitleInsertTag', 'doReplace'];

// Add back end modules
$GLOBALS['BE_MOD']['DynamicPages']['DynamicPages'] = array
(
	'tables' => array('tl_dynamicregion_archive', 'tl_dynamicregion_item','tl_content')
);
// Add back end modules
$GLOBALS['BE_MOD']['DynamicPages']['DynamicPages_importer'] = array
(
	'tables' => array('tl_dynamicregion_importer')
);

// // Models
 $GLOBALS['TL_MODELS']['tl_dynamicregion_item'] = DynamicregionItemModel::class;
 $GLOBALS['TL_MODELS']['tl_dynamicregion_archive'] = DynamicregionArchiveModel::class;
 $GLOBALS['TL_MODELS']['tl_dynamicregion_importer'] = DynamicregionImporterModel::class;



 // Front end modules
$GLOBALS['FE_MOD']['DynamicPages'] = array
(
	'dynamicregion_list'    => DynamicregionArchiveList::class,
	'dynamicregion_reader'  => DynamicregionItemReader::class,

);

