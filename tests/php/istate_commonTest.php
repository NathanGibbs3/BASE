<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_common.inc.php
/**
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
			"Importing GET var '$a'<BR>",
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
			"Importing POST var '$a'<BR>",
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
			"Importing SESSION var '$a'<BR>",
			$Ret = SetSessionVar($a),
			$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_SESSION = $osession;
		$debug_mode = $odb;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
