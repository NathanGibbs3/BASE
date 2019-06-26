<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_error.inc.php

/**
  * @covers ::returnErrorMessage
  */
class log_errorTest extends TestCase {
	// Tests go here.
	public function testreturnErrorMessageDefault() {
		$this->assertEquals(
			'<font color="#ff0000">message</font>',
			returnErrorMessage('message'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageInvalidColor() {
		$this->assertEquals(
			'<font color="#ff0000">message</font>',
			returnErrorMessage('message','Invalid'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageValidColor() {
		$this->assertEquals(
			'<font color="#0000ff">message</font>',
			returnErrorMessage('message','#0000ff'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageInvalidBr() {
		$this->assertEquals(
			'<font color="#0000ff">message</font>',
			returnErrorMessage('message','#0000ff','yes'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageBr() {
		$this->assertEquals(
			'<font color="#0000ff">message</font><br/>',
			returnErrorMessage('message','#0000ff',1),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::ErrorMessage
	  */
	public function testErrorMessageDefault() {
		$this->expectOutputString(
			'<font color="#ff0000">message</font>',
			ErrorMessage('message'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::ErrorMessage
	  */
	public function testErrorMessageInvalidColor() {
		$this->expectOutputString(
			'<font color="#ff0000">message</font>',
			ErrorMessage('message','Invalid'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::ErrorMessage
	  */
	public function testErrorMessageValidColor() {
		$this->expectOutputString(
			'<font color="#0000ff">message</font>',
			ErrorMessage('message','#0000ff'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::ErrorMessage
	  */
	public function testErrorMessageInvalidBr() {
		$this->expectOutputString(
			'<font color="#0000ff">message</font>',
			ErrorMessage('message','#0000ff','yes'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::ErrorMessage
	  */
	public function testErrorMessageBr() {
		$this->expectOutputString(
			'<font color="#0000ff">message</font><br/>',
			ErrorMessage('message','#0000ff',1),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
