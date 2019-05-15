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

/**
  * @covers UILang::BlankProps
  */
class commonlangTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $debug_mode;
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$lang);
			LogTC($tf,'TD file',$file);
		}
		$tmp = "UI$ll";
		// Will throw error during TD transition.
		// Use error suppression @ symbol.
		self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
			"Class for $ll not created."
		);
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}

	// Tests go here.
	public function testBlankPropsValue() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$Value = 'Value';
		$this->assertEquals(
			'Value',
			$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsNonKeyedArray() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
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
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
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
	public function testBlankPropsValueNULLThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$EEM = "Missing TD Item: NULL.\n";
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
		$$tmp->BlankProps('NULL',NULL);
	}
	public function testBlankPropsValueNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$EEM = "Missing TD Item: NULL.\n";
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			$EEM,
			@$$tmp->BlankProps('NULL',NULL),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsNonKeyedArrayNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$Value = array (1,2,3,NULL);
		$EEM = "Missing TD Item: Value[3].\n";
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			array(1,2,3,$EEM),
			@$$tmp->BlankProps('Value',$Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	public function testBlankPropsKeyedArrayNULL() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$Value = array (
			'key1' => NULL,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$EEM = "Missing TD Item: Value[key1].\n";
		// Test conditions will throw error.
		// Use error suppression @ symbol.
		$this->assertEquals(
			array (
				'key1' => $EEM,
				'key2' => 1,
				'key3' => 2,
				'key4' => 3,
				'key5' => 4
			),
			@$$tmp->BlankProps('Value', $Value),
			'BlankProps Unexpected Return Value.'
		);
	}
	/**
	  * @covers UILang::SetUIADItem
	  */
	public function testADASetItemInvalidThrowsError() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$key = 'INVALID';
			$kD = 'Invalid Item';
			$EEM = "Invalid AD Set Request for: $key.";
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
			$$tmp->SetUIADItem($key,$kD);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	/**
	  * @covers UILang::SetUICWItem
	  */
	public function testCWASetItemInvalidThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$key = 'INVALID';
		$kD = 'Invalid Item';
		$EEM = "Invalid CW Set Request for: $key.";
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
		$$tmp->SetUICWItem($key,$kD);
	}
	/**
	  * @covers UILang::SetUICPItem
	  */
	public function testCPASetItemInvalidThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$key = 'INVALID';
		$kD = 'Invalid Item';
		$EEM = "Invalid CP Set Request for: $key.";
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
		$$tmp->SetUICPItem($key,$kD);
	}
	/**
	  * @covers UILang::SetUIUAItem
	  */
	public function testUAASetItemInvalidThrowsError() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$key = 'INVALID';
		$kD = 'Invalid Item';
		$EEM = "Invalid UA Set Request for: $key.";
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
		$$tmp->SetUIUAItem($key,$kD);
	}
	// Authentication Data SubStructure.
	public function testAsEnabledADADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
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
	public function testCWADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$this->assertTrue(is_array($$tmp->CWA),
			"Common Word Structure did not default to Array."
		);
	}
	public function testCPADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$this->assertTrue(is_array($$tmp->CPA),
			"Common Phrase Structure did not default to Array."
		);
	}
	public function testUAADefaultstoArray() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$this->assertTrue(is_array($$tmp->UAA),
			"Universal Action Structure did not default to Array."
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
