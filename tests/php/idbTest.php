<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::VerifyDBAbstractionLib
  */
class dbTest extends TestCase {
	// Tests go here.
	public function testreturnVerifyDBAbstractionLibValid() {
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
		$this->assertNotTrue(
			VerifyDBAbstractionLib($DBlib_path),
			'Unexpected return VerifyDBAbstractionLib().'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
