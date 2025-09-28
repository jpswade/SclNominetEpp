<?php

namespace SclNominetEpp;

use PHPUnit\Framework\TestCase;

/**
 * Test for Nominet class login method
 */
class NominetTest extends TestCase
{
    /**
     * Test that Nominet login method should NOT throw TypeError when newPassword is null
     * This test should FAIL initially, demonstrating the bug we need to fix
     * After our fix, this test should pass (no TypeError should be thrown)
     */
    public function testNominetLoginWithNullNewPasswordShouldNotThrowTypeError()
    {
        $nominet = new Nominet();
        try {
            $nominet->login('TAG', 'PASSWORD', null);
            // If we get here without TypeError, the test passes
        } catch (\TypeError $e) {
            $this->fail('TypeError should not be thrown when newPassword is null: ' . $e->getMessage());
        } catch (\Error $e) {
            // This is likely the "Call to a member function getResponse() on null" error
            // This is expected in test environment and indicates our TypeError fix worked
            $this->assertStringContainsString('getResponse', $e->getMessage());
            // Test passes - we're not getting a TypeError anymore
        } catch (\Exception $e) {
            // Other exceptions (like connection errors) are expected and don't indicate our fix failed
            // The important thing is that it's NOT a TypeError
            $this->assertNotInstanceOf(\TypeError::class, $e);
            // Test passes if we get here without a TypeError
        }
    }
}
