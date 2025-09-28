<?php

namespace SclNominetEpp;

use DateTime;
use InvalidArgumentException;

class Greeting
{
    const ACCESS_ALL = 'all';
    const ACCESS_NONE = 'none';
    const ACCESS_NULL = 'null';
    const ACCESS_PERSONAL = 'personal';
    const ACCESS_PERSONAL_AND_OTHER = 'personalAndOther';
    const ACCESS_OTHER = 'other';
    const PURPOSE_ADMIN = 'admin';
    const PURPOSE_CONTACT = 'contact';
    const PURPOSE_PROV = 'prov';
    const PURPOSE_OTHER = 'other';
    const RECIPIENT_OTHER = 'other';
    const RECIPIENT_OURS = 'ours';
    const RECIPIENT_PUBLIC = 'public';
    const RECIPIENT_SAME = 'same';
    const RECIPIENT_UNRELATED = 'unrelated';
    const RETENTION_BUSINESS = 'business';
    const RETENTION_INDEFINITE = 'indefinite';
    const RETENTION_LEGAL = 'legal';
    const RETENTION_NONE = 'none';
    const EXPIRY_ABSOLUTE = 'absolute';
    const EXPIRY_RELATIVE = 'relative';
    /**
     *
     * {@link http://tools.ietf.org/html/rfc5730#section-2.4 <access> children documentation}
     */
    protected static array $accessTags = [
        self::ACCESS_ALL,
        self::ACCESS_NONE,
        self::ACCESS_NULL,
        self::ACCESS_PERSONAL,
        self::ACCESS_PERSONAL_AND_OTHER,
        self::ACCESS_OTHER
    ];
    /**
     * {@link http://tools.ietf.org/html/rfc5730#section-2.4 <purpose> children documentation}
     */
    protected static array $purposeTags = [
        self::PURPOSE_ADMIN,
        self::PURPOSE_CONTACT,
        self::PURPOSE_PROV,
        self::PURPOSE_OTHER
    ];
    /**
     * {@link http://tools.ietf.org/html/rfc5730#section-2.4 <purpose> children documentation}
     */
    protected static array $recipientTags = [
        self::RECIPIENT_OTHER,
        self::RECIPIENT_OURS,
        self::RECIPIENT_PUBLIC,
        self::RECIPIENT_SAME,
        self::RECIPIENT_UNRELATED
    ];
    /**
     * {@link http://tools.ietf.org/html/rfc5730#section-2.4 <purpose> children documentation}
     */
    protected static array $retentionTags = [
        self::RETENTION_BUSINESS,
        self::RETENTION_INDEFINITE,
        self::RETENTION_LEGAL,
        self::RETENTION_NONE
    ];
    /**
     * {@link http://tools.ietf.org/html/rfc5730#section-2.4 <purpose> children documentation}
     */
    protected static array $expiryTags = [
        self::EXPIRY_ABSOLUTE,
        self::EXPIRY_RELATIVE
    ];

    /**
     * The server name.
     */
    protected string $serverId;

    /**
     * The server's current date and time in UTC.
     */
    protected DateTime $serverDate;

    /**
     * The protocol version supported by the server.
     */
    protected string $version;

    /**
     * Language known by the server.
     */
    protected string $language;

    /**
     * Namespace URIs for the objects that can be manipulated in the session.
     */
    protected array $objectURIs = [];

    /**
     * Namespace extension URIs for objects that can be manipulated during the session.
     */
    protected array $extensionURIs = [];

    /**
     * The access level of the Data Collection Policy.
     */
    protected string $access;

    protected array $purposes = [];

    protected array $recipients = [];

    protected string $retention;

    /**
     * Get the server ID.
     *
     * @return string The server ID.
     */
    public function getServerId(): string
    {
        return $this->serverId;
    }

    /**
     * Set the server ID.
     *
     * @param string $serverId The server ID.
     * @return void
     */
    public function setServerId(string $serverId)
    {
        $this->serverId = $serverId;
    }

    /**
     * Get the server date.
     *
     * @return DateTime The server date.
     */
    public function getServerDate(): DateTime
    {
        return $this->serverDate;
    }

    /**
     * Set the server date.
     *
     * @param DateTime $serverDate The server date.
     * @return void
     */
    public function setServerDate(DateTime $serverDate)
    {
        $this->serverDate = $serverDate;
    }

    /**
     * Get the version.
     *
     * @return string The version.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set the version.
     *
     * @param string $version The version.
     * @return void
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * Get the language.
     *
     * @return string The language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set the language.
     *
     * @param string $language The language.
     * @return void
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;
    }

    /**
     * Get the object URIs.
     *
     * @return array The object URIs.
     */
    public function getObjectURIs(): array
    {
        return $this->objectURIs;
    }

    /**
     * Add an object URI.
     *
     * @param string $objectURI The object URI to add.
     * @return void
     */
    public function addObjectURI(string $objectURI)
    {
        $this->objectURIs[] = $objectURI;
    }

    /**
     * Get the extension URIs.
     *
     * @return array The extension URIs.
     */
    public function getExtensionURIs(): array
    {
        return $this->extensionURIs;
    }

    /**
     * Add an extension URI.
     *
     * @param string $extensionURI The extension URI to add.
     * @return void
     */
    public function addExtensionURI(string $extensionURI)
    {
        $this->extensionURIs[] = $extensionURI;
    }

    /**
     * Get the access setting.
     *
     * @return string The access setting.
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * Set the access setting.
     *
     * @param mixed $access The access setting.
     * @return void
     * @throws InvalidArgumentException If the access tag is invalid.
     */
    public function setAccess($access)
    {
        if (!in_array((string)$access, self::$accessTags)) {
            throw new InvalidArgumentException("Invalid access Tag: $access");
        }
        $this->access = (string)$access;
    }

    /**
     * Get the purposes.
     *
     * @return array The purposes.
     */
    public function getPurposes(): array
    {
        return $this->purposes;
    }

    /**
     * Add a purpose.
     *
     * @param string $purpose The purpose to add.
     * @return void
     */
    public function addPurpose(string $purpose)
    {
        $this->purposes[] = $purpose;
    }

    /**
     * Get the recipients.
     *
     * @return array The recipients.
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Add a recipient.
     *
     * @param string $recipient The recipient to add.
     * @return void
     */
    public function addRecipient(string $recipient)
    {
        $this->recipients[] = $recipient;
    }

    /**
     * Get the retention setting.
     *
     * @return string The retention setting.
     */
    public function getRetention(): string
    {
        return $this->retention;
    }

    /**
     * Set the retention setting.
     *
     * @param string $retention The retention setting.
     * @return void
     */
    public function setRetention(string $retention)
    {
        $this->retention = $retention;
    }
}
