<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_common.php
/**
  * Code Coverage Directives.
  * @covers ::ChkAccess
  * @covers ::ChkCookie
  * @covers ::ChkGet
  * @covers ::ChkLib
  * @covers ::GetAsciiClean
  * @covers ::GetQueryResultID
  * @covers ::GetVendor
  * @covers ::Htmlcolor
  * @covers ::LoadedString
  * @covers ::Percent
  * @covers ::base_array_key_exists
  * @covers ::base_include
  * @uses ::CleanVariable
  * @uses ::XSSPrintSafe
  * @uses ::ErrorMessage
  * @uses ::returnErrorMessage
  */

class base_commonTest extends TestCase {
	// Pre Test Setup.
	protected static $TA;
	protected static $URV;

	public static function setUpBeforeClass(){
		self::$URV = 'Unexpected Return Value: ';
		self::$TA = array (
			null => 'null',
			23 => 'int',
			'test' => 'string',
			'array' => array ()
		);
	}
	public static function tearDownAfterClass(){
		self::$URV = null;
		self::$TA = null;
	}

	// Tests go here.
	public function testGetQueryResultIDReturnsValid(){
		$URV = self::$URV.'GetQueryResultID().';
		$test = '#1111-(2222-3333)';
		$this->assertTrue( GetQueryResultID($test,$a,$b,$c), $URV );
		$this->assertEquals( '1111', $a, $URV );
		$this->assertEquals( '2222', $b, $URV );
		$this->assertEquals( '3333', $c, $URV );
	}
	public function testGetQueryResultIDReturnsInValid(){
		$URV = self::$URV.'GetQueryResultID().';
		$test = '#AAAA-(BBBB-CCCC)';
		$this->assertFalse( GetQueryResultID($test,$a,$b,$c), $URV );
		$this->assertEmpty( $a, $URV );
		$this->assertEmpty( $b, $URV . '2nd' );
		$this->assertEmpty( $c, $URV . '3rd' );
	}
	public function testGetVendorReturnsUnknown(){
		$URV = self::$URV.'GetVendor().';
		$this->assertEquals( 'unknown', GetVendor('FFFFFF'), $URV );
	}
	public function testGetVendorReturnsPrivate(){
		$URV = self::$URV.'GetVendor().';
		$this->assertEquals( 'Private', GetVendor('00006C'), $URV );
	}
	public function testGetVendorReturnsCantopenvendormap(){
		$URV = self::$URV.'GetVendor().';
		rename ("./base_mac_prefixes.map","./base_mac_prefixes.tmp");
		$this->assertEquals(
			"Can't open vendor map.", GetVendor('00006C'), $URV
		);
		rename ("./base_mac_prefixes.tmp","./base_mac_prefixes.map");
	}
	public function testHtmlColorWSC (){
		$URV = self::$URV.'HtmlColor().';
		$wsc = array(
			'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
			'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue',
			'teal', 'aqua'
		);
		foreach($wsc as $tc){
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorPfxHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$tc = '#' . $tc;
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorInvalidWSC (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#yellow'), $URV );
	}
	public function testHtmlColorInvalidHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('af'), $URV );
	}
	public function testHtmlColorInvalidPfHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#af'), $URV );
	}
	public function testPercentDefaultReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$this->assertEquals( '100', Percent(), $URV );
	}
	public function testPercentPercentReturnsExpected(){
		$URV = self::$URV.'Percent().';
		for ($i = 1; $i < 100; $i++ ){
			$msg = $i;
			$this->assertEquals( $msg, Percent($i,100), $URV );
		}
	}
	public function testPercentZeroPercentReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$i = 0;
		$msg = 0;
		$this->assertEquals( $msg, Percent($i,100), $URV );
	}
	public function testPercentOverPercentReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$i = 101;
		$msg = 100;
		$this->assertEquals( $msg, Percent($i,100), $URV );
	}
	public function testPercentNegativeWholeReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$msg = 0;
		$this->assertEquals( $msg, Percent(-2,-1), $URV );
	}
	public function testLoadedStringNotSet(){
		$this->assertFalse(
			LoadedString(null),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadedStringBool(){
		$this->assertFalse(
			LoadedString(false),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadedStringInt(){
		$this->assertFalse(
			LoadedString(1),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadadStringEmpty(){
		$this->assertFalse(
			LoadedString(''),
			'Unexpected return SetConst().'
		);
	}
	public function testLoadedStringFull(){
		$this->assertTrue(
			LoadedString('Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testbase_includeEmpty(){
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
	public function testbase_includeInvalidLoc(){
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
	public function testbase_includeInvalidExt(){
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
	public function testbase_includeInvalidUser(){
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
	public function testbase_includeInvalidFile(){
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
	public function testbase_includeInvalidType(){
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
	public function testbase_includeInvalidLocSym(){
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
	public function testbase_includeValidLoc(){
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
	public function testbase_includeValidExtHtm(){
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
	public function testbase_includeValidExtHtml(){
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
	public function testbase_includeValidCase(){
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
	public function testGetAsciiCleanDefault(){
		$this->assertFalse(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
	}
	public function testGetAsciiCleanGetOff(){
		$_GET['asciiclean'] = 0;
		$this->assertFalse(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_GET['asciiclean']);
	}
	public function testGetAsciiCleanGetOn(){
		$_GET['asciiclean'] = 1;
		$this->assertTrue(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_GET['asciiclean']);
	}
	public function testGetAsciiCleanCookieOff(){
		$_COOKIE['asciiclean'] = 'dirty';
		$this->assertFalse(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['asciiclean']);
	}
	public function testGetAsciiCleanCookieOn(){
		$_COOKIE['asciiclean'] = 'clean';
		$this->assertTrue(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['asciiclean']);
	}
	public function testGetAsciiCleanGetOffCookieOn(){
		$_GET['asciiclean'] = 0;
		$_COOKIE['asciiclean'] = 'clean';
		$this->assertFalse(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['asciiclean']);
		unset ($_GET['asciiclean']);
	}
	public function testChkCookieNotSet(){
		$this->assertFalse(
			ChkCookie('',0),
			'Unexpected Return Value.'
		);
	}
	public function testChkCookieSetWrongName(){
		$_COOKIE['asciiclean'] = 0;
		$this->assertFalse(
			ChkCookie('test',0),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['asciiclean']);
	}
	public function testChkCookieSetWrongValue(){
		$_COOKIE['test'] = 1;
		$this->assertFalse(
			ChkCookie('test',0),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['test']);
	}
	public function testChkCookieSetRightValue(){
		$_COOKIE['test'] = 0;
		$this->assertTrue(
			ChkCookie('test',0),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['test']);
	}
	public function testChkGetNotSet(){
		$this->assertFalse(
			ChkGet('',0),
			'Unexpected Return Value.'
		);
	}
	public function testChkGetSetWrongName(){
		$_GET['asciiclean'] = 0;
		$this->assertFalse(
			ChkGet('test',0),
			'Unexpected Return Value.'
		);
		unset ($_GET['asciiclean']);
	}
	public function testChkGetSetWrongValue(){
		$_GET['test'] = 1;
		$this->assertFalse(
			ChkGet('test',0),
			'Unexpected Return Value.'
		);
		unset ($_GET['test']);
	}
	public function testChkGetSetRightValue(){
		$_GET['test'] = 0;
		$this->assertTrue(
			ChkGet('test',0),
			'Unexpected Return Value.'
		);
		unset ($_GET['test']);
	}
	public function testreturnChkAccessDirectoryTypeInvalid(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$this->assertEquals(
			-1,
			ChkAccess($Testfile),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessDirectoryTypeValid(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$this->assertEquals(
			1,
			ChkAccess($Testfile,'d'),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessInValid(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$file = 'doesnotexist.htm';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals(
			-1,
			ChkAccess($Testfile),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessValid(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals(
			1,
			ChkAccess($Testfile),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessSafeModeCutoutDirectory(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals(
				1,
				ChkAccess($Testfile,'d'),
				'Unexpected return ChkAccess().'
			);
		}
	}
	public function testreturnChkAccessSafeModeCutoutValid(){
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals(
				1,
				ChkAccess($Testfile),
				'Unexpected return ChkAccess().'
			);
		}
	}
	public function testreturnChkLibEmpty(){
		GLOBAL $debug_mode;
		$expected ="<font color='#ff0000'>ChkLib: No Lib specified.</font><br/>";
		$this->assertEquals(
			'',
			ChkLib('','',''),
			'Unexpected return ChkLib().'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			'Unexpected Output.'
		);
		ChkLib('','','');
		$debug_mode = $odb;
	}
	public function testreturnChkLibSepinFile(){
		GLOBAL $debug_mode;
		$sc = DIRECTORY_SEPARATOR;
		$Lib = $sc . 'Graph' . $sc;
		$expected = "Req Lib: ". preg_quote($Lib,'/'). '.*';
		$expected .= 'Mod Lib: Graph';
		$odb = $debug_mode;
		$debug_mode = 2;
		$this->expectOutputRegex(
			"/".$expected."/",
			'Unexpected Output.'
		);
		ChkLib('','',$Lib);
		$debug_mode = $odb;
	}
	public function testreturnChkLibSepinLoc(){
		GLOBAL $debug_mode;
		$sc = DIRECTORY_SEPARATOR;
		$Loc = $sc . 'Image' . $sc;
		$Lib = 'Graph';
		$expected = "Req Loc: ". preg_quote($Loc,'/'). '.*';
		$expected .= "Mod Loc: Image\\$sc";
		$odb = $debug_mode;
		$debug_mode = 2;
		$this->expectOutputRegex(
			"/".$expected."/",
			'Unexpected Output.'
		);
		ChkLib('',$Loc,$Lib);
		$debug_mode = $odb;
	}
	public function testreturnChkLibSepinPath(){
		GLOBAL $debug_mode;
		$sc = DIRECTORY_SEPARATOR;
		// Setup DB Lib Path.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO){
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
		}
		$path =  $DBlib_path . $sc;
		$Lib = 'adodb.inc';
		$expected = "Req Loc: ". preg_quote($path,'/'). '.*';
		$expected .= "Mod Loc: ". preg_quote($DBlib_path,'/');
		$odb = $debug_mode;
		$debug_mode = 2;
		$this->expectOutputRegex(
			"/".$expected."/",
			'Unexpected Output.'
		);
		ChkLib($path,'',$Lib);
		$debug_mode = $odb;
	}
	public function testreturnChkLibValid(){
		GLOBAL $debug_mode;
		$sc = DIRECTORY_SEPARATOR;
		// Setup DB Lib Path.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO){
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
		}
		$path =  $DBlib_path;
		$Lib = 'adodb.inc';
		$expected = "<font color='black'>ChkLib: Chk: $path$sc$Lib".'.php';
		$expected .= "</font><br/><font color='black'>ChkLib: Lib: ";
		$expected .= "$path$sc$Lib".'.php found.</font><br/>';
		$this->assertEquals(
			"$path$sc$Lib".'.php',
			ChkLib($path,'',$Lib),
			'Unexpected return ChkLib().'
		);
		$odb = $debug_mode;
		$debug_mode = 2;
		$this->expectOutputString(
			$expected,
			'Unexpected Output.'
		);
		ChkLib($path,'',$Lib);
		$debug_mode = $odb;
	}
	public function testreturnChkLibNotFound(){
		GLOBAL $debug_mode;
		$sc = DIRECTORY_SEPARATOR;
		// Setup DB Lib Path.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO){
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
		}
		$path =  $DBlib_path;
		$Lib = 'notthere';
		$expected = "<font color='black'>ChkLib: Chk: $path$sc$Lib".'.php';
		$expected .= "</font><br/><font color='red'>ChkLib: Lib: ";
		$expected .= "$path$sc$Lib".'.php not found.</font><br/>';
		$this->assertEquals(
			'',
			ChkLib($path,'',$Lib),
			'Unexpected return ChkLib().'
		);
		$odb = $debug_mode;
		$debug_mode = 2;
		$this->expectOutputString(
			$expected,
			'Unexpected Output.'
		);
		ChkLib($path,'',$Lib);
		$debug_mode = $odb;
	}

	public function testBAKEDefaultReturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$this->assertFalse( base_array_key_exists('notthere', $TA), $URV );
	}
	public function testBAKENonArrayreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$Idx = 'array';
		$this->assertFalse( base_array_key_exists($Idx, $Idx), $URV );
	}
	public function testBAKENullreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$Idx = null;
		$msg = 'null';
		$this->assertTrue( base_array_key_exists($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testBAKEEmptyreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$Idx = '';
		$msg = 'null';
		$this->assertTrue( base_array_key_exists($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testBAKEIntreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$Idx = 23;
		$msg = 'int';
		$this->assertTrue( base_array_key_exists($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testBAKEStringreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$Idx = 'test';
		$msg = 'string';
		$this->assertTrue( base_array_key_exists($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testBAKEArrayreturnsExpected(){
		$URV = self::$URV.'base_array_key_exists().';
		$TA = self::$TA;
		$Idx = 'array';
		$this->assertTrue( base_array_key_exists($Idx, $TA), $URV );
		$this->assertTrue( is_array($TA[$Idx]), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
