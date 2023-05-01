<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: includes/base_rtl.php

/**
  * Code Coverage Directives.
  * @covers ::ChkAccess
  * @covers ::ErrorMessage
  * @covers ::GetPHPSV
  * @covers ::Htmlcolor
  * @covers ::LoadedString
  * @covers ::NLI
  * @covers ::NLIO
  * @covers ::SetConst
  * @covers ::XSSPrintSafe
  * @covers ::is_key
  * @covers ::returnErrorMessage
  */

class base_rtlTest extends TestCase {
	// Pre Test Setup.
	protected static $TA;
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass(){
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
		self::$TA = array (
			null => 'null',
			23 => 'int',
			'test' => 'string',
			'array' => array ()
		);
	}
	public static function tearDownAfterClass(){
		self::$UOV = null;
		self::$URV = null;
		self::$TA = null;
		self::$PHPUV = null;
	}

	// Tests go here.
	public function testreturnChkAccessDirectoryTypeInvalid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$this->assertEquals(
			-1,
			ChkAccess($Testfile),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessDirectoryTypeValid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$this->assertEquals(
			1,
			ChkAccess($Testfile,'d'),
			'Unexpected return ChkAccess().'
		);
	}
	public function testreturnChkAccessInValid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'doesnotexist.htm';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals( -1, ChkAccess($Testfile), $URV );
	}
	public function testreturnChkAccessValid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals( 1, ChkAccess($Testfile), $URV );
	}
	public function testreturnChkAccessSafeModeCutoutDirectory(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 1, ChkAccess($Testfile,'d'), $URV );
		}
	}
	public function testreturnChkAccessSafeModeCutoutValid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 1, ChkAccess($Testfile), $URV );
		}
	}
	public function testLoadedStringNotSet(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(null), $URV );
	}
	public function testLoadedStringBool(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(false), $URV );
	}
	public function testLoadedStringInt(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(1), $URV );
	}
	public function testLoadadStringEmpty(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(''), $URV );
	}
	public function testLoadedStringFull(){
		$URV = self::$URV.'LoadedString().';
		$this->assertTrue( LoadedString('Valid'), $URV );
	}
	public function testSetConstSetNotString() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst(1,'Valid'), $URV );
	}
	public function testSetConstSetEmpty() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst('','Valid'), $URV );
	}
	public function testSetConstSetValid() {
		$URV = self::$URV.'SetConst().';
		$this->assertTrue( SetConst('Valid','Valid'), $URV );
	}
	public function testSetConstSetSuccess() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst('Valid','Valid'), $URV );
	}
	public function testSetConstSetSuccessValue() {
		$this->assertTrue(defined('Valid'),'CONST not defined');
	}
	public function testis_keyDefaultReturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$this->assertFalse( is_key('notthere', $TA), $URV );
	}
	public function testis_keyNonArrayreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$Idx = 'array';
		$this->assertFalse( is_key($Idx, $Idx), $URV );
	}
	public function testis_keyNullreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = null;
		$msg = 'null';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyEmptyreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = '';
		$msg = 'null';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyIntreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 23;
		$msg = 'int';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyStringreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 'test';
		$msg = 'string';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyArrayreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 'array';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertTrue( is_array($TA[$Idx]), $URV );
	}
	public function testNLIBlankReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n", NLI(), $URV );
	}
	public function testNLIInvalidCountReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>','string'), $URV );
	}
	public function testNLINoIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>'), $URV );
	}
	public function testNLIIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n\t\t\t\t\t<td>", NLI('<td>',5), $URV );
	}
	public function testNLIOBlankOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n", NLIO(), $UOV );
	}
	public function testNLIOInvalidCountOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>','string'), $UOV );
	}
	public function testNLIONoIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>'), $UOV );
	}
	public function testNLIOIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n\t\t\t\t\t<td>", NLIO('<td>',5), $UOV );
	}
	public function testGetPHPSV(){
		$URV = self::$URV.'GetPHPV().';
		$TV = explode('.', GetPHPSV());
		$this->assertEquals( PHP_MAJOR_VERSION, $TV[0], $URV );
		$this->assertEquals( PHP_MINOR_VERSION, $TV[1], $URV );
		$this->assertEquals( PHP_RELEASE_VERSION, $TV[2], $URV );
	}
	public function testRTLConstant() {
		IsSetConst($this, 'BASE_RTL');
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
	public function testXSSPrintSafeNullReturnsNull() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertNull(XSSPrintSafe(NULL),$URV);
	}
	public function testXSSPrintSafeValueReturnsNotNull() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertNotNull(XSSPrintSafe('Value'),$URV);
	}
	public function testXSSPrintSafeNoTransformValue() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertEquals('Value',XSSPrintSafe('Value'),$URV);
	}
	public function testXSSPrintSafeTransformValue() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = '&"<>';
		$this->assertEquals('&amp;&quot;&lt;&gt;',XSSPrintSafe($Value),$URV);
	}
	public function testXSSPrintSafeNoTransformNonKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array (1,2,3,4);
		$this->assertEquals(array(1,2,3,4),XSSPrintSafe($Value),$URV);
	}
	public function testXSSPrintSafeTransformNonKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array ('&"<>',1,2,3,4);
		$this->assertEquals(
			array('&amp;&quot;&lt;&gt;',1,2,3,4),XSSPrintSafe($Value),$URV
		);
	}
	public function testXSSPrintSafeNoTransformKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array (
			'key1' => 0,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '0',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			XSSPrintSafe($Value),$URV
		);
	}
	public function testXSSPrintSafeTransformKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array (
			'key1' => '&"<>',
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '&amp;&quot;&lt;&gt;',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			XSSPrintSafe($Value),$URV
		);
	}
	public function testHtmlColorWSC (){
		$URV = self::$URV.'HtmlColor().';
		$wsc = array(
			'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
			'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue',
			'teal', 'aqua'
		);
		foreach($wsc as $tc){
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorPfxHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$tc = '#' . $tc;
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorInvalidWSC (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#yellow'), $URV );
	}
	public function testHtmlColorInvalidHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('af'), $URV );
	}
	public function testHtmlColorInvalidPfHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#af'), $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
