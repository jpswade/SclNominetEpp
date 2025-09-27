<?php

/**
 * Contains the nominet CheckHost request class definition.
 */

namespace SclNominetEpp\Request\Check;

use SclNominetEpp\Response\Check\Host as CheckHostResponse;

/**
 * This class build the XML for a Nominet EPP host:check command.
 */
class Host extends AbstractCheck
{
    const TYPE = 'host';
    const CHECK_NAMESPACE = 'urn:ietf:params:xml:ns:host-1.0';
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
            new CheckHostResponse()
        );
    }

    public function setValues(array $hosts)
    {
        // @todo
    }
}
