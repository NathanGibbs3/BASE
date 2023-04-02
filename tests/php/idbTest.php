<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::GetFieldLength
  * @covers ::MssqlKludgeValue
  * @covers baseCon::baseErrorMessage
  * @covers baseCon::baseFieldExists
  * @covers baseCon::baseTableExists
  * @covers baseCon::baseIndexExists
  * @covers baseCon::baseExecute
  * @uses ::ChkAccess
  * @uses ::LoadedString
  * @uses ::HtmlColor
  * @uses ::base_array_key_exists
  * @uses ::returnErrorMessage
  * @uses baseRS
  */
class dbTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $DBlib_path;
	protected static $dbt;
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
			self::$DBlib_path = $DBlib_path;
			self::$db = $db;
			self::$dbt = $db->DB->databaseType; // DB Type from ADODB Object.
		}
		self::$URV = 'Unexpected Return Value.';
	}
	public static function tearDownAfterClass() {
		self::$URV = null;
		self::$dbt = null;
		self::$db = null;
		self::$DBlib_path = null;
	}

	// Tests go here.
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
			// Doesn't apply to postgresql, so Pass.
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
	public function testbaseErrorMessageValidSQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseErrorMessage().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$db->baseExecute($sql);
		$this->assertEquals( '', $db->baseErrorMessage(), $URV );
	}
	public function testbaseExecuteValidSQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf( 'baseRS', $db->baseExecute($sql), $URV );
	}
	public function testbaseExecuteSQLWithLimitReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf( 'baseRS', $db->baseExecute($sql,1,4), $URV );
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
	public function testbaseExecuteInvalidSQLThrowsError(){
		$db = self::$db;
		$dbt = self::$dbt;
		$sql = 'SELEXT * FROM acid_event';
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
			$PHPV = GetPHPV();
			if ( version_compare($PHPV, '8.0', '>=') ){
				$this->expectException("PHPUnit\Framework\Error\Error");
			}else{
				$this->expectException("PHPUnit\Framework\Error\Notice");
			}
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 9+
			$PHPV = GetPHPV();
			if ( version_compare($PHPV, '8.0', '>=') ){
				$this->expectError();
				$this->expectErrorMessage($EEM);
			}else{
				$this->expectNotice();
				$this->expectNoticeMessage($EEM);
			}
		}
		$db->baseExecute($sql);
	}
	public function testbaseExecuteInvalidSQLReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELEXT * FROM acid_event';
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

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
