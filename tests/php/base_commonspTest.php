<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /base_common.php
// Tests that need process isolation.

/**
  * @covers ::ChkAccess
  * @covers ::ChkLib
  * @runTestsInSeparateProcesses
  */
class base_commonSPTest extends TestCase {
	// Pre Test Setup.

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
	}
	public static function tearDownAfterClass() {
	}

	// Tests go here.
	public function testreturnChkAccessReadFail() {
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$file = 'readTestFail.txt';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$expected = -2;
		if (
			version_compare($PHPV, '5.1.5', '<')
			&& ini_get("safe_mode") == true
		){ // Safe Mode Cutout Return Value
			$expected = 1;
		}
		$this->assertEquals(
			$expected,
			ChkAccess($Testfile),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkLibNotReadable() {
		GLOBAL $debug_mode;
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
		$expected = '<font color="black">ChkLib: Chk: '."$path$sc$Lib".'.php';
		$expected .= '</font><br/><font color="red">ChkLib: Lib: ';
		$expected .= "$path$sc$Lib".'.php not readable.</font><br/>';
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$this->assertEquals(
			'',
			ChkLib($path,'',$Lib),
			'Unexpected return ChkLib().'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			$expected,
			'Unexpected Output.'
		);
		ChkLib($path,'',$Lib);
		$debug_mode = $odb;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
