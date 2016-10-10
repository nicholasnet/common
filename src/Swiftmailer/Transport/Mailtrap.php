<?php

/**
 * TrackedMessage.php
 *
 * @author Nirmal <nirmalp@hotmail.com>
 */
namespace IdeasBucket\Common\Swiftmailer\Transport;

/**
 * Class Mailtrap
 *
 * @package IdeasBucket\Common\Swiftmailer\Transport
 */
class Mailtrap extends \Swift_SmtpTransport
{
    /**
     * Mailtrap constructor.
     * @param \Swift_Transport_IoBuffer $username
     * @param array|\Swift_Transport_EsmtpHandler[] $password
     */
    public function __construct($username, $password)
    {
        parent::__construct('mailtrap.io', 2525, null);

        $this->setAuthMode('cram-md5');
        $this->setUsername($username);
        $this->setPassword($password);
    }
}