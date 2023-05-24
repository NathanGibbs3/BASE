<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_log_timing.inc.php

/**
  * Code Coverage Directives.
  * @covers EventTiming
  * @uses BaseUser
  * @uses baseCon
  * @uses ::ARC
  * @uses ::ChkAccess
  * @uses ::ChkLib
  * @uses ::GetPHPSV
  * @uses ::LoadedString
  * @uses ::NewBASEDBConnection
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::SetConst
  */

class log_timingTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;
	protected static $db;
	protected static $tc;

	public static function setUpBeforeClass(){
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db, $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
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
				'baseCon', $db, 'DB Object Not Initialized.'
			);
			self::$db = $db;
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass(){
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
		self::$db = null;
		self::$tc = null;
	}

	// Tests go here.
	// Tests for Class EventTiming
	public function testClassEventTimingConstruct(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(0),
			'Class Not Initialized.'
		);
		self::$tc = $tc;
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(0, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load.', $tc->event_log[0][1], $URV);
	}
	// Test Mark Function
	public function testClassEventTimingMarkEmpty(){
		$URV = self::$URV.'Mark().';
		$tc = self::$tc;
		$tc->Mark();
		$this->assertNotEquals(0, $tc->event_log[1][0], $URV);
		$this->assertEquals('MARK', $tc->event_log[1][1], $URV);
	}
	public function testClassEventTimingMarkLoaded(){
		$URV = self::$URV.'Mark().';
		$tc = self::$tc;
		$tc->Mark('What');
		$this->assertNotEquals(0, $tc->event_log[2][0], $URV);
		$this->assertEquals('What', $tc->event_log[2][1], $URV);
	}
	// Test PrintTiming Function
	public function testClassEventTimingPrintTimng0(){
		$UOV = self::$UOV.'PrintTimng().';
		$tc = self::$tc;
		$expected = '';
		$this->expectOutputString($expected, $tc->PrintTiming(), $UOV);
	}
	// Test Classify Function
	public function testClassEventTimingClassify(){
		$URV = self::$URV.'Classify().';
		$tc = self::$tc;
		$TA = array(0, 10, 30, 60);
		$TE = array(
			0 => "<span style='color: green;'>",
			10 => "<span>",
			30 => "<span style='color: yellow;'>",
			60 => "<span style='color: red;'>"
		);
		foreach( $TA as $val ){
			$this->assertEquals($TE[$val], $tc->Classify($val), $URV);
		}
	}
	// Test debug display lockout for unauthenticated users
	public function testClassEventTimingConstructUnAuth(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(1),
			'Class Not Initialized.'
		);
		self::$tc = $tc;
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(0, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load.', $tc->event_log[0][1], $URV);
	}
	// Test PrintTiming Function
	public function testClassEventTimingPrintTimng1UnAuth(){
		$UOV = self::$UOV.'PrintTimng().';
		$tc = self::$tc;
		$expected = '';
		$this->expectOutputString($expected, $tc->PrintTiming(), $UOV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
