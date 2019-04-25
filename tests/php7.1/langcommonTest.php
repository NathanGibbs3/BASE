<?php
use PHPUnit\Framework\TestCase;

// Test the UILang class functons that are TD format agnostic.

// Test internal UILang functions.
// Test UILang with various data.
//  Various settings.
//  Invalid call params.
// Verify UILang
//  Throws appropriate errors.
//  Inits into sensible state.

class commonlangTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass(): void {
		GLOBAL $BASE_path, $debug_mode;
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			self::LogTC($tf,'language',$lang);
			self::LogTC($tf,'TD file',$file);
		}
		$tmp = "UI$ll";
		self::assertInstanceOf('UILang',self::$UIL = new UILang($ll),
			"Class for $ll not created."
		);
	}
	public static function tearDownAfterClass(): void {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testBlankPropsValue() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$Value = 'Value';
		$this->assertEquals(
			'Value',
			$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsValueNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$EEM = "Missing TD Item: NULL.\n";
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
		$this->assertEquals(
			$EEM,
			$$tmp->BlankProps('NULL',NULL),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsNonKeyedArray() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$Value = array (1,2,3,4);
		$this->assertEquals(
			array (1,2,3,4),
			$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsKeyedArray() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$Value = array (
			'key1' => 0,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			$Value = array (
				'key1' => 0,
				'key2' => 1,
				'key3' => 2,
				'key4' => 3,
				'key5' => 4
			),
			$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsNonKeyedArrayNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$Value = array (1,2,3,NULL);
		$EEM = "Missing TD Item: Value[3].\n";
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
		$this->assertEquals(
			array(1,2,3,$EEM),
			$$tmp->BlankProps('Value',$Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsKeyedArrayNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$Value = array (
			'key1' => NULL,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$EEM = "Missing TD Item: Value[key1].\n";
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
		$this->assertEquals(
			array (
				'key1' => $EEM,
				'key2' => 1,
				'key3' => 2,
				'key4' => 3,
				'key5' => 4
			),
			$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}

	public function testADASetItemInvalidThrowsError() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$key = 'INVALID';
			$kD = 'Invalid Item';
			$EEM = "Invalid AD Set Request for: $key.";
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
			$$tmp->SetUIADItem($key,$kD);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	public function testCPASetItemInvalidThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$key = 'INVALID';
		$kD = 'Invalid Item';
		$EEM = "Invalid CP Set Request for: $key.";
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
		$$tmp->SetUICPItem($key,$kD);
	}
	public function testUAASetItemInvalidThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$key = 'INVALID';
		$kD = 'Invalid Item';
		$EEM = "Invalid UA Set Request for: $key.";
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
		$$tmp->SetUIUAItem($key,$kD);
	}
	// Authentication Data SubStructure.
	public function testAsEnabledADADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$this->assertTrue(is_array($$tmp->ADA),
				"Auth System Enabled.\n"
				."Auth Data Structure did not default to Array."
			);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	public function testCPADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$this->assertTrue(is_array($$tmp->CPA),
			"Common Phrase Structure did not default to Array."
		);
	}
	public function testUAADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		$this->LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$this->LogTC($tf,'TD file',$file);
		$this->assertTrue(is_array($$tmp->UAA),
			"Universal Action Structure did not default to Array."
		);
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
?>

