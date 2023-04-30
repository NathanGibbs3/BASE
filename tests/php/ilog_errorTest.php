<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_error.inc.php

/**
  * Code Coverage Directives.
  * @covers ::DivErrorMessage
  * @covers ::DDT
  * @covers ::LibIncError
  * @uses ::ErrorMessage
  * @uses ::FramedBoxFooter
  * @uses ::FramedBoxHeader
  * @uses ::Htmlcolor
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::PrintFramedBoxFooter
  * @uses ::PrintFramedBoxHeader
  * @uses ::PrintTblNewRow
  * @uses ::TblNewRow
  * @uses ::XSSPrintSafe
  * @uses ::returnErrorMessage
  */

class log_errorTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass() {
		GLOBAL $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testDivErrorMessageDefault() {
		$URV = self::$URV.'DivErrorMessage().';
		$this->expectOutputString(
			"\n<div class='errorMsg' align='center'>message</div>",
			DivErrorMessage('message'),$URV
		);
	}
	public function testDivErrorMessageIndent() {
		$URV = self::$URV.'DivErrorMessage().';
		$this->expectOutputString(
			"\n\t\t<div class='errorMsg' align='center'>message</div>",
			DivErrorMessage('message',2),$URV
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
		if ( ini_get('safe_mode') ){
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
	public function testDDTIemsDesV() {
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

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
