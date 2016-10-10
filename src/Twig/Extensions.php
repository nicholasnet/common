<?php

namespace IdeasBucket\Common\Twig;

use IdeasBucket\Common\Utils\StringHelper;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Class Extensions
 */
class Extensions extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('bin2hex', [$this, 'bin2hexFilter']),
            new Twig_SimpleFilter('slug', [$this, 'slugFilter']),
            new Twig_SimpleFilter('md5', [$this, 'md5Filter']),
        ];
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function md5Filter($string)
    {
        return md5($string);
    }

    /**
     * @param string $string
     * @param bool   $convertCase
     * @param string $separator
     *
     * @return string
     */
    public function slugFilter($string, $convertCase = true, $separator = '-')
    {
        return StringHelper::slug($string, $convertCase, $separator);
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function bin2hexFilter($string)
    {
        return bin2hex($string);
    }
}