<?php

/**
 * Contains the nominet List request class definition.
 */

namespace SclNominetEpp\Request;

use SclNominetEpp\Request;
use SclNominetEpp\Response\ListDomains as ListDomainsResponse;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP list command.
 */
class ListDomains extends Request
{
    /**
     * Year-month period for the list request (e.g. "2024-3").
     */
    protected string $month;

    /**
     * Tells the parent class what the action of this request is.
     */
    public function __construct()
    {
        parent::__construct('info', new ListDomainsResponse());
    }

    public function setDate($month, $year): ListDomains
    {
        $this->month = $year . '-' . $month;

        return $this;
    }

    protected function addContent(SimpleXMLElement $action)
    {

        $lNS = 'http://www.nominet.org.uk/epp/xml/std-list-1.0';

        $listXSI = $lNS . ' std-list-1.0.xsd';

        $domainList = $action->addChild('l:list', '', $lNS);

        $domainList->addAttribute('xsi:schemaLocation', $listXSI, self::XSI_NAMESPACE);

        $domainList->addChild('month', $this->month);
    }
}
