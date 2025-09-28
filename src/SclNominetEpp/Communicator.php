<?php

/**
 * Contains the Communicator class definition.
 */

namespace SclNominetEpp;

use SclRequestResponse\Exception\ConnectionFailedException;
use SclSocket\SocketInterface;
use SclRequestResponse\Communicator\PersistentCommunicator;

/**
 * Sets up communication with the Nominet EPP server and sends requests and
 * processes the responses.
 */
class Communicator extends PersistentCommunicator
{
    /**
     * Connection settings for Nominets server.
     *
     * @var array
     */
    public static array $config = [
        'live' => [
            'secure' => [
                'host' => 'epp.nominet.org.uk',
                'port' => '700',
            ],
            'insecure' => [
                'host' => 'epp.nominet.org.uk',
                'port' => '8700',
            ],
        ],
        'test' => [
            'secure' => [
                'host' => 'testbed-epp.nominet.org.uk',
                'port' => '700',
            ],
            'insecure' => [
                'host' => 'testbed-epp.nominet.org.uk',
                'port' => '8700',
            ],
        ],
    ];

    /**
     * Constructor
     *
     * @param SocketInterface $socket The socket interface.
     * @return void
     */
    public function __construct(SocketInterface $socket)
    {
        parent::__construct($socket, '!</epp>!');
    }

    /**
     * Connect to the server.
     *
     * @param boolean $live   Whether to use live or test environment.
     * @param boolean $secure Whether to use secure connection.
     *
     * @return void
     * @throws ConnectionFailedException When connection setup fails.
     */
    public function setupConnection(bool $live = false, bool $secure = true): void
    {
        $liveIndex = $live ? 'live' : 'test';
        $secureIndex = $secure ? 'secure' : 'insecure';

        $config = self::$config[$liveIndex][$secureIndex];

        $this->connect($config['host'], $config['port'], $secure);

        $response = $this->read();
        if (empty($response)) {
            throw new ConnectionFailedException('No greeting received from server.');
        }
    }
}
