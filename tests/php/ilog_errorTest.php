<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_error.inc.php

/**
  * Code Coverage Directives.
  * @covers ::DDT
  * @covers ::ErrorMessage
  * @covers ::LibIncError
  * @covers ::PrintHistory
  * @covers ::returnErrorMessage
  * @covers ::returnSMFN
  * @uses ::CleanVariable
  * @uses ::FramedBoxFooter
  * @uses ::FramedBoxHeader
  * @uses ::GetPHPSV
  * @uses ::Htmlcolor
  * @uses ::InitArray
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::PushHistory
  * @uses ::PrintFramedBoxFooter
  * @uses ::PrintFramedBoxHeader
  * @uses ::PrintTblNewRow
  * @uses ::TblNewRow
  * @uses ::XSSPrintSafe
  * @uses ::VS2SV
  */

class log_errorTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;
	protected static $omh;

	public static function setUpBeforeClass() {
		GLOBAL $BCR, $maintain_history;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		self::$omh = $maintain_history;
		$maintain_history = 1;
		$_SESSION = NULL; // Initialize the history.
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
	}
	public static function tearDownAfterClass() {
		GLOBAL $BCR, $maintain_history;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		$maintain_history = self::$omh;
		self::$UOV = null;
		self::$URV = null;
		self::$omh = null;
	}

	// Tests go here.
	public function testreturnErrorMessageDefault() {
		$URV = self::$URV.'returnErrorMessage().';
		$this->assertEquals(
			"<font color='#ff0000'>message</font>",
			returnErrorMessage('message'),$URV
		);
	}
	public function testreturnErrorMessageInvalidColor() {
		$URV = self::$URV.'returnErrorMessage().';
		$this->assertEquals(
			"<font color='#ff0000'>message</font>",
			returnErrorMessage('message','Invalid'),$URV
		);
	}
	public function testreturnErrorMessageValidColor() {
		$URV = self::$URV.'returnErrorMessage().';
		$this->assertEquals(
			"<font color='#0000ff'>message</font>",
			returnErrorMessage('message','#0000ff'),$URV
		);
	}
	public function testreturnErrorMessageInvalidBr() {
		$URV = self::$URV.'returnErrorMessage().';
		$this->assertEquals(
			"<font color='#0000ff'>message</font>",
			returnErrorMessage('message','#0000ff','yes'),$URV
		);
	}
	public function testreturnErrorMessageBr() {
		$URV = self::$URV.'returnErrorMessage().';
		$this->assertEquals(
			"<font color='#0000ff'>message</font><br/>",
			returnErrorMessage('message','#0000ff',1),$URV
		);
	}
	public function testErrorMessageDefault() {
		$URV = self::$URV.'ErrorMessage().';
		$this->expectOutputString(
			"<font color='#ff0000'>message</font>",
			ErrorMessage('message'),$URV
		);
	}
	public function testErrorMessageInvalidColor() {
		$URV = self::$URV.'ErrorMessage().';
		$this->expectOutputString(
			"<font color='#ff0000'>message</font>",
			ErrorMessage('message','Invalid'),$URV
		);
	}
	public function testErrorMessageValidColor() {
		$URV = self::$URV.'ErrorMessage().';
		$this->expectOutputString(
			"<font color='#0000ff'>message</font>",
			ErrorMessage('message','#0000ff'),$URV
		);
	}
	public function testErrorMessageInvalidBr() {
		$URV = self::$URV.'ErrorMessage().';
		$this->expectOutputString(
			"<font color='#0000ff'>message</font>",
			ErrorMessage('message','#0000ff','yes'),$URV
		);
	}
	public function testErrorMessageBr() {
		$URV = self::$URV.'ErrorMessage().';
		$this->expectOutputString(
			"<font color='#0000ff'>message</font><br/>",
			ErrorMessage('message','#0000ff',1),$URV
		);
	}
	public function testLibIncErrorDefault() {
		$UOV = self::$UOV.'LibIncError().';
		$EOM = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$EOM, LibIncError('Desc', 'Loc', 'Lib'),$UOV
		);
	}
	public function testLibIncErrorPear() {
		$UOV = self::$UOV.'LibIncError().';
		$EOM = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.<br/>The underlying Desc library '.
		'currently used is LibName.</font><br/>'.
		"<font color='black'>Check your Pear::LibName installation!<br/>Make ".
		'sure PEAR libraries can be found by PHP.<pre>pear config-show | grep '.
		'&quot;PEAR directory&quot;'."\n".'PEAR directory      php_dir     '.
		'/usr/share/pear</pre>This path must be part of the include path of '.
		'php (cf. /etc/php.ini).<pre>php -i | '.
		'grep &quot;include_path&quot;include_path =&gt; '.
		'.:/usr/share/pear:/usr/share/php =&gt; '.
		'.:/usr/share/pear:/usr/share/php</pre>';
		if( ini_get('safe_mode') ){
			$EOM .= 'In &quot;safe_mode&quot; it must also be part of '.
			'safe_mode_include_dir in /etc/php.ini';
		};
		$EOM .= '</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: LibName required.".
		'</font><br/>';
		$this->expectOutputString(
			$EOM, LibIncError(
				'Desc', 'Loc', 'Lib', '', 'LibName', '', 0, 1
			),$UOV
		);
	}
	public function testLibIncErrorMsg() {
		$UOV = self::$UOV.'LibIncError().';
		$EOM = "<font color='black'><b>Error loading the Desc library:".
		"</b> from &quot;Loc&quot;.</font><br/><font color='black'>Msg".
		'</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$EOM, LibIncError('Desc', 'Loc', 'Lib', 'Msg'),$UOV
		);
	}
	public function testLibIncErrorLibName() {
		$UOV = self::$UOV.'LibIncError().';
		$EOM = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.<br/>The underlying Desc library '.
		'currently used is LibName.</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$EOM, LibIncError('Desc', 'Loc', 'Lib', '', 'LibName'),$UOV
		);
	}
	public function testLibIncErrorURL() {
		$UOV = self::$UOV.'LibIncError().';
		$EOM = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.<br/>The underlying Desc library '.
		'currently used is LibName, that can be downloaded at '.
		"<a href='URL'>URL</a>.</font><br/>".
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$EOM, LibIncError('Desc', 'Loc', 'Lib', '', 'LibName', 'URL'),$UOV
		);
	}
	public function testDDTDeafult() {
		$UOV = self::$UOV.'DDT().';
		$TA = array();
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA), $UOV );
	}
	public function testDDTInvalid() {
		$UOV = self::$UOV.'DDT().';
		$TA = array();
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString(
			$EOM, DDT($TA, '', '', '', '', '', ''), $UOV
		);
	}
	public function testDDTXSSOff() {
		$UOV = self::$UOV.'DDT().';
		$TA = array('<br/>');
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\t"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>"."\n\t\t\t\t\t\t<br/>".
		"\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString(
			$EOM, DDT($TA, '', '', '', '', '', 0), $UOV
		);
	}
	public function testDDTTitle() {
		$UOV = self::$UOV.'DDT().';
		$TA = array();
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Title'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tTitle\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, $TA,'Title'), $UOV );
	}
	public function testDDTabs() {
		$UOV = self::$UOV.'DDT().';
		$TA = array();
		$EOM = "\n\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t<tr>".
		"\n\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\tDebug Data Table\n\t\t\t</td>".
		"\n\t\t</tr><tr>".
		"\n\t\t</tr>\n\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, $TA,'', 1), $UOV );
	}
	public function testDDTWidth() {
		$UOV = self::$UOV.'DDT().';
		$TA = array();
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:100%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, $TA,'', '', 100), $UOV );
	}
	public function testDDTIems() {
		$UOV = self::$UOV.'DDT().';
		$TA = array( 'a', 'b', 'c' );
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\ta"."\n\t\t\t\t\t</td><td>".
		"\n\t\t\t\t\t\tb"."\n\t\t\t\t\t</td><td>"."\n\t\t\t\t\t\tc".
		"\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA), $UOV );
	}
	public function testDDTIemsV() {
		$UOV = self::$UOV.'DDT().';
		$TA = array( 'a', 'b', 'c' );
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\ta"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\tb"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\tc".
		"\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, '', '', '', '', 1), $UOV );
	}
	public function testDDTIemsDesc() {
		$UOV = self::$UOV.'DDT().';
		$TA = array( 'a', 'b', 'c' );
		$TD = array( 'd', 'e', 'f' );
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\td"."\n\t\t\t\t\t</td><td>".
		"\n\t\t\t\t\t\te"."\n\t\t\t\t\t</td><td>"."\n\t\t\t\t\t\tf".
		"\n\t\t\t\t\t</td>"."\n\t\t\t\t</tr><tr>"."\n\t\t\t\t\t<td>".
		"\n\t\t\t\t\t\ta"."\n\t\t\t\t\t</td><td>".
		"\n\t\t\t\t\t\tb"."\n\t\t\t\t\t</td><td>"."\n\t\t\t\t\t\tc".
		"\n\t\t\t\t\t</td>\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, $TD), $UOV );
	}
	public function testDDTIemsDescV() {
		$UOV = self::$UOV.'DDT().';
		$TA = array( 'a', 'b', 'c' );
		$TD = array( 'd', 'e', 'f' );
		$EOM = "\n\t\t\t<table style = ".
		"'border: 2px solid red; border-collapse: collapse; width:75%;' ".
		"summary='Debug Data Table'>".
		"\n\t\t\t\t<tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' style='text-align: center;' ".
		"colspan='20'>\n\t\t\t\t\t\tDebug Data Table\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' ".
		"style='text-align: right; padding-right: 10px; width: 10%'>".
		"\n\t\t\t\t\t\td: ".
		"\n\t\t\t\t\t</td><td style='padding-left:10px;'>".
		"\n\t\t\t\t\t\ta"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' ".
		"style='text-align: right; padding-right: 10px; width: 10%'>".
		"\n\t\t\t\t\t\te: ".
		"\n\t\t\t\t\t</td><td style='padding-left:10px;'>".
		"\n\t\t\t\t\t\tb"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr><tr>".
		"\n\t\t\t\t\t<td class='sectiontitle' ".
		"style='text-align: right; padding-right: 10px; width: 10%'>".
		"\n\t\t\t\t\t\tf: ".
		"\n\t\t\t\t\t</td><td style='padding-left:10px;'>".
		"\n\t\t\t\t\t\tc"."\n\t\t\t\t\t</td>".
		"\n\t\t\t\t</tr>\n\t\t\t</table>";
		$this->expectOutputString( $EOM, DDT($TA, $TD, '', '', '', 1), $UOV );
	}
	public function testPrintHistoryNullSession() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		$_SESSION = NULL;
		$this->assertEquals('', PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryMaintHistOff() {
		GLOBAL $maintain_history;
		$URV = self::$URV.'PrintHistory().';
		$omh = $maintain_history;
		$maintain_history = 0;
		$this->assertEquals('', PrintHistory(), $URV);
		$maintain_history = $omh;
	}
	public function testPrintHistoryInvalidStack() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list'] = 'string';
		$EOM = "<font color='#ff0000'>History corrupted!</font><br/>"
		. "<font color='#ff0000'>string</font><br/>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryInValidStackInit() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, 's');
		$EOM = "<pre class='session'>0: History Start corrupted.\n</pre>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryValidStackInit() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, '');
		$EOM = "<pre class='session'>0: History Start.\n</pre>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryInValidStackEntry() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
		PushHistory(); // Load History
		unset($_SESSION['back_list'][1]['session']);
		$EOM = "<pre class='session'>0: History Start.\n"
		. "1: History Entry corrupted.\n</pre>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryValidStackEntry() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
		PushHistory(); // Load History
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			$tmp = '/usr';
		}else{ // Running in Travis CI
			$version = explode('.', phpversion());
			if (
				( $version[0] == 5 && $version[1] == 3 )
				|| ( $version[0] == 7 && $version[1] == 2 )
				|| ( $version[0] == 7 && $version[1] == 3 )
			){ // Composer Installed PHPUnit
				$tmp = 'vendor';
			}else{ // System PHPUnit
				$tmp = "/home/travis/.phpenv/versions/$version[0].$version[1]";
			}
		}
		$tmp .= '/bin/phpunit';
		$EOM = "<pre class='session'>0: History Start.\n"
		. "1: \tURL: $tmp\n\tSession: \n\n</pre>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
	}
	public function testPrintHistoryValidStackEntryQS() {
		$URV = self::$URV.'PrintHistory().';
		$osession = $_SESSION;
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
		$_SERVER['QUERY_STRING'] = '&amp;front=1';
		PushHistory(); // Load History
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			$tmp = '/usr';
		}else{ // Running in Travis CI
			$version = explode('.', phpversion());
			if (
				( $version[0] == 5 && $version[1] == 3 )
				|| ( $version[0] == 7 && $version[1] == 2 )
				|| ( $version[0] == 7 && $version[1] == 3 )
			){ // Composer Installed PHPUnit
				$tmp = 'vendor';
			}else{ // System PHPUnit
				$tmp = "/home/travis/.phpenv/versions/$version[0].$version[1]";
			}
		}
		$tmp .= '/bin/phpunit';
		$EOM = "<pre class='session'>0: History Start.\n"
		. "1: \tURL: $tmp?&amp;front=1\n\tSession: \n\n</pre>";
		$this->assertEquals($EOM, PrintHistory(), $URV);
		$_SESSION = $osession;
		unset($_SERVER['QUERY_STRING']);
	}
	public function testreturnSMFNDefault() {
		$URV = self::$URV.'returnSMFN().';
		$EOM = '';
		$this->assertEquals($EOM, returnSMFN(), $URV);
	}
	public function testreturnSMFNMsg() {
		$URV = self::$URV.'returnSMFN().';
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') ){
			$EOM = '';
			if( ini_get('safe_mode') ){
				$EOM = "<font color='black'>" . 'In PHP safe_mode Test must '
				.'be owned by the user under which the web server is '
				. 'running.</font><br/>';
			}
			$this->assertEquals($EOM, returnSMFN('Test'), $URV);
		}else{
			$this->assertTrue(true,'Passing Test.');
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
