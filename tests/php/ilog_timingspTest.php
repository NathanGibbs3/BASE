<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_timing.inc.php

/**
  * Code Coverage Directives.
  * @covers EventTiming
  * @uses ::NLI
  * @uses ::NLIO
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class log_timingSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $UOV;
	protected static $URV;
	protected static $tc;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $debug_mode;
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
			self::assertInstanceOf(
				'UILang', self::$UIL = @new UILang($ll),
				"Class for $ll not created."
			);
		}else{
			self::$files = $file;
		}
		self::assertInstanceOf(
			'EventTiming', self::$tc = new EventTiming(1),
			'Class EventTiming Not Initialized.'
		);
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$UOV = null;
		self::$URV = null;
		self::$langs = null;
		self::$files = null;
		self::$tc = null;
	}

	// Tests go here.
	// Test PrintTiming Function
	public function testClassEventTimingPrintTiming1(){
		GLOBAL $BASE_installID;
		$UOV = self::$UOV.'PrintTimng().';
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
