<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/constants.inc.php

/**
  * @covers ::SetConst
  */
class constantsTest extends TestCase {

	// Tests go here.
	public function testSetConstSetNotString() {
		$this->assertFalse(
			SetConst(1,'Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetEmpty() {
		$this->assertFalse(
			SetConst('','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetValid() {
		$this->assertTrue(
			SetConst('Valid','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetSuccess() {
		$this->assertFalse(
			SetConst('Valid','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetSuccessValue() {
		$this->assertTrue(defined('Valid'),'CONST not defined');
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
