<?php
use PHPUnit\Framework\TestCase;

// Test the UILang class functons with an empty TD (Translation Data) file.

// UILang is designed to Init TD Items to a default state when both
// legacy TD ( _CONSTANSTS ) & new TD ( $variables ) are unavailable..

// We want to ensure that UILang can gracefully work under these conditions.

// Tests UILang Data Structures.
// Verifies that all TD Items Init to defaults.

class newformatbrokenlangTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $EEM;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $debug_mode;
		$tf = __FUNCTION__;
		$ll = 'broken-ntd';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$lang);
			LogTC($tf,'TD file',$file);
		}
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
			"Class for $ll not created."
		);
		unlink ("$BASE_path/languages/$lf");
		self::$EEM = "Missing TD Item: ";
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
		self::$EEM = null;
	}

	// Tests go here.
	public function testSetUIILocale() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$TItem = 'ILocale';
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			'en_US.utf-8',
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	public function testSetUICharsetDefaultstoutf8() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$TItem = 'Charset';
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			'utf-8',
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
