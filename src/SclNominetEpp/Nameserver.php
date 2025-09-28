<?php

namespace SclNominetEpp;

use DateTime;
use InvalidArgumentException;

class Nameserver
{
    use Traits\UpDateTrait;

    /**
     * The nameserver host name
     */
    private string $hostName;

    /**
     * Array of status of a Nameserver
     */
    private array $status = [];

    /**
     * The identifier of the sponsoring client.
     */
    private string $clientID;

    /**
     * The identifier of the client that created the host object
     */
    private string $creatorID;

    /**
     * The date and time of host-object creation.
     */
    private DateTime $created;

    /**
     * The identifier of the client
     * that last updated the host object.
     */
    private string $upID = '';

    private ?string $ipv4 = null;

    private ?string $ipv6 = null;

    private ?string $id = null;

    /**
     * Add a status to the nameserver.
     *
     * @param string $status The status to add.
     * @return void
     */
    public function addStatus(string $status)
    {
        $this->status[] = $status;
    }

    /**
     * Convert nameserver to string representation.
     *
     * @return string The host name.
     */
    public function __toString(): string
    {
        return $this->getHostName();
    }

    /**
     * Get the host name.
     *
     * @return string The host name.
     */
    public function getHostName(): string
    {
        return $this->hostName;
    }

    /**
     * Set the host name.
     *
     * @param string $hostName The host name.
     * @return void
     * @throws InvalidArgumentException When host name is empty or invalid.
     */
    public function setHostName(string $hostName)
    {
        if (empty($hostName)) {
            throw new InvalidArgumentException('HostName parameter is empty');
        }
        if (filter_var($hostName, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            throw new InvalidArgumentException(sprintf('HostName parameter "%s" is invalid', $hostName));
        }
        $this->hostName = $hostName;
    }

    /**
     * Convert nameserver to array representation.
     *
     * @return array The array representation.
     */
    public function __toArray(): array
    {
        return [
            'hostName' => $this->getHostName(),
            'status' => $this->getStatus(),
            'clientId' => $this->getClientID(),
            'creatorID' => $this->getCreatorID(),
            'created' => $this->getCreated(),
            'upID' => $this->getUpID(),
            'upDate' => $this->getUpDate(),
            'ipv4' => $this->getIpv4(),
            'ipv6' => $this->getIpv6(),
            'id' => $this->getId(),
        ];
    }

    /**
     * Get the status array.
     *
     * @return array The status array.
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * Get the client ID.
     *
     * @return string The client ID.
     */
    public function getClientID(): string
    {
        return $this->clientID;
    }

    /**
     * Set the client ID.
     *
     * @param string $clientID The client ID.
     * @return void
     */
    public function setClientID(string $clientID)
    {
        $this->clientID = $clientID;
    }

    /**
     * Get the creator ID.
     *
     * @return string The creator ID.
     */
    public function getCreatorID(): string
    {
        return $this->creatorID;
    }

    /**
     * Set the creator ID.
     *
     * @param string $creatorID The creator ID.
     * @return void
     */
    public function setCreatorID(string $creatorID)
    {
        $this->creatorID = $creatorID;
    }

    /**
     * Get the creation date.
     *
     * @return DateTime The creation date.
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * Set the creation date.
     *
     * @param DateTime $created The creation date.
     * @return void
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get the ID of the user that last changed the domain name.
     *
     * @return string The update ID.
     */
    public function getUpID(): string
    {
        return $this->upID;
    }

    /**
     * Set the ID of the user that last changed the domain name.
     *
     * @param string $upID The update ID.
     * @return void
     */
    public function setUpID(string $upID)
    {
        $this->upID = $upID;
    }

    /**
     * Get the IPv4 address.
     *
     * @return string|null The IPv4 address.
     */
    public function getIpv4(): ?string
    {
        return $this->ipv4;
    }

    /**
     * Set the IPv4 address.
     *
     * @param string $ipv4 The IPv4 address.
     * @return void
     * @throws InvalidArgumentException When IPv4 address is invalid.
     */
    public function setIpv4(string $ipv4)
    {
        if (filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            throw new InvalidArgumentException('Ipv4 parameter is invalid');
        }
        $this->ipv4 = $ipv4;
    }

    /**
     * Get the IPv6 address.
     *
     * @return string The IPv6 address.
     */
    public function getIpv6(): string
    {
        return $this->ipv6;
    }

    /**
     * Set the IPv6 address.
     *
     * @param string $ipv6 The IPv6 address.
     * @return void
     * @throws InvalidArgumentException When IPv6 address is invalid.
     */
    public function setIpv6(string $ipv6)
    {
        if (filter_var($ipv6, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            throw new InvalidArgumentException('Ipv4 parameter is invalid');
        }
        $this->ipv6 = $ipv6;
    }

    /**
     * Get the ID.
     *
     * @return string|null The ID.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the ID.
     *
     * @param string $id The ID.
     * @return void
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }
}
