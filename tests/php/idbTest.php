<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::GetFieldLength
  * @covers ::MssqlKludgeValue
  * @covers ::NewBASEDBConnection
  * @covers baseCon::__construct
  * @covers baseCon::baseClose
  * @covers baseCon::baseCon
  * @covers baseCon::baseConnect
  * @covers baseCon::baseErrorMessage
  * @covers baseCon::baseExecute
  * @covers baseCon::baseFieldExists
  * @covers baseCon::baseGetDBversion
  * @covers baseCon::baseGetFLOP
  * @covers baseCon::baseIndexExists
  * @covers baseCon::basePConnect
  * @covers baseCon::baseSetDBversion
  * @covers baseCon::baseSetFLOP
  * @covers baseCon::baseTableExists
  * @uses ::ChkAccess
  * @uses ::ChkLib
  * @uses ::GetPHPSV
  * @uses ::HtmlColor
  * @uses ::LoadedString
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::is_key
  * @uses ::returnErrorMessage
  * @uses baseRS
  */

class dbTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $DBlib_path;
	protected static $dbt;
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;
	protected static $tc;

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
			self::$DBlib_path = $DBlib_path;
			self::$db = $db;
			self::$dbt = $db->DB->databaseType; // DB Type from ADODB Object.
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value.';
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
		self::$dbt = null;
		self::$db = null;
		self::$DBlib_path = null;
		self::$tc = null;
	}

	// Tests go here.
	// Tests for Class baseCon
	public function testClassbaseConConstruct(){
		GLOBAL $DBtype;
		$URV = self::$URV . 'Construct().';
		$DBlib_path = self::$DBlib_path;
		$this->assertInstanceOf(
			'baseCon',
			$tc = NewBASEDBConnection($DBlib_path, $DBtype),
			'DB Object Not Initialized.'
		);
		self::$tc = $tc;
		$Ec = 0;
		if( $DBtype == 'mysql' ){
			$Ec = 1;
		}
		$this->assertEquals($DBtype, $tc->DB_type, $URV);
		$this->assertNotNull($tc->DB_class, $URV);
		$this->assertEquals($Ec, $tc->DB_class, $URV);
		$this->assertEquals(0, $tc->version, $URV);
		$this->assertEmpty($tc->lastSQL, $URV);
		$this->assertNull($tc->DB_name, $URV);
		$this->assertNull($tc->DB_host, $URV);
		$this->assertNull($tc->DB_port, $URV);
		$this->assertNull($tc->DB_username, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseGetDBversion(){
		$URV = self::$URV . 'baseGetDBversion().';
		$tc = self::$tc;
		$this->assertEquals(0, $tc->baseGetDBversion(), $URV);
	}
	public function testClassbaseConbaseGetFLOPDBNo(){
		$URV = self::$URV . 'baseGetFLOP().';
		$tc = self::$tc;
		$this->assertNull($tc->FLOP, $URV);
		$this->assertFalse($tc->baseGetFLOP(), $URV);
		$this->assertNull($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseSetFLOPDBNo(){
		$URV = self::$URV . 'baseSetFLOP().';
		$tc = self::$tc;
		$this->assertNull($tc->FLOP, $URV);
		$this->assertFalse($tc->baseSetFLOP(), $URV);
		$this->assertNull($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseSetDBversionDBNo(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetDBversion().';
		$URV = self::$URV . 'baseSetDBversion().';
		$tc = self::$tc;
		$EOM = 'baseSetDBversion: DB not connected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(0, $tc->baseSetDBversion(), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
		$this->assertEquals(0, $tc->baseSetDBversion(), $URV);
	}
	public function testClassbaseConbaseConnect(){
		GLOBAL $alert_dbname, $alert_host, $alert_user, $alert_password,
		$alert_port;
		$URV = self::$URV . 'baseConnect().';
		$tc = self::$tc;
		$this->assertTrue(
			$tc->baseConnect(
				$alert_dbname, $alert_host, $alert_port, $alert_user,
				$alert_password
			), $URV
		);
		$this->assertEquals($alert_dbname, $tc->DB_name, $URV);
		$this->assertEquals($alert_host, $tc->DB_host, $URV);
		$this->assertEquals($alert_port, $tc->DB_port, $URV);
		$this->assertEquals($alert_user, $tc->DB_username, $URV);
		$this->assertEquals(107, $tc->version, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseSetFLOPDBYes(){
		$URV = self::$URV . 'baseSetFLOP().';
		$tc = self::$tc;
		$this->assertNull($tc->FLOP, $URV);
		$this->assertFalse($tc->baseSetFLOP(), $URV);
		$this->assertFalse($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseGetFLOPDBYes(){
		$URV = self::$URV . 'baseGetFLOP().';
		$tc = self::$tc;
		$this->assertFalse($tc->FLOP, $URV);
		$this->assertFalse($tc->baseGetFLOP(), $URV);
		$this->assertFalse($tc->FLOP, $URV);
	}
	public function testClassbaseConbaseSetDBversionDBYes(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetDBversion().';
		$URV = self::$URV . 'baseSetDBversion().';
		$tc = self::$tc;
		$EOM = 'baseSetDBversion:  DB Schema set to 107';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(107, $tc->baseSetDBversion(), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}
	public function testClassbaseConbaseClose(){
		GLOBAL $DBtype;
		$URV = self::$URV . 'baseClose().';
		$tc = self::$tc;
		$Ec = 0;
		if( $DBtype == 'mysql' ){
			$Ec = 1;
		}
		$this->assertTrue($tc->DB->isConnected(), $URV);
		$tc->baseClose();
		$this->assertFalse($tc->DB->isConnected(), $URV);
		$this->assertEquals($DBtype, $tc->DB_type, $URV);
		$this->assertNotNull($tc->DB_class, $URV);
		$this->assertEquals($Ec, $tc->DB_class, $URV);
		$this->assertEquals(0, $tc->version, $URV);
		$this->assertEmpty($tc->lastSQL, $URV);
		$this->assertNull($tc->DB_name, $URV);
		$this->assertNull($tc->DB_host, $URV);
		$this->assertNull($tc->DB_port, $URV);
		$this->assertNull($tc->DB_username, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}
	public function testClassbaseConbasePConnect(){
		GLOBAL $alert_dbname, $alert_host, $alert_user, $alert_password,
		$alert_port;
		$URV = self::$URV . 'basePConnect().';
		$tc = self::$tc;
		$this->assertTrue(
			$tc->basePConnect(
				$alert_dbname, $alert_host, $alert_port, $alert_user,
				$alert_password
			), $URV
		);
		$this->assertEquals($alert_dbname, $tc->DB_name, $URV);
		$this->assertEquals($alert_host, $tc->DB_host, $URV);
		$this->assertEquals($alert_port, $tc->DB_port, $URV);
		$this->assertEquals($alert_user, $tc->DB_username, $URV);
		$this->assertEquals(107, $tc->version, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}

	/**
	 * @backupGlobals disabled
	 */
	public function testbaseFieldExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseFieldExists().';
		$this->assertEquals(
			0, $db->baseFieldExists( 'what','ipc_fqdn'), $URV
		);
	}
	public function testbaseFieldExistsNonExistantFieldReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseFieldExists().';
		$this->assertEquals(
			0, $db->baseFieldExists( 'acid_ip_cache','How'), $URV
		);
	}
	public function testbaseFieldExistsValidDataReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseFieldExists().';
		$this->assertEquals(
			1, $db->baseFieldExists( 'acid_ip_cache','ipc_fqdn'), $URV
		);
	}
	public function testbaseTableExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseTableExists().';
		$this->assertEquals( 0, $db->baseTableExists( 'what'), $URV );
	}
	public function testbaseTableExistsValidDataReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseTableExists().';
		$this->assertEquals( 1, $db->baseTableExists( 'acid_ip_cache'), $URV );
	}
	public function testbaseIndexExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseIndexExists().';
		$this->assertEquals( 0, $db->baseIndexExists( 'what','ipc_ip'), $URV );
	}
	public function testbaseIndexExistsNonExistantFieldReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseIndexExists().';
		$this->assertEquals(
			0, $db->baseIndexExists( 'acid_ag_alert','How'), $URV
		);
	}
	public function testbaseIndexExistsValidDataReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseIndexExists().';
		$this->assertEquals(
			1, $db->baseIndexExists( 'acid_ag_alert','ag_id'), $URV
		);
	}

	public function testGetFieldLengthInvalidObjectThrowsError(){
		$EEM = "BASE GetFieldLength() Invalid DB Object.";
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
		GetFieldLength('db','What','How');
	}
	public function testGetFieldLengthInvalidObjectReturnsExpected(){
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals( 0, @GetFieldLength('db','What','How'), $URV );
	}
	public function testGetFieldLengthInvalidTableThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Table.";
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
		GetFieldLength($db, 1,2);
	}
	public function testGetFieldLengthInvalidTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals( 0, @GetFieldLength($db, 1,2), $URV );
	}
	public function testGetFieldLengthInvalidFieldThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Field.";
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
		GetFieldLength($db, 'acid_ip_cache',2);
	}
	public function testGetFieldLengthInvalidFieldReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0, @GetFieldLength($db, 'acid_ip_cache',2), $URV
		);
	}
	public function testGetFieldLengthEmptyTableThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Table.";
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
		GetFieldLength($db, '',2);
	}
	public function testGetFieldLengthEmptyTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals( 0, @GetFieldLength($db, '',2), $URV );
	}
	public function testGetFieldLengthEmptyFieldThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Field.";
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
		GetFieldLength($db, 'acid_ip_cache','');
	}
	public function testGetFieldLengthEmptyFieldReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0, @GetFieldLength($db, 'acid_ip_cache',''), $URV
		);
	}
	public function testGetFieldLengthNonExistantTableThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Table.";
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
		GetFieldLength($db, 'what','ipc_fqdn');
	}
	public function testGetFieldLengthNonExistantTableReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0, @GetFieldLength($db, 'what','ipc_fqdn'), $URV
		);
	}
	public function testGetFieldLengthNonExistantFieldThrowsError(){
		$db = self::$db;
		$EEM = "BASE GetFieldLength() Invalid Field.";
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
		GetFieldLength($db, 'acid_ip_cache','How');
	}
	public function testGetFieldLengthNonExistantFieldReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0, @GetFieldLength($db, 'acid_ip_cache','How'), $URV
		);
	}
	public function testGetFieldLengthFullSchemaSweep(){
		$db = self::$db;
		$URV = self::$URV.'GetFieldLength().';
		if ($db->DB_type == 'postgres' ){
			// Doesn't apply to postgresql, so Pass. See Issue #71
			$this->assertTrue(true,'Passing Test.');
		}else{
			$wtds = array (
				'key1' => 'acid_event,sig_name,255',
				'key2' => 'acid_ag,ag_name,40',
				'key3' => 'acid_ip_cache,ipc_fqdn,50'
			);
			foreach($wtds as $wtd){
				$wtps = explode(',', $wtd);
				$this->assertEquals(
					$wtps[2], GetFieldLength($db, $wtps[0],$wtps[1]), $URV
				);
			}
		}
	}
	public function testbaseExecuteValidSQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf( 'baseRS', $db->baseExecute($sql), $URV );
	}
	public function testbaseExecuteEmptySQLThrowsError(){
		$db = self::$db;
		$dbt = self::$dbt;
		$sql = '';
		$EEM = "$dbt error: [";
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
		$db->baseExecute($sql);
	}
	public function testbaseExecuteEmptySQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = '';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertFalse( @$db->baseExecute($sql,0,-1,false), $URV );
	}
	public function testMssqlKludgeValueReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'MssqlKludgeValue().';
		$sql = 'Test';
		$this->assertEquals( '[T][e][s][t]', MssqlKludgeValue('Test'), $URV );
	}
	public function testbaseErrorMessageValidSQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseErrorMessage().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$db->baseExecute($sql);
		$this->assertEquals( '', $db->baseErrorMessage(), $URV );
	}
	public function testbaseExecuteSQLLimitReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf( 'baseRS', $db->baseExecute($sql,1,4), $URV );
	}
	public function testbaseExecuteInvalidLimitsReturnExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$TD = array ( '',NULL, 1.5 );
		foreach($TD as $Top ){ // Test Start
			$this->assertFalse( $db->baseExecute($sql,$Top,4), $URV );
		}
		foreach($TD as $Top ){ // Test Count
			$this->assertFalse( $db->baseExecute($sql,1,$Top), $URV );
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
