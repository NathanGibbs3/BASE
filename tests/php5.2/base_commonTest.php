<?php

class base_commonTest extends PHPUnit_Framework_TestCase {
	// Tests go here.
	public function testGetVendorReturnsUnknown() {
		$this->assertEquals(
			'unknown',
			GetVendor('FFFFFF'),
			'Unexpected Return Value.'
		);
	}
	public function testGetVendorReturnsPrivate() {
		$this->assertEquals(
			' Private',
			GetVendor('00006C'),
			'Unexpected Return Value.'
		);
		// Stop here and mark test incomplete.
		//$this->markTestIncomplete(
		//	'Incomplete Test.'
		//);
	}
}

?>
