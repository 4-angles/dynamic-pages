<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

 namespace FourAngles\DynamicPages\Modules;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\Model\Collection;
use Symfony\Component\Routing\Exception\ExceptionInterface;
use Contao\Module;
use Contao\BackendUser;
use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use Contao\System;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Template;
use Contao\ContentModel;
use Contao\Date;
use Contao\UserModel;


/**
 * Parent class for news modules.
 *
 * @property string $news_template
 */
abstract class DynamicregionModule extends Module
{
	/**
	 * Sort out protected archives
	 *
	 * @param array $arrArchives
	 *
	 * @return array
	 */
	protected function sortOutProtected($arrArchives)
	{
		if (empty($arrArchives) || !\is_array($arrArchives))
		{
			return $arrArchives;
		}

		$objArchive = DynamicregionArchiveModel::findMultipleByIds($arrArchives);
		$arrArchives = array();

		if ($objArchive !== null)
		{
			$security = System::getContainer()->get('security.helper');

			while ($objArchive->next())
			{
				if ($objArchive->protected && !$security->isGranted(ContaoCorePermissions::MEMBER_IN_GROUPS, $objArchive->groups))
				{
					continue;
				}

				$arrArchives[] = $objArchive->id;
			}
		}

		return $arrArchives;
	}

	/* Parse an item and return it as string
	*
	* @param NewsModel $objArticle
	* @param boolean   $blnAddArchive
	* @param string    $strClass
	* @param integer   $intCount
	*
	* @return string
	*/
   protected function parseArticle($objArticle, $blnAddArchive=false, $strClass='', $intCount=0)
   {
	   $objTemplate = new FrontendTemplate($this->news_template ?: 'regions_simple');
	   $objTemplate->setData($objArticle->row());

	   if ($objArticle->cssClass)
	   {
		   $strClass = ' ' . $objArticle->cssClass . $strClass;
	   }


	   $url = $this->generateContentUrl($objArticle, $blnAddArchive);
	   $objTemplate->class = $strClass;
	   $objTemplate->newsHeadline = $objArticle->headline;
	   $objTemplate->linkHeadline = $objArticle->headline;
	   $objTemplate->text = '';

	   if (null !== $url)
	   {
		   $objTemplate->linkHeadline = $this->generateLink($objArticle->headline, $objArticle, $blnAddArchive);
		   $objTemplate->more = $this->generateLink($objArticle->linkText ?: $GLOBALS['TL_LANG']['MSC']['more'], $objArticle, $blnAddArchive, true);
		   $objTemplate->link = $url;
	   }



	   // Compile the news text
	   else
	   {
		   $id = $objArticle->pid;

		   $objTemplate->text = $this::once(function () use ($id) {
			   $strText = '';
			   $objElement = ContentModel::findPublishedByPidAndTable($id, 'tl_dynamicregion_archive');

			   if ($objElement !== null)
			   {
				   while ($objElement->next())
				   {
					   $strText .= $this->getContentElement($objElement->current());
				   }
			   }

			   return $strText;
		   });

		   $objTemplate->hasText = null === $url ? false : $this::once(static function () use ($objArticle) {
			   return ContentModel::countPublishedByPidAndTable($objArticle->id, 'tl_dynamicregion_archive') > 0;
		   });
	   }

	   global $objPage;


	   // Add the meta information
	   $objTemplate->timestamp = $objArticle->date;
	   $objTemplate->datetime = date('Y-m-d\TH:i:sP', $objArticle->date);



	   // Tag the news (see #2137)
	   if (System::getContainer()->has('fos_http_cache.http.symfony_response_tagger'))
	   {
		   $responseTagger = System::getContainer()->get('fos_http_cache.http.symfony_response_tagger');
		   $responseTagger->addTags(array('contao.db.tl_dynamicregion_item.' . $objArticle->id));
	   }


	   return $objTemplate->parse();
   }
/**
	 * Parse one or more items and return them as array
	 *
	 * @param Collection $objArticles
	 * @param boolean    $blnAddArchive
	 *
	 * @return array
	 */
	protected function parseArticles($objArticles, $blnAddArchive=false)
	{
		$limit = $objArticles->count();

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$arrArticles = array();
		$uuids = array();

		foreach ($objArticles as $objArticle)
		{
			if ($objArticle->addImage && $objArticle->singleSRC)
			{
				$uuids[] = $objArticle->singleSRC;
			}
		}

		// Preload all images in one query, so they are loaded into the model registry
		FilesModel::findMultipleByUuids($uuids);



		foreach ($objArticles as $objArticle)
		{
			$arrArticles[] = $this->parseArticle($objArticle, $blnAddArchive, '', ++$count);
			#var_dump($arrArticles);exit;
		}

		return $arrArticles;
	}

	/**
	 * Generate a link and return it as string
	 *
	 * @param string    $strLink
	 * @param DynamicregionItemModel $objArticle
	 * @param boolean   $blnAddArchive
	 * @param boolean   $blnIsReadMore
	 *
	 * @return string
	 */
	protected function generateLink($strLink, $objArticle, $blnAddArchive=false, $blnIsReadMore=false)
	{
		$blnIsInternal = $objArticle->source != 'external';
		$strReadMore = $blnIsInternal ? $GLOBALS['TL_LANG']['MSC']['readMore'] : $GLOBALS['TL_LANG']['MSC']['open'];
		$strArticleUrl = $this->generateContentUrl($objArticle, $blnAddArchive);

		return sprintf(
			'<a href="%s" title="%s"%s>%s%s</a>',
			$strArticleUrl,
			StringUtil::specialchars(sprintf($strReadMore, $blnIsInternal ? $objArticle->headline : $strArticleUrl), true),
			$objArticle->target && !$blnIsInternal ? ' target="_blank" rel="noreferrer noopener"' : '',
			$strLink,
			$blnIsReadMore && $blnIsInternal ? '<span class="invisible"> ' . $objArticle->headline . '</span>' : ''
		);
	}

	private function generateContentUrl($content, bool $addArchive): string|null
	{
		$parameters = array();

		// Add the current archive parameter (news archive)
		if ($addArchive && Input::get('month'))
		{
			$parameters['month'] = Input::get('month');
		}

		try
		{
			return System::getContainer()->get('contao.routing.content_url_generator')->generate($content, $parameters);
		}
		catch (ExceptionInterface)
		{
			return null;
		}
	}

	public static function once(callable $callback)
	{
		return static function () use (&$callback) {
			if (\is_callable($callback))
			{
				$callback = $callback();
			}

			return $callback;
		};
	}
}