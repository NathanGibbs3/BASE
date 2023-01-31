<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php

/**
  * Code Coverage Directives.
  * @covers ::FramedBoxFooter
  * @covers ::FramedBoxHeader
  * @covers ::HBarGraph
  * @covers ::HtmlPercent
  * @covers ::NLI
  * @covers ::NLIO
  * @covers ::PageEnd
  * @covers ::PrintFramedBoxFooter
  * @covers ::PrintFramedBoxHeader
  * @covers ::chk_check
  * @covers ::chk_select
  * @uses ::Htmlcolor
  * @uses ::LoadedString
  * @uses ::Percent
  * @uses ::XSSPrintSafe
  */
class output_htmlTest extends TestCase {
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass() {
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$UOV = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testNLIBlankReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n", NLI(), $URV );
	}
	public function testNLIInvalidCountReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>','string'), $URV );
	}
	public function testNLINoIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>'), $URV );
	}
	public function testNLIIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n\t\t\t\t\t<td>", NLI('<td>',5), $URV );
	}
	public function testNLIOBlankOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n", NLIO(), $UOV );
	}
	public function testNLIOInvalidCountOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>','string'), $UOV );
	}
	public function testNLIONoIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>'), $UOV );
	}
	public function testNLIOIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n\t\t\t\t\t<td>", NLIO('<td>',5), $UOV );
	}
	public function testPageEndOutputsExpected() {
		$UOV = self::$UOV.'PageEnd().';
		$this->expectOutputString( "\n\t</body>\n</html>", PageEnd(), $UOV );
	}
	public function testchk_selectNEReturnsExpected() {
		$URV = self::$URV.'chk_select().';
		$this->assertEquals( ' ', chk_select(1,2), $URV );
	}
	public function testchk_selectEQReturnsExpected() {
		$URV = self::$URV.'chk_select().';
		$this->assertEquals( ' selected', chk_select(1,1), $URV );
	}
	public function testchk_checkNEReturnsExpected() {
		$URV = self::$URV.'chk_check().';
		$this->assertEquals( ' ', chk_check(1,2), $URV );
	}
	public function testchk_checkEQReturnsExpected() {
		$URV = self::$URV.'chk_check().';
		$this->assertEquals( ' checked', chk_check(1,1), $URV );
	}
	public function testHBarGraphDefaultReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$this->assertEquals(
			'<td bgcolor="#ff0000" width="100%">&nbsp;</td>', HBarGraph(), $URV
		);
	}
	public function testHBarGraphPercentReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$pfx = '<td bgcolor="#ff0000" width="';
		$sfx ='%">&nbsp;</td><td bgcolor="#ffffff"></td>';
		for ($i = 1; $i < 100; $i++ ){
			$msg = $pfx . $i . $sfx;
			$this->assertEquals( $msg, HBarGraph($i,100), $URV );
		}
	}
	public function testHBarGraphZeroPercentReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$pfx = '<td bgcolor="#ffffff" width="';
		$sfx ='%">&nbsp;</td>';
		$i = 0;
		$msg = $pfx . '100' . $sfx;
		$this->assertEquals( $msg, HBarGraph($i,100), $URV );
	}
	public function testHBarGraphOverPercentReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$pfx = '<td bgcolor="#ff0000" width="';
		$sfx ='%">&nbsp;</td>';
		$i = 101;
		$msg = $pfx . '100' . $sfx;
		$this->assertEquals( $msg, HBarGraph($i,100), $URV );
	}
	public function testHBarGraphInvalidFGCReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$this->assertEquals(
			'<td bgcolor="#ff0000" width="100%">&nbsp;</td>',
			HBarGraph(1,1,'#yellow'), $URV
		);
	}
	public function testHBarGraphInvalidBGCReturnsExpected() {
		$URV = self::$URV.'HBarGraph().';
		$this->assertEquals(
			'<td bgcolor="#ff0000" width="100%">&nbsp;</td>',
			HBarGraph(1,1,'ff0000','#yellow'), $URV
		);
	}
	public function testHtmlPercentDefaultReturnsExpected() {
		$URV = self::$URV.'HtmlPercent().';
		$sfx = '%';
		$msg = '100' . $sfx;
		$this->assertEquals( $msg, HtmlPercent(), $URV );
	}
	public function testHtmlPercentPercentReturnsExpected() {
		$URV = self::$URV.'HtmlPercent().';
		$sfx = '%';
		for ($i = 1; $i < 100; $i++ ){
			$msg = $i . $sfx;
			$this->assertEquals( $msg, HtmlPercent($i,100), $URV );
		}
	}
	public function testHtmlPercentZeroPercentReturnsExpected() {
		$URV = self::$URV.'HtmlPercent().';
		$sfx ='%';
		$i = 0;
		$msg = '&lt; 1' . $sfx;
		$this->assertEquals( $msg, HtmlPercent($i,100), $URV );
	}
	public function testHtmlPercentOverPercentReturnsExpected() {
		$URV = self::$URV.'HtmlPercent().';
		$sfx ='%';
		$i = 101;
		$msg = '100' . $sfx;
		$this->assertEquals( $msg, HtmlPercent($i,100), $URV );
	}
	public function testFramedBoxHeaderBlankReturnsExpected() {
		$URV = self::$URV.'FramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;'>".
		"\n\t\t\t\t<tr>";
		$this->assertEquals( $msg, FramedBoxHeader(), $URV );
	}
	public function testFramedBoxHeaderInvalidReturnsExpected() {
		$URV = self::$URV.'FramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='&lt;XXS Code&gt;'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>&lt;XXS Code&gt;</td>".
		"\n\t\t\t\t</tr><tr>";
		$this->assertEquals(
			$msg,
			FramedBoxHeader(
				'<XXS Code>','InvaldColor','InvalidNtd>Flag','InvaldiTAB',
				'InvalidAlign', 'InvlaidWdith'
			), $URV
		);
	}
	public function testFramedBoxHeaderTDReturnsExpected() {
		$URV = self::$URV.'FramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;'>".
		"\n\t\t\t\t<tr>\n\t\t\t\t\t<td>";
		$this->assertEquals( $msg, FramedBoxHeader('','',1), $URV );
	}
	public function testFramedBoxHeaderAlignmenteturnsExpected() {
		$URV = self::$URV.'FramedBoxHeader().';
		$Pfx = "\n<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='title'>\n\t<tr>\n\t\t<td class='sectiontitle' ".
		"style='text-align: ";
		$Sfx = ";' colspan='20'>title</td>\n\t</tr><tr>";
		$hal = array( 'left', 'center', 'right' );
		foreach( $hal as $align ){
			$this->assertEquals(
				$Pfx . $align . $Sfx,
				FramedBoxHeader('title','',0,0,$align), $URV
			);
		}
	}
	public function testPrintFramedBoxHeaderBlankOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;'>".
		"\n\t\t\t\t<tr>";
		$this->expectOutputString( $msg, PrintFramedBoxHeader(), $UOV );
	}
	public function testPrintFramedBoxHeaderInvalidOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='&lt;XXS Code&gt;'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>&lt;XXS Code&gt;</td>".
		"\n\t\t\t\t</tr><tr>";
		$this->expectOutputString(
			$msg,
			PrintFramedBoxHeader(
				'<XXS Code>','InvaldColor','InvalidNtd>Flag','InvaldiTAB',
				'InvalidAlign', 'InvlaidWdith'
			), $UOV
		);
	}
	public function testPrintFramedBoxHeaderTDOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxHeader().';
		$msg = "\n\t\t\t<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;'>".
		"\n\t\t\t\t<tr>\n\t\t\t\t\t<td>";
		$this->expectOutputString( $msg, PrintFramedBoxHeader('','',1), $UOV );
	}
	public function testPrintFramedBoxHeaderAlignmentOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxHeader().';
		$Pfx = "\n<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='title'>\n\t<tr>\n\t\t<td class='sectiontitle' ".
		"style='text-align: ";
		$Sfx = ";' colspan='20'>title</td>\n\t</tr><tr>";
		$hal = array( 'left', 'center', 'right' );
		$msg = '';
		foreach( $hal as $align ){
			$msg .= $Pfx . $align . $Sfx;
			$this->expectOutputString(
				$msg, PrintFramedBoxHeader('title','',0,0,$align), $UOV
			);
		}
	}
	public function testFramedBoxFooterBlankReturnsExpected() {
		$URV = self::$URV.'FramedBoxFooter().';
		$msg = "\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->assertEquals( $msg, FramedBoxFooter(), $URV );
	}
	public function testFramedBoxFooterInvalidReturnsExpected() {
		$URV = self::$URV.'FramedBoxFooter().';
		$msg = "\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->assertEquals(
			$msg, FramedBoxFooter('InvalidNtd>Flag','InvaldiTAB'), $URV
		);
	}
	public function testFramedBoxFooterTDReturnsExpected() {
		$URV = self::$URV.'FramedBoxFooter().';
		$msg = "\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->assertEquals( $msg, FramedBoxFooter(1), $URV );
	}
	public function testPrintFramedBoxFooterBlankOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxFooter().';
		$msg = "\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $msg, PrintFramedBoxFooter(), $UOV );
	}
	public function testPrintFramedBoxFooterInvalidOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxFooter().';
		$msg = "\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString(
			$msg, PrintFramedBoxFooter('InvalidNtd>Flag','InvaldiTAB'), $UOV
		);
	}
	public function testPrintFramedBoxFooterTDOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxFooter().';
		$msg = "\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $msg, PrintFramedBoxFooter(1), $UOV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
