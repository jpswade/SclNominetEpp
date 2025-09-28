<?php

namespace SclNominetEpp;

use DateTime;

/**
 * This class is the fork object for the fork command response data
 */
class Fork
{
    /**
     * New contact identifier.
     */
    protected string $contactId;

    /**
     * The Date of contact creation.
     */
    protected DateTime $createDate;

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId)
    {
        $this->contactId = $contactId;
    }

    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTime $createDate)
    {
        $this->createDate = $createDate;
    }
}
