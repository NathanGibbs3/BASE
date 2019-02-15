<?php
use PHPUnit\Framework\TestCase;

class BlankTest extends TestCase
{
    public function testSomething()
    {
        // Optional: Test anything here, if you want.
        $this->assertTrue(true, 'This should already work.');

        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
          'Placeholder Blank Test.'
        );
    }
}

?>
