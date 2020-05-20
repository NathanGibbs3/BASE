<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_constants.inc.php

/**
  * @covers ::SetConst
  * @uses ::LoadedString
  */
class constantsTest extends TestCase {

	// Tests go here.
	public function testSetConstSetNotString() {
		$this->assertFalse(
			SetConst(1,'Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetEmpty() {
		$this->assertFalse(
			SetConst('','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetValid() {
		$this->assertTrue(
			SetConst('Valid','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetSuccess() {
		$this->assertFalse(
			SetConst('Valid','Valid'),
			'Unexpected return SetConst().'
		);
	}
	public function testSetConstSetSuccessValue() {
		$this->assertTrue(defined('Valid'),'CONST not defined');
	}
	public function testContents() {
		ValidateConst($this,"UDP", 17);
		ValidateConst($this,"TCP", 6);
		ValidateConst($this,"ICMP", 1);
		ValidateConst($this,"SOURCE_PORT", 1);
		ValidateConst($this,"DEST_PORT", 2);
		ValidateConst($this,"SOURCE_IP", 1);
		ValidateConst($this,"DEST_IP", 2);
		ValidateConst($this,"NULL_IP", "256.256.256.256");
		// Page ID
		ValidateConst($this,"PAGE_QRY_ALERTS", 1);
		ValidateConst($this,"PAGE_STAT_ALERTS", 2);
		ValidateConst($this,"PAGE_STAT_SENSOR", 3);
		ValidateConst($this,"PAGE_QRY_AG", 4);
		ValidateConst($this,"PAGE_ALERT_DISPLAY", 5);
		ValidateConst($this,"PAGE_STAT_IPLINK", 6);
		ValidateConst($this,"PAGE_STAT_CLASS", 7);
		ValidateConst($this,"PAGE_STAT_UADDR", 8);
		ValidateConst($this,"PAGE_STAT_PORTS", 9);
		// Criteria Field count
		ValidateConst($this,"IPADDR_CFCNT", 11);
		ValidateConst($this,"TIME_CFCNT", 10);
		ValidateConst($this,"PROTO_CFCNT", 6);
		ValidateConst($this,"TCPFLAGS_CFCNT", 9);
		ValidateConst($this,"PAYLOAD_CFCNT", 5);
		// DB connection method
		ValidateConst($this,"DB_CONNECT", 2);
		ValidateConst($this,"DB_PCONNECT", 1);
		// CleanVariable() Mask
		ValidateConst($this,"VAR_DIGIT",       1);
		ValidateConst($this,"VAR_LETTER",      2);
		ValidateConst($this,"VAR_ULETTER",     4);
		ValidateConst($this,"VAR_LLETTER",     8);
		ValidateConst($this,"VAR_ALPHA",      16);
		ValidateConst($this,"VAR_PUNC",       32);
		ValidateConst($this,"VAR_SPACE",      64);
		ValidateConst($this,"VAR_FSLASH",    128);
		ValidateConst($this,"VAR_PERIOD",    256);
		ValidateConst($this,"VAR_OPERATOR",  512);
		ValidateConst($this,"VAR_OPAREN",   1024);  /*  (   */
		ValidateConst($this,"VAR_CPAREN",   2048);  /*  )   */
		ValidateConst($this,"VAR_USCORE",   4096);
		ValidateConst($this,"VAR_AT",       8192);
		ValidateConst($this,"VAR_SCORE",   16384);
		ValidateConst($this,"VAR_BOOLEAN", 32768);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
