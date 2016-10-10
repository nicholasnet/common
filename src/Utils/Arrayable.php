<?php

namespace IdeasBucket\Common\Utils;

/**
 * Interface Arrayable
 *
 * Note: Adapted from Laravel Framework.
 * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
 *
 * @package IdeasBucket\Common\Utils
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}