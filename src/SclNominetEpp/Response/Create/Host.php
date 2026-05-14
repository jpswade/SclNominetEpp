<?php

namespace SclNominetEpp\Response\Create;

use SclNominetEpp\Nameserver;

/**
 * This class gives AbstractCreate information to interpret XML
 * for a Nominet EPP host:create command response.
 */
class Host extends AbstractCreate
{
    const TYPE = 'host';
    const VALUE_NAME = 'name';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            new Nameserver(),
            self::VALUE_NAME
        );
    }

    /**
     * Overriding setter of AbstractCreate Response
     *
     * @param string $hostName
     */
    protected function setIdentifier($hostName)
    {
        $this->object->setHostName($hostName);
    }
}
