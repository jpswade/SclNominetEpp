<?php

namespace SclNominetEpp\Request\Update;

use SclNominetEpp\Response\Update\ContactID as UpdateContactIDResponse;
use SclNominetEpp\Request;
use SclNominetEpp\Request\Update\Field\UpdateFieldInterface;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP contact:update command.
 */
class ContactID extends Request
{
    const TYPE = 'contact'; //For possible Abstracting later
    const UPDATE_NAMESPACE = 'urn:ietf:params:xml:ns:contact-1.0';

    const VALUE_NAME = 'id';

    protected $value;
    protected string $contactID;

    /**
     * Array of fields to add in the update request.
     *
     * @var UpdateFieldInterface[]
     */
    public array $add = [];

    /**
     * Array of fields to remove in the update request.
     *
     * @var UpdateFieldInterface[]
     */
    public array $remove = [];

    /**
     * Constructor for ContactID update request.
     *
     * @param string $value The contact ID value.
     */
    public function __construct(string $value)
    {
        parent::__construct('update', new UpdateContactIDResponse());
        $this->value = $value;
    }

    /**
     * The <b>add()</b> function assigns a Field object as an element of the add array
     * for including specific fields in the update request "contactID:add" tag.
     *
     * @param \SclNominetEpp\Request\Update\Field\UpdateFieldInterface $field The field to add.
     * @return void
     */
    public function add(UpdateFieldInterface $field)
    {
        $this->add[] = $field;
    }

    /**
     * The <b>remove()</b> function assigns a Field object as an element of the remove array
     * for including specific fields in the update request "contactID:remove" tag.
     *
     * @param \SclNominetEpp\Request\Update\Field\UpdateFieldInterface $field The field to remove.
     * @return void
     */
    public function remove(UpdateFieldInterface $field)
    {
        $this->remove[] = $field;
    }

    /**
     * Add content to the XML request.
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    public function addContent(SimpleXMLElement $action)
    {
        $contactNS   = self::UPDATE_NAMESPACE;

        $contactXSI   =   $contactNS . ' ' . 'contact-id-1.0.xsd';

        $update = $action->addChild('contact-id:update', '', $contactNS);
        $update->addAttribute('xsi:schemaLocation', $contactXSI);
        $update->addChild(self::VALUE_NAME, $this->contactID, self::UPDATE_NAMESPACE);
        $change = $update->addChild('chg');
        $change->addChild(self::VALUE_NAME, $this->value);
    }

    /**
     * Set the contact ID for the update request.
     *
     * @param string $contactID The contact ID to set.
     * @return void
     */
    public function setContactID(string $contactID)
    {
        $this->contactID = $contactID;
    }
}
