<?php

namespace SclNominetEpp\Request\Update\Field;

/**
 * UpdateDomain "add" and "remove" both use "status" as a field
 */
class HostAddress implements UpdateFieldInterface
{
    private $address;
    private $version;

    /**
     * Constructor for HostAddress field.
     *
     * @param string $address The IP address.
     * @param string $version The IP version (v4 or v6).
     * @return void
     */
    public function __construct(string $address, string $version)
    {
        $this->address = $address;
        $this->version  = $version;
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
        $address = $xml->addChild('addr', $this->address, $namespace);
        $address->addAttribute('ip', $this->version);
    }
}
