<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use GuzzleHttp\ClientInterface;
use IdeasBucket\Common\Swiftmailer\Message\TrackedMessage;
use Swift_Mime_Message;

/**
 * Class SparkPost.
 *
 * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
 */
class SparkPost extends AbstractTransport
{
    /**
     * Guzzle client instance.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * The SparkPost API key.
     *
     * @var string
     */
    protected $key;

    /**
     * Transmission options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Create a new SparkPost transport instance.
     *
     * @param ClientInterface $client
     * @param string          $key
     * @param array           $options
     */
    public function __construct(ClientInterface $client, $key, $options = [])
    {
        $this->key = $key;
        $this->client = $client;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $recipients = $this->getRecipients($message);

        $message->setBcc([]);

        $options = [
            'headers' => [
                'Authorization' => $this->key,
            ],
            'json'    => [
                'recipients' => $recipients,
                'content'    => [
                    'email_rfc822' => $message->toString(),
                ],
            ],
        ];

        if (!empty($this->options)) {
            $options['json']['options'] = $this->options;
        }

        if ($message instanceof TrackedMessage) {
            $campaignId = $message->getCampaignId();
            $description = $message->getDescription();

            if (!empty($campaignId)) {
                $options['json']['campaign_id'] = $campaignId;
            }

            if (!empty($description)) {
                $options['json']['description'] = $description;
            }
        }

        $this->client->post('https://api.sparkpost.com/api/v1/transmissions', $options);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get all the addresses this message should be sent to.
     *
     * Note that SparkPost still respects CC, BCC headers in raw message itself.
     *
     * @param \Swift_Mime_Message $message
     *
     * @return array
     */
    protected function getRecipients(Swift_Mime_Message $message)
    {
        $to = [];

        if ($message->getTo()) {
            $to = array_merge($to, array_keys($message->getTo()));
        }

        if ($message->getCc()) {
            $to = array_merge($to, array_keys($message->getCc()));
        }

        if ($message->getBcc()) {
            $to = array_merge($to, array_keys($message->getBcc()));
        }

        $recipients = array_map(function ($address) {
            return compact('address');
        }, $to);

        return $recipients;
    }

    /**
     * Get the API key being used by the transport.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the API key being used by the transport.
     *
     * @param string $key
     *
     * @return string
     */
    public function setKey($key)
    {
        return $this->key = $key;
    }
}
