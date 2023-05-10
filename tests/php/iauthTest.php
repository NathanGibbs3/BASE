<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_auth.inc.php
// in the BaseUser class.

/**
  * @covers BaseUser::AuthenticateCore
  * @covers BaseUser::isActive
  * @covers BaseUser::returnEditUser
  * @covers BaseRole::returnEditRole
  * @covers BaseUser::returnRoleNamesDropDown
  * @covers BaseUser::returnUser
  * @covers BaseUser::returnUserID
  * @covers BaseUser::readRoleCookie
  * @covers BaseUser::roleName
  * @uses ::ChkAccess
  * @uses ::ChkCookie
  * @uses ::ChkLib
  * @uses ::LoadedString
  * @uses ::NewBASEDBConnection
  * @uses ::NLI
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::chk_select
  * @uses ::filterSql
  * @uses baseCon
  * @uses baseRS
  * @uses BaseUser::cryptpassword
  */

class authTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $user;
	protected static $role;
	protected static $PHPUV;
	protected static $URV;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass(){
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
		$alert_host, $alert_user, $alert_password, $alert_port,
		$db_connect_method, $db, $archive_dbname, $archive_host,
		$archive_port, $archive_user, $archive_password, $et;
		$tf = __FUNCTION__;
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			require('../database.php');
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO){
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
				if ($debug_mode > 1){
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
			self::$db = $db;
			self::assertInstanceOf(
				'BaseUser',
				$user = new BaseUser(),
				'User Object Not Initialized.'
			);
			self::$user = $user;
			self::assertInstanceOf(
				'BaseRole',
				$role = new BaseRole(),
				'Role Object Not Initialized.'
			);
			self::$role = $role;
		}
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
		// Shim for testing functions that access the EventTiming Class via the
		// global $et var, which is not defined under test conditions.
		if ( !isset($et) ){
			$et = 'Temp';
		}
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass(){
		GLOBAL $et;
		if ( $et == 'Temp' ){ // EventTiming Shim clean up.
			unset($et);
		}
		self::$URV = null;
		self::$PHPUV = null;
		self::$db = null;
		self::$user = null;
		self::$role = null;
	}

	// Tests go here.
	public function testreturnRoleNamesDropDownNone(){
		$URV = self::$URV . 'returnRoleNamesDropDown().';
		$user = self::$user;
		$msg = 'selected';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertDoesNotMatchRegularExpression(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(0), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertNotRegExp(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(0), $URV
			);
		}
	}
	public function testreturnRoleNamesDropDownAdmin(){
		$URV = self::$URV . 'returnRoleNamesDropDown().';
		$user = self::$user;
		$msg = '<option value=\'1\' selected>Admin<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(1), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(1), $URV
			);
		}
	}
	public function testreturnRoleNamesDropDownUser(){
		$URV = self::$URV . 'returnRoleNamesDropDown().';
		$user = self::$user;
		$msg = '<option value=\'10\' selected>user<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(10), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(10), $URV
			);
		}
	}
	public function testreturnRoleNamesDropDownAnonymous(){
		$URV = self::$URV . 'returnRoleNamesDropDown().';
		$user = self::$user;
		$msg = '<option value=\'10000\' selected>anonymous<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(10000), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(10000), $URV
			);
		}
	}
	public function testreturnRoleNamesDropDownAGEditor(){
		$URV = self::$URV . 'returnRoleNamesDropDown().';
		$user = self::$user;
		$msg = '<option value=\'50\' selected>ag_editor<\/option>';
		$PHPUV = self::$PHPUV;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(50), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/', $user->returnRoleNamesDropDown(50), $URV
			);
		}
	}
	public function testreturnUserCookie(){
		$URV = self::$URV . 'returnUser().';
		$user = self::$user;
		$_COOKIE['BASERole'] = 'passwd|user|';
		$this->assertEquals( 'user', $user->returnUser(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreturnUserNoCookie(){
		$URV = self::$URV . 'returnUser().';
		$user = self::$user;
		$this->assertEquals( '', $user->returnUser(), $URV );
	}
	public function testreadRoleCookieNone(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
	}
	public function testreadRoleCookieNull(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$_COOKIE['BASERole'] = NULL;
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeformed(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw';
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeformed2(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw|';
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeBadPW(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw|TestUser';
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeBadUser(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|Test";
		$this->assertEquals( 0, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeOK(){
		$URV = self::$URV . 'readRoleCookie().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAdmin";
		$this->assertEquals( 1, $user->readRoleCookie(), $URV );
		unset ($_COOKIE['BASERole']);
	}
	public function testreturnEditUserInvalidType(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = 'User';
		$this->assertFalse( $user->returnEditUser($uid), $URV );
	}
	public function testreturnEditUserInvalidID(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = 25000;
		$expected = '';
		$this->assertEquals( $expected, $user->returnEditUser($uid), $URV );
	}
	public function testreturnEditUserValidreturnsarray(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = $user->returnUserID('Test<br/>XSS');
		$this->assertTrue( is_array($user->returnEditUser($uid)), $URV );
	}
	public function testreturnEditUserValidXSSOff(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = $user->returnUserID('Test<br/>XSS');
		$expected = array (
			'Test<br/>XSS', 'Test<br/>XXS in Username',
			'Test<br/>XSS', 'Test<br/>XXS in Username',
		);
		$users = $user->returnEditUser($uid,0);
		$returned = array (
			$users[1], $users[3], $users['usr_login'], $users['usr_name']
		);
		$this->assertEquals( $expected, $returned, $URV );
	}
	public function testreturnEditUserValidXSSOn(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = $user->returnUserID('Test<br/>XSS');
		$expected = array (
			'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Username',
			'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Username',
		);
		$users = $user->returnEditUser($uid,1);
		$returned = array (
			$users[1], $users[3], $users['usr_login'], $users['usr_name']
		);
		$this->assertEquals( $expected, $returned, $URV );
	}
	public function testreturnEditUserValidXSSInvalid(){
		$URV = self::$URV . 'returnEditUser().';
		$user = self::$user;
		$uid = $user->returnUserID('Test<br/>XSS');
		$expected = array (
			'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Username',
			'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Username',
		);
		$users = $user->returnEditUser($uid,'What');
		$returned = array (
			$users[1], $users[3], $users['usr_login'], $users['usr_name']
		);
		$this->assertEquals( $expected, $returned, $URV );
	}
	public function testreturnUserIDInvalidType(){
		$URV = self::$URV . 'returnUserID().';
		$user = self::$user;
		$uid = 1;
		$this->assertFalse( $user->returnUserID($uid), $URV );
	}
	public function testreturnUserIDInvalidID(){
		$URV = self::$URV . 'returnUserID().';
		$user = self::$user;
		$uid = 'Test';
		$expected = '';
		$this->assertEquals( $expected, $user->returnUserID($uid), $URV );
	}
	public function testreturnUserIDValidreturnsInt(){
		$URV = self::$URV . 'returnUserID().';
		$user = self::$user;
		$uid = 'TestUser';
		$this->assertTrue( is_int($user->returnUserID($uid)), $URV );
	}
	public function testreturnEditRoleInvalidType(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = 'Role';
		$this->assertFalse( $role->returnEditRole($uid), $URV );
	}
	public function testreturnEditRoleInvalidID(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = 25000;
		$expected = '';
		$this->assertEquals( $expected, $role->returnEditRole($uid), $URV );
	}
	public function testreturnEditRoleValidreturnsarray(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = '30000';
		$this->assertTrue( is_array($role->returnEditRole($uid)), $URV );
	}
	public function testreturnEditRoleValidXSSOff(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test<br/>XSS', 'Test<br/>XXS in Rolename',
			'role_name' => 'Test<br/>XSS', 'role_id' => '30000',
			'role_desc' => 'Test<br/>XXS in Rolename'
		);
		$this->assertEquals( $expected, $role->returnEditRole($uid,0), $URV );
	}
	public function testreturnEditRoleValidXSSOn(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Rolename',
			'role_name' => 'Test&lt;br/&gt;XSS', 'role_id' => '30000',
			'role_desc' => 'Test&lt;br/&gt;XXS in Rolename'
		);
		$this->assertEquals( $expected, $role->returnEditRole($uid,1), $URV );
	}
	public function testreturnEditRoleValidXSSInvalid(){
		$URV = self::$URV . 'returnEditRole().';
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Rolename',
			'role_name' => 'Test&lt;br/&gt;XSS', 'role_id' => '30000',
			'role_desc' => 'Test&lt;br/&gt;XXS in Rolename'
		);
		$this->assertEquals(
			$expected, $role->returnEditRole($uid,'What'), $URV
		);
	}
	public function testroleNameInvalidType(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = 'User';
		$this->assertFalse( $user->roleName($id), $URV );
	}
	public function testroleNameInvalidID(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = 2;
		$expected = '';
		$this->assertTrue( is_string($user->roleName($id)), $URV );
		$this->assertEquals( $expected, $user->roleName($id), $URV );
	}
	public function testroleNameAID(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = 1;
		$msg = 'Admin';
		$PHPUV = self::$PHPUV;
		$this->assertTrue( is_string($user->roleName($id)), $URV );
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $user->roleName($id), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/', $user->roleName($id), $URV
			);
		}
	}
	public function testroleNameValidXSSOff(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = '30000';
		$expected = 'Test<br/>XSS';
		$this->assertEquals( $expected, $user->roleName($id,0), $URV );
	}
	public function testroleNameValidXSSOn(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = '30000';
		$expected = 'Test&lt;br/&gt;XSS';
		$this->assertEquals( $expected, $user->roleName($id,1), $URV );
	}
	public function testroleNameValidXSSInvalid(){
		$URV = self::$URV . 'roleName().';
		$user = self::$user;
		$id = '30000';
		$expected = 'Test&lt;br/&gt;XSS';
		$this->assertEquals( $expected, $user->roleName($id,'What'), $URV );
	}
	public function testAuthenticateCoreDefaults(){
		$URV = self::$URV . 'AuthenticateCore().';
		$user = self::$user;
		$this->assertEquals( 3, $user->AuthenticateCore(), $URV );
	}
	public function testAuthenticateCoreInvalidUser(){
		$URV = self::$URV . 'AuthenticateCore().';
		$user = self::$user;
		$UN = 'nonexistent';
		$this->assertEquals( 3, $user->AuthenticateCore( $UN ), $URV );
	}
	public function testAuthenticateCoreDisabledUser(){
		$URV = self::$URV . 'AuthenticateCore().';
		$user = self::$user;
		$UN = 'TestDisabledUser';
		$this->assertEquals( 2, $user->AuthenticateCore( $UN, 'password' ), $URV );
	}
	public function testAuthenticateCoreUserOKPWInvalid(){
		$URV = self::$URV . 'AuthenticateCore().';
		$user = self::$user;
		$UN = 'TestUser';
		$this->assertEquals( 1, $user->AuthenticateCore( $UN, 'passwor' ), $URV );
	}
	public function testAuthenticateCoreUserOKPWValid(){
		$URV = self::$URV . 'AuthenticateCore().';
		$user = self::$user;
		$UN = 'TestUser';
		$this->assertEquals( 0, $user->AuthenticateCore( $UN, 'password' ), $URV );
	}
	public function testisActiveDefaults(){
		$URV = self::$URV . 'isActive().';
		$user = self::$user;
		$this->assertFalse( $user->isActive(), $URV );
	}
	public function testisActiveInvalidUser(){
		$URV = self::$URV . 'isActive().';
		$user = self::$user;
		$UN = 'nonexistent';
		$this->assertFalse( $user->isActive($UN), $URV );
	}
	public function testisActiveDisabledUser(){
		$URV = self::$URV . 'isActive().';
		$user = self::$user;
		$UN = 'TestDisabledUser';
		$this->assertFalse( $user->isActive($UN), $URV );
	}
	public function testisActiveEnabledUser(){
		$URV = self::$URV . 'isActive().';
		$user = self::$user;
		$UN = 'TestUser';
		$this->assertTrue( $user->isActive($UN), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
