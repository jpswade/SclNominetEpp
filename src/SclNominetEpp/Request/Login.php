<?php

/**
 * Contains the nominet Login request class definition.
 */

namespace SclNominetEpp\Request;

use SclNominetEpp\Response\Login as LoginResponse;
use SclNominetEpp\Request;
use SimpleXMLElement;

/**
 * This class build the XML for a Nominet EPP login command.
 */
class Login extends Request
{
    /**
     * The registrar tag.
     */
    protected string $tag;

    /**
     * The password to login with.
     */
    protected string $password;

    /**
     * The new password if the password is to be changed.
     */
    protected ?string $newPassword = null;

    /**
     * Tells the parent class what the action of this request is.
     */
    public function __construct()
    {
        parent::__construct('login', new LoginResponse());
    }

    /**
     * Sets the login details for this.
     *
     * @param string $tag      The registrar tag.
     * @param string $password The password.
     * @return static
     */
    public function setCredentials(string $tag, string $password)
    {
        $this->tag = $tag;
        $this->password = $password;

        return $this;
    }

    /**
     * Sets a new password for this account.
     *
     * @param string $newPassword The new password.
     * @return static
     */
    public function changePassword(string $newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * Add object URI to XML.
     *
     * @param SimpleXMLElement $xml The XML element.
     * @param string           $uri The URI to add.
     * @return void
     */
    private function addObjUri(SimpleXMLElement $xml, string $uri): void
    {
        $xml->addChild('objURI', $uri);
    }

    /**
     * Add service extension to XML.
     *
     * @param SimpleXMLElement $xml The XML element.
     * @param string           $uri The URI to add.
     * @return void
     */
    private function addSvcExtension(SimpleXMLElement $xml, string $uri): void
    {
        $xml->addChild(
            'extURI',
            'http://www.nominet.org.uk/epp/xml/' . $uri
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param SimpleXMLElement $action The XML action element.
     * @return void
     */
    protected function addContent(SimpleXMLElement $action): void
    {
        $action->addChild('clID', $this->tag);
        $action->addChild('pw', $this->password);

        if (null !== $this->newPassword) {
            $action->addChild('newPw', $this->newPassword);
        }

        $options = $action->addChild('options');

        $options->addChild('version', '1.0');
        $options->addChild('lang', 'en');

        $svcs = $action->addChild('svcs');

        $this->addObjUri($svcs, 'urn:ietf:params:xml:ns:domain-1.0');
        $this->addObjUri($svcs, 'urn:ietf:params:xml:ns:contact-1.0');
        $this->addObjUri($svcs, 'urn:ietf:params:xml:ns:host-1.0');

        $svcExt = $svcs->addChild('svcExtension');

        // TODO Decide if we should load all these every time
        $this->addSvcExtension($svcExt, 'domain-nom-ext-1.2');
        $this->addSvcExtension($svcExt, 'contact-nom-ext-1.0');
        $this->addSvcExtension($svcExt, 'std-notifications-1.2');
        $this->addSvcExtension($svcExt, 'std-warning-1.1');
        $this->addSvcExtension($svcExt, 'std-contact-id-1.0');
        $this->addSvcExtension($svcExt, 'std-release-1.0');
        $this->addSvcExtension($svcExt, 'std-handshake-1.0');
        $this->addSvcExtension($svcExt, 'nom-abuse-feed-1.0');
        $this->addSvcExtension($svcExt, 'std-fork-1.0');
        $this->addSvcExtension($svcExt, 'std-list-1.0');
        $this->addSvcExtension($svcExt, 'std-locks-1.0');
        $this->addSvcExtension($svcExt, 'std-unrenew-1.0');
    }
}
