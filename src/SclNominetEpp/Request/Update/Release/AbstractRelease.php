<?php

namespace SclNominetEpp\Request\Update\Release;

use SclNominetEpp\Request;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP r:release command.
 */
abstract class AbstractRelease extends Request
{
    /**
     * The value of the check Identifier.
     */
    protected string $value = '';

    /**
     * The type of check this is.
     */
    private string $type;

    /**
     * The namespace of update.
     */
    private string $updateNamespace;

    /**
     * The name of the check Identifier (e.g. 'id', 'name').
     */
    private string $valueName;

    /**
     * This is the tag the domain name is currently on.
     * When used with a release or transfer operation,
     * this is the tag of the registrar receiving the domain name.
     */
    private string $registrarTag;

    /**
     * Constructor
     *
     * @param string                $type            The type of release operation.
     * @param string                $updateNamespace The namespace for the update operation.
     * @param string                $valueName       The name of the value being updated.
     * @param SimpleXMLElement|null $response        The response object.
     */
    public function __construct(string $type, string $updateNamespace, string $valueName, $response = null)
    {
        parent::__construct('update', $response);
        $this->type  = $type;
        $this->updateNamespace = $updateNamespace;
        $this->valueName = $valueName;
    }

    /**
     * SetValue
     *
     * @param string $value The value to set.
     * @return static
     */
    public function lookup(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set the tag for the current domain name
     *
     * @param string $registrarTag The registrar tag.
     * @return void
     */
    public function setRegistrarTag(string $registrarTag): void
    {
        $this->registrarTag = $registrarTag;
    }

    /**
     * Add content to the request form.
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    public function addContent(SimpleXMLElement $action)
    {
        $releaseNS  = $this->updateNamespace;

        $releaseXSI = $releaseNS . ' ' . 'release-1.0.xsd';

        $update = $action->addChild('r:release', '', $releaseNS);
        $update->addAttribute('xsi:schemaLocation', $releaseXSI);
        $update->addChild($this->valueName, $this->value, $releaseNS);
        $update->addChild('registrarTag', $this->registrarTag);
    }
}
