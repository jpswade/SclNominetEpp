<?php

namespace SclNominetEpp\Response;

use DateTime;
use PHPUnit\Framework\TestCase;
use SclNominetEpp\Greeting as GreetingObject;
use SimpleXMLElement;

/**
 * Greeting response test.
 */
class GreetingTest extends TestCase
{
    protected Greeting $response;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->response = new Greeting();
    }

    /**
     * Test greeting response functionality.
     *
     * @return void
     */
    public function testGreetingResponse()
    {
        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->response->init($xml);

        $this->assertEquals(1000, $this->response->code());
        $this->assertEquals('Hello', $this->response->message());
        $this->assertInstanceOf(SimpleXMLElement::class, $this->response->data());
        $this->assertEquals(true, $this->response->success());
    }

    /**
     * Test greeting object properties are correctly parsed.
     *
     * @return void
     */
    public function testGreetingObjectProperties()
    {
        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->response->init($xml);

        $greetingObject = $this->getGreetingObject();

        // Test server ID
        $this->assertEquals('Nominet EPP server epp.nominet.org.uk', $greetingObject->getServerId());

        // Test server date
        $serverDate = $greetingObject->getServerDate();
        $this->assertInstanceOf(DateTime::class, $serverDate);
        $this->assertEquals('2018-07-24T08:18:36+00:00', $serverDate->format('c'));

        // Test version
        $this->assertEquals('1.0', $greetingObject->getVersion());

        // Test language
        $this->assertEquals('en', $greetingObject->getLanguage());

        // Test object URIs
        $objectURIs = $greetingObject->getObjectURIs();
        $this->assertCount(6, $objectURIs);
        $this->assertContains('http://www.nominet.org.uk/epp/xml/nom-abuse-feed-1.0', $objectURIs);
        $this->assertContains('urn:ietf:params:xml:ns:contact-1.0', $objectURIs);
        $this->assertContains('urn:ietf:params:xml:ns:domain-1.0', $objectURIs);
        $this->assertContains('urn:ietf:params:xml:ns:host-1.0', $objectURIs);

        // Test extension URIs
        $extensionURIs = $greetingObject->getExtensionURIs();
        $this->assertCount(19, $extensionURIs);
        $this->assertContains('http://www.nominet.org.uk/epp/xml/contact-nom-ext-1.0', $extensionURIs);
        $this->assertContains('urn:ietf:params:xml:ns:secDNS-1.1', $extensionURIs);
        $this->assertContains('urn:ietf:params:xml:ns:validate-0.1', $extensionURIs);

        // Test access
        $this->assertEquals('all', $greetingObject->getAccess());

        // Test purposes
        $purposes = $greetingObject->getPurposes();
        $this->assertCount(2, $purposes);
        $this->assertContains('admin', $purposes);
        $this->assertContains('prov', $purposes);

        // Test recipients
        $recipients = $greetingObject->getRecipients();
        $this->assertCount(1, $recipients);
        $this->assertContains('ours', $recipients);

        // Test retention
        $this->assertEquals('business', $greetingObject->getRetention());
    }

    /**
     * Test greeting response with invalid XML.
     *
     * @return void
     */
    public function testGreetingResponseWithInvalidXml()
    {
        $this->expectException(\SclRequestResponse\Exception\InvalidResponsePacketException::class);
        $this->expectExceptionMessage('XML is not a greeting packet.');

        $invalidXml = '<?xml version="1.0" encoding="UTF-8"?><epp><response><result code="1000"><msg>Test</msg></result></response></epp>';
        $this->response->init($invalidXml);
    }

    /**
     * Test greeting response with malformed XML.
     *
     * @return void
     */
    public function testGreetingResponseWithMalformedXml()
    {
        $this->expectException(\Exception::class);

        $malformedXml = '<?xml version="1.0" encoding="UTF-8"?><epp><greeting><invalid>';
        $this->response->init($malformedXml);
    }

    /**
     * Test that greeting object is properly instantiated.
     *
     * @return void
     */
    public function testGreetingObjectInstantiation()
    {
        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->response->init($xml);

        $greetingObject = $this->getGreetingObject();
        $this->assertInstanceOf(GreetingObject::class, $greetingObject);
    }

    /**
     * Test that all expected object URIs are present.
     *
     * @return void
     */
    public function testAllExpectedObjectURIs()
    {
        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->response->init($xml);

        $greetingObject = $this->getGreetingObject();
        $objectURIs = $greetingObject->getObjectURIs();

        $expectedURIs = [
            'http://www.nominet.org.uk/epp/xml/nom-abuse-feed-1.0',
            'http://www.nominet.org.uk/epp/xml/nom-reseller-1.0',
            'http://www.nominet.org.uk/epp/xml/nom-tag-1.0',
            'urn:ietf:params:xml:ns:contact-1.0',
            'urn:ietf:params:xml:ns:domain-1.0',
            'urn:ietf:params:xml:ns:host-1.0'
        ];

        foreach ($expectedURIs as $expectedURI) {
            $this->assertContains($expectedURI, $objectURIs, "Expected URI {$expectedURI} not found in object URIs");
        }
    }

    /**
     * Test that all expected extension URIs are present.
     *
     * @return void
     */
    public function testAllExpectedExtensionURIs()
    {
        $filename = __DIR__ . '/' . pathinfo(__FILE__, PATHINFO_FILENAME) . '.xml';
        $xml = file_get_contents($filename);
        $this->response->init($xml);

        $greetingObject = $this->getGreetingObject();
        $extensionURIs = $greetingObject->getExtensionURIs();

        $expectedURIs = [
            'http://www.nominet.org.uk/epp/xml/contact-nom-ext-1.0',
            'http://www.nominet.org.uk/epp/xml/domain-nom-ext-1.0',
            'http://www.nominet.org.uk/epp/xml/domain-nom-ext-1.1',
            'http://www.nominet.org.uk/epp/xml/domain-nom-ext-1.2',
            'http://www.nominet.org.uk/epp/xml/nom-data-quality-1.0',
            'http://www.nominet.org.uk/epp/xml/nom-data-quality-1.1',
            'http://www.nominet.org.uk/epp/xml/std-contact-id-1.0',
            'http://www.nominet.org.uk/epp/xml/std-fork-1.0',
            'http://www.nominet.org.uk/epp/xml/std-handshake-1.0',
            'http://www.nominet.org.uk/epp/xml/std-list-1.0',
            'http://www.nominet.org.uk/epp/xml/std-locks-1.0',
            'http://www.nominet.org.uk/epp/xml/std-notifications-1.0',
            'http://www.nominet.org.uk/epp/xml/std-notifications-1.1',
            'http://www.nominet.org.uk/epp/xml/std-notifications-1.2',
            'http://www.nominet.org.uk/epp/xml/std-release-1.0',
            'http://www.nominet.org.uk/epp/xml/std-unrenew-1.0',
            'http://www.nominet.org.uk/epp/xml/std-warning-1.1',
            'urn:ietf:params:xml:ns:secDNS-1.1',
            'urn:ietf:params:xml:ns:validate-0.1'
        ];

        foreach ($expectedURIs as $expectedURI) {
            $this->assertContains(
                $expectedURI,
                $extensionURIs,
                "Expected extension URI {$expectedURI} not found in extension URIs"
            );
        }
    }

    /**
     * Get the greeting object from the response using reflection.
     *
     * @return GreetingObject
     * @throws \ReflectionException
     */
    private function getGreetingObject(): GreetingObject
    {
        $reflection = new \ReflectionClass($this->response);
        $property = $reflection->getProperty('greetingObject');
        $property->setAccessible(true);
        return $property->getValue($this->response);
    }
}
