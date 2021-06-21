<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::GetFieldLength
  * @covers baseCon::baseErrorMessage
  * @covers baseCon::baseFieldExists
  * @covers baseCon::baseTableExists
  * @covers baseCon::baseIndexExists
  * @covers baseCon::baseExecute
  * @uses ::ChkAccess
  * @uses ::LoadedString
  * @uses ::HtmlColor
  * @uses ::returnErrorMessage
  * @uses baseRS
  */
class dbTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $DBlib_path;
	protected static $dbt;

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
	}
	public static function tearDownAfterClass() {
		self::$dbt = null;
		self::$db = null;
		self::$DBlib_path = null;
	}

	// Tests go here.
	public function testbaseFieldExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			0,
			$db->baseFieldExists( 'what','ipc_fqdn'),
			'Unexpected return baseFieldExists().'
		);
	}
	public function testbaseFieldExistsNonExistantFieldReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			0,
			$db->baseFieldExists( 'acid_ip_cache','How'),
			'Unexpected return baseFieldExists().'
		);
	}
	public function testbaseFieldExistsValidDataReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			1,
			$db->baseFieldExists( 'acid_ip_cache','ipc_fqdn'),
			'Unexpected return baseFieldExists().'
		);
	}
	public function testbaseTableExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			0,
			$db->baseTableExists( 'what'),
			'Unexpected return baseTableExists().'
		);
	}
	public function testbaseTableExistsValidDataReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			1,
			$db->baseTableExists( 'acid_ip_cache'),
			'Unexpected return baseTableExists().'
		);
	}
	public function testbaseIndexExistsNonExistantTableReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			0,
			$db->baseIndexExists( 'what','ipc_ip'),
			'Unexpected return baseIndexExists().'
		);
	}
	public function testbaseIndexExistsNonExistantFieldReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			0,
			$db->baseIndexExists( 'acid_ag_alert','How'),
			'Unexpected return baseIndexExists().'
		);
	}
	public function testbaseIndexExistsValidDataReturnsExpected(){
		$db = self::$db;
		$this->assertEquals(
			1,
			$db->baseIndexExists( 'acid_ag_alert','ag_id'),
			'Unexpected return baseIndexExists().'
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength('db','What','How'),
			'Unexpected return GetFieldLength().'
		);
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, 1,2),
			'Unexpected return GetFieldLength().'
		);
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, 'acid_ip_cache',2),
			'Unexpected return GetFieldLength().'
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, '',2),
			'Unexpected return GetFieldLength().'
		);
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, 'acid_ip_cache',''),
			'Unexpected return GetFieldLength().'
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, 'what','ipc_fqdn'),
			'Unexpected return GetFieldLength().'
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
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			0,
			@GetFieldLength($db, 'acid_ip_cache','How'),
			'Unexpected return GetFieldLength().'
		);
	}
	public function testGetFieldLengthFullSchemaSweep(){
		$db = self::$db;
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
					$wtps[2],
					GetFieldLength($db, $wtps[0],$wtps[1]),
					'Unexpected return GetFieldLength().'
				);
			}
		}
	}
	public function testbaseErrorMessageValidSQLReturnsExpected(){
		$db = self::$db;
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$db->baseExecute($sql);
		$this->assertEquals(
			'',
			$db->baseErrorMessage(),
			'Unexpected return baseErrorMessage().'
		);
	}
	public function testbaseExecuteValidSQLReturnsExpected(){
		$db = self::$db;
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf(
			'baseRS',
			$db->baseExecute($sql),
			'Unexpected return baseExecute().'
		);
	}
	public function testbaseExecuteSQLWithLimitReturnsExpected(){
		$db = self::$db;
		$sql = 'SELECT * FROM acid_event WHERE 1=2';
		$this->assertInstanceOf(
			'baseRS',
			$db->baseExecute($sql,1,4),
			'Unexpected return baseExecute().'
		);
	}
	public function testbaseExecuteNULLSQLThrowsError(){
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
	public function testbaseExecuteNULLSQLReturnsExpected(){
		$db = self::$db;
		$dbt = self::$dbt;
		$sql = '';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertFalse(
			@$db->baseExecute($sql,0,-1,false),
			'Unexpected return baseExecute().'
		);
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
			$this->expectException("PHPUnit\Framework\Error\Notice");
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 9+
			$this->expectNotice();
			$this->expectNoticeMessage($EEM);
		}
		$db->baseExecute($sql);
	}
	public function testbaseExecuteInvalidSQLReturnsExpected(){
		$db = self::$db;
		$dbt = self::$dbt;
		$sql = 'SELEXT * FROM acid_event';
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertFalse(
			@$db->baseExecute($sql,0,-1,false),
			'Unexpected return baseExecute().'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
