<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php
// Tests that need Globals isolation.

/**
  * @backupGlobals disabled
  */
class output_htmlSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		// Setup UI Language Object
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
	public function testPageStartDefaults() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
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
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
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
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
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
		$_COOKIE['archive'] = 0;
	}
	public function testPageStartNoCacheON() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
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
		$html_no_cache = 0;
	}
	public function testPageStartRefreshON() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$stat_page_refresh_time;
		$MHE = '<meta http-equiv="';
		$UIL = self::$UIL;
		$ETitle = "$UIL->Title (BASE) $BASE_installID $BASE_VERSION";
		$ECS = $UIL->Charset;
		$expected =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type\" content=\"text/html; charset=$ECS\">"
		."\n\t\t$MHE"."refresh\" content=\"180; URL=/\">"
		."\n\t\t<title>$ETitle</title>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>";
		;
		$this->expectOutputString($expected);
		PageStart(1);
	}
	public function testdispMonthOptionsReturnDefaults() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
		$expected ="\n".'<option value="01" >January</option>';
		$expected .="\n".'<option value="02" >February</option>';
		$expected .="\n".'<option value="03" >March</option>';
		$expected .="\n".'<option value="04" >April</option>';
		$expected .="\n".'<option value="05" >May</option>';
		$expected .="\n".'<option value="06" >June</option>';
		$expected .="\n".'<option value="07" >July</option>';
		$expected .="\n".'<option value="08" >August</option>';
		$expected .="\n".'<option value="09" >September</option>';
		$expected .="\n".'<option value="10" >October</option>';
		$expected .="\n".'<option value="11" >November</option>';
		$expected .="\n".'<option value="12" >December</option>';
		$this->assertEquals(
			$expected,
			dispMonthOptions(''),
			'Unexpected Return Value.'
		);
	}
	public function testdispMonthOptionsReturnindents() {
		GLOBAL $UIL;
		$UIL = self::$UIL;
		$expected ="\n\t".'<option value="01" >January</option>';
		$expected .="\n\t".'<option value="02" >February</option>';
		$expected .="\n\t".'<option value="03" >March</option>';
		$expected .="\n\t".'<option value="04" >April</option>';
		$expected .="\n\t".'<option value="05" >May</option>';
		$expected .="\n\t".'<option value="06" >June</option>';
		$expected .="\n\t".'<option value="07" >July</option>';
		$expected .="\n\t".'<option value="08" >August</option>';
		$expected .="\n\t".'<option value="09" >September</option>';
		$expected .="\n\t".'<option value="10" >October</option>';
		$expected .="\n\t".'<option value="11" >November</option>';
		$expected .="\n\t".'<option value="12" >December</option>';
		$this->assertEquals(
			$expected,
			dispMonthOptions('',1),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
