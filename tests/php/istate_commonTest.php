<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_common.inc.php
/**
  * @covers ::CleanVariable
  * @covers ::InitArray
  * @covers ::SetSessionVar
  * @covers ::XSSPrintSafe
  */
class state_commonTest extends TestCase {
	// Pre Test Setup.
	protected static $CVT;
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass() {
		self::$CVT = '0Az ./()_@~!#$%^&*=<>+:;,?-|';
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$CVT = null;
		self::$UOV = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testInitArrayinvalid1Dim() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim1() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,1,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim2() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,'1',1),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayDefaults() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(1, count($a,1), $URV);
		$this->assertNull($a[0],$URV);
	}
	public function testInitArray1Dim8() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEmpty($a[$i],$URV);
		}
	}
	public function testInitArray1Dim8Valued() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,0,'Test'),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEquals('Test',$a[$i],$URV);
		}
	}
	public function testInitArray2Dim8x2() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,2),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(16, count($a,1)-count($a), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			for ( $j = 0; $j < 2; $j++ ){
				$this->assertNull($a[$i][$j],$URV);
			}
		}
	}
	public function testInitArray2Dim8x2Valued() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,2,'Test'),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(16, count($a,1)-count($a), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			for ( $j = 0; $j < 2; $j++ ){
				$this->assertEquals('Test',$a[$i][$j],$URV);
			}
		}
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
	public function testSetSessionVarDefaults() {
		$URV = self::$URV.'SetSessionVar().';
		$a = NULL;
		$this->assertEmpty(SetSessionVar($a),$URV);
	}
	public function testSetSessionVarGET() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$oget = $_GET;
		$debug_mode = 1;
		$a = 'Test';
		$_GET['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"Importing GET var '$a'<br/>\n",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_GET = $oget;
		$debug_mode = $odb;
	}
	public function testSetSessionVarPOST() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$opost = $_POST;
		$debug_mode = 1;
		$a = 'Test';
		$_POST['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"Importing POST var '$a'<br/>\n",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_POST = $opost;
		$debug_mode = $odb;
	}
	public function testSetSessionVarSESSION() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$a = 'Test';
		$_SESSION['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"Importing SESSION var '$a'<br/>\n",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_SESSION = $osession;
		$debug_mode = $odb;
	}
	public function testCleanVariableNullReturnsNull() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertNull(CleanVariable(NULL),$URV);
	}
	public function testCleanVariableValueReturnsNotNull() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertNotNull(CleanVariable('Value'),$URV);
	}
	public function testCleanVariableNoTransformValue() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertEquals('Value',CleanVariable('Value'),$URV);
	}
	public function testCleanVariableNoTransformArray() {
		$URV = self::$URV.'CleanVariable().';
		InitArray($a,1,0,1);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals($a,CleanVariable($a,VAR_DIGIT),$URV);
		$this->assertTrue(is_array($a),$URV);
	}
	public function testCleanVariableExceptionHit() {
		$URV = self::$URV.'CleanVariable().';
		$a = 1;
		InitArray($b,1,0,1);
		$this->assertEquals($a,CleanVariable($a,VAR_LETTER,$b),$URV);
	}
	public function testCleanVariableExceptionHitNoValidData() {
		$URV = self::$URV.'CleanVariable().';
		$a = 1;
		InitArray($b,1,0,1);
		$this->assertEquals($a,CleanVariable($a,'',$b),$URV);
	}
	public function testCleanVariableExceptionMiss() {
		$URV = self::$URV.'CleanVariable().';
		$a = 2;
		InitArray($b,1,0,1);
		$this->assertNotEquals($a,CleanVariable($a,VAR_LETTER,$b),$URV);
	}
	public function testCleanVariableExceptionMissNoValidData() {
		$URV = self::$URV.'CleanVariable().';
		$a = ' ';
		InitArray($b,1,0,1);
		$this->assertEquals('',CleanVariable($a,'',$b),$URV);
	}
	public function testCleanVariableGetDigit() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('0',CleanVariable($Value,VAR_DIGIT),$URV);
	}
	public function testCleanVariableGetLetters() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('Az',CleanVariable($Value,VAR_LETTER),$URV);
	}
	public function testCleanVariableGetLettersCaps() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('A',CleanVariable($Value,VAR_ULETTER),$URV);
	}
	public function testCleanVariableGetLettersNonCaps() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('z',CleanVariable($Value,VAR_LLETTER),$URV);
	}
	public function testCleanVariableGetAlphNum() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('0Az',CleanVariable($Value,VAR_ALPHA),$URV);
	}
	public function testCleanVariableGetSpace() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(' ',CleanVariable($Value,VAR_SPACE),$URV);
	}
	public function testCleanVariableGetPeriod() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('.',CleanVariable($Value,VAR_PERIOD),$URV);
	}
	public function testCleanVariableGetFSlash() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('/',CleanVariable($Value,VAR_FSLASH),$URV);
	}
	public function testCleanVariableGetOpenParam() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('(',CleanVariable($Value,VAR_OPAREN),$URV);
	}
	public function testCleanVariableGetCloseParam() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(')',CleanVariable($Value,VAR_CPAREN),$URV);
	}
	public function testCleanVariableGetBOOL() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('!&=|',CleanVariable($Value,VAR_BOOLEAN),$URV);
	}
	public function testCleanVariableGetOp() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(
			'/()!%^&*=<>+-|',CleanVariable($Value,VAR_OPERATOR),$URV
		);
	}
	public function testCleanVariableGetUnderscore() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('_',CleanVariable($Value,VAR_USCORE),$URV);
	}
	public function testCleanVariableGetAt() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('@',CleanVariable($Value,VAR_AT),$URV);
	}
	public function testCleanVariableGetPunc() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(
			' .()_~!#$%^&*=+:;,?-',CleanVariable($Value,VAR_PUNC),$URV
		);
	}
	public function testCleanVariableGetDash() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('-',CleanVariable($Value,VAR_SCORE),$URV);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
