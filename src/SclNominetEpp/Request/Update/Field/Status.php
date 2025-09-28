<?php

namespace SclNominetEpp\Request\Update\Field;

use SimpleXMLElement;

/**
 * UpdateDomain "add" and "remove" both use "status" as a field
 */
class Status implements UpdateFieldInterface
{
    private $message;
    private $status;

    /**
     * Constructor for Status field.
     *
     * @param string $message The status message.
     * @param string $status  The status value.
     * @return void
     */
    public function __construct(string $message, string $status)
    {
        $this->message = $message;
        $this->status  = $status;
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
        $status = $xml->addChild('status', $this->message, $namespace);
        $status->addAttribute('s', $this->status);
        $status->addAttribute('lang', 'en');
    }
}
