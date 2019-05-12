<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_citems.inc.php
// Tests that need process isolation.

/**
  * @preserveGlobalState disabled
  * A necessary evil for tests touching UILang during TD Transition.
  * @runTestsInSeparateProcesses
  * Apparently the covers annotations are ignored whe the above necessary
  * evil is in effect. Will Add covers annotations once we get rid of
  * necessary evil.
  */
class state_citemsSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db;
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$lang);
			LogTC($tf,'TD file',$file);
		}
		// Setup UI Language Object
		// Will throw error during TD transition.
		// Use error suppression @ symbol.
		self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
			"Class for $ll not created."
		);
		// Setup DB System.
		// Default Debian/Ubuntu location.
		$DBlib_path = '/usr/share/php/adodb';
		$DB = getenv('DB');
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			require('../database.php');
		}else{
			$PHPV = GetPHPV();
			if(version_compare($PHPV, '5.5', '>=')){
				// System Versions of ADODB on travis:
				// When using mysql & running on PHP 5.5+
				// Throws the following error:
				// mysql_pconnect(): The mysql extension is deprecated and
				// will be removed in the future: use mysqli or PDO instead.
				//
				// When running on PHP 7+, has Issue #7
				// https://github.com/NathanGibbs3/BASE/issues/7
				// Their problem, not ours, so we will try to use an
				// installed from source version instead.
				$ADO = getenv('ADODBPATH');
				if (!$ADO) {
					self::markTestIncomplete('Unable to setup ADODB');
				}else{
					$DBlib_path = "build/adodb/$ADO";
				}
			}else{
				// Use System installed Version.
			}
			if (!$DB){
				self::markTestIncomplete('Unable to get DB Engine.');
			}elseif ($DB == 'mysql' ){
				require('./tests/phpcommon/DB.mysql.php');
			}elseif ($DB == 'postgres' ){
				require('./tests/phpcommon/DB.pgsql.php');
			}else{
				self::markTestSkipped("CI Support unavialable for DB: $DB.");
			}
			if (!isset($DBtype)){
				self::markTestIncomplete("Unable to Set DB: $DB.");
			}
		}
		$alert_dbname='snort';
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}


	// Tests go here.
	public function testClassSignatureCriteriaFuncDescriptionReturnsNULL() {
		GLOBAL $DBlib_path, $DBtype, $UIL, $alert_dbname, $alert_host,
		$alert_user, $alert_password, $alert_port, $db_connect_method, $db;
		$UIL = self::$UIL;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$cst = $cs->criteria['sig']; // Porperty under test.
		$db = NewBASEDBConnection($DBlib_path, $DBtype); // Setup DB Connection
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password
		);
		$EEM = '';
		$this->assertEquals( // CSO Not Setup.
			$EEM,
			$cst->Description(''),
			'CSO Not Init Unexpected Return Value.'
		);
		// CSO Space
		$cst->criteria[0] = ' ';
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Space Unexpected Return Value.'
		);
		// CSO Blank Sig
		$cst->criteria[0] = '=';
		$cst->criteria[1] = '';
		$cst->criteria[2] = '=';
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Blank Sig Unexpected Return Value.'
		);
	}
	public function testClassSignatureCriteriaFuncDescription() {
		GLOBAL $DBlib_path, $DBtype, $UIL, $alert_dbname, $alert_host,
		$alert_user, $alert_password, $alert_port, $db_connect_method, $db;
		$UIL = self::$UIL;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$cst = $cs->criteria['sig']; // Porperty under test.
		$db = NewBASEDBConnection($DBlib_path, $DBtype); // Setup DB Connection
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password
		);
		// Common
		$Ts = 'Test Signature';
		$cst->criteria[1] = $Ts;
		$Pfx = 'Signature ';
		$Sfx = '&nbsp;&nbsp;<A HREF="Unit_Test?clear_criteria=sig&amp;clear_criteria_element=">...Clear...</A><br>';
		// CSO INvalid
		$cst->criteria[0] = 'Invalid';
		$cst->criteria[2] = '=';
		$EEM = $Pfx.''.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Invalid Unexpected Return Value.'
		);
		// CSO INvalid2
		$cst->criteria[0] = '=';
		$cst->criteria[2] = 'Invalid';
		$EEM = $Pfx.''.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Invalid2 Unexpected Return Value.'
		);
		// CSO Equals
		$cst->criteria[0] = '=';
		$cst->criteria[2] = '=';
		$EEM = $Pfx.'='.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Equals Unexpected Return Value.'
		);
		// CSO Not Equals
		$cst->criteria[0] = '=';
		$cst->criteria[2] = '!=';
		$EEM = $Pfx.'!='.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Not Equals Unexpected Return Value.'
		);
		// CSO Containing
		$cst->criteria[0] = 'LIKE';
		$cst->criteria[2] = '=';
		$EEM = $Pfx.' contains '.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Contains Unexpected Return Value.'
		);
		// CSO Not containing
		$cst->criteria[0] = 'LIKE';
		$cst->criteria[2] = '!=';
		$EEM = $Pfx.' does not contain '.' "'.$Ts.'"'.$Sfx;
		$this->assertEquals(
			$EEM,
			$cst->Description(''),
			'CSO Not Contains Unexpected Return Value.'
		);
	}


	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
