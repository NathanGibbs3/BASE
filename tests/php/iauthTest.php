<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_auth.inc.php

/**
  * @covers BaseUser::returnRoleNamesDropDown
  * @uses ::XSSPrintSafe
  * @uses baseCon
  * @uses baseRS
  */
class authTest extends TestCase {
	// Pre Test Setup.
	protected static $user;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
		$alert_host, $alert_user, $alert_password, $alert_port,
		$db_connect_method, $db;
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			require('../database.php');
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO) {
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
			$DB = getenv('DB');
			if (!$DB){
				self::markTestIncomplete('Unable to get DB Engine.');
			}elseif ($DB == 'mysql' ){
				require('./tests/phpcommon/DB.mysql.php');
			}elseif ($DB == 'postgres' ){
				require('./tests/phpcommon/DB.pgsql.php');
			}else{
				self::markTestSkipped("CI Support unavialable for DB: $DB.");
			}
			if (!isset($DBtype)){
				self::markTestIncomplete("Unable to Set DB: $DB.");
			}
		}
		$alert_dbname='snort';
		$db = NewBASEDBConnection($DBlib_path, $DBtype); // Setup DB Connection
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password
		);
		self::$user = new BaseUser();
	}
	public static function tearDownAfterClass() {
		self::$user = null;
	}

	// Tests go here.
	public function testreturnRoleNamesDropDownNone() {
		$user = self::$user;
		$msg = 'selected';
		$this->assertNotRegExp(
			'/'.$msg.'/',
			$user->returnRoleNamesDropDown(0),
			'Unexpected Return Value.'
		);
	}
	public function testreturnRoleNamesDropDownAdmin() {
		$user = self::$user;
		$msg = '<option value=\'1\' selected>Admin<\/option>';
		$this->assertRegExp(
			'/'.$msg.'/',
			$user->returnRoleNamesDropDown(1),
			'Unexpected Return Value.'
		);
	}
	public function testreturnRoleNamesDropDownUser() {
		$user = self::$user;
		$msg = '<option value=\'10\' selected>user<\/option>';
		$this->assertRegExp(
			'/'.$msg.'/',
			$user->returnRoleNamesDropDown(10),
			'Unexpected Return Value.'
		);
	}
	public function testreturnRoleNamesDropDownAnonymous() {
		$user = self::$user;
		$msg = '<option value=\'10000\' selected>anonymous<\/option>';
		$this->assertRegExp(
			'/'.$msg.'/',
			$user->returnRoleNamesDropDown(10000),
			'Unexpected Return Value.'
		);
	}
	public function testreturnRoleNamesDropDownAGEditor() {
		$user = self::$user;
		$msg = '<option value=\'50\' selected>ag_editor<\/option>';
		$this->assertRegExp(
			'/'.$msg.'/',
			$user->returnRoleNamesDropDown(50),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
