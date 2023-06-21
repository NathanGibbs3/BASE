<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_log_error.inc.php

/**
  * Code Coverage Directives.
  * @covers ::BuildError
  * @covers ::returnBuildError
  * @uses ::LoadedString
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class log_errorspTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $UOV;
	protected static $URV;

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
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$UOV = null;
		self::$URV = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testBuildErrorDefault(){
		GLOBAL $BASE_installID;
		$UOV = self::$UOV.'BuildError().';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'>PHP ERROR:</font><br/>";
		$EOM .= "<font color='black'>message</font><br/><br/>";
		$this->expectOutputString(
			$EOM, BuildError('message'), $UOV
		);
	}
	public function testreturnBuildErrorDefault(){
		GLOBAL $BASE_installID;
		$URV = self::$URV.'returnBuildError().';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$this->assertEquals('', returnBuildError(), $URV);
	}
	public function testreturnBuildErrorMessage(){
		GLOBAL $BASE_installID;
		$URV = self::$URV.'returnBuildError().';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "<font color='#ff0000'>PHP ERROR:</font><br/>\n"
		. "<b>PHP build incomplete</b>: Monkey Food support required.<br/>\n"
		. 'Recompile PHP with Monkey Food support '
		. '(<code>--with-bananas</code>) .<br/>';
		$this->assertEquals(
			$EOM, returnBuildError('Monkey Food', '--with-bananas'), $URV
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
