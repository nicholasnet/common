<?php
/**
 * Created by PhpStorm.
 * User: minty
 * Date: 10/3/16
 * Time: 2:06 PM.
 */
namespace IdeasBucket\Common\Utils;

/**
 * Interface Jsonable.
 *
 * Note: Adapted from Laravel Framework.
 *
 * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
 */
interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0);
}
