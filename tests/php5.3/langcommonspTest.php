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
			self::LogTC($tf,'language',$lang);
			self::LogTC($tf,'TD file',$file);
		}
	}

	// Tests go here.
	public function testTDFNotExistClassDefaultsToEnglish() {
		$lang = 'invalid';
		$tmp = "UI$lang";
		$$tmp = new tdflang();
		$this->assertEquals(
			'english',
			@$$tmp->lang($lang),
			'Class did not deafult Lang to english.'
		);
	}
	public function testTDFNotExistClassThrowsError() {
		$lang = 'invalid';
		$tmp = "UI$lang";
		$EEM = "No TD found for Language: invalid. Default to english.\n";
		$PHPUV = $this->GetPHPUV();
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

	public function testSetUILocaleInvalidTDFDefaultsToNULL() {
		GLOBAL $BASE_path;
		$lang = 'broken';
		$lf = "$lang.lang.php";
		$tmp = "UI$lang";
		$tf = __FUNCTION__;
		$this->LogTC($tf,'language',$lang);
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf('UILang',$this->UIL = new UILang($lang),
			"Class for $lang not created."
		);
		unlink ("$BASE_path/languages/$lf");
		$$tmp = $this->UIL;
		// Will not run until TD is transitioned.
		$file = $$tmp->TDF;
		$this->LogTC($tf,'Invalid TD file',$file);
		// Test Locale
		if (is_array($$tmp->Locale) ) {
			$this->markTestSkipped("Valid TDF: $file.");
		}else{
			$this->assertNull(
				$$tmp->Locale, 'Class did not deafult Locale to NULL.'
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
		$this->LogTC($tf,'language',$lang);
		$this->assertInstanceOf('UILang',$this->UIL = new UILang($lang),
			"Class for $lang not created."
		);
		$$tmp = $this->UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
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

	// Test Support Functions.
	private function GetPHPUV () { // Get PHPUnit Version
		if ( method_exists('PHPUnit_Runner_Version','id')) {
			$Ret = PHPUnit_Runner_Version::id();
		}elseif (method_exists('PHPUnit\Runner\Version','id')) {
			$Ret = PHPUnit\Runner\Version::id();
		}else{
			$Ret = 0.0;
		}
		return $Ret;
	}

	private static function LogTC ($cf,$Item,$Value) { // Output to Test Console
		GLOBAL $debug_mode;
		if ($debug_mode > 0) {
			print "\n$cf Testing $Item: $Value";
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
class tdflang {
	public function lang($lang) {
		$lang = 'invalid';
		$tmp = "UI$lang";
		$$tmp = new UILang($lang);
		return $$tmp->Lang;
	}
}
?>
