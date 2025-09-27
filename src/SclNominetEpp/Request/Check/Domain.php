<?php

/**
 * Contains the nominet CheckDomain request class definition.
 */

namespace SclNominetEpp\Request\Check;

use SclNominetEpp\Response\Check\Domain as CheckDomainResponse;

/**
 * This class build the XML for a Nominet EPP domain:check command.
 */
class Domain extends AbstractCheck
{
    const TYPE = 'domain';
    const CHECK_NAMESPACE = 'urn:ietf:params:xml:ns:domain-1.0';
    const VALUE_NAME = 'name';

    /**
     * Tells the parent class what the action of this request is.
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            self::CHECK_NAMESPACE,
            self::VALUE_NAME,
            new CheckDomainResponse()
        );
    }
}
