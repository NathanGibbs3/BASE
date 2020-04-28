<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_auth.inc.php

/**
  * @covers BaseUser::returnRoleNamesDropDown
  * @covers BaseUser::returnUser
  * @uses baseCon
  * @uses baseRS
  * @uses ::chk_select
  * @uses ::NLI
  * @uses ::XSSPrintSafe
  */
class authTest extends TestCase {
	// Pre Test Setup.
	protected static $user;
	protected static $PHPUV;

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
		}
		if (!isset($DBtype)){
			self::markTestIncomplete("Unable to Set DB: $DB.");
		}else{
			$alert_dbname='snort';
			// Setup DB Connection
			$db = NewBASEDBConnection($DBlib_path, $DBtype);
			// Check ADODB Sanity.
			// See: https://github.com/NathanGibbs3/BASE/issues/35
			if (ADODB_DIR != $DBlib_path ){
				self::markTestIncomplete(
					"Expected ADODB in location: $DBlib_path\n".
					"   Found ADODB in location: ".ADODB_DIR
				);
			}else{
				$db->baseDBConnect(
					$db_connect_method, $alert_dbname, $alert_host,
					$alert_port, $alert_user, $alert_password
				);
			}
			self::assertInstanceOf(
				'baseCon',
				$db,
				'DB Object Not Initialized.'
			);
			self::assertInstanceOf(
				'BaseUser',
				$user = new BaseUser(),
				'User Object Not Initialized.'
			);
			self::$user = $user;
		}
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')) { // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
	}
	public static function tearDownAfterClass() {
		self::$user = null;
		self::$PHPUV = null;
	}

	// Tests go here.
	public function testreturnRoleNamesDropDownNone() {
		$user = self::$user;
		$msg = 'selected';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertDoesNotMatchRegularExpression(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(0),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertNotRegExp(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(0),
				'Unexpected Return Value.'
			);
		}
	}
	public function testreturnRoleNamesDropDownAdmin() {
		$user = self::$user;
		$msg = '<option value="1" selected>Admin<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(1),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(1),
				'Unexpected Return Value.'
			);
		}
	}
	public function testreturnRoleNamesDropDownUser() {
		$user = self::$user;
		$msg = '<option value="10" selected>user<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(10),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(10),
				'Unexpected Return Value.'
			);
		}
	}
	public function testreturnRoleNamesDropDownAnonymous() {
		$user = self::$user;
		$msg = '<option value="10000" selected>anonymous<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(10000),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(10000),
				'Unexpected Return Value.'
			);
		}
	}
	public function testreturnRoleNamesDropDownAGEditor() {
		$user = self::$user;
		$msg = '<option value="50" selected>ag_editor<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(50),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/',
				$user->returnRoleNamesDropDown(50),
				'Unexpected Return Value.'
			);
		}
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testreturnUserCookie(){
		$user = self::$user;
		$_COOKIE['BASERole'] = 'passwd|user|';
		$this->assertEquals(
			'user',
			$user->returnUser(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreturnUserNoCookie(){
		$user = self::$user;
		$this->assertEquals(
			'',
			$user->returnUser(),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
