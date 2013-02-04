<?php
namespace SclNominetEpp\Request;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-16 at 11:07:28.
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Request('test-action');
    }


    /**
     * @covers SclNominetEpp\Request\Request::__toString
     * @todo   Implement test__toString().
     */
    public function testRequestXML()
    {

        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <command>
        <test-action/>
    </command>
</epp>

EOF;

        //$this->assertEquals($xml, (string)$this->object);

    }
}
