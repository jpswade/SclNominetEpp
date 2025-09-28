<?php

namespace SclNominetEpp\Request\Update\Release;

/**
 * This class build the XML for a Nominet EPP r:release command.
 */
class Domain extends AbstractRelease
{
    const TYPE = 'domain'; //For possible Abstracting later
    const UPDATE_NAMESPACE = 'urn:ietf:params:xml:ns:release-1.0';
    const VALUE_NAME = 'domainName';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            self::UPDATE_NAMESPACE,
            self::VALUE_NAME,
            new Domain()
        );
    }
}
