<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php

/**
  * @covers ::NLI
  * @covers ::NLIO
  * @covers ::PageEnd
  * @covers ::chk_select
  * @covers ::chk_check
  * @covers ::HBarGraph
  * @covers ::HtmlPercent
  * @uses ::Htmlcolor
  * @uses ::Percent
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
	public function testNLIOBlankOutputsExpected() {
		$this->expectOutputString(
			"\n",
			NLIO(),
			'Unexpected Return Value.'
		);
	}
	public function testNLIOInvalidCountOutputsExpected() {
		$this->expectOutputString(
			"\n<td>",
			NLIO('<td>','string'),
			'Unexpected Return Value.'
		);
	}
	public function testNLIONoIndentOutputsExpected() {
		$this->expectOutputString(
			"\n<td>",
			NLIO('<td>'),
			'Unexpected Return Value.'
		);
	}
	public function testNLIOIndentOutputsExpected() {
		$this->expectOutputString(
			"\n\t\t\t\t\t<td>",
			NLIO('<td>',5),
			'Unexpected Return Value.'
		);
	}
	public function testPageEndOutputsExpected() {
		$this->expectOutputString(
			"\n\t</body>\n</html>",
			PageEnd(),
			'Unexpected Return Value.'
		);
	}
	public function testchk_selectNEReturnsExpected() {
		$this->assertEquals(
			' ',
			chk_select(1,2),
			'Unexpected Return Value.'
		);
	}
	public function testchk_selectEQReturnsExpected() {
		$this->assertEquals(
			' selected',
			chk_select(1,1),
			'Unexpected Return Value.'
		);
	}
	public function testchk_checkNEReturnsExpected() {
		$this->assertEquals(
			' ',
			chk_check(1,2),
			'Unexpected Return Value.'
		);
	}
	public function testchk_checkEQReturnsExpected() {
		$this->assertEquals(
			' checked',
			chk_check(1,1),
			'Unexpected Return Value.'
		);
	}
	public function testHBarGraphDefaultReturnsExpected() {
		$this->assertEquals(
			'<td bgcolor="#ff0000" width="100%">&nbsp;</td>',
			HBarGraph(),
			'Unexpected Return Value.'
		);
	}
	public function testHBarGraphPercentReturnsExpected() {
		$pfx = '<td bgcolor="#ff0000" width="';
		$sfx ='%">&nbsp;</td><td bgcolor="#ffffff"></td>';
		for ($i = 1; $i < 100; $i++ ){
			$msg = $pfx . $i . $sfx;
			$this->assertEquals(
				$msg,
				HBarGraph($i,100),
				'Unexpected Return Value.'
			);
		}
	}
	public function testHBarGraphZeroPercentReturnsExpected() {
		$pfx = '<td bgcolor="#ffffff" width="';
		$sfx ='%">&nbsp;</td>';
		$i = 0;
		$msg = $pfx . '100' . $sfx;
		$this->assertEquals(
			$msg,
			HBarGraph($i,100),
			'Unexpected Return Value.'
		);
	}
	public function testHBarGraphOverPercentReturnsExpected() {
		$pfx = '<td bgcolor="#ff0000" width="';
		$sfx ='%">&nbsp;</td>';
		$i = 101;
		$msg = $pfx . '100' . $sfx;
		$this->assertEquals(
			$msg,
			HBarGraph($i,100),
			'Unexpected Return Value.'
		);
	}
	public function testHtmlPercentDefaultReturnsExpected() {
		$sfx = '%';
		$msg = '100' . $sfx;
		$this->assertEquals(
			$msg,
			HtmlPercent(),
			'Unexpected Return Value.'
		);
	}
	public function testHtmlPercentPercentReturnsExpected() {
		$sfx = '%';
		for ($i = 1; $i < 100; $i++ ){
			$msg = $i . $sfx;
			$this->assertEquals(
				$msg,
				HtmlPercent($i,100),
				'Unexpected Return Value.'
			);
		}
	}
	public function testHtmlPercentZeroPercentReturnsExpected() {
		$sfx ='%';
		$i = 0;
		$msg = '&lt; 1' . $sfx;
		$this->assertEquals(
			$msg,
			HtmlPercent($i,100),
			'Unexpected Return Value.'
		);
	}
	public function testHtmlPercentOverPercentReturnsExpected() {
		$sfx ='%';
		$i = 101;
		$msg = '100' . $sfx;
		$this->assertEquals(
			$msg,
			HtmlPercent($i,100),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
