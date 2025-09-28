<?php

namespace SclNominetEpp\Response;

use SclNominetEpp\Response;
use SimpleXMLElement;

/**
 * This class interprets XML for a Nominet EPP list command response.
 */
class ListDomains extends Response
{
    public const LIST_MONTH = 1;
    public const LIST_EXPIRY = 2;

    protected array $domains = [];

    /**
     * Process the XML data for list domains response.
     *
     * @param SimpleXMLElement $xml The XML element to process.
     * @return void
     */
    protected function processData(SimpleXMLElement $xml): void
    {
        if (!$this->success()) {
            return;
        }
        if (!$this->xmlValid($xml)) {
            return;
        }

        $ns = $xml->getNamespaces(true);

        $domains = $xml->response->resData->children($ns['list'])->listData;

        foreach ($domains->domainName as $domain) {
            $this->domains[] = (string)$domain;
        }
    }

    /**
     * Get the list of domains.
     *
     * @return array The array of domain names.
     */
    public function getDomains(): array
    {
        return $this->domains;
    }
}
