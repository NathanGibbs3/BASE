<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_auth.inc.php
// The Authorized*() functions.

/**
  * @covers ::AuthorizedClient
  * @covers ::AuthorizedRole
  * @covers ::AuthorizedPage
  * @covers ::AuthorizedURI
  * @covers ::ARC
  * @uses ::BCS
  * @uses ::HTTP_header
  * @uses ::filterSql
  * @uses ::ChkAccess
  * @uses ::ChkLib
  * @uses ::DumpSQL
  * @uses ::GetPHPSV
  * @uses ::LoadedString
  * @uses ::NewBASEDBConnection
  * @uses ::NMHC
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::VS2SV
  * @uses ::ipconvert
  * @uses ::is_ip4
  * @uses ::is_ip6
  * @uses ::is_ip
  * @uses ::is_key
  * @uses ::netmask
  * @uses BaseUser
  * @uses baseCon
  * @uses baseRS
  */

class authTest2 extends TestCase {
	// Pre Test Setup.
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;
	protected static $user;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
		$alert_host, $alert_user, $alert_password, $alert_port,
		$db_connect_method, $db, $archive_dbname, $archive_host,
		$archive_port, $archive_user, $archive_password;
		$tf = __FUNCTION__;
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if( !$TRAVIS ){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			$DB = 'mysql';
		}else{
			$ADO = getenv('ADODBPATH');
			if( !$ADO ){
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
			$DB = getenv('DB');
		}
		if( !$DB ){
			self::markTestIncomplete('Unable to get DB Engine.');
		}elseif( $DB == 'mysql' ){
			require('./tests/phpcommon/DB.mysql.RI.php');
		}elseif( $DB == 'postgres' ){
			require('./tests/phpcommon/DB.pgsql.php');
		}elseif( $DB == 'mssql' ){
			require('./tests/phpcommon/DB.mssql.php');
		}else{
			self::markTestSkipped("CI Support unavialable for DB: $DB.");
		}
		if (!isset($DBtype)){
			self::markTestIncomplete("Unable to Set DB: $DB.");
		}else{ // Setup DB Connection
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
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$PHPUV = null;
		self::$URV = null;
		self::$UOV = null;
		self::$user = null;
	}

	// Tests go here.
	public function testARCASOff(){
		GLOBAL $Use_Auth_System;
		$BAStmp = $Use_Auth_System;
		$Use_Auth_System = 0;
		$URV = self::$URV . 'ARC().';
		$this->assertTrue(ARC(), $URV );
		$Use_Auth_System = $BAStmp;
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testARCASOn(){
		$URV = self::$URV . 'ARC().';
		$this->assertFalse(ARC(), $URV );
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleLogsAuthenticateError(){
		$URV = self::$URV . 'AuthorizedRole().';
		$UOV = self::$UOV . 'AuthorizedRole().';
		$PHPUV = self::$PHPUV;
		$EOM = ' BASE Security Alert AuthorizedRole(): '
		. 'Unauthenticated user access';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse( AuthorizedRole(), $URV );
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}
	}
	// test against test users.
	/**
	 * @backupGlobals disabled
	 */
	public function testARCFail(){
		$URV = self::$URV . 'ARC().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestUser|";
		$this->assertFalse(ARC(1), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testARCDisabledUserFail(){
		$URV = self::$URV . 'ARC().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestDisabledUser|";
		$this->assertFalse(ARC(1000), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testARCPass(){
		$URV = self::$URV . 'ARC().';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestOver|";
		$this->assertTrue( ARC(20000), $URV );
		unset ($_COOKIE['BASERole']);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testAuthorizedRoleLogsAuthorizeError(){
		$URV = self::$URV . 'AuthorizedRole().';
		$UOV = self::$UOV . 'AuthorizedRole().';
		$PHPUV = self::$PHPUV;
		$EOM = ' BASE Security Alert AuthorizedRole(): '
		. 'Unauthorized user access: TestAnonUser';
		$user = self::$user;
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAnonUser|";
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse( AuthorizedRole(1), $URV );
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}
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
		$URV = self::$URV . 'AuthorizedURI().';
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
		$this->assertTrue(AuthorizedURI(), $URV);
		unset ($_SERVER['REQUEST_URI']);
	}
	public function testAuthorizedClientNone(){
		$URV = self::$URV . 'AuthorizedClient().';
		$this->assertTrue(AuthorizedClient(), $URV);
	}
	public function testAuthorizedClientIPv4Fail(){
		GLOBAL $AllowedClients;
		$URV = self::$URV . 'AuthorizedClient().';
		$osv = $_SERVER;
		$oAC = $AllowedClients;
		$_SERVER['REMOTE_ADDR'] = '192.168.1.1';
		$AllowedClients = '192.168.0.0/24';
		$this->assertFalse(AuthorizedClient(), $URV);
		$AllowedClients = $oAC;
		$_SERVER = $osv;
	}
	public function testAuthorizedClientIPv4Pass(){
		GLOBAL $AllowedClients;
		$URV = self::$URV . 'AuthorizedClient().';
		$osv = $_SERVER;
		$oAC = $AllowedClients;
		$_SERVER['REMOTE_ADDR'] = '192.168.0.1';
		$AllowedClients = '192.168.0.0/24';
		$this->assertTrue(AuthorizedClient(), $URV);
		$AllowedClients = $oAC;
		$_SERVER = $osv;
	}
	public function testAuthorizedClientIPv4InvalidNetmask(){
		GLOBAL $AllowedClients;
		$URV = self::$URV . 'AuthorizedClient().';
		$osv = $_SERVER;
		$oAC = $AllowedClients;
		$_SERVER['REMOTE_ADDR'] = '192.168.0.1';
		$AllowedClients = '192.168.0.0/35';
		$this->assertTrue(AuthorizedClient(), $URV);
		$AllowedClients = $oAC;
		$_SERVER = $osv;
	}
	public function testAuthorizedClientIPv6Fail(){
		GLOBAL $AllowedClients;
		$URV = self::$URV . 'AuthorizedClient().';
		if( IPv6i() ){ // Can RTL Do IPv6 on this install.
			$osv = $_SERVER;
			$oAC = $AllowedClients;
			$_SERVER['REMOTE_ADDR'] = '1000::1';
			$AllowedClients = '::/96';
			$this->assertFalse(AuthorizedClient(), $URV);
			$AllowedClients = $oAC;
			$_SERVER = $osv;
		}else{
			self::markTestSkipped();
		}
	}
	public function testAuthorizedClientIPv6Pass(){
		GLOBAL $AllowedClients;
		$URV = self::$URV . 'AuthorizedClient().';
		if( IPv6i() ){ // Can RTL Do IPv6 on this install.
			$osv = $_SERVER;
			$oAC = $AllowedClients;
			$_SERVER['REMOTE_ADDR'] = '::1';
			$AllowedClients = '::/96';
			$this->assertTrue(AuthorizedClient(), $URV);
			$AllowedClients = $oAC;
			$_SERVER = $osv;
		}else{
			self::markTestSkipped();
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
