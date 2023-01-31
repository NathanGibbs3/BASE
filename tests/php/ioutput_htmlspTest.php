<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php
// Tests that need Globals isolation.

/**
  * Code Coverage Directives.
  * @backupGlobals disabled
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */
class output_htmlSPTest extends TestCase {
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
	public function testPageStartDefaults() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$MHE = "<meta http-equiv='";
		$MNM = "<meta name='";
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
			$HTitle = "$UIL->Title (BASE) $BASE_installID";
			$ECS = $UIL->Charset;
		}else{
			include_once(self::$files);
			$HTitle = _TITLE;
			$ECS = _CHARSET;
		}
		$ETitle = $HTitle . " $BASE_VERSION";
		$EOM =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type' content='text/html; charset=$ECS'>"
		."\n\t\t$MNM"."Author' content='Nathan Gibbs'>"
		."\n\t\t$MNM"."Generator' content='BASE 0.0.0 (Joette)'>"
		."\n\t\t$MNM"."viewport' content='width=device-width, initial-scale=1'>"
		."\n\t\t<title>$ETitle</title>"
//		."\n\t\t$MNM"."color-scheme\" content=\"dark light\"/>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/base_common.css\">"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>"
		."\n\t\t<div class=\"mainheadertitle\">$HTitle</div>"
		;
		$this->expectOutputString($EOM);
		PageStart();
	}
	public function testPageStartCustomTitle() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$MHE = "<meta http-equiv='";
		$MNM = "<meta name='";
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
			$HTitle = "$UIL->Title (BASE) $BASE_installID";
			$ECS = $UIL->Charset;
		}else{
			include_once(self::$files);
			$HTitle = _TITLE;
			$ECS = _CHARSET;
		}
		$ETitle = $HTitle . " $BASE_VERSION: Custom Title";
		$EOM =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type' content='text/html; charset=$ECS'>"
		."\n\t\t$MNM"."Author' content='Nathan Gibbs'>"
		."\n\t\t$MNM"."Generator' content='BASE 0.0.0 (Joette)'>"
		."\n\t\t$MNM"."viewport' content='width=device-width, initial-scale=1'>"
		."\n\t\t<title>$ETitle</title>"
//		."\n\t\t$MNM"."color-scheme\" content=\"dark light\"/>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/base_common.css\">"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>"
		."\n\t\t<div class=\"mainheadertitle\">$HTitle</div>"
		;
		$this->expectOutputString($EOM);
		PageStart(0,'Custom Title');
	}
	public function testPageStartArchiveTitle() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style;
		$_COOKIE['archive'] = 1;
		$MHE = "<meta http-equiv='";
		$MNM = "<meta name='";
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
			$HTitle = "$UIL->Title (BASE) $BASE_installID";
			$ECS = $UIL->Charset;
		}else{
			include_once(self::$files);
			$HTitle = _TITLE;
			$ECS = _CHARSET;
		}
		$ETitle = $HTitle . " $BASE_VERSION";
		$ETitle .= ' -- ARCHIVE';
		$HTitle .= ' -- ARCHIVE';
		$EOM =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type' content='text/html; charset=$ECS'>"
		."\n\t\t$MNM"."Author' content='Nathan Gibbs'>"
		."\n\t\t$MNM"."Generator' content='BASE 0.0.0 (Joette)'>"
		."\n\t\t$MNM"."viewport' content='width=device-width, initial-scale=1'>"
		."\n\t\t<title>$ETitle</title>"
//		."\n\t\t$MNM"."color-scheme\" content=\"dark light\"/>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/base_common.css\">"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>"
		."\n\t\t<div class=\"mainheadertitle\">$HTitle</div>"
		;
		$this->expectOutputString($EOM);
		PageStart();
		$_COOKIE['archive'] = 0;
	}
	public function testPageStartNoCacheON() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$html_no_cache;
		$html_no_cache = 1;
		$MHE = "<meta http-equiv='";
		$MNM = "<meta name='";
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
			$HTitle = "$UIL->Title (BASE) $BASE_installID";
			$ECS = $UIL->Charset;
		}else{
			include_once(self::$files);
			$HTitle = _TITLE;
			$ECS = _CHARSET;
		}
		$ETitle = $HTitle . " $BASE_VERSION";
		$EOM =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type' content='text/html; charset=$ECS'>"
		."\n\t\t$MHE"."pragma' content='no-cache'>"
		."\n\t\t$MNM"."Author' content='Nathan Gibbs'>"
		."\n\t\t$MNM"."Generator' content='BASE 0.0.0 (Joette)'>"
		."\n\t\t$MNM"."viewport' content='width=device-width, initial-scale=1'>"
		."\n\t\t<title>$ETitle</title>"
//		."\n\t\t$MNM"."color-scheme\" content=\"dark light\"/>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/base_common.css\">"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>"
		."\n\t\t<div class=\"mainheadertitle\">$HTitle</div>"
		;
		$this->expectOutputString($EOM);
		PageStart();
		$html_no_cache = 0;
	}
	public function testPageStartRefreshON() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$stat_page_refresh_time;
		$MHE = "<meta http-equiv='";
		$MNM = "<meta name='";
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
			$HTitle = "$UIL->Title (BASE) $BASE_installID";
			$ECS = $UIL->Charset;
		}else{
			include_once(self::$files);
			$HTitle = _TITLE;
			$ECS = _CHARSET;
		}
		$ETitle = $HTitle . " $BASE_VERSION";
		$EOM =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
		. "\n<!-- $ETitle -->\n<html>\n\t<head>"
		."\n\t\t$MHE"."Content-Type' content='text/html; charset=$ECS'>"
		."\n\t\t$MHE"."refresh' content='180; URL=/'>"
		."\n\t\t$MNM"."Author' content='Nathan Gibbs'>"
		."\n\t\t$MNM"."Generator' content='BASE 0.0.0 (Joette)'>"
		."\n\t\t$MNM"."viewport' content='width=device-width, initial-scale=1'>"
		."\n\t\t<title>$ETitle</title>"
//		."\n\t\t$MNM"."color-scheme\" content=\"dark light\"/>"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/base_common.css\">"
		."\n\t\t<link rel=\"stylesheet\" type=\"text/css\""
		." HREF=\"/styles/$base_style\">\n\t</head>\n\t<body>"
		."\n\t\t<div class=\"mainheadertitle\">$HTitle</div>"
		;
		$this->expectOutputString($EOM);
		PageStart(1);
	}
	public function testdispMonthOptionsReturnDefaults() {
		GLOBAL $UIL;
		if ( function_exists('dispMonthOptions') ){
			if ( is_object(self::$UIL) ){
				$UIL = self::$UIL;
			}else{
				include_once(self::$files);
			}
			$EOM ="\n".'<option value="01" >January</option>';
			$EOM .="\n".'<option value="02" >February</option>';
			$EOM .="\n".'<option value="03" >March</option>';
			$EOM .="\n".'<option value="04" >April</option>';
			$EOM .="\n".'<option value="05" >May</option>';
			$EOM .="\n".'<option value="06" >June</option>';
			$EOM .="\n".'<option value="07" >July</option>';
			$EOM .="\n".'<option value="08" >August</option>';
			$EOM .="\n".'<option value="09" >September</option>';
			$EOM .="\n".'<option value="10" >October</option>';
			$EOM .="\n".'<option value="11" >November</option>';
			$EOM .="\n".'<option value="12" >December</option>';
			$this->assertEquals(
				$EOM,
				dispMonthOptions(''),
				'Unexpected Return Value.'
			);
		}else{
			// A test of nothing, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}
	}
	public function testdispMonthOptionsReturnindents() {
		GLOBAL $UIL;
		if ( function_exists('dispMonthOptions') ){
			if ( is_object(self::$UIL) ){
				$UIL = self::$UIL;
			}else{
				include_once(self::$files);
			}
			$EOM ="\n\t".'<option value="01" >January</option>';
			$EOM .="\n\t".'<option value="02" >February</option>';
			$EOM .="\n\t".'<option value="03" >March</option>';
			$EOM .="\n\t".'<option value="04" >April</option>';
			$EOM .="\n\t".'<option value="05" >May</option>';
			$EOM .="\n\t".'<option value="06" >June</option>';
			$EOM .="\n\t".'<option value="07" >July</option>';
			$EOM .="\n\t".'<option value="08" >August</option>';
			$EOM .="\n\t".'<option value="09" >September</option>';
			$EOM .="\n\t".'<option value="10" >October</option>';
			$EOM .="\n\t".'<option value="11" >November</option>';
			$EOM .="\n\t".'<option value="12" >December</option>';
			$this->assertEquals(
				$EOM,
				dispMonthOptions('',1),
				'Unexpected Return Value.'
			);
		}else{
			// A test of nothing, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}
	}
	public function testPrintBASEMenuDefaults() {
		$EOM = '';
		$this->expectOutputString($EOM);
		PrintBASEMenu();
	}
	public function testPrintBASEMenuInvalid() {
		$EOM = '';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Invalid');
	}
	public function testPrintBASEMenuHeader() {
		GLOBAL $BASE_installID;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "\n\t\t".'<div class=\'mainheadermenu\'>';
		$EOM .= "\n\t\t\t".'<table border=\'0\'>';
		$EOM .= "\n\t\t\t\t".'<tr>';
		$EOM .= "\n\t\t\t\t\t".'<td class=\'menuitem\'>';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_main.php\'>Home</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_qry_main.php?new=1\'>Search</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a>';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Header');
	}
	public function testPrintBASEMenuHeaderBackLink() {
		GLOBAL $BASE_installID;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "\n\t\t".'<div class=\'mainheadermenu\'>';
		$EOM .= "\n\t\t\t".'<table border=\'0\'>';
		$EOM .= "\n\t\t\t\t".'<tr>';
		$EOM .= "\n\t\t\t\t\t".'<td class=\'menuitem\'>';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_main.php\'>Home</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_qry_main.php?new=1\'>Search</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'Test';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Header', 'Test');
	}
	public function testPrintBASEMenuFooter() {
		GLOBAL $BASE_installID;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "\n\t\t".'<div class=\'mainheadermenu\'>';
		$EOM .= "\n\t\t\t".'<table border=\'0\'>';
		$EOM .= "\n\t\t\t\t".'<tr>';
		$EOM .= "\n\t\t\t\t\t".'<td class=\'menuitem\'>';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_ag_main.php?ag_action=list\'>';
		$EOM .= 'Alert Group Maintenance</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_maintenance.php\'>Cache & Status</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/admin/index.php\'>Administration</a>';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Footer');
	}
	public function testPrintBASEMenuFooterDebugTimeModeOn() {
		GLOBAL $BASE_installID, $et;
		$et = new EventTiming(1);
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = "\n\t\t".'<div class=\'mainheadermenu\'>';
		$EOM .= "\n\t\t\t".'<table border=\'0\'>';
		$EOM .= "\n\t\t\t\t".'<tr>';
		$EOM .= "\n\t\t\t\t\t".'<td class=\'menuitem\'>';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_ag_main.php?ag_action=list\'>';
		$EOM .= 'Alert Group Maintenance</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_maintenance.php\'>Cache & Status</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a> | ';
		$EOM .= "\n\t\t\t\t\t\t".'<a class=\'menuitem\' ';
		$EOM .= 'href=\'/admin/index.php\'>Administration</a> | ';
		$EOM .= "\n\t\t\t\t\t".'</td><td>';
		$EOM .= "\n\t\t\t\t\t\t".'<!-- Timing Information -->';
		$EOM .= "\n\t\t\t\t\t\t".'<div class=\'systemdebug\'>';
		$EOM .= "\n\t\t\t\t\t\t\t".'[Loaded in 0 seconds]<br/>';
		$EOM .= "\n\t\t\t\t\t\t".'</div>';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Footer');
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
