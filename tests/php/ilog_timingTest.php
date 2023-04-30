<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_timing.inc.php

/**
  * Code Coverage Directives.
  * @covers EventTiming
  * @uses ::LoadedString
  * @uses ::NLI
  * @uses ::NLIO
  */

class log_timingTest extends TestCase {
	// Pre Test Setup.
	protected static $UOV;
	protected static $URV;
	protected static $tc;

	public static function setUpBeforeClass(){
		GLOBAL $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass(){
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
		self::$tc = null;
	}

	// Tests go here.
	// Tests for Class EventTiming
	public function testClassEventTimingConstruct(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(0),
			'Class Not Initialized.'
		);
		self::$tc = $tc;
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(0, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load.', $tc->event_log[0][1], $URV);
	}
	// Test Mark Function
	public function testClassEventTimingMark(){
		$URV = self::$URV.'Mark().';
		$tc = self::$tc;
		$tc->Mark('What');
		$this->assertNotEquals(0, $tc->event_log[1][0], $URV);
		$this->assertEquals('What', $tc->event_log[1][1], $URV);
	}
	// Test PrintTiming Function
	public function testClassEventTimingPrintTimng0(){
		$UOV = self::$UOV.'PrintTimng().';
		$tc = self::$tc;
		$expected = '';
		$this->expectOutputString($expected);
		$tc->PrintTiming();
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
