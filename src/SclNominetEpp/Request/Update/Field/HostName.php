<?php

namespace SclNominetEpp\Request\Update\Field;

use SimpleXMLElement;

/**
 * UpdateDomain "add" and "remove" both use "status" as a field
 */
class HostName implements UpdateFieldInterface
{
    private $nameserver;

    /**
     * Constructor for HostName field.
     *
     * @param string $nameserver The nameserver hostname.
     * @return void
     */
    public function __construct(string $nameserver)
    {
        $this->nameserver = $nameserver;
    }

    /**
     * Generate XML for the field.
     *
     * @param SimpleXMLElement $xml       The XML element to add to.
     * @param string|null      $namespace The namespace to use.
     * @return void
     */
    public function fieldXml(SimpleXMLElement $xml, string $namespace = null)
    {
        $xml->addChild('name', $this->nameserver, $namespace);
    }
}
