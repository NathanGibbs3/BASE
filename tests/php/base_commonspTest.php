<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_common.php
// Tests that need process isolation.

/**
  * Code Coverage Directives.
  * @covers ::ChkLib
  * @runTestsInSeparateProcesses
  */

class base_commonSPTest extends TestCase {
	// Pre Test Setup.
	protected static $PHPUV;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		$PHPUV = GetPHPUV(); // PHPUnit Version
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
	}
	public static function tearDownAfterClass() {
		self::$PHPUV = null;
	}

	// Tests go here.
	public function testreturnChkLibNotReadable() {
		$PHPUV = self::$PHPUV;
		$sc = DIRECTORY_SEPARATOR;
		// Setup DB Lib Path.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO) {
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
		}
		$path =  $DBlib_path;
		$Lib = 'readTestFail';
		$EOM = preg_quote("ChkLib: Chk: $path$sc$Lib" , '/') . '\.php\n.*';
		$EOM .= preg_quote("ChkLib: Lib: $path$sc$Lib", '/')
		. '\.php not readable\.\n';
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(
			'', ChkLib($path,'',$Lib), 'Unexpected return ChkLib().'
		);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, 'Unexpected Output ChkLib().'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, 'Unexpected Output ChkLib().'
			);
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
