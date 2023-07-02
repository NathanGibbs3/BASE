<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: includes/base_net.inc.php

/**
  * Code Coverage Directives.
  * @uses baseCon
  * @uses baseRS
  * @uses ::LoadedString
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class netSPTest extends TestCase {
	// Pre Test Setup.
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;
	protected static $db;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
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
				'baseCon', $db, 'DB Object Not Initialized.'
			);
			self::$db = $db;
		}
		$PHPUV = GetPHPUV(); // PHPUnit Version
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
	}
	public static function tearDownAfterClass() {
		self::$PHPUV = null;
		self::$UOV = null;
		self::$URV = null;
		self::$db = null;
	}

	// Tests go here.
	public function testbaseGetHostByAddrNoIpReturnsExpected() {
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseGetHostByAddr().';
		$URV = self::$URV . 'baseGetHostByAddr().';
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$EOM = 'baseGetHostByAddr: Invalid Parameter \$ipaddr.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(
			'<I>' . _ERRRESOLVEADDRESS . '</I>',
			baseGetHostByAddr('', $db, -10), $URV
		);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/'.$EOM.'$/', $elOutput, $UOV);
		}
	}

	public function testbaseGetHostByAddrInvalidIpReturnsExpected() {
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseGetHostByAddr().';
		$URV = self::$URV . 'baseGetHostByAddr().';
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$EOM = 'baseGetHostByAddr: Invalid Parameter \$ipaddr.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(
			'github.com', baseGetHostByAddr('github.com', $db, -10), $URV
		);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp('/'.$EOM.'$/', $elOutput, $UOV);
		}
	}

	public function testbaseGetHostByAddrValidIpCacheMiss() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$tip = '167.99.172.230';
		$thn = 'dr1.cmpublishers.com';
		$ip32 = baseIP2long($tip);
		$sql = "SELECT ipc_ip,ipc_fqdn,ipc_dns_timestamp".
		" FROM acid_ip_cache  WHERE ipc_ip = '$ip32' ";
		$dsql = "DELETE FROM acid_ip_cache WHERE ipc_ip = '$ip32' ";
		$db->baseExecute($dsql);
		baseGetHostByAddr($tip,$db,-10);
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$ots = $ip_cache[2];
		sleep(1);
		baseGetHostByAddr($tip,$db,-10);
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$this->assertNotEquals(
			$ots,
			$ip_cache[2],
			'Unexpected return baseGetHostByAddr().'
		);
		$this->assertEquals(
			$thn,
			$ip_cache[1],
			'Unexpected return baseGetHostByAddr().'
		);
	}
	public function testbaseGetHostByAddrValidIpCacheHit() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$tip = '167.99.172.230';
		$thn = 'dr1.cmpublishers.com';
		$ip32 = baseIP2long($tip);
		baseGetHostByAddr($tip,$db,-10);
		$sql = "SELECT ipc_ip,ipc_fqdn,ipc_dns_timestamp".
		" FROM acid_ip_cache  WHERE ipc_ip = '$ip32' ";
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$ots = $ip_cache[2];
		baseGetHostByAddr($tip,$db,10);
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$this->assertEquals(
			$ots,
			$ip_cache[2],
			'Unexpected return baseGetHostByAddr().'
		);
		$this->assertEquals(
			$thn,
			$ip_cache[1],
			'Unexpected return baseGetHostByAddr().'
		);
	}
	public function testbaseGetHostByAddrValidIpCacheHitReturnsIP() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$tip = '127.0.0.254';
		$thn = '<I>Unable to resolve address</I>';
		$ip32 = baseIP2long($tip);
		baseGetHostByAddr($tip,$db,-10);
		$sql = "SELECT ipc_ip,ipc_fqdn,ipc_dns_timestamp".
		" FROM acid_ip_cache  WHERE ipc_ip = '$ip32' ";
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$ots = $ip_cache[2];
		baseGetHostByAddr($tip,$db,10);
		$result = $db->baseExecute($sql);
		$ip_cache = $result->baseFetchRow();
		$this->assertEquals(
			$ots,
			$ip_cache[2],
			'Unexpected return baseGetHostByAddr().'
		);
		$this->assertEquals(
			$thn,
			baseGetHostByAddr($tip,$db,10),
			'Unexpected return baseGetHostByAddr().'
		);
	}
	public function testbaseGetHostByAddrValidIpCacheHitReturnsFQDN() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$tip = '167.99.172.230';
		$thn = 'dr1.cmpublishers.com';
		$this->assertEquals(
			$thn,
			baseGetHostByAddr($tip,$db,10),
			'Unexpected return baseGetHostByAddr().'
		);
	}

	public function testbaseGetHostByAddrValidIpOverflowFQDNReturnsExpected() {
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseGetHostByAddr().';
		$URV = self::$URV . 'baseGetHostByAddr().';
		$db = self::$db;
		if ($db->DB_type == 'postgres' ){
			// Doesn't apply to postgresql, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}else{
			// Remove once we TD migrate this.
			define('_ERRRESOLVEADDRESS','Unable to resolve address');
			$tip = '3.24.117.66';
			$thn = '2-3-24-117-66.ap-southeast-2.compute.amazonaws.com';
			$EOM = 'baseGetHostByAddr: Warning: Issue #58 DB Field Overflow, '
			. 'FQDN for 3.24.117.66 concatenated to '
			. '2-3-24-117-66.ap-southeast-2.compute.amazonaws.com. '
			. 'See: https:\/\/github.com\/NathanGibbs3\/BASE\/issues\/58';
			$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
			$capture = tmpfile();
			$tmp = stream_get_meta_data($capture);
			ini_set('error_log', $tmp['uri']);
			$this->assertEquals($thn, baseGetHostByAddr($tip,$db,10), $URV);
			ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
			$elOutput = stream_get_contents($capture);
			if ( $PHPUV > 1 ){ // PHPUnit 9+
				$this->assertMatchesRegularExpression(
					'/' . $EOM . '$/', $elOutput, $UOV
				);
			}else{ // Legacy PHPUnit
				$this->assertRegExp('/' . $EOM . '$/', $elOutput, $UOV);
			}
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
