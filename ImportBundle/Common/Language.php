<?php
/**
 * Created by PhpStorm.
 * User: ishwar
 * Date: 09/04/21
 * Time: 12:02 PM
 */

namespace ImportBundle\Common;

class Language
{
    /**
     * file => pimcore
     * @var array
     */
    static $languages = [
        'cz' => 'cs', // czech
        'de' => 'de', // german
        'en' => 'en', // default English
        'dk' => 'da', //danish
        'es' => 'es', // spanish
        'fi' => 'fi', // finnish
        'fr' => 'fr', // french
        'it' => 'it', // italian
        'nl' => 'nl', // dutch
        'no' => 'nb', // norwegian
        'pl' => 'pl', // polish
        'pt' => 'pt', // portugese
        'ru' => 'ru', // russian
        'se' => 'sv' // swedish
    ];

    public static function getLanguageCode($fileLanguage)
    {
        return isset(self::$languages[$fileLanguage]) ? self::$languages[$fileLanguage] : null;
    }

    public static function getAllLanguages()
    {
        return self::$languages;
    }

    public function getLanguageForPP($fileLanguage)
    {
        $languages = array_flip(self::$languages);
        return isset($languages[$fileLanguage]) ? $languages[$fileLanguage] : null;
    }
}
