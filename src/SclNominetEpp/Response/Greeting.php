<?php

namespace SclNominetEpp\Response;

use DateTime;
use DOMDocument;
use SclNominetEpp\Response;
use SclRequestResponse\Exception\InvalidResponsePacketException;
use SclRequestResponse\ResponseInterface;
use SimpleXMLElement;
use SclNominetEpp\Greeting as GreetingObject;

/**
 * This class interprets XML for a Nominet EPP list command response.
 */
class Greeting extends Response
{
    protected GreetingObject $greetingObject;

    /**
     * @throws \Exception
     */
    public function init($data): ResponseInterface
    {
        $data = new SimpleXMLElement($data);

        if (!isset($data->greeting)) {
            throw new InvalidResponsePacketException('XML is not a greeting packet.');
        }

        // Greeting responses don't have result codes - they're always successful
        $this->code = Response::SUCCESS_STANDARD;
        $this->message = 'Hello';

        $this->data = $data;

        $this->processData($data);

        return $this;
    }

    public function xmlValid(SimpleXMLElement $xml): bool
    {
        if (empty($xml->greeting)) {
            return false;
        }
        $dom = new DOMDocument();
        $domDocument = $dom->loadXML($xml->asXML());
        return (bool)$domDocument;
    }

    protected function processData(SimpleXMLElement $xml)
    {
        if (!$this->xmlValid($xml)) {
            return;
        }
        $this->greetingObject = new GreetingObject();
        $this->greetingObject->setServerId($xml->greeting->svID);
        $this->greetingObject->setServerDate(new DateTime($xml->greeting->svDate));
        $serviceMenu = $xml->greeting->svcMenu;
        $this->greetingObject->setVersion($serviceMenu->version);
        $this->greetingObject->setLanguage($serviceMenu->lang);
        $objectURIs = $serviceMenu->children()->objURI;

        foreach ($objectURIs as $objectURI) {
            $this->greetingObject->addObjectURI((string)$objectURI);
        }

        $extensionURIs = $serviceMenu->svcExtension->children()->extURI;

        foreach ($extensionURIs as $extensionURI) {
            $this->greetingObject->addExtensionURI((string)$extensionURI);
        }

        $dataCollectionPolicy = $xml->greeting->dcp;
        $accesses = $dataCollectionPolicy->children()->access->children();
        foreach ($accesses as $access) {
            $this->greetingObject->setAccess($access->getName());
        }

        $statement = $dataCollectionPolicy->statement;

        $purposes = $statement->purpose->children();
        foreach ($purposes as $purpose) {
            $this->greetingObject->addPurpose($purpose->getName());
        }

        $recipients = $statement->recipient->children();
        foreach ($recipients as $recipient) {
            $this->greetingObject->addRecipient($recipient->getName());
        }

        $retention = $statement->retention->children();
        foreach ($retention as $retentionType) {
            $this->greetingObject->setRetention($retentionType->getName());
        }
    }
}
