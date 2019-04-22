<?php

// Test fucntions in /includes/base_output_html.inc.php
/**
 * A necessary evil for anything touching UILang during TD Transition.
 * @runTestsInSeparateProcesses
 */
class output_htmlTest extends PHPUnit_Framework_TestCase {
	// Tests go here.
	public function testPageStartDefaults() {
		GLOBAL $BASE_path, $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$MHE = '<meta http-equiv="';
		$UIL = new UILang('english');
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
		$UIL = new UILang('english');
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
		$UIL = new UILang('english');
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
		$UIL = new UILang('english');
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
		$UIL = new UILang('english');
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
	public function testPageEndOutputsExpected() {
		$this->expectOutputString(
			"\n\t</body>\n</html>",
			PageEnd(),
			'Unexpected Return Value.'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
