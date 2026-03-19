<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace FourAngles\DynamicPages\Modules;

use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Util\UrlUtil;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Contao\BackendUser;
use Contao\System;
use Contao\Input;
use Contao\StringUtil;
use FourAngles\DynamicPages\Models\DynamicregionItemModel;
use Contao\Environment;
use Contao\BackendTemplate;

/**
 * Front end module "newsreader".
 *
 * @property Comments $Comments
 * @property string   $com_template
 * @property array    $item_categories
 */
class DynamicregionItemReader extends DynamicregionModule
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_dynamicregion_reader';

	/**
	 * Display a wildcard in the back end
	 *
	 * @throws InternalServerErrorException
	 *
	 * @return string
	 */
	public function generate()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['FMD']['dynamicregion_reader'][0] . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = StringUtil::specialcharsUrl(System::getContainer()->get('router')->generate('contao_backend', array('do'=>'themes', 'table'=>'tl_module', 'act'=>'edit', 'id'=>$this->id)));

			return $objTemplate->parse();
		}

		// Return an empty string if "auto_item" is not set to combine list and reader on same page
		if (Input::get('auto_item') === null)
		{
			return '';
		}

		$this->item_categories = $this->sortOutProtected(StringUtil::deserialize($this->item_categories));

		if (empty($this->item_categories) || !\is_array($this->item_categories))
		{
			throw new InternalServerErrorException('The newsreader ID ' . $this->id . ' has no archives specified.');
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->Template->articles = '';

		$urlGenerator = System::getContainer()->get('contao.routing.content_url_generator');

		if ($this->overviewPage && ($overviewPage = PageModel::findById($this->overviewPage)))
		{
			$this->Template->referer = $urlGenerator->generate($overviewPage);
			$this->Template->back = $this->customLabel ?: $GLOBALS['TL_LANG']['MSC']['newsOverview'];
		}

		// Get the news item
		$objArticle = DynamicregionItemModel::findPublishedByParentAndIdOrAlias(Input::get('auto_item'), $this->item_categories);

		// The news item does not exist (see #33)
		if ($objArticle === null)
		{
			throw new PageNotFoundException('Page not found: ' . Environment::get('uri'));
		}


		// Set the default template
		if (!$this->news_template)
		{
			$this->news_template = 'regions_full';
		}

		// Overwrite the page metadata (see #2853, #4955 and #87)
		$responseContext = System::getContainer()->get('contao.routing.response_context_accessor')->getResponseContext();

		if ($responseContext?->has(HtmlHeadBag::class))
		{
			$htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
			$htmlDecoder = System::getContainer()->get('contao.string.html_decoder');

			if ($objArticle->pageTitle)
			{
				$htmlHeadBag->setTitle($objArticle->pageTitle); // Already stored decoded
			}
			elseif ($objArticle->headline)
			{
				$htmlHeadBag->setTitle($htmlDecoder->inputEncodedToPlainText($objArticle->headline));
			}

			// if ($objArticle->description)
			// {
			// 	$htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($objArticle->description));
			// }
			// if ($objArticle->robots)
			// {
			// 	$htmlHeadBag->setMetaRobots($objArticle->robots);
			// }
		}
		$arrArticle = $this->parseArticle($objArticle);
		
		$this->Template->articles = $arrArticle;


	}
}