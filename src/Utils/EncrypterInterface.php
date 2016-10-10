<?php
/**
 * Created by PhpStorm.
 * User: minty
 * Date: 10/6/16
 * Time: 12:35 PM
 */

namespace IdeasBucket\Common\Utils;


interface EncrypterInterface
{
    /**
     * Encrypt the given value.
     *
     * @param  string  $value
     * @return string
     */
    public function encrypt($value);
    /**
     * Decrypt the given value.
     *
     * @param  string  $payload
     * @return string
     */
    public function decrypt($payload);
}