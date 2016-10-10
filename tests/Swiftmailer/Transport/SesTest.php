<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use Swift_Message;

class SesTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $message = new Swift_Message('Foo subject', 'Bar body');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');
        $message->setBcc('you@example.com');

        $client = $this->getMockBuilder('Aws\Ses\SesClient')
                       ->setMethods(['sendRawEmail'])
                       ->disableOriginalConstructor()
                       ->getMock();

        $transport = new Ses($client);

        $client->expects($this->once())
               ->method('sendRawEmail')
               ->with($this->equalTo([
                   'Source'     => 'myself@example.com',
                   'RawMessage' => ['Data' => (string) $message],
               ]));
        $transport->send($message);
    }
}
