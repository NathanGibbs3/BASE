<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_auth.inc.php
// in the AuthorizedRole() function

/**
  * @covers ::AuthorizedRole
  * @covers ::AuthorizedPage
  * @covers ::AuthorizedURI
  * @uses ::base_header
  * @uses ::filterSql
  * @uses ::ChkAccess
  * @uses ::ChkLib
  * @uses ::LoadedString
  * @uses ::NewBASEDBConnection
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses BaseUser
  * @uses baseCon
  * @uses baseRS
  */
class authTest2 extends TestCase {
	// Pre Test Setup.
	protected static $user;
	protected static $URV;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
		$alert_host, $alert_user, $alert_password, $alert_port,
		$db_connect_method, $db, $archive_dbname, $archive_host,
		$archive_port, $archive_user, $archive_password;
		$tf = __FUNCTION__;
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
				if ($debug_mode > 1) {
					LogTC($tf,'DB',"$alert_dbname@$alert_host:$alert_port");
				}
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
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$user = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testAuthorizedRoleASOff(){
		GLOBAL $Use_Auth_System;
		$BAStmp = $Use_Auth_System;
		$Use_Auth_System = 0;
		$URV = self::$URV . 'AuthorizedRole().';
		$this->assertTrue( AuthorizedRole(), $URV );
		$Use_Auth_System = $BAStmp;
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleThrowsAuthenticateError(){
		$URV = self::$URV . 'AuthorizedRole().';
		$EEM = 'Unauthenticated user access';
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '3.0', '<')) {
			$this->markTestSkipped('Requires Phpunit 3+ to run.');
		}elseif (version_compare($PHPUV, '5.0', '<')) { // PHPUnit 3x - 4x
			$this->setExpectedException(
				"PHPUnit_Framework_Error_Notice", $EEM
			);
		}elseif (version_compare($PHPUV, '6.0', '<')) { // PHPUnit 5x
			$this->expectException("PHPUnit_Framework_Error_Notice");
			$this->expectExceptionMessage($EEM);
		}elseif (version_compare($PHPUV, '9.0', '<')) { // PHPUnit 6x - 8x
			$this->expectException("PHPUnit\Framework\Error\Notice");
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 9+
			$this->expectNotice();
			$this->expectNoticeMessage($EEM);
		}
		$this->assertFalse( AuthorizedRole(), $URV );
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRole(){
		$URV = self::$URV . 'AuthorizedRole().';
		$this->assertFalse( @AuthorizedRole(), $URV );
	}
	// test against test users.
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleThrowsAuthorizeError(){
		$URV = self::$URV . 'AuthorizedRole().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAnonUser|";
		$EEM = 'Unauthorized user access: TestAnonUser';
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '3.0', '<')) {
			$this->markTestSkipped('Requires Phpunit 3+ to run.');
		}elseif (version_compare($PHPUV, '5.0', '<')) { // PHPUnit 3x - 4x
			$this->setExpectedException(
				"PHPUnit_Framework_Error_Notice", $EEM
			);
		}elseif (version_compare($PHPUV, '6.0', '<')) { // PHPUnit 5x
			$this->expectException("PHPUnit_Framework_Error_Notice");
			$this->expectExceptionMessage($EEM);
		}elseif (version_compare($PHPUV, '9.0', '<')) { // PHPUnit 6x - 8x
			$this->expectException("PHPUnit\Framework\Error\Notice");
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 9+
			$this->expectNotice();
			$this->expectNoticeMessage($EEM);
		}
		$this->assertFalse( AuthorizedRole(1), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleRedirectLock(){
		$URV = self::$URV . 'AuthorizedRole().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestOver|";
		$this->assertFalse( @AuthorizedRole(10000), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleFail(){
		$URV = self::$URV . 'AuthorizedRole().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestUser|";
		$this->assertFalse( @AuthorizedRole(1), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleDisabledUserFail(){
		$URV = self::$URV . 'AuthorizedRole().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestDisabledUser|";
		$this->assertFalse( @AuthorizedRole(1000), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testAuthorizedRolePass(){
		$URV = self::$URV . 'AuthorizedRole().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestOver|";
		$this->assertTrue( AuthorizedRole(20000), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testAuthorizedPageFail(){
		$this->assertFalse(
			AuthorizedPage(),
			'Unexpected Return Value.'
		);
	}
	public function testAuthorizedPageOK(){
		$tmp = $_SERVER['SCRIPT_NAME'];
		$_SERVER['SCRIPT_NAME'] = '/main.php';
		$this->assertTrue(
			AuthorizedPage('main'),
			'Unexpected Return Value.'
		);
		$_SERVER['SCRIPT_NAME'] = $tmp;
	}
	public function testAuthorizedURIUnsetFail(){
		$this->assertFalse(
			AuthorizedURI(),
			'Unexpected Return Value.'
		);
	}
	public function testAuthorizedURISetFail(){
		$_SERVER['REQUEST_URI'] = '/base/index.php';
		$this->assertFalse(
			AuthorizedURI(),
			'Unexpected Return Value.'
		);
		unset ($_SERVER['REQUEST_URI']);
	}
	public function testAuthorizedURISetOK(){
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			$tmp = '/usr';
		}else{ // Running in Travis CI
			$version = explode('.', phpversion());
			if (
				( $version[0] == 5 && $version[1] == 3 )
				|| ( $version[0] == 7 && $version[1] == 2 )
				|| ( $version[0] == 7 && $version[1] == 3 )
			){ // Composer Installed PHPUnit
				$tmp = 'vendor';
			}else{ // System PHPUnit
				$tmp = "/home/travis/.phpenv/versions/$version[0].$version[1]";
			}
		}
		$tmp .= '/bin/phpunit';
		$_SERVER['REQUEST_URI'] = $tmp;
		$this->assertTrue(
			AuthorizedURI(),
			'Unexpected Return Value.'
		);
		unset ($_SERVER['REQUEST_URI']);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
