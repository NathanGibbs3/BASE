<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_db.inc.php

/**
  * @covers ::GetFieldLength
  * @covers ::VerifyDBAbstractionLib
  * @covers baseCon::baseErrorMessage
  * @uses ::LoadedString
  * @uses ::HtmlColor
  * @uses ::returnErrorMessage
  * @uses baseCon
  * @uses baseRS
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */
class dbspTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $DBlib_path;
	protected static $dbt;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db;
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
	public function testbaseErrorMessageInvalidSQLReturnsExpected(){
		$db = self::$db;
		$sql = 'SELEXT * FROM acid_event';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		@$db->baseExecute($sql, 0, -1, false);
		$this->assertContains(
			'<b>Database ERROR:</b>',
			$db->baseErrorMessage(),
			'Unexpected return baseErrorMessage().'
		);
	}
	public function testbaseErrorMessageInvalidSQLDebugReturnsExpected(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$sql = 'SELEXT * FROM acid_event';
		// Remove once we TD migrate this.
		define('_ERRSQLDB','Database ERROR:');
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		@$db->baseExecute($sql, 0, -1, false);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->assertContains(
			"<p><code>$sql</code></p>",
			$db->baseErrorMessage(),
			'Unexpected return baseErrorMessage().'
		);
		$debug_mode = $odb;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
