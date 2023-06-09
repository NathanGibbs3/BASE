<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: includes/base_rtl.php
// Tests that need process isolation.

/**
  * Code Coverage Directives.
  * @covers ::ChkAccess
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */
class base_rtlSPTest extends TestCase {
	// Pre Test Setup.
	protected static $URV;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$URV = null;
	}

	// Tests go here.
	public function testreturnChkAccessDirectoryNotRead(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . "custom$sc" . 'testdir.notread';
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
		$this->assertEquals($expected , ChkAccess($Testfile,'d'), $URV);
	}
	public function testreturnChkAccessFileNotRead() {
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
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
		$this->assertEquals($expected, ChkAccess($Testfile), $URV);
	}
	public function testreturnChkAccessDirectoryExists(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testdir.notexec';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$this->assertEquals(1, ChkAccess($Testfile, 'd'), $URV );
	}
	public function testreturnChkAccessDirectoryNotExec(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testdir.notexec';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$this->assertEquals(1, ChkAccess($Testfile,'d'), $URV);
	}
	public function testreturnChkAccessDirectoryIsExec(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . "custom$sc" . 'testdir.isexec';
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$this->assertEquals(2, ChkAccess($Testfile,'d'), $URV);
	}
	public function testreturnChkAccessFileIsExec(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . "custom$sc" . 'testexec';
		if ( posix_getuid() != 1000 ){ // Swith UID to test Read Failure.
			posix_setuid(1000);
		}
		$this->assertEquals(2, ChkAccess($Testfile), $URV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
