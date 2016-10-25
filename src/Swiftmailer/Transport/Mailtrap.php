<?php

/**
 * TrackedMessage.php.
 *
 * @author Nirmal <nirmalp@hotmail.com>
 */
namespace IdeasBucket\Common\Swiftmailer\Transport;

/**
 * Class Mailtrap.
 */
class Mailtrap extends \Swift_SmtpTransport
{
    /**
     * Mailtrap constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        parent::__construct('mailtrap.io', 2525, null);

        $this->setAuthMode('cram-md5');
        $this->setUsername((string) $username);
        $this->setPassword($password);
    }
}
