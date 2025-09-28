<?php

namespace SclNominetEpp\Request\Info;

use SclNominetEpp\Response\Info\Contact as ContactInfoResponse;
use SclNominetEpp\Contact as ContactObject;

/**
 * Page-Level DocBlock
 */

/**
 * This class build the XML for a Nominet EPP contact:info command.
 */
class Contact extends AbstractInfo
{
    const TYPE = 'contact';
    const INFO_NAMESPACE = "urn:ietf:params:xml:ns:contact-1.0";
    const VALUE_NAME = "id";

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            self::INFO_NAMESPACE,
            self::VALUE_NAME,
            new ContactInfoResponse()
        );
    }

    /**
     * Set Contact to the passed ContactObject file.
     *
     * @param ContactObject $object
     */
    public function setContact(ContactObject $object)
    {
        $this->object = $object;
    }

    public function lookup(string $contactID): self
    {
        $contact = new ContactObject();
        $contact->setId($contactID);
        $this->setContact($contact);
        return $this;
    }

    protected function getName()
    {
        return $this->object->getId();
    }
}
