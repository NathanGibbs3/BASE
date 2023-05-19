<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php

/**
  * Code Coverage Directives.
  * @covers ::FramedBoxFooter
  * @covers ::FramedBoxHeader
  * @covers ::HBarGraph
  * @covers ::HtmlPercent
  * @covers ::Icon
  * @covers ::LINext
  * @covers ::PageEnd
  * @covers ::TblNewRow
  * @covers ::PrintFramedBoxFooter
  * @covers ::PrintFramedBoxHeader
  * @covers ::PrintIcon
  * @covers ::PrintLINext
  * @covers ::PrintTblNewRow
  * @covers ::chk_check
  * @covers ::chk_select
  * @covers ::returnExportHTTPVar
  * @uses ::CleanVariable
  * @uses ::ChkAccess
  * @uses ::GetPHPSV
  * @uses ::Htmlcolor
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
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
		"colspan='20'>\n\t\t\t\t\t\t&lt;XXS Code&gt;\n\t\t\t\t\t</td>".
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
		"\n\t\t\t\t<tr>\n\t\t\t\t\t<td style='text-align: center;'>";
		$this->assertEquals( $msg, FramedBoxHeader('','',1), $URV );
	}
	public function testFramedBoxHeaderAlignmentreturnsExpected() {
		$URV = self::$URV.'FramedBoxHeader().';
		$Pfx = "\n<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='title'>\n\t<tr>\n\t\t<td class='sectiontitle' ".
		"style='text-align: ";
		$Sfx = ";' colspan='20'>\n\t\t\ttitle\n\t\t</td>\n\t</tr><tr>";
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
		"colspan='20'>\n\t\t\t\t\t\t&lt;XXS Code&gt;\n\t\t\t\t\t</td>".
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
		"\n\t\t\t\t<tr>\n\t\t\t\t\t<td style='text-align: center;'>";
		$this->expectOutputString( $msg, PrintFramedBoxHeader('','',1), $UOV );
	}
	public function testPrintFramedBoxHeaderAlignmentOutputsExpected() {
		$UOV = self::$UOV.'PrintFramedBoxHeader().';
		$Pfx = "\n<table style = ".
		"'border: 2px solid black; border-collapse: collapse; width:100%;' ".
		"summary='title'>\n\t<tr>\n\t\t<td class='sectiontitle' ".
		"style='text-align: ";
		$Sfx = ";' colspan='20'>\n\t\t\ttitle\n\t\t</td>\n\t</tr><tr>";
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
	public function testTblNewRowBlankReturnsExpected() {
		$URV = self::$URV.'TblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>";
		$this->assertEquals( $msg, TblNewRow(), $URV );
	}
	public function testTblNewRowInvalidReturnsExpected() {
		$URV = self::$URV.'TblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>";
		$this->assertEquals(
			$msg, TblNewRow('InvalidNtd>Flag','InvalidAlign','InvaldiTAB'), $URV
		);
	}
	public function testTblNewRowTDReturnsExpected() {
		$URV = self::$URV.'TblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>\n\t\t\t<td>";
		$this->assertEquals( $msg, TblNewRow(1), $URV );
	}
	public function testTblNewRowAlignmentReturnsExpected() {
		$URV = self::$URV.'TblNewRow().';
		$Pfx = "\n\t\t\t</td>\n\t\t</tr><tr>\n\t\t\t<td style='text-align: ";
		$Sfx = ";'>";
		$hal = array( 'left', 'center', 'right' );
		foreach( $hal as $align ){
			$this->assertEquals(
				$Pfx . $align . $Sfx,
				TblNewRow(1, $align), $URV
			);
		}
	}
	public function testPrintTblNewRowBlankReturnsExpected() {
		$UOV = self::$UOV.'PrintTblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>";
		$this->expectOutputString( $msg, PrintTblNewRow(), $UOV );
	}
	public function testPrintTblNewRowInvalidReturnsExpected() {
		$UOV = self::$UOV.'PrintTblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>";
		$this->expectOutputString(
			$msg, PrintTblNewRow('InvalidNtd>Flag','InvalidAlign','InvaldiTAB'), $UOV
		);
	}
	public function testPrintTblNewRowTDReturnsExpected() {
		$UOV = self::$UOV.'PrintTblNewRow().';
		$msg = "\n\t\t\t</td>\n\t\t</tr><tr>\n\t\t\t<td>";
		$this->expectOutputString( $msg, PrintTblNewRow(1), $UOV );
	}
	public function testPrintTblNewRowAlignmentReturnsExpected() {
		$UOV = self::$UOV.'PrintTblNewRow().';
		$Pfx = "\n\t\t\t</td>\n\t\t</tr><tr>\n\t\t\t<td style='text-align: ";
		$Sfx = ";'>";
		$hal = array( 'left', 'center', 'right' );
		$msg = '';
		foreach( $hal as $align ){
			$msg .= $Pfx . $align . $Sfx;
			$this->expectOutputString( $msg, PrintTblNewRow(1, $align), $UOV );
		}
	}
	public function testLINextDefaultReturnsExpected() {
		$URV = self::$URV.'LINext().';
		$msg = "\n\t\t\t</li><li>";
		$this->assertEquals( $msg, LINext(), $URV );
	}
	public function testLINextInvalidReturnsExpected() {
		$URV = self::$URV.'LINext().';
		$msg = "\n\t\t\t</li><li>";
		$this->assertEquals( $msg, LINext('string'), $URV );
	}
	public function testPrintLINextDefaultReturnsExpected() {
		$UOV = self::$UOV.'PrintLINext().';
		$msg = "\n\t\t\t</li><li>";
		$this->expectOutputString( $msg, PrintLINext(), $UOV );
	}
	public function testPrintLINextInvalidReturnsExpected() {
		$UOV = self::$UOV.'PrintLINext().';
		$msg = "\n\t\t\t</li><li>";
		$this->expectOutputString( $msg, PrintLINext('string'), $UOV );
	}
	public function testIconDefaultReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$msg = '';
		$this->assertEquals( $msg, Icon(), $URV );
	}
	public function testIconNonExistentReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'notthere';
		$msg = '';
		$this->assertEquals( $msg, Icon($Icon), $URV );
	}
	public function testIconInvalidFileNameReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'del-ete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->assertEquals( $msg, Icon($Icon), $URV );
	}
	public function testIconValidFileNameReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->assertEquals( $msg, Icon($Icon), $URV );
	}
	public function testIconInvalidDescReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'delete';
		$Desc = 'button-delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' "
		. "alt='buttondelete' />";
		$this->assertEquals( $msg, Icon($Icon, $Desc), $URV );
	}
	public function testIconValidDescReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'delete';
		$Desc = 'button_delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' "
		. "alt='button_delete' />";
		$this->assertEquals( $msg, Icon($Icon, $Desc), $URV );
	}
	public function testIconInvalidTabReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->assertEquals( $msg, Icon($Icon, '', 'InvalidTab'), $URV );
	}
	public function testIconValidTabReturnsExpected() {
		$URV = self::$URV.'Icon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->assertEquals( $msg, Icon($Icon, '', 4), $URV );
	}
	public function testPrintIconDefaultReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$msg = '';
		$this->expectOutputString( $msg, PrintIcon(), $UOV );
	}
	public function testPrintIconNonExistentReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'notthere';
		$msg = '';
		$this->expectOutputString( $msg, PrintIcon($Icon), $UOV );
	}
	public function testPrintIconInvalidFileNameReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'del-ete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->expectOutputString( $msg, PrintIcon($Icon), $UOV );
	}
	public function testPrintIconValidFileNameReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->expectOutputString( $msg, PrintIcon($Icon), $UOV );
	}
	public function testPrintIconInvalidDescReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'delete';
		$Desc = 'button-delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' "
		. "alt='buttondelete' />";
		$this->expectOutputString( $msg, PrintIcon($Icon, $Desc), $UOV );
	}
	public function testPrintIconValidDescReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'delete';
		$Desc = 'button_delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' "
		. "alt='button_delete' />";
		$this->expectOutputString( $msg, PrintIcon($Icon, $Desc), $UOV );
	}
	public function testPrintIconInvalidTabReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->expectOutputString( $msg, PrintIcon($Icon, '', 'InvalidTab'), $UOV );
	}
	public function testPrintIconValidTabReturnsExpected() {
		$UOV = self::$UOV.'PrintIcon().';
		$Icon = 'delete';
		$msg = "\n\t\t\t\t<img class='icon' src='images/base_icon_0_delete.png' />";
		$this->expectOutputString( $msg, PrintIcon($Icon, '', 4), $UOV );
	}
	public function testreturnExportHTTPVarDefaults() {
		$URV = self::$URV.'returnExportHTTPVar().';
		$msg = '';
		$this->assertEquals( $msg, returnExportHTTPVar(), $URV );
	}
	public function testreturnExportHTTPVarNameValid() {
		$URV = self::$URV.'returnExportHTTPVar().';
		$msg = "\n\t\t\t<input type='hidden' name='Test' value=''/>";
		$this->assertEquals( $msg, returnExportHTTPVar('Test'), $URV );
	}
	public function testreturnExportHTTPVarNameValue() {
		$URV = self::$URV.'returnExportHTTPVar().';
		$msg = "\n\t\t\t<input type='hidden' name='Test' value='TestVal'/>";
		$this->assertEquals(
			$msg, returnExportHTTPVar('Test', 'TestVal'), $URV
		);
	}
	public function testreturnExportHTTPVarTabInvalid() {
		$URV = self::$URV.'returnExportHTTPVar().';
		$msg = "\n\t\t\t<input type='hidden' name='Test' value=''/>";
		$this->assertEquals(
			$msg, returnExportHTTPVar('Test', '', 'String'), $URV
		);
	}
	public function testreturnExportHTTPVarTabValid() {
		$URV = self::$URV.'returnExportHTTPVar().';
		$msg = "\n\t\t\t\t<input type='hidden' name='Test' value=''/>";
		$this->assertEquals( $msg, returnExportHTTPVar('Test', '', 4), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
