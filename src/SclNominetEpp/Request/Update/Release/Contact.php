<?php

namespace SclNominetEpp\Request\Update\Release;

use SclNominetEpp\Response\Update\Contact as ContactReleaseResponse;

/**
 * This class build the XML for a Nominet EPP r:release command.
 */
class Contact extends AbstractRelease
{
    const TYPE = 'contact'; //For possible Abstracting later
    const UPDATE_NAMESPACE = 'urn:ietf:params:xml:ns:release-1.0';
    const VALUE_NAME = 'registrant';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            self::UPDATE_NAMESPACE,
            self::VALUE_NAME,
            new ContactReleaseResponse()
        );
    }
}
