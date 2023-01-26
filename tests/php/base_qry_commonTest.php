<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_qry_common.php
/**
  * @covers ::FormatTimeDigit
  */

class base_qry_commonTest extends TestCase {
	// Tests go here.
	public function testFormatTimeDigitSpaces(){
		$this->assertEquals(
			'01',
			FormatTimeDigit(' 1 '),
			'Unexpected Return Value.'
		);
	}
	public function testFormatTimeDigit1Digit(){
		$this->assertEquals(
			'01',
			FormatTimeDigit('1'),
			'Unexpected Return Value.'
		);
	}
	public function testFormatTimeDigit2Digit(){
		$this->assertEquals(
			'01',
			FormatTimeDigit(' 01 '),
			'Unexpected Return Value.'
		);
	}
	public function testFormatTimeDigitString(){
		$this->assertEquals(
			'00',
			FormatTimeDigit(' aa '),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
