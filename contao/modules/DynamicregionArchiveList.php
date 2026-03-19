<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace FourAngles\DynamicPages\Modules;

use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\Model\Collection;
use Contao\Backend;
use Contao\System;
use Contao\StringUtil;
use Contao\BackendTemplate;
use Contao\Input;
use Contao\Database;
use Contao\Module;
use Contao\PageModel;
use Contao\ArticleModel;
use Contao\Config;
use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use FourAngles\DynamicPages\Models\DynamicregionItemModel;

/**
 * Front end module "news list".
 *
 * @property array  $item_categories
 * @property string $news_featured
 * @property string $news_order
 */
class DynamicregionArchiveList extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_dynamicregion_list';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['dynamicregion_list'][0] . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = StringUtil::specialcharsUrl(System::getContainer()->get('router')->generate('contao_backend', array('do' => 'themes', 'table' => 'tl_module', 'act' => 'edit', 'id' => $this->id)));

			return $objTemplate->parse();
		}

		$this->item_categories = StringUtil::deserialize($this->item_categories);

		// Return if there are no archives
		if (empty($this->item_categories) || !\is_array($this->item_categories)) {
			return '';
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{

		$objItems = $this->fetchItems($this->item_categories);
		$arrayOfItems = [];

		if (!$objItems) {
			return 0;
		}



		foreach ($objItems as $item) {
			$archive = DynamicregionArchiveModel::findById($item->pid);
			$overviewPage = PageModel::findById($archive->jumpTo);
			// Weird solution, but need to be implemented on 4.13. There is different solution on 5+ but we need it to work on 4.13
			$url = StringUtil::ampersand($overviewPage->getFrontendUrl(Config::get('useAutoItem') ? "/$item->alias" : "/$item->alias"));

			$arrayOfItems[substr($item->headline, 0, 1)][$item->id] = [
				"url" => $url,
				"item" => $item
			];

		}

		ksort($arrayOfItems);





		$this->Template->items = $arrayOfItems;


	}



	/**
	 * Fetch the matching items
	 *
	 * @param array   $items
	 *
	 * @return Collection|NewsModel|null
	 */
	protected function fetchItems($items)
	{

		// Determine sorting
		$t = DynamicregionItemModel::getTable();

		$order = "$t.ASC";



		return DynamicregionItemModel::findPublishedByPids($items, array('order' => $order));
	}
}