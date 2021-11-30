<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in base_qry_common.php
/**
  * @covers ::PrintCriteria
  * @uses ::NLI
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class base_qry_commonTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $db;
	protected static $EOP;
	protected static $EOS;

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
		"colspan='2'>Search Criteria</td>".
		"\n\t\t\t</tr><tr>".
		"\n\t\t\t\t<td class='metatitle' style='width: 35%;'>".
		"Meta Criteria</td>".
		"\n\t\t\t\t<td>";
		self::$EOS =
		"\n\t\t\t\t</td>\n\t\t\t</tr>\n\t\t</table>";
	}
	public static function tearDownAfterClass() {
		self::$EOS = null;
		self::$EOP = null;
		self::$db = null;
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testPrintCriteriaLastTCPReturnsValid() {
		GLOBAL $cs, $UIL;
		$UIL = self::$UIL;
		$db = self::$db;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_tcp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 TCP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastUDPReturnsValid() {
		GLOBAL $cs, $UIL;
		$UIL = self::$UIL;
		$db = self::$db;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_udp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 UDP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastICMPReturnsValid() {
		GLOBAL $cs, $UIL;
		$UIL = self::$UIL;
		$db = self::$db;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_icmp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 ICMP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastAnyReturnsValid() {
		GLOBAL $cs, $UIL;
		$UIL = self::$UIL;
		$db = self::$db;
		$cs = new CriteriaState('Unit_Test'); // Create Criteria State Object.
		$caller = 'last_any';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
