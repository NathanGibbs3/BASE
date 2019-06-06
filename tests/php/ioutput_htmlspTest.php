<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php
// Tests that need process isolation.

/**
  * @preserveGlobalState disabled
  * A necessary evil for tests touching UILang during TD Transition.
  * @runTestsInSeparateProcesses
  * Apparently the covers annotations are ignored whe the above necessary
  * evil is in effect. Will Add covers annotations once we get rid of
  * necessary evil.
  */
class output_htmlSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $PSM;

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
		self::assertInstanceOf('UILang',self::$UIL = new UILang($ll),
			"Class for $ll not created."
		);
		if ( version_compare(GetPHPV(), '5.4', '<') ){
			self::$PSM = ini_get("safe_mode");
		}else{
			self::$PSM = false;
		}
	}
	public static function tearDownAfterClass() {
		self::$PSM = null;
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
	}
	protected function setUp() {
		$PSM = self::$PSM;
		if ( version_compare(GetPHPV(), '5.4', '<') ){
			print "IO PHP SafeMode value at $tf is: '$PSM'\n";
		}
		if ( $PSM != false ){
			print "PHP SafeMode value at $tf is: '$PSM'\n";
			// Try to turn off safe mode.
//			if ( ini_set('safe_mode','0') === false){
				self::markTestIncomplete('PHP SafeMode: On');
//			}

		$EEM = "PHP Deprecated:  Directive 'safe_mode' is ".
		"deprecated in PHP 5.3 and greater in Unknown on line 0";
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '4.0', '<')) {
			$this->markTestSkipped('Requires Phpunit 4+ to run.');
		}elseif (version_compare($PHPUV, '5.0', '<')) { // PHPUnit 4x
			$this->setExpectedException(
				"PHPUnit_Framework_Error_Error", $EEM
			);
		}elseif (version_compare($PHPUV, '6.0', '<')) { // PHPUnit 5x
			$this->expectException("PHPUnit_Framework_Error");
			$this->expectExceptionMessage($EEM);
		}else{ // PHPUnit 6+
			$this->expectException("PHPUnit\Framework\Error\Error");
			$this->expectExceptionMessage($EEM);
		}

		}
	}

	// Tests go here.
	public function testPageStartDefaults() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION";
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart();
	}
	public function testPageStartCustomTitle() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION : "
		.'Custom Title';
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart(0,'Custom Title');
	}
	public function testPageStartArchiveTitle() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$_COOKIE['archive'] = 1;
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION"
		.' -- ARCHIVE';
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart();
	}
	public function testPageStartNoCacheON() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$html_no_cache;
		$html_no_cache = 1;
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION";
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t$MHE"."pragma\" content=\"no-cache\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart();
	}
	public function testPageStartRefreshON() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$stat_page_refresh_time;
		$_SERVER["REQUEST_URI"] = "$BASE_path/testing";
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION";
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t$MHE"."refresh\" content=\"180; URL=$BASE_path/testing\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart(1);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
