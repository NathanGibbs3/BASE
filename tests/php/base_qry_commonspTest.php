<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_qry_common.php
// Tests that need process isolation.

/**
  * @covers ::DateTimeRows2sql
  * @covers ::PrintCriteria
  * @uses ::NLI
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class base_qry_commonspTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $db;
	protected static $EOP;
	protected static $EOS;
	protected static $DTR2SQL;
	protected static $URV;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db;
		$tf = __FUNCTION__;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
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
			self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
				"Class for $ll not created."
			);
		}else{
			self::$files = $file;
		}
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
		self::$EOP =
		"\n\t\t<table style = 'border: 2px solid black;".
		" border-collapse: collapse; width:30%;' summary='Search Criteria'>".
		"\n\t\t\t<tr>".
		"\n\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>Search Criteria</td>".
		"\n\t\t\t</tr><tr>".
		"\n\t\t\t\t<td class='metatitle' style='width: 35%;'>".
		"Meta Criteria</td>".
		"\n\t\t\t\t<td>";
		self::$EOS =
		"\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t</table>";
		InitArray(self::$DTR2SQL,2,10,''); //Setup Test Array
		self::$URV = 'Unexpected Return Value.';
	}
	public static function tearDownAfterClass() {
		self::$URV = null;
		self::$DTR2SQL = null;
		self::$EOS = null;
		self::$EOP = null;
		self::$db = null;
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testPrintCriteriaLastTCPReturnsValid() {
		GLOBAL $cs, $BASE_installID;
		$db = self::$db;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_tcp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 TCP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastUDPReturnsValid() {
		GLOBAL $cs, $BASE_installID;
		$db = self::$db;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_udp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 UDP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastICMPReturnsValid() {
		GLOBAL $cs, $BASE_installID;
		$db = self::$db;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_icmp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 ICMP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastAnyReturnsValid() {
		GLOBAL $cs, $BASE_installID;
		$db = self::$db;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_any';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}

	// DateTimeRows2sql Basic Input Validation
	public function testDateTimeRows2sqlEmpty() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$this->assertEquals( 0, DateTimeRows2sql($TA,2,$SQL), $URV );
	}
	public function testDateTimeRows2sqlSpaces() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		InitArray($TA,2,10,' '); // Setup Test Array
		$this->assertEquals( 0, DateTimeRows2sql($TA,2,$SQL), $URV );
	}
	public function testDateTimeRows2sqlInputFieldNotArray() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$TA = '';
		$this->assertEquals( 0, DateTimeRows2sql($TA,2,$SQL), $URV );
	}
	public function testDateTimeRows2sqlInputCountNotNumeric() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$this->assertEquals( 0, DateTimeRows2sql($TA,'a',$SQL), $URV );
	}

	// DateTimeRows2sql Error Conditions
	public function testDateTimeRows2sqlErrorMessageInvalidTimeCriteria() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'><b>Criteria warning:</b> ".
		"An operator of '<' was selected indicating that some date/time ".
		'criteria should be matched, but no value was specified.</font>';
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		InitArray($TA,2,10,' '); //Setup Test Array
		$TA[$start][$op] = '<';
		$this->expectOutputString($EOM);
		$this->assertEquals( 0, DateTimeRows2sql($TA,1,$SQL), $URV );
	}
	public function testDateTimeRows2sqlErrorMessageInvalidHour() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'><b>Criteria warning:</b> ".
		'(Invalid Hour) No date criteria were entered with the specified time.'.
		'</font>';
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$op] = '<';
		$TA[$start][$hour] = '1';
		$this->expectOutputString($EOM);
		DateTimeRows2sql($TA,1,$SQL);
	}
	public function testDateTimeRows2sqlErrorMessageInvalidBool() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'><b>Criteria warning:</b> ".
		'Multiple Date/Time criteria entered without a boolean operator '.
		'(e.g. AND, OR) between them.</font>';
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$op] = '=';
		$TA[$start][$year] = '1';
		$TA[$start][$SQLOP] = ' ';
		$this->expectOutputString($EOM);
		DateTimeRows2sql($TA,2,$SQL);
	}
	public function testDateTimeRows2sqlErrorMessageInvalidOp() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'><b>Criteria warning:</b> ".
		"A date/time value of '3-1-2 4:5:6' was entered but no operator was ".
		'selected.</font>';
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$op] = '';
		$TA[$start][$month] = '1';
		$TA[$start][$day] = '2';
		$TA[$start][$year] = '3';
		$TA[$start][$hour] = '4';
		$TA[$start][$minute] = '5';
		$TA[$start][$second] = '6';
		$TA[$start][$SQLOP] = 'AND';
		$this->expectOutputString($EOM);
		DateTimeRows2sql($TA,2,$SQL);
	}

	// DateTimeRows2sql Basic Operators Validation
	public function testDateTimeRows2sqlTestOps() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$month] = '1';
		$TA[$start][$day] = '1';
		$TA[$start][$year] = '1';
		$TA[$start][$hour] = '23';
		$TA[$start][$minute] = '00';
		$TA[$start][$second] = '59';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= "( YEAR(timestamp) = 1  AND  MONTH(timestamp) = 1  AND  DAYOFMONTH(timestamp) = 1  AND  HOUR(timestamp) = 23  AND  MINUTE(timestamp) = 00  AND  SECOND(timestamp) = 59 )";
			}else{
				$EOM .= " timestamp $Top'2001-01-01 23:00:59'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}

	// DateTimeRows2sql Incomplete Data Tests.
	public function testDateTimeRows2sqlTestFirstYearNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$EOM = '';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM .= "<font color='#ff0000'><b>Criteria warning:</b> ".
			"An operator of '$Top' was selected indicating that some ".
			'date/time criteria should be matched, but no value was '.
			'specified.</font>';
			$this->expectOutputString($EOM);
			$this->assertEquals( 0, DateTimeRows2sql($TA,1,$SQL), $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestFirstMonthNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$year] = '1';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= "( YEAR(timestamp) = 1 )";
			}elseif ( $Top == '>' || $Top == '<=' ){
				$EOM .= " timestamp $Top'2001-01-01 23:59:59'";
			}else{
				$EOM .= " timestamp $Top'2001-01-01 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestFirstDayNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$month] = '2';
		$TA[$start][$year] = '1';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= "( YEAR(timestamp) = 1  AND  MONTH(timestamp) = 2 )";
			}elseif ( $Top == '>' || $Top == '<=' ){
				$EOM .= " timestamp $Top'2001-02-01 23:59:59'";
			}else{
				$EOM .= " timestamp $Top'2001-02-01 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestLastDayNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$month] = '2';
		$TA[$start][$day] = '';
		$TA[$start][$year] = '2000';
		$TA[$start][$SQLOP] = 'AND';
		$TA[$end][$month] = '2';
		$TA[$end][$year] = '2000';
		$TA[$end][$day] = '';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$TA[$end][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= '( YEAR(timestamp) = 2000  AND  MONTH(timestamp) = 2 '.
				') AND( YEAR(timestamp) = 2000  AND  MONTH(timestamp) = 2 )';
			}elseif ( $Top == '>' || $Top == '<=' ){
				$EOM .= " timestamp $Top'2000-02-01 23:59:59' ".
				"AND timestamp $Top'2000-02-29 23:59:59'";
			}else{
				$EOM .= " timestamp $Top'2000-02-01 00:00:00' ".
				"AND timestamp $Top'2000-02-29 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,2,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestLastDayNotSetIssue132() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$month] = '2';
		$TA[$start][$day] = '';
		$TA[$start][$year] = '2000';
		$TA[$start][$SQLOP] = 'AND';
		$TA[$end][$month] = '2';
		$TA[$end][$year] = '2000';
		$TA[$end][$day] = '';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$TA[$end][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= '( YEAR(timestamp) = 2000  AND  MONTH(timestamp) = 2 )';
			}elseif ( $Top == '>' || $Top == '<=' ){
				$EOM .= " timestamp $Top'2000-02-01 23:59:59'";
			}else{
				$EOM .= " timestamp $Top'2000-02-01 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestFirstHourNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$op] = '<';
		$TA[$start][$month] = '2';
		$TA[$start][$year] = '1';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= '( YEAR(timestamp) = 1  AND  MONTH(timestamp) = 2 )';
			}elseif ( $Top == '>' || $Top == '<=' ){
				$EOM .= " timestamp $Top'2001-02-01 23:59:59'";
			}else{
				$EOM .= " timestamp $Top'2001-02-01 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestFirstMinuteNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$month] = '2';
		$TA[$start][$day] = '1';
		$TA[$start][$year] = '1';
		$TA[$start][$hour] = '0';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= '( YEAR(timestamp) = 1  AND  MONTH(timestamp) = 2  '.
				'AND  DAYOFMONTH(timestamp) = 1  AND  HOUR(timestamp) = 0 )';
			}elseif ( $Top == '<=' ){
				$EOM .= " timestamp $Top'2001-02-01 00:59:59'";
			}else{
				$EOM .= " timestamp $Top'2001-02-01 00:00:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}
	public function testDateTimeRows2sqlTestFirstSecondNotSet() {
		GLOBAL $BASE_installID;
		$db = self::$db;
		$TA = self::$DTR2SQL;
		$URV = self::$URV;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$start = 0;
		$end = 1;
		$op = 1;
		$month = 2;
		$day = 3;
		$year = 4;
		$hour = 5;
		$minute = 6;
		$second = 7;
		$stop = 8;
		$SQLOP = 9;
		$TA[$start][$op] = '>=';
		$TA[$start][$month] = '2';
		$TA[$start][$day] = '1';
		$TA[$start][$year] = '1';
		$TA[$start][$hour] = '0';
		$TA[$start][$minute] = '1';
		$TestOps = array ( '<', '>', '<=', '>=', '!=', '=' );
		$EPfx = " AND (";
		$ESfx = " ) ";
		foreach($TestOps as $Top ){
			$TA[$start][$op] = $Top;
			$EOM = $EPfx;
			if ( $Top == '=' ){
				$EOM .= '( YEAR(timestamp) = 1  AND  MONTH(timestamp) = 2  '.
				'AND  DAYOFMONTH(timestamp) = 1  AND  HOUR(timestamp) = 0  '.
				'AND  MINUTE(timestamp) = 1 )';
			}elseif ( $Top == '<=' ){
				$EOM .= " timestamp $Top'2001-02-01 00:01:59'";
			}else{
				$EOM .= " timestamp $Top'2001-02-01 00:01:00'";
			}
			$EOM .= $ESfx;
			$this->assertEquals( 1, DateTimeRows2sql($TA,1,$SQL), "$Top $URV" );
			$this->assertEquals( $EOM, $SQL, $URV );
			$SQL = '';
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
