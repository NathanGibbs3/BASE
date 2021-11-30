<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_error.inc.php

/**
  * Code Coverage Directives.
  * @covers ::ErrorMessage
  * @covers ::LibIncError
  * @covers ::returnErrorMessage
  * @uses ::Htmlcolor
  * @uses ::LoadedString
  * @uses ::XSSPrintSafe
  */

class log_errorTest extends TestCase {
	// Tests go here.
	public function testreturnErrorMessageDefault() {
		$this->assertEquals(
			"<font color='#ff0000'>message</font>",
			returnErrorMessage('message'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageInvalidColor() {
		$this->assertEquals(
			"<font color='#ff0000'>message</font>",
			returnErrorMessage('message','Invalid'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageValidColor() {
		$this->assertEquals(
			"<font color='#0000ff'>message</font>",
			returnErrorMessage('message','#0000ff'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageInvalidBr() {
		$this->assertEquals(
			"<font color='#0000ff'>message</font>",
			returnErrorMessage('message','#0000ff','yes'),
			'Unexpected Return Value.'
		);
	}
	public function testreturnErrorMessageBr() {
		$this->assertEquals(
			"<font color='#0000ff'>message</font><br/>",
			returnErrorMessage('message','#0000ff',1),
			'Unexpected Return Value.'
		);
	}
	public function testErrorMessageDefault() {
		$this->expectOutputString(
			"<font color='#ff0000'>message</font>",
			ErrorMessage('message'),
			'Unexpected Return Value.'
		);
	}
	public function testErrorMessageInvalidColor() {
		$this->expectOutputString(
			"<font color='#ff0000'>message</font>",
			ErrorMessage('message','Invalid'),
			'Unexpected Return Value.'
		);
	}
	public function testErrorMessageValidColor() {
		$this->expectOutputString(
			"<font color='#0000ff'>message</font>",
			ErrorMessage('message','#0000ff'),
			'Unexpected Return Value.'
		);
	}
	public function testErrorMessageInvalidBr() {
		$this->expectOutputString(
			"<font color='#0000ff'>message</font>",
			ErrorMessage('message','#0000ff','yes'),
			'Unexpected Return Value.'
		);
	}
	public function testErrorMessageBr() {
		$this->expectOutputString(
			"<font color='#0000ff'>message</font><br/>",
			ErrorMessage('message','#0000ff',1),
			'Unexpected Return Value.'
		);
	}
	public function testLibIncErrorDefault() {
		$expected = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$expected, LibIncError('Desc', 'Loc', 'Lib'), 'Unexpected Output.'
		);
	}
	public function testLibIncErrorPear() {
		$expected = "<font color='black'><b>Error loading the Desc library:".
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
			$expected .= 'In &quot;safe_mode&quot; it must also be part of '.
			'safe_mode_include_dir in /etc/php.ini';
		};
		$expected .= '</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: LibName required.".
		'</font><br/>';
		$this->expectOutputString(
			$expected, LibIncError(
				'Desc', 'Loc', 'Lib', '', 'LibName', '', 0, 1
			),
			'Unexpected Output.'
		);
	}
	public function testLibIncErrorMsg() {
		$expected = "<font color='black'><b>Error loading the Desc library:".
		"</b> from &quot;Loc&quot;.</font><br/><font color='black'>Msg".
		'</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$expected, LibIncError('Desc', 'Loc', 'Lib', 'Msg'),
			'Unexpected Output.'
		);
	}
	public function testLibIncErrorLibName() {
		$expected = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.<br/>The underlying Desc library '.
		'currently used is LibName.</font><br/>'.
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$expected, LibIncError('Desc', 'Loc', 'Lib', '', 'LibName'),
			'Unexpected Output.'
		);
	}
	public function testLibIncErrorURL() {
		$expected = "<font color='black'><b>Error loading the Desc library:".
		'</b> from &quot;Loc&quot;.<br/>The underlying Desc library '.
		'currently used is LibName, that can be downloaded at '.
		"<a href='URL'>URL</a>.</font><br/>".
		"<font color='#ff0000'>PHP setup incomplete: Lib required.</font><br/>";
		$this->expectOutputString(
			$expected, LibIncError('Desc', 'Loc', 'Lib', '', 'LibName', 'URL'),
			'Unexpected Output.'
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
