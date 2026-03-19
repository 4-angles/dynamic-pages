<?php

namespace FourAngles\DynamicPages\EventListener;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\SitemapEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use FourAngles\DynamicPages\Models\DynamicregionItemModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Environment;
use Contao\Config;

#[AsEventListener(ContaoCoreEvents::SITEMAP)]
class SitemapListener
{
    public function __invoke(SitemapEvent $event): void
    {
        $items = DynamicregionItemModel::findAll();
		foreach($items as $item){
			if($item->published){
                $archive = DynamicregionArchiveModel::findById($item->pid);
                $overviewPage = PageModel::findById($archive->jumpTo);

                // Weird solution, but need to be implemented on 4.13. There is different solution on 5+ but we need it to work on 4.13
                $url = StringUtil::ampersand(Environment::get("url").$overviewPage->getFrontendUrl(Config::get('useAutoItem') ? "/$item->alias" : "/$item->alias"));

                $sitemap = $event->getDocument();
                $urlSet = $sitemap->childNodes[0];
        
                $loc = $sitemap->createElement('loc');
                $loc->appendChild($sitemap->createTextNode($url));
        
                $urlEl = $sitemap->createElement('url');
                $urlEl->appendChild($loc);
                $urlSet->appendChild($urlEl);

                //$event->addUrlToDefaultUrlSet($url);
			}

		}
    }
}