<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_query.inc.php

/**
  * Code Coverage Directives.
  * @covers QueryResultsOutput
  * @covers ::qroPrintEntryFooter
  * @covers ::qroPrintEntryHeader
  * @covers ::qroPrintEntry
  * @covers ::qroReturnSelectALLCheck
  * @uses ::ChkAccess
  * @uses ::CleanVariable
  * @uses ::ErrorMessage
  * @uses ::FramedBoxHeader
  * @uses ::HtmlColor
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::PrintFramedBoxHeader
  * @uses ::XSSPrintSafe
  * @uses ::base_array_key_exists
  * @uses ::returnErrorMessage
  */

class output_queryTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;
	var $pfx;
	var $sfx;
	var $cf;

	public static function setUpBeforeClass() {
		GLOBAL $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
	}

	protected function setUp() {
		$this->pfx = '<tr bgcolor="#';
		$this->sfx = '">';
		$this->cf = 1;
	}

	// Tests go here.
	// Check normal running conditions.
	public function testoutput_qroPrintEntryHeaderTestDefault() {
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx . 'FFFFFF' . $sfx, qroPrintEntryHeader(), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestEven() {
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx . 'DDDDDD' . $sfx, qroPrintEntryHeader(2), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestOdd() {
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx . 'FFFFFF' . $sfx, qroPrintEntryHeader(1), $UOV
		);
	}
	// Color Tests
	// From base_conf.php.dist
	// Red, yellow, orange, gray, white, green
	// $priority_colors = array ('FF0000','FFFF00','FF9900','999999','FFFFFF','006600');
	public function testoutput_qroPrintEntryHeaderTestPriColors() {
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		GLOBAL $priority_colors;
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$msg ='';
		for ($i = 1; $i < 7; $i++){
			$msg .= $pfx.$priority_colors[$i-1].$sfx;
			$this->expectOutputString($msg, qroPrintEntryHeader($i,$cf), $UOV);
		}
	}
	// Check for Issue #57
	public function testoutput_qroPrintEntryHeaderTestNULL(){
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx . 'DDDDDD' . $sfx, qroPrintEntryHeader(NULL), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestNULLColor(){
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx . '999999' . $sfx, qroPrintEntryHeader(NULL,$cf), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestInvalidIndexColor(){
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx . '999999' . $sfx, qroPrintEntryHeader(7,$cf), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestNegativeIndexColor(){
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx . '999999' . $sfx, qroPrintEntryHeader(-1,$cf), $UOV
		);
	}
	public function testoutput_qroPrintEntryHeaderTestInvalidStringColor(){
		$UOV = self::$UOV.'qroPrintEntryHeader().';
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx . '999999' . $sfx, qroPrintEntryHeader('red',$cf), $UOV
		);
	}
	public function testoutput_qroPrintEntryTestDefault() {
		$UOV = self::$UOV . 'qroPrintEntry().';
		$expected = "\n\t\t\t".
		"<td style='text-align: center; vertical-align: top; ".
		"padding-left: 15px; padding-right: 15px'>".
		"\n\t\t\t\tvalue".
		"\n\t\t\t</td>";
		$this->expectOutputString($expected, qroPrintEntry('value'), $UOV);
	}
	public function testoutput_qroPrintEntryTestAlignmentValues(){
		$UOV = self::$UOV . 'qroPrintEntry().';
		$expected = "\n\t\t\t".
		"<td style='text-align: left; vertical-align: bottom; ".
		"padding-left: 15px; padding-right: 15px'>".
		"\n\t\t\t\tvalue".
		"\n\t\t\t</td>";
		$this->expectOutputString(
			$expected, qroPrintEntry('value','left','bottom'), $UOV
		);
	}
	public function testoutput_qroPrintEntryFooterTestDefault(){
		$UOV = self::$UOV . 'qroPrintEntryFooter().';
		$expected = "\n\t\t</tr>";
		$this->expectOutputString($expected, qroPrintEntryFooter(), $UOV);
	}
	public function testoutput_qroPrintEntryTestMalformed(){
		$UOV = self::$UOV . 'qroPrintEntry().';
		$expected = "\n\t\t\t".
		"<td style='text-align: left; vertical-align: bottom; ".
		"padding-left: 15px; padding-right: 15px'>".
		"\n\t\t\t\tvalue".
		"\n\t\t\t</td>";
		$this->expectOutputString(
			$expected, qroPrintEntry('value','LEFT','BoTTOm'), $UOV
		);
	}
	public function testoutput_qroPrintEntryTestInvalid() {
		$UOV = self::$UOV . 'qroPrintEntry().';
		$expected = "\n\t\t\t".
		"<td style='text-align: center; vertical-align: top; ".
		"padding-left: 15px; padding-right: 15px'>".
		"\n\t\t\t\tvalue".
		"\n\t\t\t</td>";
		$this->expectOutputString(
			$expected, qroPrintEntry('value','Invalid','Invalid'), $UOV
		);
	}
	public function testoutput_qroReturnSelectALLCheckTestDefault() {
		$URV = self::$URV . 'qroReturnSelectALLCheck().';
		$expected = "<input type=checkbox value='Select All' ".
		"onClick='if (this.checked) SelectAll(); ".
		"if (!this.checked) UnselectAll();'>";
		$this->assertEquals($expected, qroReturnSelectALLCheck(), $URV);
	}
	// Tests for Class QueryResultsOutput
	public function testClassQueryResultsOutputConstruct(){
		$URV = self::$URV . 'Construct().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$this->assertEquals('uri', $tc->url, $URV);
		$this->assertNull($tc->qroHeader, $URV);
		$this->assertEquals(
			'/js/base_output_query.inc.js', $tc->JavaScript, $URV
		);
	}
	public function testClassQueryResultsOutputConstructDebugModeJavaScriptYes(){
		GLOBAL $debug_mode;
		$URV = self::$URV . 'Construct().';
		$odb = $debug_mode;
		$debug_mode = 1;
		$expected = "<font color='black'>Resource available JavaScript: /js/base_output_query.inc.js</font><br/>";
		$this->expectOutputString($expected);
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$debug_mode = $odb;
	}
	public function testClassQueryResultsOutputConstructDebugModeJavaScriptNo(){
		GLOBAL $debug_mode;
		$URV = self::$URV . 'Construct().';
		$odb = $debug_mode;
		$debug_mode = 1;
		$expected = "<font color='#ff0000'>Resource not available JavaScript: /js/base_output_query.inc.js</font><br/>";
		$this->expectOutputString($expected);
		rename ("./js/base_output_query.inc.js","./js/base_output_query.inc.tmp");
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$debug_mode = $odb;
		rename ("./js/base_output_query.inc.tmp","./js/base_output_query.inc.js");
	}
	public function testClassQueryResultsOutputAddTitleDefault(){
		$URV = self::$URV . 'AddTitle().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tmp = 'title';
		$te = $tmp."_ ";
		$tc->AddTitle($tmp);
		$this->assertTrue(is_array($tc->qroHeader), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp]), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp][$te]), $URV); // Asc Sort
		$this->assertEmpty($tc->qroHeader[$tmp][$te][0], $URV);
		$this->assertEmpty($tc->qroHeader[$tmp][$te][1], $URV);
	}
	public function testClassQueryResultsOutputAddTitleFullEmpty(){
		$URV = self::$URV . 'AddTitle().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tmp = 'title';
		$tf = '1st';
		$te1 = $tmp."_$tf";
		$te2 = $tmp."_ ";
		$tc->AddTitle($tmp,$tf);
		$this->assertTrue(is_array($tc->qroHeader), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp]), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp][$te1]), $URV); // Asc Sort
		$this->assertTrue(is_array($tc->qroHeader[$tmp][$te2]), $URV); // Desc Sort
		$this->assertEmpty($tc->qroHeader[$tmp][$te1][0], $URV);
		$this->assertEmpty($tc->qroHeader[$tmp][$te1][1], $URV);
		$this->assertEmpty($tc->qroHeader[$tmp][$te2][0], $URV);
		$this->assertEmpty($tc->qroHeader[$tmp][$te2][1], $URV);
	}
	public function testClassQueryResultsOutputAddTitleFullValued(){
		$URV = self::$URV . 'AddTitle().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tmp = 'title';
		$tf = '1st';
		$ts = '2nd';
		$te1 = $tmp."_$tf";
		$te2 = $tmp."_$ts";
		$tc->AddTitle($tmp,$tf,'1','2',$ts,'3','4');
		$this->assertTrue(is_array($tc->qroHeader), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp]), $URV);
		$this->assertTrue(is_array($tc->qroHeader[$tmp][$te1]), $URV); // Asc Sort
		$this->assertTrue(is_array($tc->qroHeader[$tmp][$te2]), $URV); // Desc Sort
		$this->assertEquals('1',$tc->qroHeader[$tmp][$te1][0], $URV);
		$this->assertEquals('2',$tc->qroHeader[$tmp][$te1][1], $URV);
		$this->assertEquals('3',$tc->qroHeader[$tmp][$te2][0], $URV);
		$this->assertEquals('4',$tc->qroHeader[$tmp][$te2][1], $URV);
	}
	public function testClassQueryResultsOutputGetSortSQL(){
		$URV = self::$URV . 'GetSortSQL().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tc->AddTitle('title','1st','1','2','2nd','3','4');
		$tc->AddTitle('titleSecond','3rd','7','8','4th','5','6');
		$Value = array (
			'title' => array (
				'1st' => array (
					0 => 1,
					1 => 2
				),
				'2nd' => array (
					0 => 3,
					1 => 4
				)
			),
			'title2' => array (
				'3rd' => array (
					0 => 7,
					1 => 8
				),
				'4th' => array (
					0 => 5,
					1 => 6
				)
			)
		);
		$this->assertEquals($Value['title']['1st'],$tc->GetSortSQL('title_1st',''), $URV);
		$this->assertEquals($Value['title']['2nd'],$tc->GetSortSQL('title_2nd',''), $URV);
		$this->assertEquals($Value['title2']['3rd'],$tc->GetSortSQL('titleSecond_3rd',''), $URV);
		$this->assertEquals($Value['title2']['4th'],$tc->GetSortSQL('titleSecond_4th',''), $URV);
		$this->assertNull($tc->GetSortSQL('5th',''), $URV);
	}
	public function testClassQueryResultsOutputGetSortSQLNull(){
		$URV = self::$URV . 'GetSortSQL().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$this->assertNull($tc->GetSortSQL('5th',''), $URV);
	}
	public function testClassQueryResultsOutputPrintHeaderDefault(){
		$UOV = self::$UOV . 'PrintHeader().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tc->AddTitle('title','1st','1','2','2nd','3','4');
		$tc->AddTitle('titleSecond');
		$expected = "\n\t\t\t<script type='text/javascript'".
		" src='/js/base_output_query.inc.js'></script>".
		"\n\t\t\t<!-- Query Results Title Bar -->".
		"\n\t\t\t<table style = 'border: 2px solid black;".
		" border-collapse: collapse; width:100%;'>\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='plfieldhdr' style='text-align:center;'>".
		"\n\t\t\t\t\t\t<a href='uri&amp;sort_order=title_1st'>&lt;</a>".
		"&nbsp;title&nbsp;<a href='uri&amp;sort_order=title_2nd'>&gt;</a>".
		"\n\t\t\t\t\t</td>".
		"\n\t\t\t\t\t<td class='plfieldhdr' style='text-align:center;'>".
		"\n\t\t\t\t\t\ttitleSecond".
		"\n\t\t\t\t\t</td>\n\t\t\t\t</tr>".
		"\n\t\t\t\t<!-- Query Results Table -->";
		$this->expectOutputString($expected, $tc->PrintHeader(), $UOV);
	}
	public function testClassQueryResultsOutputPrintHeaderAlign(){
		$UOV = self::$UOV . 'PrintHeader().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$tc->AddTitle('title');
		$expected = "\n\t\t\t<script type='text/javascript'".
		" src='/js/base_output_query.inc.js'></script>".
		"\n\t\t\t<!-- Query Results Title Bar -->".
		"\n\t\t\t<table style = 'border: 2px solid black;".
		" border-collapse: collapse; width:100%;'>\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='plfieldhdr' style='text-align:center;'>".
		"\n\t\t\t\t\t\ttitle".
		"\n\t\t\t\t\t</td>\n\t\t\t\t</tr>".
		"\n\t\t\t\t<!-- Query Results Table -->";
		$this->expectOutputString($expected, $tc->PrintHeader('left'), $UOV);
	}
	public function testClassQueryResultsOutputPrintHeaderNull(){
		$UOV = self::$UOV . 'PrintHeader().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$expected = "\n\t\t\t<script type='text/javascript'".
		" src='/js/base_output_query.inc.js'></script>".
		"\n\t\t\t<!-- Query Results Title Bar -->".
		"\n\t\t\t<table style = 'border: 2px solid black;".
		" border-collapse: collapse; width:100%;'>\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='plfieldhdr'>".
		"NULL Header.</td>\n\t\t\t\t</tr>".
		"\n\t\t\t\t<!-- Query Results Table -->";
		$this->expectOutputString($expected, $tc->PrintHeader(), $UOV);
	}
	public function testClassQueryResultsOutputPrintHeaderInvalid(){
		$UOV = self::$UOV . 'PrintHeader().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$expected = "\n\t\t\t<script type='text/javascript'".
		" src='/js/base_output_query.inc.js'></script>".
		"\n\t\t\t<!-- Query Results Title Bar -->".
		"\n\t\t\t<table style = 'border: 2px solid black;".
		" border-collapse: collapse; width:100%;'>\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='plfieldhdr'>".
		"NULL Header.</td>\n\t\t\t\t</tr>".
		"\n\t\t\t\t<!-- Query Results Table -->";
		$this->expectOutputString($expected);
		$tc->PrintHeader('Invalid');
	}
	public function testClassQueryResultsOutputPrintFooterDefault(){
		$UOV = self::$UOV . 'PrintHeader().';
		$this->assertInstanceOf(
			'QueryResultsOutput',
			$tc = new QueryResultsOutput('uri'),
			'Class Not Initialized.'
		);
		$expected = "\n\t\t\t</table>";
		$this->expectOutputString($expected, $tc->PrintFooter(), $UOV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
