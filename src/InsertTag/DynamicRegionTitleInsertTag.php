<?php

namespace FourAngles\DynamicPages\InsertTag;

use FourAngles\DynamicPages\Models\DynamicregionArchiveModel;
use FourAngles\DynamicPages\Models\DynamicregionItemModel;

class DynamicRegionTitleInsertTag
{
    /**
     * Supported insert tags:
     *
     *   {{dynreg_item::fieldname}}            — any field on the current region item
     *   {{dynreg_item::fieldname|demonym_de}} — field value run through German demonym logic
     *   {{dynreg_archive::fieldname}}         — any field on the parent archive of the current item
     *   {{dynreg_archive::fieldname|demonym_de}}
     *
     * Legacy tags (kept for backward compatibility):
     *   {{dynreg_title}}             → dynreg_item::headline
     *   {{dynreg_title_relative_de}} → dynreg_item::headline|demonym_de
     */
    public function doReplace($tag, $blnCache, $strTag, $flags, $tags, $arrCache, $_rit, $_cnt): string|false
    {
        $elements = explode('::', $tag);
        $prefix   = strtolower($elements[0]);
        $field    = $elements[1] ?? null;

        switch ($prefix) {
            // Legacy shortcuts
            case 'dynreg_title':
                $item = $this->getCurrentItem();
                return $item ? (string) $item->headline : false;

            case 'dynreg_title_relative_de':
                $item = $this->getCurrentItem();
                return $item ? $this->getDemonymDE((string) $item->headline) : false;

            // Dynamic: any field on the current region item
            case 'dynreg_item':
                if ($field === null) {
                    return false;
                }
                $item = $this->getCurrentItem();
                if (!$item) {
                    return false;
                }
                return $this->applyFlags((string) ($item->{$field} ?? ''), $flags);

            // Dynamic: any field on the parent archive of the current item
            case 'dynreg_archive':
                if ($field === null) {
                    return false;
                }
                $item = $this->getCurrentItem();
                if (!$item) {
                    return false;
                }
                $archive = DynamicregionArchiveModel::findById($item->pid);
                if (!$archive) {
                    return false;
                }
                return $this->applyFlags((string) ($archive->{$field} ?? ''), $flags);
        }

        return false;
    }

    private function getCurrentItem(): ?DynamicregionItemModel
    {
        $path  = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
        $parts = explode('/', $path);
        $last  = end($parts);
        $alias = explode('.', $last)[0];

        if ($alias === '') {
            return null;
        }

        return DynamicregionItemModel::findByAlias($alias);
    }

    private function applyFlags(string $value, array $flags): string
    {
        if (in_array('demonym_de', $flags, true)) {
            return $this->getDemonymDE($value);
        }

        return $value;
    }

    private function getDemonymDE($city): string
    {
        // Convert the city name to lowercase for easier manipulation
        $lowerCity = strtolower($city);

        // Special cases and exceptions
        $exceptions = [
            'München' => 'Münchner',
            'Köln' => 'Kölner',
            'Leipzig' => 'Leipziger',
            'Frankfurt' => 'Frankfurter',
            'Dresden' => 'Dresdner',
            'Aachen' => 'Aachener',
            'Bremen' => 'Bremer',
        ];

        // Check for exceptions first
        if (isset($exceptions[$city])) {
            return $exceptions[$city];
        }

        // General rules for forming demonyms
        if (substr($lowerCity, -4) === 'burg') {
            return $city . 'er'; // Freiburg → Freiburger
        } elseif (substr($lowerCity, -5) === 'stadt') {
            return substr($city, 0, -5) . 'städter'; // Darmstadt → Darmstädter
        } elseif (substr($lowerCity, -2) === 'en') {
            return $city . 'er'; // Essen → Essener
        } elseif (substr($lowerCity, -1) === 'n') {
            return $city . 'er'; // Berlin → Berliner
        } elseif (substr($lowerCity, -1) === 's') {
            return $city . 'er'; // Mainz → Mainzer
        } elseif (substr($lowerCity, -2) === 'au') {
            return $city . 'er'; // Dessau → Dessauer
        } elseif (substr($lowerCity, -2) === 'ne') {
            return $city . 'r'; // Lohne → Lohner
        } elseif (substr($lowerCity, -2) === 'de') {
            return $city . 'ner'; // Glinde → Glindener
        } elseif (substr($lowerCity, -1) === 'e') {
            return substr($city, 0, -1) . 'ner'; // Kassel → Kasseler
        } else {
            return $city . 'er'; // General rule
        }

    }
}