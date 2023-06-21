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
  * @uses ::VS2SV
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class log_timingSPTest extends TestCase {
	// Pre Test Setup.
	protected static $UIL;
	protected static $UOV;
	protected static $URV;
	protected static $files;
	protected static $langs;
	protected static $tc;

	// We are using a single TD file.
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
				'baseCon', $db, 'DB Object Not Initialized.'
			);
		}
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$ll);
			LogTC($tf,'TD file',$file);
		}
		if ( class_exists('UILang') ){
			// Setup UI Language Object
			// Will throw error during TD transition.
			// Use error suppression @ symbol.
			self::assertInstanceOf(
				'UILang', self::$UIL = @new UILang($ll),
				"Class for $ll not created."
			);
		}else{
			self::$files = $file;
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		self::assertInstanceOf(
			'BaseUser',
			$user = new BaseUser(),
			'User Object Not Initialized.'
		);
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAdmin";
		self::assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(1),
			'Class Not Initialized.'
		);
		self::$tc = $tc;
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$UOV = null;
		self::$URV = null;
		self::$langs = null;
		self::$files = null;
		self::$tc = null;
		unset ($_COOKIE['BASERole']);
	}
	// Tests go here.
	public function testClassEventTimingConstructAuth(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(1),
			'Class Not Initialized.'
		);
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(1, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load.', $tc->event_log[0][1], $URV);
	}
	public function testClassEventTimingConstructAuthInvalidParam(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming('string'),
			'Class Not Initialized.'
		);
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(0, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load.', $tc->event_log[0][1], $URV);
	}
	// Test PrintTiming Function
	public function testClassEventTimingPrintTiming1(){
		GLOBAL $BASE_installID;
		$UOV = self::$UOV . 'PrintTimng().';
		$tc = self::$tc;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = '\n\t\t\t\t\t\t<!-- Timing Information -->'
		. '\n\t\t\t\t\t\t<div class=\'systemdebug\'>'
		. '\n\t\t\t\t\t\t\t\<span( style=\'color: green;\')?\>'
		. 'Loaded in\<\/span\> \[[0-1] seconds\]\<br\/\>'
		. '\n\t\t\t\t\t\t\<\/div\>';
		$this->expectOutputRegex('/^' . $EOM . '$/', $tc->PrintTiming(), $UOV);
	}
	public function testClassEventTimingPrintTiming2(){
		GLOBAL $BASE_installID;
		$UOV = self::$UOV.'PrintTimng().';
		$tc = self::$tc;
		$tc->Mark('What');
		$tc->verbose = 2;
		if( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = '\n\t\t\t\t\t\t<!-- Timing Information -->'
		. '\n\t\t\t\t\t\t<div class=\'systemdebug\'>'
		. '\n\t\t\t\t\t\t\t\<span( style=\'color: green;\')?\>'
		. 'Loaded in\<\/span\> \[[0-1] seconds\]\<br\/\>'
		. '\n\t\t\t\t\t\t\tEvent Log:\<br\/\>'
		. '\n\t\t\t\t\t\t\t1 \<span( style=\'color: green;\')?\>'
		. 'Page Load.\<\/span\> \[[0-1] seconds\]\<br\/\>'
		. '\n\t\t\t\t\t\t\t2 \<span( style=\'color: green;\')?\>'
		. 'What\<\/span\> \[[0-1] seconds\]\<br\/\>'
		. '\n\t\t\t\t\t\t\<\/div\>';
		$this->expectOutputRegex('/^' . $EOM . '$/', $tc->PrintTiming(), $UOV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
