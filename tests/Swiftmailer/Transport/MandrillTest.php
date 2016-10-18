<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use IdeasBucket\Common\Swiftmailer\Message\TrackedMessage;

class MandrillTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $message = new TrackedMessage('Foo subject', 'Bar body');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');
        $message->setCc('me@example.com');
        $message->setBcc('me@example.com');

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
                       ->setMethods(['send', 'sendAsync', 'request', 'requestAsync', 'getConfig', 'post'])
                       ->disableOriginalConstructor()
                       ->getMock();

        $transport = new Mandrill($client, 'test');

        $client->expects($this->once())
               ->method('post')
               ->with('https://mandrillapp.com/api/1.0/messages/send-raw.json', $this->equalTo([
                    'form_params' => [
                        'key'         => 'test',
                        'to'          => ['me@example.com', 'me@example.com', 'me@example.com'],
                        'raw_message' => $message->toString(),
                        'async'       => true
                    ],
               ]));

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

        $transport = new Mandrill($client, 'test');
        $transport->setKey('another');
        $this->assertEquals('another', $transport->getKey());
    }
}
