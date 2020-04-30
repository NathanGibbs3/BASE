<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_output_query.inc.php

/**
  * @covers ::qroPrintEntryHeader
  * @uses ::XSSPrintSafe
  */

class output_queryTest extends TestCase {
	// Pre Test Setup.
	var $pfx;
	var $sfx;
	var $cf;
	protected function setUp() {
		$this->pfx = '<tr bgcolor="#';
		$this->sfx = '">';
		$this->cf = 1;
	}
	// Tests go here.
	// Check normal running conditions.
	public function testoutput_htmlTestDefault() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx.'FFFFFF'.$sfx,
			qroPrintEntryHeader(),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestEven() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx.'DDDDDD'.$sfx,
			qroPrintEntryHeader(2),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestOdd() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx.'FFFFFF'.$sfx,
			qroPrintEntryHeader(1),
			'Unexpected Return Value.'
		);
	}
	// Color Tests
	// From base_conf.php.dist
	// Red, yellow, orange, gray, white, blue
	// $priority_colors = array ('FF0000','FFFF00','FF9900','999999','FFFFFF','006600');
	public function testoutput_htmlTestPriColors() {
		GLOBAL $priority_colors;
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$msg ='';
		for ($i = 1; $i < 7; $i++){
			$msg .= $pfx.$priority_colors[$i-1].$sfx;
			$this->expectOutputString(
				$msg,
				qroPrintEntryHeader($i,$cf),
				'Unexpected Return Value.'
			);
		}
	}
	// Check for Issue #57
	public function testoutput_htmlTestNULL() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$this->expectOutputString(
			$pfx.'DDDDDD'.$sfx,
			qroPrintEntryHeader(NULL),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestNULLColor() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx.'999999'.$sfx,
			qroPrintEntryHeader(NULL,$cf),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestInvalidIndexColor() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx.'999999'.$sfx,
			qroPrintEntryHeader(7,$cf),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestNegativeIndexColor() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx.'999999'.$sfx,
			qroPrintEntryHeader(-1,$cf),
			'Unexpected Return Value.'
		);
	}
	public function testoutput_htmlTestInvalidStringColor() {
		$pfx = $this->pfx;
		$sfx = $this->sfx;
		$cf = $this->cf;
		$this->expectOutputString(
			$pfx.'999999'.$sfx,
			qroPrintEntryHeader('red',$cf),
			'Unexpected Return Value.'
		);
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
