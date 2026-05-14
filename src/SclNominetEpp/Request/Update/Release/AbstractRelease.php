<?php

namespace SclNominetEpp\Request\Update\Release;

use SclNominetEpp\Request;
use SclRequestResponse\ResponseInterface;
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
     * @param string                $updateNamespace The namespace for the update operation.
     * @param string                $valueName       The name of the value being updated.
     * @param ResponseInterface|null $response        The response object.
     */
    public function __construct(
        string $updateNamespace,
        string $valueName,
        ?ResponseInterface $response = null
    ) {
        parent::__construct('update', $response);
        $this->updateNamespace = $updateNamespace;
        $this->valueName = $valueName;
    }

    /**
     * SetValue
     *
     * @param string $value The value to set.
     * @return static
     */
    public function lookup(string $value): AbstractRelease
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
     * Get the registrar tag.
     *
     * @return string The registrar tag.
     */
    public function getRegistrarTag(): string
    {
        return $this->registrarTag;
    }

    /**
     * Add content to the request form.
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    public function addContent(SimpleXMLElement $action): void
    {
        $releaseNS  = $this->updateNamespace;

        $releaseXSI = $releaseNS . ' ' . 'release-1.0.xsd';

        $update = $action->addChild('r:release', '', $releaseNS);
        $update->addAttribute('xsi:schemaLocation', $releaseXSI);
        $update->addChild($this->valueName, $this->value, $releaseNS);
        $update->addChild('registrarTag', $this->registrarTag);
    }
}
