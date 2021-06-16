<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: /includes/base_net.inc.php
/**
  * Code Coverage Directives.
  * @uses ::LoadedString
  * @uses baseCon
  * @uses baseRS
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */


class netSPTest extends TestCase {
	// Pre Test Setup.
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
			self::$db = $db;
		}
	}
	public static function tearDownAfterClass() {
		self::$db = null;
	}

	// Tests go here.
	public function testbaseGetHostByAddrNoIpThrowsError() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$EEM = "BASE baseGetHostByAddr() Invalid Parameter(s) \$ipaddr.";
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
		baseGetHostByAddr('',$db,-10);
	}
	public function testbaseGetHostByAddrNoIpReturnsExpected() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			"<I>"._ERRRESOLVEADDRESS."</I>",
			@baseGetHostByAddr('',$db,-10),
			'Unexpected return baseGetHostByAddr().'
		);
	}
	public function testbaseGetHostByAddrInvalidIpThrowsError() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		$EEM = "BASE baseGetHostByAddr() Invalid Parameter(s) \$ipaddr.";
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
		baseGetHostByAddr('github.com',$db,-10);
	}
	public function testbaseGetHostByAddrInvalidIpReturnsExpected() {
		$db = self::$db;
		// Remove once we TD migrate this.
		define('_ERRRESOLVEADDRESS','Unable to resolve address');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			'github.com',
			@baseGetHostByAddr('github.com',$db,-10),
			'Unexpected return baseGetHostByAddr().'
		);
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
	public function testbaseGetHostByAddrValidIpOverflowFQDNThrowsError() {
		$db = self::$db;
		if ($db->DB_type == 'postgres' ){
			// Doesn't apply to postgresql, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}else{
			// Remove once we TD migrate this.
			define('_ERRRESOLVEADDRESS','Unable to resolve address');
			$EEM = "BASE baseGetHostByAddr() DB Field Overflow, FQDN for ";
			$EEM .= "3.24.117.66 concatenated to ";
			$EEM .= "2-3-24-117-66.ap-southeast-2.compute.amazonaws.com. ";
			$EEM .= "See: https://github.com/NathanGibbs3/BASE/issues/58";
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
			$tip = '3.24.117.66';
			$thn = '2-3-24-117-66.ap-southeast-2.compute.amazonaws.com';
			baseGetHostByAddr($tip,$db,10);
		}
	}
	public function testbaseGetHostByAddrValidIpOverflowFQDNReturnsExpected() {
		$db = self::$db;
		if ($db->DB_type == 'postgres' ){
			// Doesn't apply to postgresql, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}else{
			// Remove once we TD migrate this.
			define('_ERRRESOLVEADDRESS','Unable to resolve address');
			$tip = '3.24.117.66';
			$thn = '2-3-24-117-66.ap-southeast-2.compute.amazonaws.com';
			// Test conditions will throw error.
			// Use error suppression @ symbol.
			$this->assertEquals(
				$thn,
				@baseGetHostByAddr($tip,$db,10),
				'Unexpected return baseGetHostByAddr().'
			);
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
