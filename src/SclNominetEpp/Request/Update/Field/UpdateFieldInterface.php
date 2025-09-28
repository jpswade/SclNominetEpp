<?php

namespace SclNominetEpp\Request\Update\Field;

use SimpleXMLElement;

/**
 * Details the functions required for an UpdateField (fields like status)
 */
interface UpdateFieldInterface
{
    /**
     * Generate XML for the field.
     *
     * @param SimpleXMLElement $xml       The XML element to add to.
     * @param string|null      $namespace The namespace to use.
     * @return void
     */
    public function fieldXml(SimpleXMLElement $xml, string $namespace = null);
}
