<?php

namespace SclNominetEpp\Response\Update;

use DateTime;
use SclNominetEpp\Response;
use SimpleXMLElement;

/**
 * This class interprets XML for a Nominet EPP fork command response.
 */
class Fork extends Response
{
    /**
     * New contact identifier.
     */
    protected string $contactId = '';

    /**
     * The date of contact creation.
     */
    protected ?DateTime $createDate = null;

    /**
     * Process the XML data for fork response.
     *
     * @param SimpleXMLElement $xml The XML element to process.
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

        $contactDetails = $xml->response->resData->children($ns['contact'])->creData;
        $this->contactId = (string) $contactDetails->id;
        $crDate = (string) $contactDetails->crDate;
        $this->createDate = $crDate !== '' ? new DateTime($crDate) : null;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function getCreateDate(): ?DateTime
    {
        return $this->createDate;
    }
}
