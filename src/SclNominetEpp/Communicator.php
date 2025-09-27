<?php

/**
 * Contains the Communicator class definition.
 */

namespace SclNominetEpp;

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
    public static $config = [
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
     * @param SocketInterface $socket
     */
    public function __construct(SocketInterface $socket)
    {
        parent::__construct($socket, '!</epp>!');
    }

    /**
     * Connect to the server.
     *
     * @param boolean $live
     * @param boolean $secure
     *
     * @return void
     */
    public function setupConnection($live = false, $secure = true)
    {
        $liveIndex = $live ? 'live' : 'test';
        $secureIndex = $secure ? 'secure' : 'insecure';

        $config = self::$config[$liveIndex][$secureIndex];

        $this->connect($config['host'], $config['port'], $secure);

        // TODO Parse and verify the greeting.
        $this->read();
    }
}
