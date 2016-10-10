<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

class MailtrapTest extends \PHPUnit_Framework_TestCase
{
    public function testCanSetUsernamePasswordAndAuthMode()
    {
        $transport = new Mailtrap('test', 'test');

        $this->assertEquals('test', $transport->getUsername());
        $this->assertEquals('test', $transport->getPassword());
        $this->assertEquals('cram-md5', $transport->getAuthMode());
    }
}
