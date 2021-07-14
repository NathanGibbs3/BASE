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
	protected static $EOP;
	protected static $EOS;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $debug_mode;
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
		self::$EOP =
		"\n\t\t\t\t<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" ".
		"bgcolor=\"#FFFFFF\">".
		"\n\t\t\t\t\t<tr>".
		"\n\t\t\t\t\t\t<td class=\"metatitle\">Meta Criteria</td>".
		"\n\t\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\t\t";
		self::$EOS =
		"\n\t\t\t\t\t\t</td>\n\t\t\t\t\t</tr>\n\t\t\t\t</table>";
	}
	public static function tearDownAfterClass() {
		self::$EOS = null;
		self::$EOP = null;
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testPrintCriteriaLastTCPReturnsValid() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
		$caller = 'last_tcp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 TCP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastUDPReturnsValid() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
		$caller = 'last_udp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 UDP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastICMPReturnsValid() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
		$caller = 'last_icmp';
		$EOM = self::$EOP;
		$EOM .= '&nbsp;&nbsp;Last 15 ICMP Alert&nbsp;&nbsp;'.self::$EOS;
		$this->expectOutputString($EOM);
		PrintCriteria($caller);
	}
	public function testPrintCriteriaLastAnyReturnsValid() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
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
