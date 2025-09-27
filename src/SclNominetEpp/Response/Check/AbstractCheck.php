<?php

/**
 * Contains the nominet AbstractCheck response class definition.
 */

namespace SclNominetEpp\Response\Check;

use SclNominetEpp\Response;
use SimpleXMLElement;

/**
 * This class interprets XML for a Nominet EPP check command response.
 */
abstract class AbstractCheck extends Response
{
    /**
     * Type is the "check request type" (contact/domain/host)
     *
     * @var string
     */
    private $type;

    /**
     * Value Name is the name of the identifying value, "valueName" (name/id)
     *
     * @var string
     */
    private $valueName;

    /**
     *
     *
     * @var array
     */
    private $values = [];

    /**
     * Constructor
     *
     * @param string $type
     * @param string $valueName
     */
    public function __construct($type, $valueName)
    {
        $this->type = $type;
        $this->valueName = $valueName;
    }

    /**
     * {@inheritDoc}
     *
     * @param SimpleXMLElement $data
     * @return void
     */
    protected function processData(SimpleXMLElement $xml)
    {
        if (!$this->success()) {
            return;
        }
        if (!$this->xmlValid($xml)) {
            return;
        }

        $ns = $xml->getNamespaces(true);

        $xmlValues = $xml->response->resData->children($ns[$this->type]);

        $valueName = $this->valueName;
        foreach ($xmlValues->chkData->cd as $value) {
            $available = (bool) (string) $value->$valueName->attributes()->avail;
            $this->values[(string) $value->$valueName] = $available;
        }
    }

    /**
     * Get $this->values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
