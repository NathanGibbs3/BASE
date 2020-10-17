<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_common.php
/**
  * @covers ::GetQueryResultID
  * @covers ::GetVendor
  * @covers ::Htmlcolor
  * @covers ::LoadedString
  * @covers ::Percent
  * @covers ::base_include
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
	public function testHtmlColorWSC () {
		$wsc = array(
			'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
			'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue',
			'teal', 'aqua'
		);
		foreach($wsc as $tc){
			$this->assertTrue(
				HtmlColor($tc),
				'Unexpected Return Value.'
			);
		}
	}
	public function testHtmlColorHex () {
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$this->assertTrue(
				HtmlColor($tc),
				'Unexpected Return Value.'
			);
		}
	}
	public function testHtmlColorPfxHex () {
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$tc = '#' . $tc;
			$this->assertTrue(
				HtmlColor($tc),
				'Unexpected Return Value.'
			);
		}
	}
	public function testHtmlColorInvalidWSC () {
		$this->assertFalse(
			HtmlColor('#yellow'),
			'Unexpected Return Value.'
		);
	}
	public function testHtmlColorInvalidHex () {
		$this->assertFalse(
			HtmlColor('af'),
			'Unexpected Return Value.'
		);
	}
	public function testHtmlColorInvalidPfHex () {
		$this->assertFalse(
			HtmlColor('#af'),
			'Unexpected Return Value.'
		);
	}
	public function testPercentDefaultReturnsExpected() {
		$this->assertEquals(
			'100',
			Percent(),
			'Unexpected Return Value.'
		);
	}
	public function testPercentPercentReturnsExpected() {
		for ($i = 1; $i < 100; $i++ ){
			$msg = $i;
			$this->assertEquals(
				$msg,
				Percent($i,100),
				'Unexpected Return Value.'
			);
		}
	}
	public function testPercentZeroPercentReturnsExpected() {
		$i = 0;
		$msg = 0;
		$this->assertEquals(
			$msg,
			Percent($i,100),
			'Unexpected Return Value.'
		);
	}
	public function testPercentOverPercentReturnsExpected() {
		$i = 101;
		$msg = 100;
		$this->assertEquals(
			$msg,
			Percent($i,100),
			'Unexpected Return Value.'
		);
	}
	public function testPercentNegativeWholeReturnsExpected() {
		$msg = 0;
		$this->assertEquals(
			$msg,
			Percent(-2,-1),
			'Unexpected Return Value.'
		);
	}
	public function testLoadedStringNotSet() {
		$this->assertFalse(
			LoadedString(null),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadedStringNotString() {
		$this->assertFalse(
			LoadedString(1),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadadStringEmpty() {
		$this->assertFalse(
			LoadedString(''),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadedStringFull() {
		$this->assertTrue(
			LoadedString('Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testbase_includeEmpty() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = '';
		$expected = "Test: $file\n";
		$expected .= "File: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidLoc() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = '/etc/passwd';
		$expected = "Test: $file\n";
		$expected .= "File: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidExt() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testext.php';
		$expected = "Test: $file\n";
		$expected .= "File: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidUser() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testuser.htm';
		$expected = "Test: $file\n";
		$expected .= "Access: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidFile() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'doesnotexist.htm';
		$expected = "Test: $file\n";
		$expected .= "Access: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidType() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testdir.htm';
		$expected = "Test: $file\n";
		$expected .= "Access: $BASE_path/custom/$file\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeInvalidLocSym() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testsym.htm';
		$expected = "Test: $file\n";
		$expected .= "Loc: $BASE_path/custom/$file -> /etc/BASEtestsym.htm\n";
		$this->assertFalse(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeValidLoc() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testsymok.htm';
		$expected = "Test: $file\n";
		$expected .= "OK: $BASE_path/custom/$file\n";
		$this->assertTrue(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeValidExtHtm() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testhtm.htm';
		$expected = "Test: $file\n";
		$expected .= "OK: $BASE_path/custom/$file\n";
		$this->assertTrue(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeValidExtHtml() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testhtml.html';
		$expected = "Test: $file\n";
		$expected .= "OK: $BASE_path/custom/$file\n";
		$this->assertTrue(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}
	public function testbase_includeValidCase() {
		GLOBAL $BASE_path, $debug_mode;
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$file = 'testCASE.HTML';
		$expected = "Test: $file\n";
		$expected .= "OK: $BASE_path/custom/$file\n";
		$this->assertTrue(
			base_include($file),
			$URV
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			$UOV
		);
		base_include($file);
		$debug_mode = $odb;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
