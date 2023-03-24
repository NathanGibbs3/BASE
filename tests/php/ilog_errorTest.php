<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_error.inc.php

/**
  * Code Coverage Directives.
  * @covers ::DivErrorMessage
  * @covers ::ErrorMessage
  * @covers ::LibIncError
  * @covers ::returnErrorMessage
  * @uses ::Htmlcolor
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::XSSPrintSafe
  */

class log_errorTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass() {
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
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
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
