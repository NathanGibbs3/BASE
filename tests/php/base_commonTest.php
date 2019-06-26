<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /base_common.php
/**
  * @covers ::GetQueryResultID
  * @covers ::GetVendor
  * @uses ::CleanVariable
  */

class base_commonTest extends TestCase {
	// Tests go here.
	public function testGetQueryResultIDReturnsValid() {
		$test = '#1111-(2222-3333)';
		$this->assertTrue(
			GetQueryResultID($test,$a,$b,$c),
			'Unexpected Return Value.'
		);
		$this->assertEquals(
			'1111',
			$a,
			'Unexpected Return Value.'
		);
		$this->assertEquals(
			'2222',
			$b,
			'Unexpected Return Value.'
		);
		$this->assertEquals(
			'3333',
			$c,
			'Unexpected Return Value.'
		);
	}
	public function testGetQueryResultIDReturnsInValid() {
		$test = '#AAAA-(BBBB-CCCC)';
		$this->assertFalse(
			GetQueryResultID($test,$a,$b,$c),
			'Unexpected Return Value.'
		);
		$this->assertEmpty(
			$a,
			'Unexpected Return Value.'
		);
		$this->assertEmpty(
			$b,
			'Unexpected 2nd Return Value.'
		);
		$this->assertEmpty(
			$c,
			'Unexpected 3rd Return Value.'
		);
	}
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
