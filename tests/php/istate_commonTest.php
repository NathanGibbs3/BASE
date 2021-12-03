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
	// Tests go here.
	public function testInitArrayinvalid1Dim() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertFalse(InitArray($a,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim1() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertFalse(InitArray($a,1,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim2() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertFalse(InitArray($a,'1',1),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayDefaults() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertTrue(InitArray($a),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(1, count($a,1), $URV);
		$this->assertNull($a[0],$URV);
	}
	public function testInitArray1Dim8() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertTrue(InitArray($a,8),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEmpty($a[$i],$URV);
		}
	}
	public function testInitArray1Dim8Valued() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
		$this->assertTrue(InitArray($a,8,0,'Test'),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEquals('Test',$a[$i],$URV);
		}
	}
	public function testInitArray2Dim8x2() {
		$a = NULL;
		$URV = 'Unexpected return InitArray().';
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
		$URV = 'Unexpected return InitArray().';
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
		$this->assertNull(
			XSSPrintSafe(NULL),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeValueReturnsNotNull() {
		$this->assertNotNull(
			XSSPrintSafe('Value'),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformValue() {
		$this->assertEquals(
			'Value',
			XSSPrintSafe('Value'),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformValue() {
		$Value = '&"<>';
		$this->assertEquals(
			'&amp;&quot;&lt;&gt;',
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformNonKeyedArray() {
		$Value = array (1,2,3,4);
		$this->assertEquals(
			array(1,2,3,4),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformNonKeyedArray() {
		$Value = array ('&"<>',1,2,3,4);
		$this->assertEquals(
			array('&amp;&quot;&lt;&gt;',1,2,3,4),
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeNoTransformKeyedArray() {
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
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testXSSPrintSafeTransformKeyedArray() {
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
			XSSPrintSafe($Value),
			'XSSPrintSafe Unexpected Return Value.'
		);
	}
	public function testSetSessionVarDefaults() {
		$a = NULL;
		$URV = 'Unexpected return SetSessionVar().';
		$this->assertEmpty(
			SetSessionVar($a),
			$URV
		);
	}
	public function testSetSessionVarGET() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$oget = $_GET;
		$debug_mode = 1;
		$a = 'Test';
		$_GET['Test'] = $a;
		$URV = 'Unexpected return SetSessionVar().';
		$UOV = 'Unexpected Output SetSessionVar().';
		$this->expectOutputString(
			"Importing GET var '$a'<br/>\n",
			$Ret = SetSessionVar($a),
			$UOV
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
		$URV = 'Unexpected return SetSessionVar().';
		$UOV = 'Unexpected Output SetSessionVar().';
		$this->expectOutputString(
			"Importing POST var '$a'<br/>\n",
			$Ret = SetSessionVar($a),
			$UOV
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
		$URV = 'Unexpected return SetSessionVar().';
		$UOV = 'Unexpected Output SetSessionVar().';
		$this->expectOutputString(
			"Importing SESSION var '$a'<br/>\n",
			$Ret = SetSessionVar($a),
			$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_SESSION = $osession;
		$debug_mode = $odb;
	}
	public function testCleanVariableNullReturnsNull() {
		$this->assertNull(
			CleanVariable(NULL),
			'CleanVariable Unexpected Return Value.'
		);
	}
	public function testCleanVariableValueReturnsNotNull() {
		$this->assertNotNull(
			CleanVariable('Value'),
			'CleanVariable Unexpected Return Value.'
		);
	}
	public function testCleanVariableNoTransformValue() {
		$this->assertEquals(
			'Value',
			CleanVariable('Value'),
			'CleanVariable Unexpected Return Value.'
		);
	}
	public function testCleanVariableNoTransformArray() {
		InitArray($a,1,0,1);
		$URV = 'Unexpected return CleanVariable().';
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(
			$a,
			CleanVariable($a,VAR_DIGIT),
			$URV
		);
		$this->assertTrue(is_array($a),$URV);
	}
	public function testCleanVariableExceptionHit() {
		$a = 1;
		InitArray($b,1,0,1);
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			$a,
			CleanVariable($a,VAR_DIGIT,$b),
			$URV
		);
	}
	public function testCleanVariableExceptionMiss() {
		$a = 2;
		InitArray($b,1,0,1);
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			$a,
			CleanVariable($a,VAR_DIGIT,$b),
			$URV
		);
	}
	public function testCleanVariableGetDigit() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'0',
			CleanVariable($Value,VAR_DIGIT),
			$URV
		);
	}
	public function testCleanVariableGetLetters() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'Az',
			CleanVariable($Value,VAR_LETTER),
			$URV
		);
	}
	public function testCleanVariableGetLettersCaps() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'A',
			CleanVariable($Value,VAR_ULETTER),
			$URV
		);
	}
	public function testCleanVariableGetLettersNonCaps() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'z',
			CleanVariable($Value,VAR_LLETTER),
			$URV
		);
	}
	public function testCleanVariableGetAlphNum() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'0Az',
			CleanVariable($Value,VAR_ALPHA),
			$URV
		);
	}
	public function testCleanVariableGetSpace() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			' ',
			CleanVariable($Value,VAR_SPACE),
			$URV
		);
	}
	public function testCleanVariableGetPeriod() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'.',
			CleanVariable($Value,VAR_PERIOD),
			$URV
		);
	}
	public function testCleanVariableGetFSlash() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'/',
			CleanVariable($Value,VAR_FSLASH),
			$URV
		);
	}
	public function testCleanVariableGetOpenParam() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'(',
			CleanVariable($Value,VAR_OPAREN),
			$URV
		);
	}
	public function testCleanVariableGetCloseParam() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			')',
			CleanVariable($Value,VAR_CPAREN),
			$URV
		);
	}
	public function testCleanVariableGetBOOL() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			')',
			CleanVariable($Value,VAR_BOOLEAN),
			$URV
		);
	}
	public function testCleanVariableGetOp() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			')',
			CleanVariable($Value,VAR_OPERATOR),
			$URV
		);
	}
	public function testCleanVariableGetUnderscore() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'_',
			CleanVariable($Value,VAR_USCORE),
			$URV
		);
	}
	public function testCleanVariableGetAt() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'@',
			CleanVariable($Value,VAR_AT),
			$URV
		);
	}
	public function testCleanVariableGetPunc() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
				' .()_~!#$%^&*=+:;,?-',
			CleanVariable($Value,VAR_PUNC),
			$URV
		);
	}
	public function testCleanVariableGetDash() {
		$Value = '0Az ./()_@~!#$%^&*=+:;,?-';
		$URV = 'Unexpected return CleanVariable().';
		$this->assertEquals(
			'-',
			CleanVariable($Value,VAR_SCORE),
			$URV
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
