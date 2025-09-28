<?php

/**
 * Contains the nominet CheckContact request class definition.
 */

namespace SclNominetEpp\Request\Check;

use SclNominetEpp\Response\Check\Contact as CheckContactResponse;

/**
 * This class build the XML for a Nominet EPP contact:check command.
 */
class Contact extends AbstractCheck
{
    const TYPE = 'contact';
    const CHECK_NAMESPACE = 'urn:ietf:params:xml:ns:contact-1.0';
    const VALUE_NAME = 'id';

    /**
     * Tells the parent class what the action of this request is.
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            self::CHECK_NAMESPACE,
            self::VALUE_NAME,
            new CheckContactResponse()
        );
    }

    /**
     * Set the contact IDs to check.
     *
     * @param array $contactIds The contact IDs to check.
     * @return void
     */
    public function setValues(array $contactIds)
    {
        $this->lookup($contactIds);
    }
}
