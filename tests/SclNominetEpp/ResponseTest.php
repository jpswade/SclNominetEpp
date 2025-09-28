<?php

namespace SclNominetEpp;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testSuccessMethodThrowsErrorWhenCodeNotInitialized()
    {
        $this->expectException(\SclNominetEpp\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Response not initialized. Call init() first.');

        $response = new Response();
        $response->success();
    }

    public function testCodeMethodThrowsErrorWhenNotInitialized()
    {
        $this->expectException(\SclNominetEpp\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Response not initialized. Call init() first.');

        $response = new Response();
        $response->code();
    }

    public function testMessageMethodThrowsErrorWhenNotInitialized()
    {
        $this->expectException(\SclNominetEpp\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Response not initialized. Call init() first.');

        $response = new Response();
        $response->message();
    }

    public function testDataMethodThrowsErrorWhenNotInitialized()
    {
        $this->expectException(\SclNominetEpp\Exception\RuntimeException::class);
        $this->expectExceptionMessage('Response not initialized. Call init() first.');

        $response = new Response();
        $response->data();
    }

    public function testSuccessMethodWorksAfterInit()
    {
        $xmlData = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <response>
        <result code="1000">
            <msg>Command completed successfully</msg>
        </result>
        <trID>
            <clTRID>ABC-12345</clTRID>
            <svTRID>54321-XYZ</svTRID>
        </trID>
    </response>
</epp>';

        $response = new Response();
        $response->init($xmlData);

        $this->assertTrue($response->success());
        $this->assertEquals(1000, $response->code());
    }

    public function testSuccessMethodReturnsFalseForErrorCode()
    {
        $xmlData = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
    <response>
        <result code="2000">
            <msg>Unknown command</msg>
        </result>
        <trID>
            <clTRID>ABC-12345</clTRID>
            <svTRID>54321-XYZ</svTRID>
        </trID>
    </response>
</epp>';

        $response = new Response();
        $response->init($xmlData);

        $this->assertFalse($response->success());
        $this->assertEquals(2000, $response->code());
    }
}
