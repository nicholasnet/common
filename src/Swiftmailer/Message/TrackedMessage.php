<?php

/**
 * TrackedMessage.php.
 *
 * @author Nirmal <nirmalp@hotmail.com>
 */
namespace IdeasBucket\Common\Swiftmailer\Message;

/**
 * Class TrackedMessage.
 */
class TrackedMessage extends \Swift_Message
{
    /**
     * Campaign Id.
     *
     * @var string
     */
    protected $campaignId;

    /**
     * Message Description.
     *
     * @var string
     */
    protected $description;

    /**
     * @return string
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @param string $campaignId
     *
     * @return TrackedMessage
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return TrackedMessage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Create a new Message.
     *
     * @param string $subject
     * @param string $body
     * @param string $contentType
     * @param string $charset
     *
     * @return TrackedMessage
     */
    public static function newInstance($subject = null, $body = null, $contentType = null, $charset = null)
    {
        return new self($subject, $body, $contentType, $charset);
    }
}
