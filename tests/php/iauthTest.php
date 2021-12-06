<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_auth.inc.php
// in the BaseUser class.

/**
  * @covers BaseUser::returnEditUser
  * @covers BaseRole::returnEditRole
  * @covers BaseUser::returnRoleNamesDropDown
  * @covers BaseUser::returnUser
  * @covers BaseUser::returnUserID
  * @covers BaseUser::readRoleCookie
  * @covers BaseUser::roleName
  * @uses ::chk_select
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::XSSPrintSafe
  * @uses baseCon
  * @uses baseRS
  * @uses BaseUser::cryptpassword
  */
class authTest extends TestCase {
	// Pre Test Setup.
	protected static $user;
	protected static $role;
	protected static $PHPUV;

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
			self::assertInstanceOf(
				'BaseRole',
				$role = new BaseRole(),
				'Role Object Not Initialized.'
			);
			self::$role = $role;
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
		self::$PHPUV = null;
		self::$user = null;
		self::$role = null;
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
		$msg = '<option value=\'1\' selected>Admin<\/option>';
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
		$msg = '<option value=\'10\' selected>user<\/option>';
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
		$msg = '<option value=\'10000\' selected>anonymous<\/option>';
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
		$msg = '<option value=\'50\' selected>ag_editor<\/option>';
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
	public function testreadRoleCookieNone(){
		$user = self::$user;
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
	}
	public function testreadRoleCookieNull(){
		$user = self::$user;
		$_COOKIE['BASERole'] = NULL;
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeformed(){
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw';
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeformed2(){
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw|';
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeBadPW(){
		$user = self::$user;
		$_COOKIE['BASERole'] = 'pw|TestUser';
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeBadUser(){
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|Test";
		$this->assertEquals(
			0,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreadRoleCookiedeOK(){
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAdmin";
		$this->assertEquals(
			1,
			$user->readRoleCookie(),
			'Unexpected Return Value.'
		);
		unset ($_COOKIE['BASERole']);
	}
	public function testreturnEditUserInvalidType(){
		$user = self::$user;
		$uid = 'User';
		$this->assertFalse(
			$user->returnEditUser($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditUserInvalidID(){
		$user = self::$user;
		$uid = 25000;
		$expected = '';
		$this->assertEquals(
			$expected,
			$user->returnEditUser($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditUserValidreturnsarray(){
		$user = self::$user;
		$uid = $user->returnUserID('Test<br/>XSS');
		$this->assertTrue(
			is_array($user->returnEditUser($uid)),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditUserValidXSSOff(){
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
		$this->assertEquals(
			$expected,
			$returned,
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditUserValidXSSOn(){
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
		$this->assertEquals(
			$expected,
			$returned,
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditUserValidXSSInvalid(){
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
		$this->assertEquals(
			$expected,
			$returned,
			'Unexpected Return Value.'
		);
	}
	public function testreturnUserIDInvalidType(){
		$user = self::$user;
		$uid = 1;
		$this->assertFalse(
			$user->returnUserID($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnUserIDInvalidID(){
		$user = self::$user;
		$uid = 'Test';
		$expected = '';
		$this->assertEquals(
			$expected,
			$user->returnUserID($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnUserIDValidreturnsInt(){
		$user = self::$user;
		$uid = 'TestUser';
		$this->assertTrue(
			is_int($user->returnUserID($uid)),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleInvalidType(){
		$role = self::$role;
		$uid = 'Role';
		$this->assertFalse(
			$role->returnEditRole($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleInvalidID(){
		$role = self::$role;
		$uid = 25000;
		$expected = '';
		$this->assertEquals(
			$expected,
			$role->returnEditRole($uid),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleValidreturnsarray(){
		$role = self::$role;
		$uid = '30000';
		$this->assertTrue(
			is_array($role->returnEditRole($uid)),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleValidXSSOff(){
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test<br/>XSS', 'Test<br/>XXS in Rolename',
			'role_name' => 'Test<br/>XSS', 'role_id' => '30000',
			'role_desc' => 'Test<br/>XXS in Rolename'
		);
		$this->assertEquals(
			$expected,
			$role->returnEditRole($uid,0),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleValidXSSOn(){
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Rolename',
			'role_name' => 'Test&lt;br/&gt;XSS', 'role_id' => '30000',
			'role_desc' => 'Test&lt;br/&gt;XXS in Rolename'
		);
		$this->assertEquals(
			$expected,
			$role->returnEditRole($uid,1),
			'Unexpected Return Value.'
		);
	}
	public function testreturnEditRoleValidXSSInvalid(){
		$role = self::$role;
		$uid = '30000';
		$expected = array (
			'30000', 'Test&lt;br/&gt;XSS', 'Test&lt;br/&gt;XXS in Rolename',
			'role_name' => 'Test&lt;br/&gt;XSS', 'role_id' => '30000',
			'role_desc' => 'Test&lt;br/&gt;XXS in Rolename'
		);
		$this->assertEquals(
			$expected,
			$role->returnEditRole($uid,'What'),
			'Unexpected Return Value.'
		);
	}
	public function testroleNameInvalidType() {
		$user = self::$user;
		$id = 'User';
		$this->assertFalse(
			$user->roleName($id),
			'Unexpected Return Value.'
		);
	}
	public function testroleNameInvalidID(){
		$user = self::$user;
		$id = 2;
		$expected = '';
		$this->assertTrue(
			is_string($user->roleName($id)),
			'Unexpected Return Value.'
		);
		$this->assertEquals(
			$expected,
			$user->roleName($id),
			'Unexpected Return Value.'
		);
	}
	public function testroleNameAID() {
		$user = self::$user;
		$id = 1;
		$msg = 'Admin';
		$PHPUV = self::$PHPUV;
		$this->assertTrue(
			is_string($user->roleName($id)),
			'Unexpected Return Value.'
		);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/',
				$user->roleName($id),
				'Unexpected Return Value.'
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$msg.'/',
				$user->roleName($id),
				'Unexpected Return Value.'
			);
		}
	}
	public function testroleNameValidXSSOff(){
		$user = self::$user;
		$id = '30000';
		$expected = 'Test<br/>XSS';
		$this->assertEquals(
			$expected,
			$user->roleName($id,0),
			'Unexpected Return Value.'
		);
	}
	public function testroleNameValidXSSOn(){
		$user = self::$user;
		$id = '30000';
		$expected = 'Test&lt;br/&gt;XSS';
		$this->assertEquals(
			$expected,
			$user->roleName($id,1),
			'Unexpected Return Value.'
		);
	}
	public function testroleNameValidXSSInvalid(){
		$user = self::$user;
		$id = '30000';
		$expected = 'Test&lt;br/&gt;XSS';
		$this->assertEquals(
			$expected,
			$user->roleName($id,'What'),
			'Unexpected Return Value.'
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
