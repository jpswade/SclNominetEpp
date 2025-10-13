<?php

/**
 * Contains the Nominet Renew request class definition.
 */

namespace SclNominetEpp\Request;

use DateTimeInterface;
use SclNominetEpp\Request;
use SclNominetEpp\Response\Renew as RenewResponse;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP renew command.
 */
class Renew extends Request
{
    /** @const int Default renewal period. */
    protected const DEFAULT_PERIOD = 1;

    /** @const int Default renewal unit, years. */
    protected const DEFAULT_UNIT = 'y';

    /** @var string Format of the atomic type 'xs:date', eg: 2009-04-07 */
    protected const CURRENT_EXPIRY_DATE_FORMAT = 'Y-m-d';

    /**
     * The domain name.
     */
    protected string $domain;

    /**
     * The expiry date.
     */
    protected ?DateTimeInterface $currentExpiryDate = null;

    /**
     * The period to register for.
     */
    protected int $period = self::DEFAULT_PERIOD;

    /**
     * The unit used for the period.
     */
    protected string $unit = self::DEFAULT_UNIT;

    /**
     * Tells the parent class what the action of this request is.
     */
    public function __construct(?string $domain = null)
    {
        parent::__construct('renew', new RenewResponse());
        if ($domain) {
            $this->domain = $domain;
        }
    }

    /**
     * Set the domain
     */
    public function setDomain(string $domain, ?DateTimeInterface $currentExpiryDate = null): Renew
    {
        $this->domain = $domain;

        if ($currentExpiryDate) {
            $this->setDate($currentExpiryDate);
        }

        return $this;
    }

    /**
     * Set the date
     */
    public function setDate(?DateTimeInterface $currentExpiryDate = null): Renew
    {
        $this->currentExpiryDate = $currentExpiryDate;
        return $this;
    }

    /**
     * Set the period
     */
    public function setPeriod(int $period, ?string $unit = null): Renew
    {
        $this->period = $period;
        if ($unit) {
            $this->setUnit($unit);
        }
        return $this;
    }

    /**
     * Set the unit
     */
    public function setUnit(string $unit): Renew
    {
        $this->unit = $unit;
        return $this;
    }

    public function getCurrentExpiryDate(string $format = self::CURRENT_EXPIRY_DATE_FORMAT): string
    {
        if ($this->currentExpiryDate === null) {
            throw new \InvalidArgumentException('Current Expiry Date is required.');
        }
        return $this->currentExpiryDate->format($format);
    }

    protected function addContent(SimpleXMLElement $action)
    {
        $domainNS = 'urn:ietf:params:xml:ns:domain-1.0';
        $domainXSI = $domainNS . ' domain-1.0.xsd';

        $domainRenew = $action->addChild('domain:renew', '', $domainNS);
        $domainRenew->addAttribute('xsi:schemaLocation', $domainXSI, self::XSI_NAMESPACE);
        $domainRenew->addChild('name', $this->domain);
        $domainRenew->addChild('curExpDate', $this->getCurrentExpiryDate());
        $period = $domainRenew->addChild('period', $this->period);
        $period->addAttribute('unit', $this->unit);
    }
}
