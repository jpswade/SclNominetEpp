<?php

namespace SclNominetEpp\Response\Update;

use SclNominetEpp\Contact as ContactObject;
use SclNominetEpp\Response;

/**
 * Response handler for contact update operations.
 */
class Contact extends AbstractUpdate
{
    /**
     * Parsed contact from the update response, when implemented.
     */
    public function getContact(): ?ContactObject
    {
        return null;
    }
}
