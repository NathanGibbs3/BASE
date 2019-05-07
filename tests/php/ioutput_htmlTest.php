<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php

/**
  * @covers ::NLI
  */
class output_htmlTest extends TestCase {
	// Tests go here.
	public function testNLIBlankReturnsExpected() {
		$this->assertEquals(
			"\n",
			NLI(),
			'Unexpected Return Value.'
		);
	}
	public function testNLIInvalidCountReturnsExpected() {
		$this->assertEquals(
			"\n<td>",
			NLI('<td>','string'),
			'Unexpected Return Value.'
		);
	}
	public function testNLINoIndentReturnsExpected() {
		$this->assertEquals(
			"\n<td>",
			NLI('<td>'),
			'Unexpected Return Value.'
		);
	}
	public function testNLIIndentReturnsExpected() {
		$this->assertEquals(
			"\n\t\t\t\t\t<td>",
			NLI('<td>',5),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::NLIO
	  */
	public function testNLIOBlankOutputsExpected() {
		$this->expectOutputString(
			"\n",
			NLIO(),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::NLIO
	  */
	public function testNLIOInvalidCountReturnsExpected() {
		$this->expectOutputString(
			"\n<td>",
			NLIO('<td>','string'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::NLIO
	  */
	public function testNLIONoIndentOutputsExpected() {
		$this->expectOutputString(
			"\n<td>",
			NLIO('<td>'),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::NLIO
	  */
	public function testNLIOIndentOutputsExpected() {
		$this->expectOutputString(
			"\n\t\t\t\t\t<td>",
			NLIO('<td>',5),
			'Unexpected Return Value.'
		);
	}
	/**
	  * @covers ::PageEnd
	  * @uses ::NLIO
	  */
	public function testPageEndOutputsExpected() {
		$this->expectOutputString(
			"\n\t</body>\n</html>",
			PageEnd(),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
