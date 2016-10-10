<?php

namespace IdeasBucket\Common\Swiftmailer\Transport;

use Aws\Ses\SesClient;
use Swift_Mime_Message;

/**
 * Class Ses
 *
 * @package IdeasBucket\Common\Swiftmailer\Transport
 *
 * Note: Adapted from Laravel Framework.
 * @see https://github.com/laravel/framework/blob/5.3/LICENSE.md
 */
class Ses extends AbstractTransport
{
    /**
     * The Amazon SES instance.
     *
     * @var \Aws\Ses\SesClient
     */
    protected $ses;

    /**
     * Create a new SES transport instance.
     *
     * @param  SesClient  $ses
     */
    public function __construct(SesClient $ses)
    {
        $this->ses = $ses;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $this->ses->sendRawEmail([
            'Source' => key($message->getSender() ?: $message->getFrom()),
            'RawMessage' => [
                'Data' => $message->toString(),
            ],
        ]);

        return $this->numberOfRecipients($message);
    }
}