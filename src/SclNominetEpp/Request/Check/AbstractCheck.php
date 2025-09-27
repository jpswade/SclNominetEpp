<?php

namespace SclNominetEpp\Request\Check;

use SclNominetEpp\Request;
use SclNominetEpp\Response;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP check command.
 */
abstract class AbstractCheck extends Request
{
    /**
     * The type of check this is.
     */
    private string $type;

    /**
     * The namespace for the Nominet EPP check request.
     */
    private string $checkNamespace;

    /**
     * The name of the identifying value for the check request
     * (e.g. name or id).
     */
    private string $valueName;

    /**
     * Array of values to check.
     */
    private array $values = [];

    /**
     * Constructor.
     *
     * @param string        $type           The type of check operation.
     * @param string        $checkNamespace The namespace for the check operation.
     * @param string        $valueName      The name of the value being checked.
     * @param Response|null $response       The response object.
     */
    public function __construct(string $type, string $checkNamespace, string $valueName, Response $response = null)
    {
        parent::__construct('check', $response);

        $this->type           = $type;
        $this->checkNamespace = $checkNamespace;
        $this->valueName      = $valueName;
    }

    /**
     * The values to lookup.
     *
     * @param array|string $values The values to check.
     * @return static
     */
    public function lookup($values): AbstractCheck
    {
        if (is_array($values)) {
            $this->values = $values;
        } else {
            $this->values = [$values];
        }

        return $this;
    }

    /**
     * Add content to the XML request.
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    protected function addContent(SimpleXMLElement $action): void
    {
        $check = $action->addChild("{$this->type}:check", '', $this->checkNamespace);

        foreach ($this->values as $value) {
            $check->addChild($this->valueName, $value, $this->checkNamespace);
        }
    }
}
