<?php

namespace SclNominetEpp\Traits;

use DateTime;

trait UpDateTrait
{
    /**
     * The date and time of the most recent object modification.
     */
    private ?DateTime $upDate;

    /**
     * Get the date the object was last changed.
     */
    public function getUpDate(): ?DateTime
    {
        return $this->upDate;
    }

    /**
     * Set the date the object was last changed.
     */
    public function setUpDate(?DateTime $upDate)
    {
        $this->upDate = $upDate;
    }
}
