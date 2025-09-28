<?php

namespace SclNominetEpp\Response\Info;

use SclNominetEpp\Nameserver;
use SimpleXMLElement;

/**
 * This class interprets XML for a Nominet EPP host:info command response.
 */
class Host extends AbstractInfo
{
    const TYPE = 'host';
    const VALUE_NAME = 'name';

    /**
     * Constructor for Host info response.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            new Nameserver(),
            self::VALUE_NAME
        );
    }

    /**
     * Populate status array from XML data.
     *
     * @param SimpleXMLElement $infData The information data element.
     * @return void
     */
    public function statusArrPopulate(SimpleXMLElement $infData)
    {
        if (null === $infData->status) {
            return;
        }
        foreach ($infData->status as $s) {
            if (null !== $s->attributes()->s) {
                $this->object->addStatus($s->attributes()->s);
            } else {
                $this->object->addStatus('ok');
            }
        }
    }

    /**
     * ipCheck finds, for all addresses in host,
     * the addresses which are ip "v4" and ip "v6" and
     * associates them to the host object,
     * If there is no "ip" attribute it defaults to "v4".
     *
     * @param SimpleXMLElement $infData The information data element.
     * @return void
     */
    public function ipCheck(SimpleXMLElement $infData)
    {
        if (!isset($infData->addr)) {
            return;
        }

        foreach ($infData->addr as $address) {
            $type = 'v4';
            if (isset($address->attributes()->ip)) {
                $type = $address->attributes()->ip;
            }
            if ('v6' == $type) {
                $this->object->setIpv6($address);
            } else {
                $this->object->setIpv4($address);
            }
        }
    }

    /**
     * Get the host nameserver object.
     *
     * @return Nameserver|null The nameserver object or null.
     */
    public function getHost(): ?Nameserver
    {
        return $this->object;
    }

    /**
     * Add information data to the host object.
     *
     * @param SimpleXMLElement $infData The information data element.
     * @return void
     */
    protected function addInfData(SimpleXMLElement $infData)
    {
        $this->object->setHostName($infData->name);
        $this->statusArrPopulate($infData);
        $this->ipCheck($infData); // sets ipv4 and ipv6:- $this->object->setIpv4 and setIpv6
        $this->object->setCreatorID($infData->crID);
        $this->object->setUpID($infData->upID);
        $this->object->setId($infData->roid);
    }

    /**
     * Set the value from XML data.
     *
     * @param SimpleXMLElement $infData The name element.
     * @return void
     */
    protected function setValue(SimpleXMLElement $infData)
    {
        $this->object->setHostName((string)$infData);
    }

    /**
     * Add extension data to the host object.
     *
     * @param SimpleXMLElement|null $extension The extension data element.
     * @return void
     */
    protected function addExtensionData(SimpleXMLElement $extension = null): void
    {
        // No extension data for host info.
        return;
    }
}
