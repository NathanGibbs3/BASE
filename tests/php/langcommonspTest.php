<?php
use PHPUnit\Framework\TestCase;

// Test the UILang class functons that are TD format agnostic.
// Tests that need process isolation.

// Test UILang with various data.
//  Various Settings.
//  Malformed files or invalid call params.
// Verify UILang
//  Throws appropriate errors.
//  Inits into sensible state.

/**
 * @preserveGlobalState disabled
 * @runTestsInSeparateProcesses
 */
class commonlangSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $BASE_installID, $debug_mode;
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$ll);
			LogTC($tf,'TD file:',$file);
		}
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	// Invalid TD Tests
	public function testTDFNotExistLangDefaultsToEnglish() {
		$lang = 'invalid';
		$tmp = "UI$lang";
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertInstanceOf('UILang',self::$UIL = @new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = self::$UIL;
		$this->assertEquals(
			'english',
			$$tmp->Lang,
			'Class did not deafult Lang to english.'
		);
	}
	public function testTDFNotExistThrowsError() {
		$lang = 'invalid';
		$tmp = "UI$lang";
		$EEM = "No TD found for Language: invalid. Default to english.\n";
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '3.0', '<')) {
			$this->markTestSkipped('Requires Phpunit 3+ to run.');
		}elseif (version_compare($PHPUV, '5.0', '<')) { // PHPUnit 3x - 4x
			$this->setExpectedException(
				"PHPUnit_Framework_Error_Notice", $EEM
			);
		}elseif (version_compare($PHPUV, '6.0', '<')) { // PHPUnit 5x
			$this->expectException("PHPUnit_Framework_Error_Notice");
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 6+
			$this->expectException("PHPUnit\Framework\Error\Notice");
			$this->expectExceptionMessage($EEM);
		}
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
	}
	public function testTDFInvalidSetUILocaleDefaults() {
		GLOBAL $BASE_path;
		$lang = 'broken';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Invalid TDF:',$file);
		if (is_array($$tmp->Locale) ) {
			$this->markTestSkipped("Valid TDF: $file.");
		}else{
			$this->assertNotNull($$tmp->Locale, 'Locale Not Set');
			$this->assertEquals(
				'Invalid', $$tmp->Locale,
				'Class did not deafult Locale to Invalid.'
			);
		}
	}
	public function testTDFnewInvalidSetUIILocaleDefaults() {
		GLOBAL $BASE_path;
		$lang = 'broken-ntd';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertInstanceOf('UILang',self::$UIL = @new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Invalid TDF:',$file);
		$TItem = 'ILocale';
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			'en_US',
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	public function testTDFnewInvalidSetUICharsetDefaults() {
		GLOBAL $BASE_path;
		$lang = 'broken-ntd';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertInstanceOf('UILang',self::$UIL = @new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Invalid TDF:',$file);
		$TItem = 'ILocale';
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			'en_US',
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	// Spacing Tests
	public function testTDFInvalidSpacingDefaults() {
		GLOBAL $BASE_path;
		$lang = 'broken';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Invalid TDF:',$file);
		$this->assertEquals(
			1, $$tmp->Spacing,
			'Class did not deafult spacing for $lang to 1.'
		);
	}
	public function testTDLegacyInitSpacingOff() {
		GLOBAL $BASE_path;
		$lang = 'legacy-chinese';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			0, $$tmp->Spacing,
			'Class did not deafult spacing for $lang to 0.'
		);
	}
	public function testTDLegacyInitSpacingOn() {
		GLOBAL $BASE_path;
		$lang = 'legacy-english';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			1, $$tmp->Spacing,
			'Class did not deafult spacing for $lang to 1.'
		);
	}
	public function testTDNewInitSpacingOff() {
		GLOBAL $BASE_path;
		$lang = 'chinese';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			0, $$tmp->Spacing,
			'Class did not deafult spacing for $lang to 0.'
		);
	}
	// Capitalization
	public function testTDFInvalidCapsDefaultsTo1() {
		GLOBAL $BASE_path;
		$lang = 'broken';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Invalid TDF:',$file);
		$this->assertEquals(
			1, $$tmp->Caps,
			'Class did not deafult capitalization for $lang to 1.'
		);
	}
	public function testTDLegacyInitCapsOff() {
		GLOBAL $BASE_path;
		$lang = 'legacy-chinese';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			0, $$tmp->Caps,
			'Class did not deafult capitalization for $lang to 0.'
		);
	}
	public function testTDLegacyInitCapsOn() {
		GLOBAL $BASE_path;
		$lang = 'legacy-english';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			1, $$tmp->Caps,
			'Class did not deafult capitalization for $lang to 1.'
		);
	}
	public function testTDNewInitCapsOff() {
		GLOBAL $BASE_path;
		$lang = 'chinese';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'Spacing Test TD file:',$file);
		$this->assertEquals(
			0, $$tmp->Caps,
			'Class did not deafult capitalization for $lang to 0.'
		);
	}
	// Authentication Data SubStructure.
	public function testAsDisabledADADefaultstoNULL() {
		GLOBAL $BASE_path, $Use_Auth_System;
		$Use_Auth_System = 0;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$this->assertInstanceOf('UILang',self::$UIL = new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 0) {
			$this->assertNull($$tmp->ADA,
				"Auth System Disabled.\n"
				."Auth Data Structure did not default to NULL."
			);
		}else{
			$this->markTestSkipped(
				'Test requires Disabled Auth System to run.'
			);
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
