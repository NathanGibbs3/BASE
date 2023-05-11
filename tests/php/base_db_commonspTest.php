<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: /base_DB_common.php
/**
  * Code Coverage Directives.
  * @covers ::verify_php_build
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class base_db_commonspTest extends TestCase {
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
	public function testverify_php_build_mysql() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertEquals(
			$expected, verify_php_build('mysql'), $URV
		);
	}
	public function testverify_php_build_mysql_mysqlt() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertEquals(
			$expected, verify_php_build('mysqlt'), $URV
		);
	}
	public function testverify_php_build_mysql_maxsql() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertEquals(
			$expected, verify_php_build('maxsql'), $URV
		);
	}
	public function testverify_php_build_PostGreSQL() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertEquals(
			$expected, verify_php_build('postgres'), $URV
		);
	}
	public function testverify_php_build_MSSQL() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertNotEquals(
			$expected, verify_php_build('mssql'), $URV
		);
	}
	public function testverify_php_build_Oracle() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertNotEquals(
			$expected, verify_php_build('oci8'), $URV
		);
	}
	public function testverify_php_build_Invalid() {
		GLOBAL $BASE_installID;
		$URV = 'Unexpected Return.';
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$expected = '';
		$this->assertNotEquals(
			$expected, verify_php_build('Invalid'), $URV
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
