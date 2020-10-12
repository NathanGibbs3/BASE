<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_log_timing.inc.php

/**
  * Code Coverage Directives.
  * @covers EventTiming
  * @uses ::NLI
  * @uses ::NLIO
  */

class log_timingTest extends TestCase {
	// Tests go here.
	// Tests for Class EventTiming
	public function testClassEventTimingConstruct(){
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(0),
			'Class Not Initialized.'
		);
		$this->assertEquals(1, $tc->num_events, $URV);
		$this->assertNotEquals(0, $tc->start_time, $URV);
		$this->assertEquals(0, $tc->verbose, $URV);
		$this->assertTrue(is_array($tc->event_log), $URV);
		$this->assertTrue(is_array($tc->event_log[0]), $URV);
		$this->assertNotEquals(0, $tc->event_log[0][0], $URV);
		$this->assertEquals('Page Load', $tc->event_log[0][1], $URV);
	}
	// Test Mark Function
	public function testClassEventTimingMark(){
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(0),
			'Class Not Initialized.'
		);
		$tc->Mark('What');
		$this->assertNotEquals(0, $tc->event_log[1][0], $URV);
		$this->assertEquals('What', $tc->event_log[1][1], $URV);
	}
	// Test PrintTiming Function
	public function testClassEventTimingPrintTimng0(){
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'EventTiming',
			$tc = new EventTiming(0),
			'Class Not Initialized.'
		);
		$expected = '';
		$this->expectOutputString($expected);
		$tc->PrintTiming();
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
