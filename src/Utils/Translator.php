<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation\Utils;

/**
 * Translation of validation rules errors
 */
abstract class Translator 
{
    /**
     * Translations
     *
     * @var array<string, array<string, string>>
     * 
     * @example 
     * ```
     * [
     *  'en' => [
     *      'home' => 'Home'
     *  ],
     *  'fr' => [
     *      'home' => 'Accueil'
     *  ]
     * ]
     * ```
     */
    private static array $translations = [];

    /**
     * Get the translation for the given key in the given locale.
     */
    public static function translate(string $key, string $locale, bool $strict = false): ?string
    {
        return self::translations($locale)[$key] ?? ($strict ? null : $key);
    }

    /**
     * Get the translations of a given locale
     */
    private static function translations(string $locale): array
    {
        $locale = strtolower(substr($locale, 0, 2));

        if (empty(self::$translations[$locale])) {
            if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $locale . '.php')) {
                self::$translations[$locale] = require  $file;
            }
        }

        return (array) (self::$translations[$locale] ?? []);
    }
}
