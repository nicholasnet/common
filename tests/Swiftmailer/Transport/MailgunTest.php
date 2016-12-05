<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use IdeasBucket\Common\Swiftmailer\Message\TrackedMessage;

class MailgunTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $message = new TrackedMessage('Foo subject', 'Bar body');
        $message->setCampaignId('test');
        $message->setDescription('test');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');
        $message->setCc('me@example.com');

        $message->setBcc('me@example.com');

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                       ->setMethods(['send', 'sendAsync', 'request', 'requestAsync', 'getConfig', 'post'])
                       ->disableOriginalConstructor()
                       ->getMock();

        $transport = new Mailgun($client, 'test', 'test');

        $client->expects($this->once())
               ->method('post')
               ->with('https://api.mailgun.net/v3/test/messages.mime', $this->arrayHasKey('auth'));

        $transport->send($message);
    }

    public function testGetterSetter()
    {
        $message = new TrackedMessage('Foo subject', 'Bar body');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                       ->setMethods(['send', 'sendAsync', 'request', 'requestAsync', 'getConfig', 'post'])
                       ->disableOriginalConstructor()
                       ->getMock();

        $transport = new Mailgun($client, 'test', 'test');
        $transport->setKey('another');
        $this->assertEquals('another', $transport->getKey());
    }
}
