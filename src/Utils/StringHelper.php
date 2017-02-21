<?php

namespace IdeasBucket\Common\Utils;

/**
 * Class StringHelper.
 */
class StringHelper
{
    use MacroableTrait;

    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $snakeCache = [];

    /**
     * The cache of camel-cased words.
     *
     * @var array
     */
    protected static $camelCache = [];

    /**
     * The cache of studly-cased words.
     *
     * @var array
     */
    protected static $studlyCache = [];

    /**
     * Get the class "basename" of the given object / class.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param  string|object  $class
     *
     * @return string
     */
    public function classBasename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string $title
     * @param bool   $convertCase
     * @param string $separator
     *
     * @return string
     */
    public static function slug($title, $convertCase = true, $separator = '-')
    {
        $title = static::ascii($title);

        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        if ($convertCase) {

            $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

        } else {

            $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', $title);

        }

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    /**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
     *
     * Note: Adapted from YII2 framework
     *
     * @see https://github.com/yiisoft/yii2/blob/master/LICENSE.md
     *
     * @param string $string The string being measured for length
     *
     * @return int the number of bytes in the given string.
     */
    public static function length($string)
    {
        return mb_strlen($string, '8bit');
    }

    /**
     * Limit the number of characters in a string.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string $value
     * @param int    $limit
     * @param string $end
     *
     * @return string
     */
    public static function truncate($value, $limit = 100, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {

            return $value;

        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }

    /**
     * Returns the portion of string specified by the start and length parameters.
     * This method ensures the string is treated as a byte array by using `mb_substr()`.
     *
     * Note: Adapted from YII2 framework
     *
     * @see https://github.com/yiisoft/yii2/blob/master/LICENSE.md
     *
     * @param string $string The input string. Must be one character or longer.
     * @param int    $start  The starting position
     * @param int    $length The desired portion length. If not specified or `null`, there will be
     *                       no limit on length i.e. the output will be until the end of the string.
     *
     * @return string the extracted part of string, or FALSE on failure or an empty string.
     *
     * @see http://www.php.net/manual/en/function.substr.php
     */
    public static function substr($string, $start, $length = null)
    {
        return mb_substr($string, $start, $length === null ? mb_strlen($string, '8bit') : $length, '8bit');
    }

    /**
     * ASCII representation of the string.
     *
     * @param string      $string           String to transliterate.
     * @param string|null $transliteratorId Transliterator identifier.
     *
     * @return string
     *
     * @see http://php.net/manual/en/transliterator.transliterate.php
     */
    public static function ascii($string, $transliteratorId = '')
    {
        $transliteratorId = $transliteratorId ?: 'Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove';
        return transliterator_transliterate($transliteratorId, $string);
    }

    /**
     * Converts number to its ordinal English form. For example, converts 13 to 13th, 2 to 2nd ...
     *
     * Note: Adapted from YII2 framework
     *
     * @see https://github.com/yiisoft/yii2/blob/master/LICENSE.md
     *
     * @param int $number the number to get its ordinal value
     *
     * @return string
     */
    public static function ordinalize($number)
    {
        if (in_array($number % 100, range(11, 13))) {

            return $number.'th';

        }

        switch ($number % 10) {

            case 1:
                return $number.'st';
            case 2:
                return $number.'nd';
            case 3:
                return $number.'rd';
            default:
                return $number.'th';

        }
    }

    /**
     * Convert a value to camel case.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string $word the word to CamelCase
     *
     * @return string
     */
    public static function camelCase($word)
    {
        if (isset(static::$camelCache[$word])) {

            return static::$camelCache[$word];

        }

        return static::$camelCache[$word] = lcfirst(static::studlyCase($word));
    }

    /**
     * Convert a value to studly caps case.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string $value
     *
     * @return string
     */
    public static function studlyCase($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {

            return static::$studlyCache[$key];

        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {

            if (substr($haystack, -strlen($needle)) === (string) $needle) {

                return true;

            }
        }

        return false;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {

            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {

                return true;

            }
        }

        return false;
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * Note: Adapted from Laravel Framework.
     *
     * @see https://github.com/laravel/framework/blob/5.4/LICENSE.md

     * @param  int  $length
     *
     * @return string
     */
    public static function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {

            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Generate a random UUID version 4.
     *
     * Warning: This method should not be used as a random seed for any cryptographic operations.
     * Instead you should use the openssl or mcrypt extensions.
     *
     * @see http://www.ietf.org/rfc/rfc4122.txt
     *
     * @return string RFC 4122 UUID
     *
     * @copyright Matt Farina MIT License https://github.com/lootils/uuid/blob/master/LICENSE
     */
    public static function uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            // 16 bits for "time_mid"
            mt_rand(0, 65535),
            // 12 bits before the 0100 of (version) 4 for "time_hi_and_version"
            mt_rand(0, 4095) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
        );
    }

    /**
     * Parse a Class@method style callback into class and method.
     *
     * @param  string  $callback
     * @param  string|null  $default
     *
     * @return array
     */
    public static function parseCallback($callback, $default = null)
    {
        return self::contains($callback, '@') ? explode('@', $callback, 2) : [$callback, $default];
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     *
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {

            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {

                return true;
            }
        }

        return false;
    }
}
