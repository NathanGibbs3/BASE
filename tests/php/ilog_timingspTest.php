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
			self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
				"Class for $ll not created."
			);
		}else{
			self::$files = $file;
		}
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	// Test PrintTiming Function
	public function testClassEventTimingPrintTiming1(){
		GLOBAL $BASE_installID;
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(1),
			'Class Not Initialized.'
		);
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = "\n\t\t\t\t\t\t".'<!-- Timing Information -->';
		$expected .= "\n\t\t\t\t\t\t".'<div class=\'systemdebug\'>';
		$expected .= "\n\t\t\t\t\t\t\t".'[Loaded in 0 seconds]<br/>';
		$expected .= "\n\t\t\t\t\t\t".'</div>';
		$this->expectOutputString($expected);
		$tc->PrintTiming();
	}
	public function testClassEventTimingPrintTiming2(){
		GLOBAL $BASE_installID;
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(2),
			'Class Not Initialized.'
		);
		$tc->Mark('What');
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = "\n\t\t\t\t\t\t".'<!-- Timing Information -->';
		$expected .= "\n\t\t\t\t\t\t".'<div class=\'systemdebug\'>';
		$expected .= "\n\t\t\t\t\t\t\t".'[Loaded in 0 seconds]<br/>';
		$expected .= "\n\t\t\t\t\t\t\t".'Event Log:<br/>';
		$expected .= "\n\t\t\t\t\t\t\t".'1 Page Load [0 seconds]<br/>';
		$expected .= "\n\t\t\t\t\t\t\t".'2 What [0 seconds]<br/>';
		$expected .= "\n\t\t\t\t\t\t".'</div>';
		$this->expectOutputString($expected);
		$tc->PrintTiming();
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
