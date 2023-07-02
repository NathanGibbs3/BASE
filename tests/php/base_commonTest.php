<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: base_common.php

/**
  * Code Coverage Directives.
  * @covers ::BCS
  * @covers ::BuildIPFormVar
  * @covers ::ChkArchive
  * @covers ::ChkCookie
  * @covers ::ChkGet
  * @covers ::ChkLib
  * @covers ::GetAsciiClean
  * @covers ::GetQueryResultID
  * @covers ::GetVendor
  * @covers ::PearInc
  * @covers ::Percent
  * @covers ::base_include
  * @uses ::ChkAccess
  * @uses ::CleanVariable
  * @uses ::ErrorMessage
  * @uses ::GetPHPSV
  * @uses ::HtmlColor
  * @uses ::LibIncError
  * @uses ::LoadedString
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::VS2SV
  * @uses ::is_ip
  * @uses ::is_ip4
  * @uses ::is_ip6
  * @uses ::returnErrorMessage
  */

class base_commonTest extends TestCase {
	// Pre Test Setup.
	protected static $TA;
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass(){
		GLOBAL $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
		self::$TA = array (
			null => 'null',
			23 => 'int',
			'test' => 'string',
			'array' => array ()
		);
	}
	public static function tearDownAfterClass(){
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
		self::$TA = null;
		self::$PHPUV = null;
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
	public function testPercentDefaultReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$this->assertEquals('100', Percent(), $URV);
	}
	public function testPercentInvalidReturnsExpected(){
		$URV = self::$URV.'Percent().';
		$this->assertEquals('100', Percent('string', 'string'), $URV);
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
		$_COOKIE['asciiclean'] = 1;
		$this->assertTrue(
			GetAsciiClean(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['asciiclean']);
	}
	public function testGetAsciiCleanGetOffCookieOn(){
		$_GET['asciiclean'] = 0;
		$_COOKIE['asciiclean'] = 1;
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

	public function testreturnChkLibEmpty(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
		$EOM = 'ChkLib: No Lib specified.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals('', ChkLib('','',''), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkLibSepinFile(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
		$sc = DIRECTORY_SEPARATOR;
		$Lib = $sc . 'Graph' . $sc;
		$EOM = preg_quote("Req Lib: $Lib", '/') . '\n.*';
		$EOM .= 'Mod Lib\: Graph';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals('', ChkLib('','',$Lib), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp( '/' . $EOM . '/', $elOutput, $UOV );
		}
	}
	public function testreturnChkLibSepinLoc(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
		$sc = DIRECTORY_SEPARATOR;
		$Loc = $sc . 'Image' . $sc;
		$Lib = 'Graph';
		$EOM = preg_quote("Req Loc: $Loc", '/'). '\n.*';
		$EOM .= "Mod Loc\: Image\\$sc";
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		ChkLib('',$Loc,$Lib);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkLibSepinPath(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
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
		$EOM = preg_quote("Req Loc: $path", '/'). '\n.*';
		$EOM .= preg_quote("Mod Loc: $DBlib_path", '/');
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals("$path$Lib" . '.php', ChkLib($path,'',$Lib), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkLibValid(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
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
		$EOM = preg_quote("ChkLib: Chk: $path$sc$Lib", '/') . '\.php\n.*';
		$EOM .= preg_quote("ChkLib: Lib: $path$sc$Lib", '/') . '\.php found\.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(
			"$path$sc$Lib" . '.php', ChkLib($path,'',$Lib), $URV
		);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkLibNotFound(){
		$URV = self::$URV . 'ChkLib().';
		$UOV = self::$UOV . 'ChkLib().';
		$PHPUV = self::$PHPUV;
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
		$EOM = preg_quote("ChkLib: Chk: $path$sc$Lib", '/') . '\.php\n.*';
		$EOM .= preg_quote("ChkLib: Lib: $path$sc$Lib", '/')
		. '\.php not found\.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals( '', ChkLib($path,'',$Lib), $URV );
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}

	public function testreturnPearincEmpty(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		GLOBAL $debug_mode;
		$EOM ="<font color='#ff0000'>PearInc: No Lib specified.</font><br/>";
		$this->assertFalse( PearInc(), $URV );
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString( $EOM, PearInc(), $UOV );
		$debug_mode = $odb;
	}
	public function testreturnPearincInvalidLib(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		GLOBAL $debug_mode;
		$Lib = 'Notthere';
		$EOM ="<font color='#ff0000'>PearInc: Notthere Lib: /Notthere.php ".
		'not accessable.</font><br/>';
		$this->assertFalse( PearInc('','',$Lib), $URV );
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString( $EOM, PearInc('','',$Lib), $UOV );
		$debug_mode = $odb;
	}
	public function testreturnPearincInvalidLibDisplayError(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		$Lib = 'Notthere';
		$EOM = "<font color='black'><b>Error loading the Notthere library:".
		'</b> from &quot;&quot;.<br/>The underlying Notthere library '.
		'currently used is Notthere, that can be downloaded at '.
		"<a href='https://pear.php.net/package/Notthere'>".
		'https://pear.php.net/package/Notthere</a>.</font><br/>'.
		"<font color='black'>Notthere Lib: /Notthere.php not accessable.".
		'</font><br/>'.
		"<font color='black'>Check your Pear::Notthere installation!<br/>Make ".
		'sure PEAR libraries can be found by PHP.<pre>pear config-show | grep '.
		'&quot;PEAR directory&quot;'."\n".'PEAR directory      php_dir     '.
		'/usr/share/pear</pre>This path must be part of the include path of '.
		'php (cf. /etc/php.ini).<pre>php -i | '.
		'grep &quot;include_path&quot;include_path =&gt; '.
		'.:/usr/share/pear:/usr/share/php =&gt; '.
		'.:/usr/share/pear:/usr/share/php</pre>';
		if ( ini_get('safe_mode') ){
			$EOM .= 'In &quot;safe_mode&quot; it must also be part of '.
			'safe_mode_include_dir in /etc/php.ini';
		};
		$EOM .= '</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Notthere required.".
		'</font><br/>';
		$this->expectOutputString( $EOM, PearInc('','',$Lib, 0), $UOV );
	}
	public function testreturnPearincInvalidLoc(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		GLOBAL $debug_mode;
		$Loc = 'Imag';
		$Lib = 'Graph';
		$EOM ="<font color='#ff0000'>PearInc: Imag_Graph Lib: Imag/Graph.php ".
		'not accessable.</font><br/>';
		$this->assertFalse( PearInc('',$Loc,$Lib), $URV );
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString( $EOM, PearInc('',$Loc,$Lib), $UOV );
		$debug_mode = $odb;
	}
	public function testreturnPearincInvalidLocDisplayError(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		$Loc = 'Imag';
		$Lib = 'Graph';
		$EOM = "<font color='black'><b>Error loading the Imag_Graph library:".
		'</b> from &quot;Imag&quot;.<br/>The underlying Imag_Graph library '.
		'currently used is Imag_Graph, that can be downloaded at '.
		"<a href='https://pear.php.net/package/Imag_Graph'>".
		'https://pear.php.net/package/Imag_Graph</a>.</font><br/>'.
		"<font color='black'>Imag_Graph Lib: Imag/Graph.php not accessable.".
		'</font><br/>'."<font color='black'>".
		'Check your Pear::Imag_Graph installation!<br/>Make '.
		'sure PEAR libraries can be found by PHP.<pre>pear config-show | grep '.
		'&quot;PEAR directory&quot;'."\n".'PEAR directory      php_dir     '.
		'/usr/share/pear</pre>This path must be part of the include path of '.
		'php (cf. /etc/php.ini).<pre>php -i | '.
		'grep &quot;include_path&quot;include_path =&gt; '.
		'.:/usr/share/pear:/usr/share/php =&gt; '.
		'.:/usr/share/pear:/usr/share/php</pre>';
		if ( ini_get('safe_mode') ){
			$EOM .= 'In &quot;safe_mode&quot; it must also be part of '.
			'safe_mode_include_dir in /etc/php.ini';
		};
		$EOM .= '</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Imag_Graph required.".
		'</font><br/>';
		$this->expectOutputString( $EOM, PearInc('',$Loc,$Lib,0), $UOV );
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testreturnPearIncValidLib(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		$Lib = 'Mail';
		$this->assertTrue( PearInc('','',$Lib), $URV );
	}
	public function testreturnPearincValidLoc(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		$Loc = 'Mail';
		$Lib = 'mime';
		$this->assertTrue( PearInc('', $Loc,$Lib), $URV );
	}
	public function testreturnPearincInvalidOpts(){
		$URV = self::$URV.'PearInc().';
		$UOV = self::$UOV.'PearInc().';
		$Lib = 'Mail';
		$this->assertTrue( PearInc('','',$Lib, 'string', 'string'), $URV );
	}
	public function testreturnChkArchiveDBOffNoParams(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		// Test Env defaults to Archive DB disabled.
//		GLOBAL $archive_exists;
//		$cur_error_log = ini_get( 'error_log' );
//		ini_set( 'error_log', '/dev/stdout' );
//		$ogv = $archive_exists;
//		$archive_exists = 1;
		$this->assertFalse( ChkArchive(), $URV );
//		$archive_exists = $ogv;
//		ini_set( 'error_log', $cur_error_log );
	}
	public function testreturnChkArchiveDBOffNoGET(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		$PHPUV = self::$PHPUV;
		// Test Env defaults to Archive DB disabled.
//		GLOBAL $archive_exists;
		$EOM = 'BASE Security Alert ChkArchive: HTTP GET tampering detected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
//		$ogv = $archive_exists;
//		$archive_exists = 1;
		$_GET['archive'] = 1;
		$this->assertFalse( ChkArchive(), $URV );
		unset ($_GET['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
//		$archive_exists = $ogv;
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkArchiveDBOffNoCookie(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		$PHPUV = self::$PHPUV;
		// Test Env defaults to Archive DB disabled.
//		GLOBAL $archive_exists;
		$EOM = 'BASE Security Alert ChkArchive: COOKIE tampering detected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
//		$ogv = $archive_exists;
//		$archive_exists = 1;
		$_COOKIE['archive'] = 1;
		$this->assertFalse( ChkArchive(), $URV );
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
//		$archive_exists = $ogv;
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testreturnChkArchiveADBOnNoParams(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		// Test Env defaults to Archive DB disabled.
		GLOBAL $archive_exists;
		$ogv = $archive_exists;
		$archive_exists = 1;
		$this->assertFalse( ChkArchive(), $URV );
		$archive_exists = $ogv;
	}
	public function testreturnChkArchiveDBOnNoGET(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		// Test Env defaults to Archive DB disabled.
		GLOBAL $archive_exists;
		$ogv = $archive_exists;
		$archive_exists = 1;
		$_GET['archive'] = 1;
		$this->assertTrue( ChkArchive(), $URV );
		unset ($_GET['archive']);
		$archive_exists = $ogv;
	}
	public function testreturnChkArchiveDBOnNoCookie(){
		$URV = self::$URV.'ChkArchive().';
		$UOV = self::$UOV.'ChkArchive().';
		// Test Env defaults to Archive DB disabled.
		GLOBAL $archive_exists;
		$ogv = $archive_exists;
		$archive_exists = 1;
		$_COOKIE['archive'] = 1;
		$this->assertTrue( ChkArchive(), $URV );
		unset ($_COOKIE['archive']);
		$archive_exists = $ogv;
	}
	public function testBCSEmpty(){
		$URV = self::$URV.'BCS().';
		$UOV = self::$UOV.'BCS().';
		$PHPUV = self::$PHPUV;
		$EOM = 'BCS: No Cookie.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse( BCS(''), $URV );
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testBCSSet(){
		$URV = self::$URV.'BCS().';
		$UOV = self::$UOV.'BCS().';
		$PHPUV = self::$PHPUV;
		$EOM = 'BCS: Set Cookie: BASERole';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue( BCS('BASERole', 1 ), $URV );
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testBCSClear(){
		$URV = self::$URV.'BCS().';
		$UOV = self::$UOV.'BCS().';
		$PHPUV = self::$PHPUV;
		$EOM = 'BCS: Clear Cookie: BASERole';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue( BCS('BASERole'), $URV );
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/' . $EOM . '/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/' . $EOM . '/', $elOutput, $UOV);
		}
	}
	public function testBuildIPFormVarDefault(){
		$URV = self::$URV.'BuildIPFormVar().';
		$this->assertEquals('', BuildIPFormVar(), $URV );
	}
	public function testBuildIPFormVarInvalid(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = NULL_IP;
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 0, -1), $URV );
	}
	public function testBuildIPFormVarNULL_IP(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = NULL_IP;
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip), $URV );
	}
	public function testBuildIPFormVarNULL_IPSrc(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = NULL_IP;
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 1), $URV );
	}
	public function testBuildIPFormVarNULL_IPBoth(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = NULL_IP;
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=OR'
		. '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B1%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B1%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 3), $URV );
	}

	public function testBuildIPFormVarIPv4(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = '1.1.1.1';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip), $URV );
	}
	public function testBuildIPFormVarIPv4Src(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = '1.1.1.1';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 1), $URV );
	}
	public function testBuildIPFormVarIPv4Both(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = '1.1.1.1';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=OR'
		. '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B1%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B1%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 3), $URV );
	}

	public function testBuildIPFormVarIPv6(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = 'ff06::c3';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip), $URV );
	}
	public function testBuildIPFormVarIPv6Src(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = 'ff06::c3';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 1), $URV );
	}
	public function testBuildIPFormVarIPv6Both(){
		$URV = self::$URV.'BuildIPFormVar().';
		$ip = 'ff06::c3';
		$EOM = '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src'
		. '&amp;ip_addr%5B0%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B0%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=OR'
		. '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst'
		. '&amp;ip_addr%5B1%5D%5B2%5D=%3D'
		. '&amp;ip_addr%5B1%5D%5B3%5D=' . urlencode($ip)
		. '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+';
		$this->assertEquals($EOM, BuildIPFormVar($ip, 3), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
