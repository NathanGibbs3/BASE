<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_criteria.inc.php

/**
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  * @covers ::PrintBackButton
  */
class state_criteriaTest extends TestCase {
	public static function setUpBeforeClass() {
		GLOBAL $maintain_history;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		if ( $maintain_history != 1 ){
			$maintain_history = 1;
		}
		RegisterGlobalState();
		/* Initialize the history */
		$_SESSION = NULL;
		InitArray($_SESSION['back_list'], 1, 3, "");
		$_SESSION['back_list_cnt'] = 0;
		PushHistory();
		PushHistory(); // Load History
	}

	// Tests go here.
	public function testPrintBackButtonOK() {
		define('_BACK','Back'); // Remove once we TD migrate this.
		$this->assertEquals(
			"\n\t\t\t\t\t".'[&nbsp;<a href="'.$_SERVER["SCRIPT_NAME"].
			'?back=1&">Back</a>&nbsp;]'."\n",
			PrintBackButton(),
			'Unexpected return PrintBackButton().'
		);
	}
	public function testPrintBackButtonMaintHistOff() {
		GLOBAL $maintain_history;
		$maintain_history = 0;
		$this->assertEquals(
			'',
			PrintBackButton(),
			'Unexpected return PrintBackButton().'
		);
	}
	public function testPrintBackButtonNullSession() {
		$_SESSION = NULL;
		$this->assertEquals(
			'',
			PrintBackButton(),
			'Unexpected return PrintBackButton().'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
