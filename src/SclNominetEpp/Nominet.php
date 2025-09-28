<?php

/**
 * Contains the Nominet class definition.
 */

namespace SclNominetEpp;

use DateTime;
use DomainException;
use Exception;
use SclNominetEpp\Exception\LoginRequiredException;
use SclNominetEpp\Request\Update;
use SclNominetEpp\Request\Update\Unrenew;
use SclNominetEpp\Response\ListDomains;
use SclRequestResponse\ResponseInterface;

/**
 * This class exposes all the actions of the Nominet EPP system in a nice PHP class.
 */
class Nominet extends AbstractRequestResponse
{
    /**
     * A client MUST NOT alter status values set by the server.
     * A server MAY alter or override status values set by a client, subject to local server policies.
     * Status values that can be added or removed by a client are prefixed with "client".
     */
    public const STATUS_CLIENT_DELETE_PROHIBITED   = 'clientDeleteProhibited';
    public const STATUS_CLIENT_HOLD                = 'clientHold';
    public const STATUS_CLIENT_RENEW               = 'clientRenewProhibited';
    public const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';
    public const STATUS_CLIENT_UPDATE_PROHIBITED   = 'clientUpdateProhibited';

    // Corresponding status values that can be added or removed by a server are prefixed with "server".
    public const STATUS_SERVER_DELETE_PROHIBITED   = 'serverDeleteProhibited';
    public const STATUS_SERVER_HOLD                = 'serverHold';
    public const STATUS_SERVER_RENEW               = 'serverRenewProhibited';
    public const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    public const STATUS_SERVER_UPDATE_PROHIBITED   = 'serverUpdateProhibited';

    /**
     * pending[action]" status MUST NOT be combined
     * with either:-
     * "client[action]Prohibited" or
     * "server[action]Prohibited" status or
     * other "pending[action]" status.
     */
    public const STATUS_PENDING_CREATE   = 'pendingCreate';
    public const STATUS_PENDING_DELETE   = 'pendingDelete';
    public const STATUS_PENDING_RENEW    = 'pendingRenew';
    public const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    public const STATUS_PENDING_UPDATE   = 'pendingUpdate';

    public const STATUS_INACTIVE = 'inactive';

    /** "ok" status MUST NOT be combined with any other status. */
    public const STATUS_OKAY = 'ok';

    /**
     * Flag that states whether we are logged into Nominet or not.
     *
     * @var boolean
     */
    private bool $loggedIn = false;

    /**
     * Disconnect cleanly if we are still logged in.
     */
    public function __destruct()
    {
        if ($this->loggedIn) {
            $this->logout();
        }
    }

    /**
     * Check if we are logged into Nominet.
     *
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    private function loginCheck()
    {
        if (!$this->loggedIn) {
            throw new LoginRequiredException('Not logged into the Nominet EPP.');
        }
    }

    /**
     * The <login> command is used to establish and authenticate a session with
     * the EPP server. The <login> command must be sent to the server before any
     * other EPP command and identifies and authenticates the tag to be used
     * by the session. An EPP session is terminated by a logout command.
     *
     * @param string      $tag         The tag to authenticate with.
     * @param string      $password    The password for authentication.
     * @param string|null $newPassword If specified will change the password.
     * @return boolean True if the login was successful.
     */
    public function login(string $tag, string $password, ?string $newPassword = null): bool
    {
        $request = new Request\Login();
        $request->setCredentials($tag, $password);

        if ($newPassword !== null) {
            $request->changePassword($newPassword);
        }

        $response = $this->processRequest($request);

        if (!$response->success()) {
            return false;
        }
        $this->loggedIn = true;

        return $response::SUCCESS_STANDARD === $response->code();
    }

    /**
     * The <hello> command is used to obtain a greeting element from our server
     * and may be used to keep your connection with our EPP server open.
     * Sending an EPP <hello> command every 59 minutes will keep your connection
     * with our EPP server open.
     * @return ResponseInterface The greeting response.
     * @throws LoginRequiredException When not logged in.
     */
    public function hello(): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Hello('hello', new \SclNominetEpp\Response\Greeting());

        return $this->processRequest($request);
    }

    /**
     * A <logout> command is used to end a session with an EPP server. On receipt
     * the EPP server responds and then closes the connection with the client.
     * @return boolean True if logout was successful.
     * @throws LoginRequiredException When not logged in.
     */
    public function logout(): bool
    {
        $this->loginCheck();

        $request = new Request('logout');

        /* @var $response Response */
        $response = $this->processRequest($request);

        $this->loggedIn = false;
        return $response::SUCCESS_ENDING_SESSION === $response->code();
    }

    /**
     * Checks if a domain or set of domains are available.
     *
     * The <check> command is used to determine if the domain name is currently
     * registered and provides a hint about whether a <check> command would be
     * successful.
     *
     * @param string|array $domains The domain(s) to check.
     * @return array The check results.
     * @throws LoginRequiredException When not logged in.
     */
    public function checkDomain($domains)
    {
        $this->loginCheck();

        $request = new Request\Check\Domain();

        $request->lookup($domains);

        $response = $this->processRequest($request);

        return $response->getValues();
    }

    /**
     * Checks if a contact or set of contacts are available.
     *
     * The check command is used to determine if the domain name is currently
     * registered and provides a hint about whether a <check> command would be
     * successful.
     *
     * @param string|array $contactIds The contact ID(s) to check.
     * @return ResponseInterface The check response.
     * @throws LoginRequiredException When not logged in.
     */
    public function checkContact($contactIds): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Check\Contact();

        $request->setValues($contactIds);

        return $this->processRequest($request);
    }

    /**
     * Checks if a host or set of hosts are available.
     *
     * The <check> command is used to determine if the domain name is currently
     * registered and provides a hint about whether a <check> command would be
     * successful.
     *
     * @param string|array $hosts The host(s) to check.
     * @return ResponseInterface The check response.
     * @throws LoginRequiredException When not logged in.
     */
    public function checkHost($hosts): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Check\Host();

        $request->setValues($hosts);

        $response = $this->processRequest($request);

        return $response;
    }

    /**
     * The <create> command allows you to create a contact
     * account.
     *
     * @param Contact $contact The contact to create.
     * @return boolean True if creation was successful.
     * @throws LoginRequiredException When not logged in.
     */
    public function createContact(Contact $contact): bool
    {
        $this->loginCheck();

        $request = new Request\Create\Contact();
        $request->setContact($contact);

        $response = $this->processRequest($request);

        return $response->success();
    }

    /**
     * The <create> command allows you to register a domain name or to create an
     * account or nameserver object to link to domain names.
     *
     * @param Domain $domain The domain to create.
     * @return boolean True if creation was successful.
     * @throws LoginRequiredException When not logged in.
     */
    public function createDomain(Domain $domain): bool
    {
        $this->loginCheck();

        $request = new Request\Create\Domain();
        $request->setDomain($domain);

        $response = $this->processRequest($request);

        return $response->success();
    }

    /**
     * The <create> command allows you to create a nameserver object to link to domain names.
     *
     * @param Nameserver $host The nameserver to create.
     * @return boolean True if creation was successful.
     * @throws LoginRequiredException When not logged in.
     */
    public function createHost(Nameserver $host): bool
    {
        $this->loginCheck();
        $request = new Request\Create\Host();

        $request->setNameserver($host);

        $response = $this->processRequest($request);

        return $response->success();
    }

    /**
     * The EPP <delete> command allows the registrar to delete a domain name.
     * Further details of this are available in RFC 5731 The delete command may
     * not be used to delete nameservers and accounts.
     *
     * @param Domain $domain The domain to delete.
     * @return boolean True if deletion was successful.
     * @throws LoginRequiredException When not logged in.
     */
    public function deleteDomain(Domain $domain): bool
    {
        $this->loginCheck();

        $request  = new Request\Delete\Domain();

        $request->setDomain($domain);

        $response = $this->processRequest($request);

        return $response->success();
    }

    /**
     * The <renew> command only applies to domain names. It has no meaning for
     * other object types.
     *
     * @param string        $domain  The domain to be renewed.
     * @param DateTime|null $expDate The new expiry date or null.
     * @return ResponseInterface The renew response.
     * @throws LoginRequiredException When not logged in.
     */
    public function renew(string $domain, ?DateTime $expDate): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Renew();
        $request->setDomain($domain, $expDate);

        return $this->processRequest($request);
    }

    /**
     * The <unrenew> operation is used to reverse a renewal request made for a
     * domain name. The renew command only applies to domain names. It has no
     * meaning for other object types.
     * @return ResponseInterface The unrenew response.
     * @throws LoginRequiredException When not logged in.
     */
    public function unrenew(): ResponseInterface
    {
        $this->loginCheck();

        $request = new Unrenew();

        return $this->processRequest($request);
    }

    /**
     * The <update> operation allows the attributes of an object to be updated.
     * @param Domain      $domain        The domain to update.
     * @param Domain|null $currentDomain The current domain state.
     * @return ResponseInterface The update response.
     * @throws LoginRequiredException When not logged in.
     */
    public function updateDomain(Domain $domain, Domain $currentDomain = null): ResponseInterface
    {
        $this->loginCheck();

        $currentDomain = $currentDomain ?: $this->domainInfo($domain->getName());

        $update = new Request\Update();
        $request = $update($domain, $currentDomain);

        return $this->processRequest($request);
    }

    /**
     * The <update> operation allows the attributes of an object to be updated.
     * @param Contact $contact The contact to be updated.
     * @return ResponseInterface The update response.
     * @throws LoginRequiredException When not logged in.
     */
    public function updateContact(Contact $contact): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Update\Contact($contact);

        $request->add(new Update\Field\Status('', self::STATUS_CLIENT_DELETE_PROHIBITED));

        $response = $this->processRequest($request);

        return $response;
    }

    /**
     * The <update> operation allows the attributes of an object to be updated.
     * @param string $value The contact ID value to update.
     * @return ResponseInterface The update response.
     * @throws LoginRequiredException When not logged in.
     */
    public function updateContactID(string $value): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Update\ContactID($value);

        $request->add(new Update\Field\Status('', self::STATUS_CLIENT_HOLD));

        return $this->processRequest($request);
    }

    /**
     * The <update> operation allows the attributes of an object to be updated.
     * @param Nameserver $host The nameserver to be updated.
     * @return ResponseInterface The update response.
     * @throws LoginRequiredException When not logged in.
     */
    public function updateHost(Nameserver $host): ResponseInterface
    {
        $this->loginCheck();

        $request = new Request\Update\Host($host->getHostName());

        $request->add(new Update\Field\Status('', self::STATUS_CLIENT_UPDATE_PROHIBITED));

        $request->add(new Update\Field\HostAddress('192.0.2.2', 'v4'));

        return $this->processRequest($request);
    }

    /**
     * The EPP <info> command is used to retrieve information associated with
     * an object.
     *
     * @param string $domainName The domain name to query.
     * @return Domain The domain object.
     * @throws LoginRequiredException When not logged in.
     * @throws DomainException When domain is not found.
     */
    public function domainInfo(string $domainName): Domain
    {
        $this->loginCheck();

        $request = new Request\Info\Domain();

        $request->lookup($domainName);

        /** @var Response\Info\Domain $response */
        $response = $this->processRequest($request);
        if (!$response->success()) {
            throw new DomainException($response->message(), $response->code());
        }
        $domain = $response->getDomain();
        if (!$domain instanceof Domain) {
            throw new DomainException('The domain requested is unregistered');
        }
        return $domain;
    }

    /**
     * The EPP <info> command is used to retrieve information associated with
     * an object. ($contactID is the $registrant from domainInfo)
     *
     * @param string $contactID The contact ID to query.
     * @return boolean True if contact was found.
     * @throws LoginRequiredException When not logged in.
     */
    public function contactInfo(string $contactID): bool
    {
        $this->loginCheck();

        $request = new Request\Info\Contact();

        $request->lookup($contactID);

        $response = $this->processRequest($request);
        if (!$response->success()) {
            return false;
        }
        return $response->getContact();
    }

    /**
     * The EPP <info> command is used to retrieve information associated with
     * an object.
     *
     * @param string $hostName The host name to query.
     * @return Nameserver|null The nameserver object or null if not found.
     * @throws LoginRequiredException When not logged in.
     */
    public function hostInfo(string $hostName): ?Nameserver
    {
        $this->loginCheck();

        $request = new Request\Info\Host();

        $request->lookup($hostName);

        $response = $this->processRequest($request);
        return $response->getHost();
    }

    /**
     * When changes take place in the registration data for domain names or
     * ENUMs on a tag, we send notifications to the registrar. It will be
     * possible for registrars to elect to receive these notifications via EPP.
     *
     * If a registrar elects to receive notifications via EPP, then
     * notifications will be placed in the message queue awaiting a poll
     * command to retrieve them. If the message queue is not empty, then a
     * successful response to a poll command returns the first message from the
     * queue.  This response includes a unique message identifier and a counter
     * that gives the number of messages in the queue.
     *
     * After a message has been received by the client, the client must respond
     * to the client with an explicit acknowledgement to confirm that the
     * message has been received. Then that message is dequeued and the next
     * message in the queue becomes available for retrieval.
     *
     * NOTE: To use the <poll> command you must have activated this notification
     * option for your account in the Online Service. In addition, version 1.1
     * or subsequent schemas must be used if polling via Nominet EPP.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function poll()
    {
        $this->loginCheck();
    }

    /**
     * The <handshake> operation allows a registrar to accept or reject a
     * registrar change/registrant transfer authorisation request.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function handshake()
    {
        $this->loginCheck();
    }

    /**
     * The <release> operation allows a registrar to move a domain name, or
     * account onto another tag.
     * @param string $id The contact ID to release.
     * @return Contact|null The released contact.
     * @throws LoginRequiredException When not logged in.
     */
    public function releaseContact(string $id)
    {
        $this->loginCheck();

        $request = new Request\Update\Release\Contact();
        $request->lookup($id);
        $response = $this->processRequest($request);
        return $response->getContact();
    }

    /**
     * The <release> operation allows a registrar to move a domain name, or
     * account onto another tag.
     * @param string $name The domain name to release.
     * @return Domain|null The released domain.
     * @throws LoginRequiredException When not logged in.
     */
    public function releaseDomain(string $name)
    {
        $this->loginCheck();

        $request = new Request\Update\Release\Domain();
        $request->lookup($name);
        $response = $this->processRequest($request);
        return $response->getDomain();
    }

    /**
     * The <fork> command allows a number of domain names on a registrant contact
     * to be moved to a copy of that contact.
     * @param string $hostName The host name to fork.
     * @return mixed The forked host.
     * @throws LoginRequiredException When not logged in.
     */
    public function fork(string $hostName)
    {
        $this->loginCheck();

        $request = new Update\Fork();

        $request->setValue($hostName);

        $response = $this->processRequest($request);
        return $response->getHost();
    }

    /**
     * Retrieves a domain list.
     * NOTE: This method is called domainList as list is a resevered word :-(
     *
     * @param integer      $year  The year to list domains for.
     * @param integer      $month The month to list domains for.
     * @param integer|null $type  The list type.
     * @return mixed The list of domains.
     * @throws LoginRequiredException When not logged in.
     * @throws Exception When invalid type is provided.
     */
    public function listDomains(int $year, int $month, ?int $type = ListDomains::LIST_MONTH)
    {
        $this->loginCheck();

        if (!in_array($type, [ListDomains::LIST_MONTH, ListDomains::LIST_EXPIRY])) {
            throw new Exception("Invalid type $type.");
        }

        $request = new Request\ListDomains();
        $request->setDate($month, $year);

        $response = $this->processRequest($request);

        return $response->getDomains();
    }

    /**
     * The investigation <lock> command can be used to lock down a domain name,
     * preventing a number of operations upon it.
     * @param string $objectName The object name to lock.
     * @param string $type       The lock type.
     * @return ResponseInterface The lock response.
     * @throws LoginRequiredException When not logged in.
     */
    public function lock(string $objectName, string $type): ResponseInterface
    {
        $this->loginCheck();

        $request = new Update\Lock($objectName, $type);

        return $this->processRequest($request);
    }

    /**
     * The reseller create command is used to define a new reseller on your tag
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function resellerCreate()
    {
        $this->loginCheck();
    }

    /**
     * The reseller delete command is used to remove a reseller from your tag.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function resellerDelete()
    {
        $this->loginCheck();
    }

    /**
     * The reseller info command returns all information associated with a
     * reseller on your tag.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function resellerInfo()
    {
        $this->loginCheck();
    }

    /**
     * The reseller list command returns information about all resellers on
     * your tag.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function resellerList()
    {
        $this->loginCheck();
    }

    /**
     * The reseller update command is used to modify the attributes of an
     * existing reseller on your tag.
     * @return void
     * @throws LoginRequiredException When not logged in.
     */
    public function resellerUpdate()
    {
        $this->loginCheck();
    }
}
