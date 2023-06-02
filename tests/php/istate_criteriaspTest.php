<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_criteria.inc.php

/**
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  * @covers ::PrintBackButton
  * @covers CriteriaState
  * @uses BaseCriteria
  * @uses IPAddressCriteria
  * @uses MultipleElementCriteria
  * @uses SignatureCriteria
  */

class state_criteriaSPTest extends TestCase {
	// Pre Test Setup.
	protected static $UIL;
	protected static $URV;
	protected static $files;
	protected static $langs;
	protected static $omh;
	protected static $osv;
	protected static $tc;

	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $debug_mode, $maintain_history;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		$tf = __FUNCTION__;
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
		self::$omh = $maintain_history;
		self::$osv = $_SERVER;
		$maintain_history = 1;
		$_SESSION = NULL; // Initialize the history.
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
		PushHistory(); // Load History
		$_SERVER['QUERY_STRING'] = '&amp;front=1';
		PushHistory();
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		$maintain_history = self::$omh;
		$_SERVER = self::$osv;
		self::$UIL = null;
		self::$URV = null;
		self::$files = null;
		self::$langs = null;
		self::$omh = null;
		self::$osv = null;
		self::$tc = null;
	}

	// Tests go here.
	public function testClassCriteriaStateConstruct(){
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$URV = self::$URV.'Construct().';
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
		self::$tc = $tc;
		$TSC = array( // Test SubCriteria
			'sig' => 'Signature',
			'sig_class' => 'SignatureClassification',
			'sig_priority' => 'SignaturePriority',
			'ag' => 'AlertGroup',
			'sensor' => 'Sensor',
			'time' => 'Time',
			'ip_addr' => 'IPAddress',
			'layer4' => 'Layer4',
			'ip_field' => 'IPField',
			'tcp_port' => 'TCPPort',
			'tcp_flags' => 'TCPFlags',
			'tcp_field' => 'TCPField',
			'udp_port' => 'UDPPort',
			'udp_field' => 'UDPField',
			'icmp_field' => 'ICMPField',
			'rawip_field' => 'TCPField',
			'data' => 'Data',
		);
		foreach( $TSC as $key => $val ){
			$tmp = $val . 'Criteria';
			$this->assertInstanceOf($tmp, $tc->criteria[$key], $CNI);
		}
	}
	public function testGetBackLink() {
		$URV = self::$URV.'GetBackLink().';
		$CNI = 'Class Not Initialized.';
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
		$this->assertEquals(
			"<a class='menuitem' href=\"".$_SERVER["SCRIPT_NAME"].
			'?back=1&">Back</a>',
			$tc->GetBackLink(), $URV
		);
	}
	public function testPrintBackButtonOK() {
		$URV = self::$URV.'PrintBackButton().';
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$this->assertEquals(
			"<a class='menuitem' href=\"".$_SERVER["SCRIPT_NAME"].
			'?back=1&">Back</a>',
			PrintBackButton(), $URV
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
