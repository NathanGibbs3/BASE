<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /base_common.php
// Tests that need process isolation.

/**
  * @covers ::ChkAccess
  * @runTestsInSeparateProcesses
  */
class base_commonSPTest extends TestCase {
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

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
