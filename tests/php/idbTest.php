<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::VerifyDBAbstractionLib
  */
class dbTest extends TestCase {
	// Pre Test Setup.
	public static function setUpBeforeClass() {
		GLOBAL $DBlib_path;
		// Check ADODB Sanity.
		// See: https://github.com/NathanGibbs3/BASE/issues/35
		if (ADODB_DIR != $DBlib_path ){
			self::markTestIncomplete(
				"Expected ADODB in location: $DBlib_path\n".
				"   Found ADODB in location: ".ADODB_DIR
			);
		}
	}

	// Tests go here.
	public function testreturnVerifyDBAbstractionLinValid() {
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
		$this->assertTrue(
			VerifyDBAbstractionLib($DBlib_path),
			'Unexpected return VerifyDBAbstractionLib().'
		);
	}
	public function testreturnVerifyDBAbstractionLibInValid() {
		$DBlib_path = "invalid";
		$this->assertFalse(
			VerifyDBAbstractionLib($DBlib_path),
			'Unexpected return VerifyDBAbstractionLib().'
		);
	}
	public function testreturnVerifyDBAbstractionLibSafeModeCutout() {
		$PHPV = GetPHPV();
		$DBlib_path = "invalid";
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertTrue(
				VerifyDBAbstractionLib($DBlib_path),
				'Unexpected return VerifyDBAbstractionLib().'
			);
		}
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
