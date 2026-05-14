<?php

namespace SclNominetEpp;

use DateTime;

/**
 * This class represents the data of a poll response in an object.
 */
class Poll
{
    /**
     * Number of messages left unacknowledged in the queue.
     *
     * @var int
     */
    protected $count;

    /**
     * Poll message identifier from the queue.
     *
     * @var string
     */
    protected $queueMessageId;

    /**
     * Date of the message in the Queue.
     *
     * @var DateTime
     */
    protected $queueDate;

    /**
     * Poll Message.
     *
     * @var string
     */
    protected $message;


    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param integer $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getId()
    {
        return $this->queueMessageId;
    }

    /**
     * @param string $queueMessageId
     */
    public function setId(string $queueMessageId): void
    {
        $this->queueMessageId = $queueMessageId;
    }

    public function getQueueDate()
    {
        return $this->queueDate;
    }

    /**
     * @param DateTime $queueDate
     */
    public function setQueueDate(DateTime $queueDate): void
    {
        $this->queueDate = $queueDate;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
