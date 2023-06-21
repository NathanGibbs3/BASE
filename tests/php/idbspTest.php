<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_db.inc.php

/**
  * @covers baseCon::baseErrorMessage
  * @covers baseCon::baseExecute
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class dbspTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $DBlib_path;
	protected static $dbt;
	protected static $PHPUV;
	protected static $URV;

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
			self::$DBlib_path = $DBlib_path;
			self::$db = $db;
			self::$dbt = $db->DB->databaseType; // DB Type from ADODB Object.
		}
		self::$URV = 'Unexpected Return Value.';
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')) { // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
	}
	public static function tearDownAfterClass() {
		self::$URV = null;
		self::$PHPUV = null;
		self::$dbt = null;
		self::$db = null;
		self::$DBlib_path = null;
	}

	// Tests go here.
	public function testbaseErrorMessageEmptySQLReturnsExpected(){
		$db = self::$db;
		$dbt = self::$dbt;
		$URV = self::$URV.'baseErrorMessage().';
		$sql = '';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		@$db->baseExecute($sql,0,-1,false);
		$PHPV = GetPHPV();
		if (
			version_compare($PHPV, '5.5', '<')
			&& ( $dbt == "mysql" || $dbt == "mysqlt" || $dbt == "maxsql" )
		){
			// Empty SQL actually returns an error.
			// On PHP < 5.5x using the mysql driver.
			$msg = '<b>Database ERROR:<\/b> Query was empty';
			$this->assertRegExp( '/'.$msg.'/', $db->baseErrorMessage(), $URV );
		}else{
			$this->assertEquals( '', $db->baseErrorMessage(), $URV );
		}
	}
	public function testbaseErrorMessageInvalidSQLReturnsExpected(){
		$db = self::$db;
		$PHPUV = self::$PHPUV;
		$URV = self::$URV.'baseErrorMessage().';
		$sql = 'SELEXT * FROM acid_event';
		$msg = '<b>Database ERROR:<\/b>';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		@$db->baseExecute($sql, 0, -1, false);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $db->baseErrorMessage(), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp( '/'.$msg.'/', $db->baseErrorMessage(), $URV );
		}
	}
	public function testbaseErrorMessageInvalidSQLDebugReturnsExpected(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$PHPUV = self::$PHPUV;
		$URV = self::$URV.'baseErrorMessage().';
		$sql = 'SELEXT x FROM acid_event';
		$msg = "<p><code>$sql<\/code><\/p>";
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		@$db->baseExecute($sql, 0, -1, false);
		$odb = $debug_mode;
		$debug_mode = 1;
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$msg.'/', $db->baseErrorMessage(), $URV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp( '/'.$msg.'/', $db->baseErrorMessage(), $URV );
		}
		$debug_mode = $odb;
	}
	public function testbaseExecuteInvalidSQLThrowsError(){
		$db = self::$db;
		$dbt = self::$dbt;
		$sql = 'SELEXT * FROM acid_event';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
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
		$URV = self::$URV.'baseExecute().';
		$sql = 'SELEXT * FROM acid_event';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertFalse( @$db->baseExecute($sql,0,-1,false), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
