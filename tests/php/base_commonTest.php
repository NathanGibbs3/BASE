<?php
use PHPUnit\Framework\TestCase;

class base_commonTest extends TestCase {
	// Tests go here.
	public function testGetVendorReturnsUnknown() {
		$this->assertEquals(
			'unknown',
			GetVendor(001266),
			'Unexpected Return Value.'
		);
		// Stop here and mark test incomplete.
		//$this->markTestIncomplete(
		//	'Incomplete Test.'
		//);
	}
}

?>
