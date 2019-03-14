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
			'Private',
			GetVendor('00006C'),
			'Unexpected Return Value.'
		);
	}
	public function testGetVendorReturnsCantopenvendormap() {
		rename ("./base_mac_prefixes.map","./base_mac_prefixes.tmp");
		$this->assertEquals(
			"Can't open vendor map.",
			GetVendor('00006C'),
			'Unexpected Return Value.'
		);
		rename ("./base_mac_prefixes.tmp","./base_mac_prefixes.map");
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
