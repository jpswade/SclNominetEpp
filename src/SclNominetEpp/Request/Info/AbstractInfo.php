<?php

/**
 * Contains the nominet AbstractInfo request class definition.
 */

namespace SclNominetEpp\Request\Info;

use SclNominetEpp\Request;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP info command.
 */
abstract class AbstractInfo extends Request
{

    /**
     * The namespace for the Nominet EPP info request.
     */
    protected string $infoNamespace;

    /**
     * The name of the identifying value for the info request
     * (e.g. name or id).
     */
    protected string $valueName;

    /**
     * The domain|contact|host object.
     */
    protected ?object $object = null;

    /**
     * Constructor
     *
     * @param string                $type          The type of info operation.
     * @param string                $infoNamespace The namespace for the info operation.
     * @param string                $valueName     The name of the value being queried.
     * @param SimpleXMLElement|null $response      The response object.
     */
    public function __construct(string $type, string $infoNamespace, string $valueName, $response = null)
    {
        parent::__construct('info', $response);
        $this->type = $type;
        $this->valueName = $valueName;
        $this->infoNamespace = $infoNamespace;
    }

    /**
     * {@inheritDoc}
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    protected function addContent(SimpleXMLElement $action)
    {
        $info = $action->addChild("{$this->type}:info", '', $this->infoNamespace);

        $info->addChild($this->valueName, $this->getName(), $this->infoNamespace);
    }
}
