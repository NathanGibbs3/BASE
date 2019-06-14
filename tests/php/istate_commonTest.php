<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_common.inc.php

/**
  * @covers ::InitArray
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

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
