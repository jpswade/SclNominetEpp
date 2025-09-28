<?php

namespace SclNominetEpp\Request;

use PHPUnit\Framework\TestCase;
use SclNominetEpp\Request;

/**
 * login epp command test
 */
class LoginTest extends TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->request = new Login();
    }

    /**
     * Test login functionality.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->request->setCredentials('TAG', 'PASSWORD');

        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->assertEquals($xml, $this->request->getPacket());
    }
}
