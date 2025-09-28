<?php

namespace SclNominetEpp\Response\Update;

use SclNominetEpp\Response;
use SimpleXMLElement;

/**
 * This class interprets XML for a Nominet EPP unrenew command response.
 */
class Unrenew extends Response
{
    /**
     * Process the XML data for unrenew response.
     *
     * @param SimpleXMLElement $xml The XML element to process.
     * @return void
     */
    public function processData(SimpleXMLElement $xml)
    {
        if (!$this->success()) {
            return;
        }
        if (!$this->xmlValid($xml)) {
            return;
        }
    }
}
