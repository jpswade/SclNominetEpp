<?php

namespace SclNominetEpp\Response\Info;

use DateTime;
use SimpleXMLElement;
use SclNominetEpp\Domain as DomainObject;
use SclNominetEpp\Nameserver;

/**
 * This class interprets XML for a Nominet EPP domain:info command response.
 */
class Domain extends AbstractInfo
{
    const TYPE = 'domain';
    const VALUE_NAME = 'name';

    /**
     * Constructor for Domain info response.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(
            self::TYPE,
            new DomainObject(),
            self::VALUE_NAME
        );
    }

    /**
     * Get the domain object.
     *
     * @return DomainObject|null The domain object or null.
     */
    public function getDomain(): ?DomainObject
    {
        return $this->object;
    }

    /**
     * Add information data to the domain object.
     *
     * @param SimpleXMLElement $infData The information data element.
     * @return void
     */
    protected function addInfData(SimpleXMLElement $infData)
    {

        $nschildren = $infData->ns->hostObj;
        foreach ($nschildren as $nschild) {
            $nameserver = new Nameserver();
            $nameserver->setHostName((string)$nschild);
            $this->object->addNameserver($nameserver);
        }

        $this->object->setRegistrant($infData->registrant);

        $this->object->setCreatorID($infData->crID);
        $this->object->setExpired(new DateTime((string) $infData->exDate));
        $this->object->setUpID($infData->upID);
    }

    /**
     * Add extension data to the domain object.
     *
     * @param SimpleXMLElement|null $extension The extension data element.
     * @return void
     */
    protected function addExtensionData(SimpleXMLElement $extension = null)
    {
                //EXTENSION DATA
        $this->object->setRegStatus($extension->{'reg-status'});
        $this->object->setFirstBill($extension->{'first-bill'});
        $this->object->setRecurBill($extension->{'recur-bill'});
        $this->object->setAutoBill($extension->{'auto-bill'});
        $this->object->setNextBill($extension->{'next-bill'});
    }

    /**
     * Set the domain name value.
     *
     * @param SimpleXMLElement $infData The name element.
     * @return void
     */
    protected function setValue(SimpleXMLElement $infData)
    {
        $this->object->setName((string)$infData);
    }
}
