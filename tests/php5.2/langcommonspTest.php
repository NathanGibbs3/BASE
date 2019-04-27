<?php

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
class commonlangSPTest extends PHPUnit_Framework_TestCase {
	// Pre Test Setup.
	var $files;
	var $langs;
	var $UIL;

	protected function setUp() {
		GLOBAL $BASE_path, $BASE_installID, $debug_mode;
		$tf = __FUNCTION__;
		$ll = 'english';
		$this->langs = $ll;
		$lf = "$ll.lang.php";
		$this->files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$lang);
			LogTC($tf,'TD file',$file);
		}
	}

	// Tests go here.
	public function testTDFNotExistLangDefaultsToEnglish() {
		$lang = 'invalid';
		$tmp = "UI$lang";
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertInstanceOf('UILang',$this->UIL = @new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = $this->UIL;
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
		if (version_compare($PHPUV, '4.0', '<')) {
			$this->markTestSkipped('Requires Phpunit 4+ to run.');
		}elseif (version_compare($PHPUV, '5.0', '<')) { // PHPUnit 4x
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
		$this->assertInstanceOf('UILang',$this->UIL = new UILang($lang),
			"Class for $lang not created."
		);
	}
	public function testTDFInvalidSetUILocaleDefaultsToInvalid() {
		GLOBAL $BASE_path;
		$lang = 'broken';
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$lf = "$lang.lang.php";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',$this->UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = $this->UIL;
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
	// Authentication Data SubStructure.
	public function testAsDisabledADADefaultstoNULL() {
		GLOBAL $BASE_path, $Use_Auth_System;
		$Use_Auth_System = 0;
		$lang = $this->langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$this->assertInstanceOf('UILang',$this->UIL = new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = $this->UIL;
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
