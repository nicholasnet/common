<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use IdeasBucket\Common\Swiftmailer\Message\TrackedMessage;

class SparkPostTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $message = TrackedMessage::newInstance('Foo subject', 'Bar body');
        $message->setCampaignId('test');
        $message->setDescription('test');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');
        $message->setCc('me@example.com');

        $expected = $message->toString();

        $message->setBcc('me@example.com');

        $client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->setMethods(['send', 'sendAsync', 'request', 'requestAsync', 'getConfig', 'post'])
            ->disableOriginalConstructor()
            ->getMock();

        $transport = new SparkPost($client, 'test');

        $client->expects($this->once())
               ->method('post')
                ->with('https://api.sparkpost.com/api/v1/transmissions', $this->equalTo([
                    'headers' => [
                        'Authorization' => 'test',
                    ],
                    'json'    => [
                        'recipients' => [
                            ['address' => 'me@example.com'],
                            ['address' => 'me@example.com'],
                            ['address' => 'me@example.com'],
                        ],
                        'content'    => [
                            'email_rfc822' => $expected,
                        ],
                        'campaign_id' => 'test',
                        'description' => 'test',
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

        $transport = new SparkPost($client, 'test');
        $transport->setKey('another');
        $this->assertEquals('another', $transport->getKey());
    }
}
