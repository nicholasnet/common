<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use IdeasBucket\Common\Utils\StringHelper;

class SesTest extends \PHPUnit_Framework_TestCase
{
    public function testSend()
    {
        $message = new \Swift_Message('Foo subject', 'Bar body');
        $message->setSender('myself@example.com');
        $message->setTo('me@example.com');
        $message->setBcc('you@example.com');

        $client = $this->getMockBuilder('Aws\Ses\SesClient')
                       ->setMethods(['sendRawEmail'])
                       ->disableOriginalConstructor()
                       ->getMock();
        $transport = new Ses($client);

        // Generate a messageId for our mock to return to ensure that the post-sent message
        // has X-SES-Message-ID in its headers
        $messageId = StringHelper::uuid();
        $sendRawEmailMock = new sendRawEmailMock($messageId);
        $client->expects($this->once())
               ->method('sendRawEmail')
               ->with($this->equalTo([
                   'Source' => 'myself@example.com',
                   'RawMessage' => ['Data' => (string) $message],
               ]))
               ->willReturn($sendRawEmailMock);

        $transport->send($message);
        $this->assertEquals($messageId, $message->getHeaders()->get('X-SES-Message-ID')->getFieldBody());
    }
}

class sendRawEmailMock
{
    protected $getResponse = null;

    public function __construct($responseValue)
    {
        $this->getResponse = $responseValue;
    }

    /**
     * Mock the get() call for the sendRawEmail response.
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function get($key)
    {
        return $this->getResponse;
    }
}