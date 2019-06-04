<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::VerifyDBAbstractionLib
  */
class dbTest extends TestCase {
	// Check ADODB Sanity.
	public static function setUpBeforeClass() {
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$tmp = '/usr/share/php/adodb';
		}else{
			$tmp = 'build/adodb/';
			$PHPV = GetPHPV();
			if (version_compare($PHPV, '5.4', '<')){ // PHP 4x-
				$ADODBVer='5.00beta';
				$tmp .= "ADOdb-$ADODBVer/phplens/adodb5";
//			}elseif (version_compare($PHPV, '5.4', '<')){ // PHP 5.3x
//				$ADODBVer='494';
				// Sourceforge Source Setup
//				$tmp .= "adodb-$ADODBVer-for-php4-and-5/adodb";
				// Sourceforge standard
				// $tmp = "adodb-$ADODBVer-for-php/adodb"
			}elseif (version_compare($PHPV, '7.0', '<')){ // PHP 5.4x
				$ADODBVer='5.09';
				$tmp .= "ADOdb-$ADODBVer/phplens/adodb5";
			}else{ // PHP 7x+
				$ADODBVer='5.20.0';
				$tmp .= "ADOdb-$ADODBVer";
			}
		}
		if (ADODB_DIR != $tmp ){
			self::markTestIncomplete(
				"Expected ADODB in location: $tmp\n".
				"   Found ADODB in location: ".ADODB_DIR
			);
		}
	}
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
