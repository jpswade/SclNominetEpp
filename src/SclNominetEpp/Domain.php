<?php

namespace SclNominetEpp;

use DateTime;
use InvalidArgumentException;
use SclNominetEpp\Traits\UpDateTrait;

class Domain
{
    use UpDateTrait;

    const BILL_REGISTRAR = 'th';
    const BILL_CUSTOMER = 'bc';
    const BILLS = [self::BILL_REGISTRAR, self::BILL_CUSTOMER];
    const REGISTRATION_PERIOD = 2;

    /**
     * Domain name
     */
    private string $name;

    /**
     * Registration Period
     */
    private int $period = self::REGISTRATION_PERIOD;

    /**
     * The Person, Company or Entity who owns or holds a domain name.
     */
    private ?string $registrant = null;

    /**
     * All the contacts of the registered domain.
     *
     * @var Contact[]
     */
    private array $contacts = [];

    /**
     * All the nameservers of the registered domain.
     *
     * @var Nameserver[]
     */
    private array $nameservers = [];

    /**
     * The identifier of the sponsoring client.
     * Specified in the Nominet EPP as "clID"
     */
    private string $clientID;

    /**
     * The identifier of the client that created the domain object.
     * Specified in the Nominet EPP as "crID"
     */
    private string $creatorID;

    /**
     * The date and time of domain object creation.
     * Specified in the Nominet EPP as "crDate"
     */
    private DateTime $created;

    /**
     * The date and time identifying the end of the domain object's registration period.
     * Specified in the Nominet EPP as "exDate"
     */
    private DateTime $expired;

    /**
     * The identifier of the client that last updated the domain object.
     * This variable MUST be null if the domain has never been modified.
     * (could be a name and email address or the value submitted from the <clTRID> element if created by EPP)
     */
    private ?string $upID = null;

    /**
     * If first-bill is not set or set to "th", the registration
     * invoice will be sent to the registrar,
     * setting this to "bc" will instead invoice the customer at the non-member registration rate.
     */
    private string $firstBill = self::BILL_REGISTRAR;

    /**
     * If recur-bill is not set or set to "th" invoices for renewals
     * will be sent to the registrar,
     * setting this to "bc" will instead invoice the customer at the non-member renewal rate
     * (the auto-bill and next-bill fields will also be cleared).
     */
    private string $recurBill = self::BILL_REGISTRAR;

    /**
     * The number of days before expiry you wish to automatically renew a domain name.
     * Values between 1-182.
     * This field can be cleared by setting the default value of 0.
     * Auto-bill cannot be set if next-bill, recur-bill or renew-not-required are set.
     */
    private ?int $autoBill = null;

    /**
     * The number of days before expiry you wish to automatically renew a domain name.
     * The next-bill field will reset to 0 after a single registration period.
     * Values between 1 and 182, indicating how many days before expiry you wish to renew the domain name.
     * This field can be cleared by setting the default value of 0.
     * Next-bill cannot be set if auto-bill, recur-bill or renew-not-required are set.
     */
    private ?int $nextBill = null;

    /**
     * Domain's current registration status
     */
    private string $regStatus;

    /**
     * Miscellaneous information relating to the domain name.
     *
     * @var ?string[]
     */
    private ?array $notes = null;

    /**
     * Password
     */
    private ?string $password;

    /**
     * Set add $contact to array of contacts
     *
     * @param Contact $contact The contact to add.
     * @return void
     */
    public function addContact(Contact $contact)
    {
        $this->contacts[] = $contact;
    }

    /**
     * Remove $contact from the array of contacts if it already exists.
     *
     * @param Contact $contact The contact to remove.
     * @return void
     */
    public function removeContact(Contact $contact)
    {
        $arrayKey = array_search($contact, $this->contacts);
        unset($this->contacts[$arrayKey]);
    }

    /**
     * Add $nameserver to the array of nameservers
     *
     * @param Nameserver $nameserver The nameserver to add.
     * @return void
     */
    public function addNameserver(Nameserver $nameserver)
    {
        $this->nameservers[] = $nameserver;
    }

    /**
     * Remove $nameserver from the array of nameservers if it already exists.
     *
     * @param Nameserver $nameserver The nameserver to remove.
     * @return void
     */
    public function removeNameserver(Nameserver $nameserver)
    {
        $arrayKey = array_search($nameserver, $this->nameservers);
        unset($this->nameservers[$arrayKey]);
    }

    /**
     * Convert domain to string representation.
     *
     * @return string The domain name.
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get the domain name.
     *
     * @return string The domain name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the domain name.
     *
     * @param string $name The domain name to set.
     * @return void
     */
    public function setName(string $name)
    {
        if (filter_var($name, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            throw new InvalidArgumentException(sprintf('Name parameter "%s" is invalid', $name));
        }
        $this->name = $name;
    }

    /**
     * Convert domain to array representation.
     *
     * @return array The domain data as an array.
     */
    public function __toArray(): array
    {
        $data = [];
        $data['name'] = $this->getName();
        $data['period'] = $this->getPeriod();
        $data['registrant'] = $this->getRegistrant();
        $data['contacts'] = $this->getContacts();
        $data['nameservers'] = $this->getNameservers();
        $data['clientID'] = $this->getClientID();
        $data['creatorID'] = $this->getCreatorID();
        $data['created'] = $this->getCreated();
        $data['expired'] = $this->getExpired();
        $data['upID'] = $this->getUpID();
        $data['upDate'] = $this->getUpDate();
        $data['firstBill'] = $this->getFirstBill();
        $data['recurBill'] = $this->getRecurBill();
        $data['autoBill'] = $this->getAutoBill();
        $data['nextBill'] = $this->getNextBill();
        $data['regStatus'] = $this->getRegStatus();
        $data['notes'] = $this->getNotes();
        $data['password'] = $this->getPassword();
        return $data;
    }

    /**
     * Get the registration period.
     *
     * @return integer The registration period in years.
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

    /**
     * Set the value of period.
     *
     * @param integer $period The registration period in years.
     * @return Domain This domain instance.
     */
    public function setPeriod(int $period): Domain
    {
        if ($period < self::REGISTRATION_PERIOD) {
            $message = sprintf("Invalid period %d, must be greater than %d", $period, self::REGISTRATION_PERIOD);
            throw new InvalidArgumentException($message);
        }
        $this->period = $period;

        return $this;
    }

    /**
     * Get the registrant identifier.
     *
     * @return string|null The registrant identifier.
     */
    public function getRegistrant(): ?string
    {
        return $this->registrant;
    }

    /**
     * Set the registrant identifier.
     *
     * @param string|null $registrant The registrant identifier.
     * @return void
     */
    public function setRegistrant(?string $registrant)
    {
        $this->registrant = $registrant;
    }

    /**
     * @return Contact[]
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * Get the array of nameservers
     * @return Nameserver[]
     */
    public function getNameservers(): array
    {
        return $this->nameservers;
    }

    /**
     * Get the identifier of the sponsoring client.
     *
     * @return string The client identifier.
     */
    public function getClientID(): string
    {
        return $this->clientID;
    }

    /**
     * Set the identifier of the sponsoring client.
     *
     * @param string $clientID The client identifier.
     * @return void
     */
    public function setClientID(string $clientID)
    {
        $this->clientID = $clientID;
    }

    /**
     * Get the identifier of the client that created the domain object.
     *
     * @return string The creator identifier.
     */
    public function getCreatorID(): string
    {
        return $this->creatorID;
    }

    /**
     * Set the identifier of the client that created the domain object.
     *
     * @param string $creatorID The creator identifier.
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
     * Get the expiration date.
     *
     * @return DateTime The expiration date.
     */
    public function getExpired(): DateTime
    {
        return $this->expired;
    }

    /**
     * Set the expiration date.
     *
     * @param DateTime $expired The expiration date.
     * @return void
     */
    public function setExpired(DateTime $expired)
    {
        $this->expired = $expired;
    }

    /**
     * Get the update identifier.
     *
     * @return string|null The update identifier.
     */
    public function getUpID(): ?string
    {
        return $this->upID;
    }

    /**
     * Set the update identifier.
     *
     * @param string $upID The update identifier.
     * @return void
     */
    public function setUpID(string $upID)
    {
        $this->upID = $upID;
    }

    /**
     * Get the first bill setting.
     *
     * @return string The first bill setting.
     */
    public function getFirstBill(): string
    {
        return $this->firstBill;
    }

    /**
     * Set the first bill setting.
     *
     * @param string $firstBill The first bill setting.
     * @return void
     */
    public function setFirstBill(string $firstBill)
    {
        $this->checkBill($firstBill);
        $this->firstBill = $firstBill;
    }

    /**
     * Check if the bill setting is valid.
     *
     * @param string $bill The bill setting to check.
     * @return void
     * @throws InvalidArgumentException If the bill setting is invalid.
     */
    protected function checkBill(string $bill): void
    {
        if ($bill === '') {
            return;
        }
        if (in_array($bill, self::BILLS) === false) {
            $options = implode(', ', self::BILLS);
            $message = sprintf("Invalid bill '%s', must one of '%s'", $bill, $options);
            throw new InvalidArgumentException($message);
        }
    }

    /**
     * Get the recurring bill setting.
     *
     * @return string The recurring bill setting.
     */
    public function getRecurBill(): string
    {
        return $this->recurBill;
    }

    /**
     * Set the recurring bill setting.
     *
     * @param string $recurBill The recurring bill setting.
     * @return void
     */
    public function setRecurBill(string $recurBill)
    {
        $this->checkBill($recurBill);
        $this->recurBill = $recurBill;
    }

    /**
     * Get the auto bill setting.
     *
     * @return integer|null The auto bill setting.
     */
    public function getAutoBill(): ?int
    {
        return $this->autoBill;
    }

    /**
     * Set the auto bill setting.
     *
     * @param integer|object $autoBill The auto bill setting.
     * @return void
     */
    public function setAutoBill($autoBill)
    {
        $this->autoBill = (int)$autoBill;
    }

    /**
     * Get the next bill setting.
     *
     * @return integer|null The next bill setting.
     */
    public function getNextBill(): ?int
    {
        return $this->nextBill;
    }

    /**
     * Set the next bill setting.
     *
     * @param integer|object $nextBill The next bill setting.
     * @return void
     */
    public function setNextBill($nextBill)
    {
        $this->nextBill = (int)$nextBill;
    }

    /**
     * Get the registration status.
     *
     * @return string The registration status.
     */
    public function getRegStatus(): string
    {
        return $this->regStatus;
    }

    /**
     * Set the registration status.
     *
     * @param string $regStatus The registration status.
     * @return void
     */
    public function setRegStatus(string $regStatus)
    {
        $this->regStatus = $regStatus;
    }

    /**
     * @return ?string[]
     */
    public function getNotes(): ?array
    {
        return $this->notes;
    }

    /**
     * Set the notes array.
     *
     * @param array $notes The notes array.
     * @return void
     */
    public function setNotes(array $notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get the password.
     *
     * @return string|null The password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the password.
     *
     * @param string $password The password.
     * @return void
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * Check if password is set.
     *
     * @return boolean True if password is set.
     */
    public function hasPassword(): bool
    {
        return isset($this->password);
    }

    /**
     * Check if auto bill is set.
     *
     * @return boolean True if auto bill is set.
     */
    public function hasAutoBill(): bool
    {
        return isset($this->autoBill);
    }

    /**
     * Check if next bill is set.
     *
     * @return boolean True if next bill is set.
     */
    public function hasNextBill(): bool
    {
        return isset($this->nextBill);
    }

    /**
     * Check if notes are set.
     *
     * @return boolean True if notes are set.
     */
    public function hasNotes(): bool
    {
        return isset($this->notes);
    }
}
