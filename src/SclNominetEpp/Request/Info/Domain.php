<?php

namespace SclNominetEpp\Request\Info;

use SclNominetEpp\Response\Info\Domain as DomainInfoResponse;
use SclNominetEpp\Domain as DomainObject;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP domain:info command.
 */
class Domain extends AbstractInfo
{
    const TYPE = 'domain';
    const INFO_NAMESPACE = "urn:ietf:params:xml:ns:domain-1.0";
    const VALUE_NAME = "name";

    /**
     * The object.
     */
    protected ?object $object = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            self::INFO_NAMESPACE,
            self::VALUE_NAME,
            new DomainInfoResponse()
        );
    }

    public function lookup(string $domainName): Domain
    {
        $domain = new DomainObject();
        $domain->setName($domainName);
        $this->setDomain($domain);
        return $this;
    }

    /**
     * Add content to the request form.
     *
     * @param SimpleXMLElement $action
     */
    protected function addContent(\SimpleXMLElement $action): void
    {
        $info = $action->addChild("{$this->type}:info", '', $this->infoNamespace);

        $name = $info->addChild($this->valueName, $this->getName(), $this->infoNamespace);
        $name->addAttribute('hosts', 'all');
    }

    /**
     * Set Domain.
     *
     * @param DomainObject $object
     */
    public function setDomain(DomainObject $object)
    {
        $this->object = $object;
    }

    protected function getName(): string
    {
        return $this->object->getName();
    }
}
