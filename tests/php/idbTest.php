<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_db.inc.php

/**
  * @covers baseCon
  * @covers ::DumpSQL
  * @covers ::GetDALSV
  * @covers ::GetFieldLength
  * @covers ::MssqlKludgeValue
  * @covers ::NewBASEDBConnection
  * @covers ::filterSql
  * @uses ::ChkAccess
  * @uses ::ChkArchive
  * @uses ::ChkLib
  * @uses ::ErrorMessage
  * @uses ::GetPHPSV
  * @uses ::HtmlColor
  * @uses ::LoadedString
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::VS2SV
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
		$this->assertFalse($tc->DBF_RI, $URV);
		$this->assertFalse($tc->DBF_TS, $URV);
		$this->assertNull($tc->DB_name, $URV);
		$this->assertNull($tc->DB_host, $URV);
		$this->assertNull($tc->DB_port, $URV);
		$this->assertNull($tc->DB_username, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}

	public function testClassbaseConbaseGetRI(){
		$URV = self::$URV . 'baseGetRI().';
		$tc = self::$tc;
		$this->assertFalse($tc->DBF_RI, $URV);
		$this->assertFalse($tc->baseGetRI(), $URV);
	}

	// Move this down and alter once we have verified SetRI functions.
	public function testClassbaseConbaseGetRION(){
		$URV = self::$URV . 'baseGetRI().';
		$tc = self::$tc;
		$ORI = $tc->baseGetRI();
		$tc->DBF_RI = true;
		$this->assertTrue($tc->DBF_RI, $URV);
		$this->assertTrue($tc->baseGetRI(), $URV);
		$tc->DBF_RI = $ORI;
	}

	public function testClassbaseConbaseGetRIInvalid(){
		$URV = self::$URV . 'baseGetRI().';
		$tc = self::$tc;
		$ORI = $tc->baseGetRI();
		$tc->DBF_RI = 'string';
		$this->assertFalse($tc->baseGetRI(), $URV);
		$tc->DBF_RI = $ORI;
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

	public function testClassbaseConbaseisDBUpNo(){
		$URV = self::$URV . 'baseisDBUp().';
		$tc = self::$tc;
		$this->assertFalse($tc->baseisDBUp(), $URV);
	}

	public function testClassbaseConbaseisDBUpInvalidNo(){
		$URV = self::$URV . 'baseisDBUp().';
		$tc = self::$tc;
		$this->assertFalse($tc->baseisDBUp('string'), $URV);
	}

	public function testClassbaseConbaseisDBUpNoLog(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseisDBUp().';
		$URV = self::$URV . 'baseisDBUp().';
		$tc = self::$tc;
		$EOM = __FUNCTION__ . ': DB not connected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse($tc->baseisDBUp(true), $URV);
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

	public function testClassbaseConbaseSetRICYDBNCReturnsExpected(){
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertFalse($tc->DBF_RI, $URV);
		$this->assertFalse($tc->baseSetRI(), $URV);
		$this->assertFalse($tc->DBF_RI, $URV);
	}


	/**
	 * @backupGlobals disabled
	 */
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

	public function testClassbaseConbaseisDBUpYes(){
		$URV = self::$URV . 'baseisDBUp().';
		$tc = self::$tc;
		$this->assertTrue($tc->baseisDBUp(), $URV);
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

	public function testClassbaseConbaseTSEInvalidReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseTSE().';
		$URV = self::$URV . 'baseTSE().';
		$tc = self::$tc;
		if( $tc->DB_class == 0 ){ // Not Mysql / MariaDB.
			$this->assertTrue(true, 'Passing Test.');
		}else{
			$EOM = 'baseTSE():  does not exist.';
			$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
			$capture = tmpfile();
			$tmp = stream_get_meta_data($capture);
			ini_set('error_log', $tmp['uri']);
			$this->assertEquals('', $tc->baseTSE(0), $URV);
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
	}

	public function testClassbaseConbaseTSENonExistantTableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseTSE().';
		$URV = self::$URV . 'baseTSE().';
		$tc = self::$tc;
		if( $tc->DB_class == 0 ){ // Not Mysql / MariaDB.
			$this->assertTrue(true, 'Passing Test.');
		}else{
			$EOM = 'baseTSE(): what does not exist.';
			$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
			$capture = tmpfile();
			$tmp = stream_get_meta_data($capture);
			ini_set('error_log', $tmp['uri']);
			$this->assertEquals('', $tc->baseTSE('what'), $URV);
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
	}

	public function testClassbaseConbaseTSEValidReturnsExpected(){
		$UOV = self::$UOV . 'baseTSE().';
		$URV = self::$URV . 'baseTSE().';
		$tc = self::$tc;
		if( $tc->DB_class == 0 ){ // Not Mysql / MariaDB.
			$this->assertTrue(true, 'Passing Test.');
		}else{
			$this->assertEquals('InnoDB', $tc->baseTSE('event'), $URV);
			$TDB = $tc->DB_name;
			$tc->DB_name = 'testpig2';
			$this->assertEquals('MyISAM', $tc->baseTSE('event'), $URV);
			$tc->DB_name = $TDB;
		}
	}

	public function testClassbaseConbaseSetRICNDBYReturnsExpected(){
		GLOBAL $use_referential_integrity;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$ORI = $use_referential_integrity;
		$use_referential_integrity = 0;
		$this->assertFalse($tc->DBF_RI, $URV);
		$this->assertFalse($tc->baseSetRI(), $URV);
		$this->assertFalse($tc->DBF_RI, $URV);
		$use_referential_integrity = $ORI;
	}

	public function testClassbaseConbaseSetRICNDBNReturnsExpected(){
		GLOBAL $use_referential_integrity;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$ORI = $use_referential_integrity;
		$use_referential_integrity = 0;
		$this->assertFalse($tc->DBF_RI, $URV);
		$TDB = $tc->DB_name;
		$tc->DB_name = 'testpig2';
		$this->assertFalse($tc->baseSetRI(), $URV);
		$this->assertFalse($tc->DBF_RI, $URV);
		$tc->DB_name = $TDB;
		$use_referential_integrity = $ORI;
	}

	public function testClassbaseConbaseSetRICYDBNReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertFalse($tc->DBF_RI, $URV);
		$TDB = $tc->DB_name;
		$tc->DB_name = 'testpig2';
		$EOM = 'baseSetRI: DB RI set to false.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse($tc->baseSetRI(), $URV);
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
		$this->assertFalse($tc->DBF_RI, $URV);
		$tc->DB_name = $TDB;
	}

	public function testClassbaseConbaseSetRICYDBYReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertFalse($tc->DBF_RI, $URV);
		$EOM = 'baseSetRI: DB RI set to true.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->baseSetRI(), $URV);
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
		$this->assertTrue($tc->DBF_RI, $URV);
	}

	public function testClassbaseConbaseSetRICYDBYInvalidReturnsExpected(){
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertTrue($tc->DBF_RI, $URV);
		$this->assertTrue($tc->baseSetRI('string', 'string'), $URV);
		$this->assertTrue($tc->DBF_RI, $URV);
	}

	public function testClassbaseConbaseSetRICYDBYSetRIDisableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertTrue($tc->DBF_RI, $URV);
		$EOM = 'baseSetRI: DB RI set to false.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertFalse($tc->baseSetRI(false), $URV);
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
		$this->assertFalse($tc->DBF_RI, $URV);
	}

	public function testClassbaseConbaseSetRICYDBYSetRIEnableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$this->assertFalse($tc->DBF_RI, $URV);
		$EOM = 'baseSetRI: DB RI set to true.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->baseSetRI(), $URV);
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
		$this->assertTrue($tc->DBF_RI, $URV);
	}

	public function testClassbaseConbaseSetRICYDBYSetRIEnableCorruptRIReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'baseSetRI().';
		$URV = self::$URV . 'baseSetRI().';
		$tc = self::$tc;
		$DBSV = VS2SV($tc->DB->serverInfo()['version']);
		$this->assertTrue($tc->DBF_RI, $URV);
		// Start Corrupt the RI Structure.
		$tc->DBF_RI = false; // Disable RI Flag.
		$tmp = 'CONSTRAINT';
		// As of MySQL 8.0.19, ALTER TABLE permits more general
		// (and SQL standard) syntax for dropping and altering existing
		// constraints of any type,
		// https://dev.mysql.com/doc/refman/8.0/en/alter-table.html
		if(
			$tc->DB_class == 1
			&& (
				$DBSV[0] < 8
				|| ($DBSV[0] == 8 && $DBSV[1] == 0 && $DBSV[2] < 19)
			)
		){ // Mysql / MariaDB < 8.0.19
			$tmp = 'FOREIGN KEY';
		}
		$sql = "ALTER TABLE opt DROP $tmp IF EXISTS opt_fkey_sid_cid";
		$rs = $tc->DB->Execute($sql); // Corrupt RI Structure.
		if( $rs != false && $tc->baseErrorMessage() == '' ){ // Error Check
			$rs->Close();
		}else{ // Transient DB Error.
			$this->markTestIncomplete('Transient DB Error.');
		}
		// End Corrupt the RI Structure.
		$this->assertFalse($tc->DBF_RI, $URV);
		$EOM = 'baseSetRI: DB RI set to true.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->baseSetRI(), $URV);
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
		$this->assertTrue($tc->DBF_RI, $URV);
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
		$this->assertFalse($tc->DBF_RI, $URV);
		$this->assertFalse($tc->DBF_TS, $URV);
		$this->assertNull($tc->DB_name, $URV);
		$this->assertNull($tc->DB_host, $URV);
		$this->assertNull($tc->DB_port, $URV);
		$this->assertNull($tc->DB_username, $URV);
		$this->assertNull($tc->Role, $URV);
		$this->assertNull($tc->FLOP, $URV);
	}

	/**
	 * @backupGlobals disabled
	 */
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

	public function testbaseFieldExistsInvalidReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseFieldExists().';
		$this->assertEquals(0, $db->baseFieldExists(0, 0), $URV);
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
			0, $db->baseFieldExists( 'acid_ip_cache'), $URV
		);
	}
	public function testbaseFieldExistsValidDataReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseFieldExists().';
		$this->assertEquals(
			1, $db->baseFieldExists( 'acid_ip_cache','ipc_fqdn'), $URV
		);
	}

	public function testbaseTableExistsInvalidReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseTableExists().';
		$this->assertEquals( 0, $db->baseTableExists(0), $URV );
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

	public function testbaseIndexExistsInvalidReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseIndexExists().';
		$this->assertEquals( 0, $db->baseIndexExists(0, 0), $URV);
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

	/**
	 * @backupGlobals disabled
	 */
	public function testbaseInsertIDReturnsExpected(){
		$db = self::$db;
		$URV = self::$URV.'baseInsertID().';
		$this->assertEquals(-1, $db->baseInsertID(), $URV);
	}

	public function testGetFieldLengthInvalidTableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Table.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(0, GetFieldLength($tc, 1,2), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}

	public function testGetFieldLengthInvalidFieldReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Field.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(0, GetFieldLength($tc, 'acid_ip_cache', 2), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}

	public function testGetFieldLengthEmptyTableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Table.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals( 0, GetFieldLength($tc, '', 2), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}

	public function testGetFieldLengthEmptyFieldReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Field.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(0, GetFieldLength($tc, 'acid_ip_cache', ''), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}

	public function testGetFieldLengthNonExistantTableReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Table.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(0, GetFieldLength($tc, 'what', 'ipc_fqdn'), $URV);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
	}

	public function testGetFieldLengthNonExistantFieldReturnsExpected(){
		$PHPUV = self::$PHPUV;
		$UOV = self::$UOV . 'GetFieldLength().';
		$URV = self::$URV . 'GetFieldLength().';
		$tc = self::$tc;
		$EOM = 'GetFieldLength: Invalid Field.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertEquals(
			0, GetFieldLength($tc, 'acid_ip_cache', 'How'), $URV
		);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'$/', $elOutput, $UOV
			);
		}
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
	public function testfilterSQLNullReturnsNull() {
		$URV = self::$URV.'filterSQL().';
		$this->assertNull(filterSQL(NULL),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLValueReturnsNotNull() {
		$URV = self::$URV.'filterSQL().';
		$this->assertNotNull(filterSQL('Value'),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLNoTransformValue() {
		$URV = self::$URV.'filterSQL().';
		$this->assertEquals('Value',filterSQL('Value'),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformValue() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = "O'Niell";
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals($Ret,filterSQL($Value),$URV);
	}
	public function testfilterSQLNoTransformNonKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$Value = array (1,2,3,4);
		$this->assertEquals(array(1,2,3,4),filterSQL($Value),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformNonKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = array ("O'Niell",1,2,3,4);
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals(
			array("$Ret",1,2,3,4),filterSQL($Value),$URV
		);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLNoTransformKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$Value = array (
			'key1' => 0,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '0',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			filterSQL($Value),$URV
		);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = array (
			'key1' => "O'Niell",
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals(
			array(
				'key1' => $Ret,
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			filterSQL($Value),$URV
		);
	}
	public function testGetDALSVreturnsexpected() {
		$URV = self::$URV.'GetDALSV().';
		$tmp = GetDALSV();
		foreach( $tmp as $val ){
			$this->assertTrue(is_numeric($val), $URV);
		}
	}
	public function testDumpSQLDefaults() {
		$UOV = self::$UOV . 'DumpSQL().';
		$EOM = '';
		$this->expectOutputString($EOM, DumpSQL(), $UOV);
	}
	public function testDumpSQLInvalidType() {
		$UOV = self::$UOV . 'DumpSQL().';
		$Msg = 'Test';
		$EOM = "<font color='black'>SQL Executed: Test</font><br/>";
		$this->expectOutputString($EOM, DumpSQL($Msg, 'string'), $UOV);
	}
	public function testDumpSQLInvalidValue() {
		$UOV = self::$UOV . 'DumpSQL().';
		$Msg = 'Test';
		$EOM = "<font color='black'>SQL Executed: Test</font><br/>";
		$this->expectOutputString($EOM, DumpSQL($Msg, -1), $UOV);
	}
	public function testDumpSQLMsg() {
		$UOV = self::$UOV . 'DumpSQL().';
		$Msg = 'Test';
		$EOM = "<font color='black'>SQL Executed: Test</font><br/>";
		$this->expectOutputString($EOM, DumpSQL($Msg), $UOV);
	}
	public function testDumpSQLMsgLvlHit() {
		$UOV = self::$UOV . 'DumpSQL().';
		$Msg = 'Test';
		$EOM = "<font color='black'>SQL Executed: Test</font><br/>";
		$this->expectOutputString($EOM, DumpSQL($Msg, 0), $UOV);
	}
	public function testDumpSQLMsgLvlMiss() {
		$UOV = self::$UOV . 'DumpSQL().';
		$Msg = 'Test';
		$EOM = '';
		$this->expectOutputString($EOM, DumpSQL($Msg, 1), $UOV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
