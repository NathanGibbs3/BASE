<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_common.inc.php
/**
  * @covers ::XSSPrintSafe
  */
class state_commonTest extends TestCase {
	// Tests go here.
	public function testXSSPrintSafeNullReturnsNull() {
		$this->assertNull(
			XSSPrintSafe(NULL),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeValueReturnsNotNull() {
		$this->assertNotNull(
			XSSPrintSafe('Value'),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformValue() {
		$this->assertEquals(
			'Value',
			XSSPrintSafe('Value'),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformValue() {
		$Value = '&"<>';
		$this->assertEquals(
			'&amp;&quot;&lt;&gt;',
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformNonKeyedArray() {
		$Value = array (1,2,3,4);
		$this->assertEquals(
			array(1,2,3,4),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformNonKeyedArray() {
		$Value = array ('&"<>',1,2,3,4);
		$this->assertEquals(
			array('&amp;&quot;&lt;&gt;',1,2,3,4),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformKeyedArray() {
		$Value = array (
			'key1' => 0,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '0',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformKeyedArray() {
		$Value = array (
			'key1' => '&"<>',
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '&amp;&quot;&lt;&gt;',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
