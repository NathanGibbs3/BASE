<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_html.inc.php
// Tests that need Globals isolation.

/**
  * Code Coverage Directives.
  * @Covers ::PageStart()
  * @uses ::NLI
  * @uses ::NLIO
  * @backupGlobals disabled
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class output_htmlSPTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $user;
	protected static $UIL;
	protected static $UOV;
	protected static $URV;

	// We are using a single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db, $BCR;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$ll);
			LogTC($tf,'TD file',$file);
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
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
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			require('../database.php');
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO) {
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
			$DB = getenv('DB');
			if (!$DB){
				self::markTestIncomplete('Unable to get DB Engine.');
			}elseif ($DB == 'mysql' ){
				require('./tests/phpcommon/DB.mysql.php');
			}elseif ($DB == 'postgres' ){
				require('./tests/phpcommon/DB.pgsql.php');
			}else{
				self::markTestSkipped("CI Support unavialable for DB: $DB.");
			}
		}
		if (!isset($DBtype)){
			self::markTestIncomplete("Unable to Set DB: $DB.");
		}else{
			$alert_dbname='snort';
			// Setup DB Connection
			$db = NewBASEDBConnection($DBlib_path, $DBtype);
			// Check ADODB Sanity.
			// See: https://github.com/NathanGibbs3/BASE/issues/35
			if (ADODB_DIR != $DBlib_path ){
				self::markTestIncomplete(
					"Expected ADODB in location: $DBlib_path\n".
					"   Found ADODB in location: ".ADODB_DIR
				);
			}else{
				if ($debug_mode > 1) {
					LogTC($tf,'DB',"$alert_dbname@$alert_host:$alert_port");
				}
				$db->baseDBConnect(
					$db_connect_method, $alert_dbname, $alert_host,
					$alert_port, $alert_user, $alert_password
				);
			}
			self::assertInstanceOf(
				'baseCon',
				$db,
				'DB Object Not Initialized.'
			);
			self::assertInstanceOf(
				'BaseUser',
				$user = new BaseUser(),
				'User Object Not Initialized.'
			);
			self::$user = $user;
		}
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
	}
	public static function tearDownAfterClass() {
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
		self::$user = null;
		self::$UOV = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testPageStartDefaults() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style, $BCR;
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
		"<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' "
		."'http://www.w3.org/TR/html4/loose.dtd'>"
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
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style, $BCR;
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
		"<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' "
		."'http://www.w3.org/TR/html4/loose.dtd'>"
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
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$archive_exists, $BCR;
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
		"<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' "
		."'http://www.w3.org/TR/html4/loose.dtd'>"
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
		$ogv = $archive_exists;
		$archive_exists = 1;
		PageStart();
		unset ($_COOKIE['archive']);
		$archive_exists = $ogv;
	}
	public function testPageStartNoCacheON() {
		GLOBAL $BASE_installID, $BASE_VERSION, $UIL, $base_style,
		$html_no_cache, $BCR;
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
		"<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' "
		."'http://www.w3.org/TR/html4/loose.dtd'>"
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
		$stat_page_refresh_time, $BCR;
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
		"<!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' "
		."'http://www.w3.org/TR/html4/loose.dtd'>"
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
			$URV = self::$URV.'dispMonthOptions().';
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
			$this->assertEquals( $EOM, dispMonthOptions(''), $URV );
		}else{
			// A test of nothing, so Pass.
			$this->assertTrue(true,'Passing Test.');
		}
	}
	public function testdispMonthOptionsReturnindents() {
		GLOBAL $UIL;
		if ( function_exists('dispMonthOptions') ){
			$URV = self::$URV.'dispMonthOptions().';
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
			$this->assertEquals( $EOM, dispMonthOptions('',1), $URV );
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
		$EOM .= 'href=\'/base_main.php\'>Home</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_qry_main.php?new=1\'>Search</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
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
		$EOM .= 'href=\'/base_main.php\'>Home</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_qry_main.php?new=1\'>Search</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | Test';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$this->expectOutputString($EOM);
		PrintBASEMenu('Header', 'Test');
	}
	public function testPrintBASEMenuFooter() {
		GLOBAL $BASE_installID;
		$user = self::$user;
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
		$EOM .= 'Alert Group Maintenance</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_maintenance.php\'>Cache & Status</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_user.php\'>User Preferences</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/base_logout.php\'>Logout</a>';
		$EOM .= "\n\t\t\t\t\t\t".' | <a class=\'menuitem\' ';
		$EOM .= 'href=\'/admin/index.php\'>Administration</a>';
		$EOM .= "\n\t\t\t\t\t".'</td>';
		$EOM .= "\n\t\t\t\t".'</tr>';
		$EOM .= "\n\t\t\t".'</table>';
		$EOM .= "\n\t\t".'</div>';
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAdmin|";
		$this->expectOutputString($EOM);
		PrintBASEMenu('Footer');
		unset ($_COOKIE['BASERole']);
	}
	public function testPrintBASEMenuFooterDebugTimeModeOn() {
		GLOBAL $BASE_installID, $et;
		$UOV = self::$UOV.'PrintBASEMenuFooter().';
		$user = self::$user;
		$et = new EventTiming(1);
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			include_once(self::$files);
		}
		$EOM = '\n\t\t<div class=\'mainheadermenu\'>';
		$EOM .= '\n\t\t\t<table border=\'0\'>';
		$EOM .= '\n\t\t\t\t<tr>';
		$EOM .= '\n\t\t\t\t\t<td class=\'menuitem\'>';
		$EOM .= '\n\t\t\t\t\t\t<a class=\'menuitem\' ';
		$EOM .= 'href=\'\/base_ag_main.php?ag_action=list\'>';
		$EOM .= 'Alert Group Maintenance<\/a>';
		$EOM .= '\n\t\t\t\t\t\t | <a class=\'menuitem\' ';
		$EOM .= 'href=\'\/base_maintenance.php\'>Cache & Status<\/a>';
		$EOM .= '\n\t\t\t\t\t\t | <a class=\'menuitem\' ';
		$EOM .= 'href=\'\/base_user.php\'>User Preferences<\/a>';
		$EOM .= '\n\t\t\t\t\t\t | <a class=\'menuitem\' ';
		$EOM .= 'href=\'\/base_logout.php\'>Logout<\/a>';
		$EOM .= '\n\t\t\t\t\t\t | <a class=\'menuitem\' ';
		$EOM .= 'href=\'\/admin\/index.php\'>Administration<\/a> | ';
		$EOM .= '\n\t\t\t\t\t<\/td><td>';
		$EOM .= '\n\t\t\t\t\t\t<!-- Timing Information -->'
		. '\n\t\t\t\t\t\t<div class=\'systemdebug\'>'
		. '\n\t\t\t\t\t\t\t\<span( style=\'color: green;\')?\>'
		. 'Loaded in\<\/span\> \[[0-1] seconds\]\<br\/\>'
		. '\n\t\t\t\t\t\t\<\/div\>';
		$EOM .= '\n\t\t\t\t\t<\/td>';
		$EOM .= '\n\t\t\t\t<\/tr>';
		$EOM .= '\n\t\t\t<\/table>';
		$EOM .= '\n\t\t<\/div>';
		$pw = $user->cryptpassword('password');
		$_COOKIE['BASERole'] = "$pw|TestAdmin|";
		$this->expectOutputRegex(
			'/^' . $EOM . '$/', PrintBASEMenu('Footer'), $UOV
		);
		unset ($_COOKIE['BASERole']);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
