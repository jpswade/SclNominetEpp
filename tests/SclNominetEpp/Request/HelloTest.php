<?php

namespace SclNominetEpp\Request;

use PHPUnit\Framework\TestCase;

/**
 * Hello request test.
 */
class HelloTest extends TestCase
{
    protected Hello $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->request = new Hello('hello');
    }

    /**
     * Test hello request XML generation.
     *
     * @return void
     */
    public function testHelloRequestXml()
    {
        $xml = $this->request->getPacket();

        $this->assertIsString($xml);
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', $xml);
        $this->assertStringContainsString('xmlns="urn:ietf:params:xml:ns:epp-1.0"', $xml);
        $this->assertStringContainsString('xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd"', $xml);
        $this->assertStringContainsString('<hello/>', $xml);
    }

    /**
     * Test that hello request is valid XML.
     *
     * @return void
     */
    public function testHelloRequestIsValidXml()
    {
        $xml = $this->request->getPacket();

        // Parse the XML to ensure it's valid
        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $this->assertTrue(true); // If we get here, the XML is valid
    }

    /**
     * Test that hello request contains correct namespace declarations.
     *
     * @return void
     */
    public function testHelloRequestNamespaces()
    {
        $xml = $this->request->getPacket();

        $this->assertStringContainsString('xmlns="urn:ietf:params:xml:ns:epp-1.0"', $xml);
        $this->assertStringContainsString('xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"', $xml);
    }

    /**
     * Test that hello request has correct structure.
     *
     * @return void
     */
    public function testHelloRequestStructure()
    {
        $xml = $this->request->getPacket();

        // Load as SimpleXML to test structure
        $simpleXml = simplexml_load_string($xml);

        $this->assertNotFalse($simpleXml);
        $this->assertTrue(isset($simpleXml->hello));
        $this->assertEmpty((string)$simpleXml->hello); // hello should be empty
    }

    /**
     * Test that hello request matches expected format.
     *
     * @return void
     */
    public function testHelloRequestMatchesExpectedFormat()
    {
        $xml = $this->request->getPacket();

        $expectedPattern = '/^<\?xml version="1\.0" encoding="UTF-8" standalone="no"\?>\s*<epp[^>]*>\s*<hello\/>\s*<\/epp>\s*$/s';
        $this->assertMatchesRegularExpression($expectedPattern, $xml);
    }
}
