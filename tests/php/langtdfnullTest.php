<?php
use PHPUnit\Framework\TestCase;

// Test the UILang class functons with an empty TD (Translation Data) file.

// UILang is designed to Init TD Items to a default state when both
// legacy TD ( _CONSTANSTS ) & new TD ( $variables ) are unavailable..

// We want to ensure that UILang can gracefully work under these conditions.

// Tests UILang Data Structures.
// Verifies that all TD Items Init to defaults.

class nulllangTest extends TestCase {
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
		$ll = 'null';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$lang);
			LogTC($tf,'TD file',$file);
		}
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		// Will throw error during TD transition.
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
	/**
	  * @covers UILang::SetUILocale
	  */
	public function testSetUILocale() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		// Test Locale
		if (is_array($$tmp->Locale) ) {
			$this->markTestSkipped("Valid TDF: $file.");
		}else{
			$this->assertNull(
				$$tmp->Locale, 'Locale did not default to NULL'
			);
			if ( !$$tmp->SetUILocale() ){
				$this->markTestSkipped(
					'Locale not implemented or locale(s) do not exist.'
				);
			}else{
				$EEM = 'Locale Not Set.';
				$this->assertNotNull( $$tmp->Locale, $EEM );
				$this->assertFalse( is_array($$tmp->Locale), $EEM );
			}
		}
	}
	public function testSetUITimefmt() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$TItem = 'Timefmt';
		$EEM = self::$EEM."$TItem.\n";
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			$EEM,
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	public function testSetUICharset() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$TItem = 'Charset';
		$EEM = self::$EEM."$TItem.\n";
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			$EEM,
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	public function testSetUITitle() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		$TItem = 'Title';
		$EEM = self::$EEM."$TItem.\n";
		$this->assertTrue(
			isset($$tmp->$TItem),
			"Unset: $TItem ."
		);
		$this->assertEquals(
			$EEM,
			$$tmp->$TItem,
			"Uninitialized: $TItem ."
		);
	}
	// Authentication Data SubStructure.
	public function testADASetItemLoginDesc() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$key = 'DescUN';
			$KA = 'ADA';
			$TItem = $KA."[$key]";
			$EEM = self::$EEM."$TItem.\n";
			$this->assertArrayHasKey($key, $$tmp->$KA,
				"Unset: $TItem ."
			);
			$this->assertEquals(
				$EEM,
				$$tmp->{$KA}[$key],
				"Uninitialized: $TItem ."
			);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	public function testADASetItemPWDesc() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$key = 'DescPW';
			$KA = 'ADA';
			$TItem = $KA."[$key]";
			$EEM = self::$EEM."$TItem.\n";
			$this->assertArrayHasKey($key, $$tmp->$KA,
				"Unset: $TItem ."
			);
			$this->assertEquals(
				$EEM,
				$$tmp->{$KA}[$key],
				"Uninitialized: $TItem ."
			);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	public function testADASetItemRIDesc() {
		GLOBAL $Use_Auth_System;
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		LogTC($tf,'TD file',$file);
		if ($Use_Auth_System == 1) {
			$key = 'DescRI';
			$KA = 'ADA';
			$TItem = $KA."[$key]";
			$EEM = self::$EEM."$TItem.\n";
			$this->assertArrayHasKey($key, $$tmp->$KA,
				"Unset: $TItem ."
			);
			$this->assertEquals(
				$EEM,
				$$tmp->{$KA}[$key],
				"Uninitialized: $TItem ."
			);
		}else{
			$this->markTestSkipped(
				'Test requires Enabled Auth System to run.'
			);
		}
	}
	// Test Commonm Phrase Items.
	public function testCPASetItemSrcDesc() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'SrcDesc';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemSrcName() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'SrcName';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemDstDesc() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'DstDesc';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemDstName() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'DstName';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemSrcDst() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'SrcDst';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemId() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Id';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemName() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Name';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemInt() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Int';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemFilter() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Filter';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testCPASetItemDesc() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Desc';
		$KA = 'CPA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->CPAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	// Test Universal Action Items.
	public function testUAASetItemEdit() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Edit';
		$KA = 'UAA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->UAAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}
	public function testUAASetItemDelete() {
		$lang = self::$langs;
		$tf = __FUNCTION__;
		$tmp = "UI$lang";
		LogTC($tf,'language',$lang);
		$$tmp = self::$UIL;
		$file = $$tmp->TDF;
		$key = 'Delete';
		$KA = 'UAA';
		$TItem = $KA."[$key]";
		$EEM = self::$EEM."$TItem.\n";
		LogTC($tf,'TD file',$file);
		$this->UAAHas($$tmp, $key, $TItem);
		$this->assertEquals(
			$EEM,
			$$tmp->{$KA}[$key],
			"Uninitialized: $TItem ."
		);
	}

	// Test Support Functions.
	private function CPAHas ($UIL, $Key, $KeyDesc) {
		$this->assertArrayHasKey($Key, $UIL->CPA,
			"Unset CP Item $KeyDesc: Key: $Key\n"
		);
	}
	private function UAAHas ($UIL, $Key, $KeyDesc) {
		$this->assertArrayHasKey($Key, $UIL->UAA,
			"Unset UA Item $KeyDesc: Key: $Key\n"
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
