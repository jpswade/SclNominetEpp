<?php
namespace SclNominetEpp\Response\Check;

use SclNominetEpp\Response\Check\Host as CheckHost;

/**
 * host:check response test
 */
class HostTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->response = new CheckHost();
    }

    /**
     *
     *
     */
    public function testProcessData()
    {

        $xml = <<<EOX
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <host:chkData
       xmlns:host="urn:ietf:params:xml:ns:host-1.0">
        <host:cd>
          <host:name avail="1">ns1.example.com</host:name>
        </host:cd>
        <host:cd>
          <host:name avail="0">ns2.example2.com</host:name>
          <host:reason>In use</host:reason>
        </host:cd>
        <host:cd>
          <host:name avail="1">ns3.example3.com</host:name>
        </host:cd>
      </host:chkData>
    </resData>
    <trID>
      <clTRID>ABC-12345</clTRID>
      <svTRID>54322-XYZ</svTRID>
    </trID>
  </response>
</epp>
EOX;

        $expected = array(
            'ns1.example.com'  => true,
            'ns2.example2.com' => false,
            'ns3.example3.com' => true
        );

        $this->response->init($xml);

        $hosts = $this->response->getValues();

        $this->assertEquals($expected, $hosts);

    }
}
