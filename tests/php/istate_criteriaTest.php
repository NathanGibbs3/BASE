<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_state_criteria.inc.php

/**
  * @covers ::PopHistory
  * @covers ::PrintBackButton
  * @covers ::PushHistory
  * @uses ::CleanVariable
  * @uses ::InitArray
  * @uses ::LoadedString
  */

class state_criteriaTest extends TestCase {
	// Pre Test Setup.
	protected static $URV;
	protected static $omh;
	protected static $opv;
	protected static $osv;
	protected static $tc;

	public static function setUpBeforeClass() {
		GLOBAL $maintain_history;
		self::$omh = $maintain_history;
		self::$opv = $_POST;
		self::$osv = $_SERVER;
		$maintain_history = 1;
		unset($_SERVER['QUERY_STRING']);
		$_SESSION = NULL; // Initialize the history.
		InitArray($_SESSION['back_list'], 1, 3, '');
		$_SESSION['back_list_cnt'] = 0;
		PushHistory(); // Load History
		$_SERVER['QUERY_STRING'] = '&amp;front=1';
		PushHistory();
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		GLOBAL $maintain_history;
		$maintain_history = self::$omh;
		$_POST = self::$opv;
		$_SERVER = self::$osv;
		self::$URV = null;
		self::$omh = null;
		self::$opv = null;
		self::$osv = null;
		self::$tc = null;
	}

	// Tests go here.
	public function testPushHistory(){
		$URV = self::$URV.'PushHistory().';
		$osession = $_SESSION;
		$SCN = $_SERVER['SCRIPT_NAME'];
		InitArray($HTP['back_list'], 1, 3, '');
		$HTP['back_list'][1]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][1]['QUERY_STRING'] = '';
		$HTP['back_list'][1]['session'] = '';
		$HTP['back_list'][2]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][2]['QUERY_STRING'] = '&amp;front=1';
		$HTP['back_list'][2]['session'] = '';
		$HTP['back_list_cnt'] = 2;
		// Test pre push stack.
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		$HTP['back_list'][3]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][3]['QUERY_STRING'] = '&amp;middle=earth';
		$HTP['back_list'][3]['session'] = '';
		$HTP['back_list_cnt'] = 3;
		$_SERVER['QUERY_STRING'] = '&amp;middle=earth';
		PushHistory(); // Test Push.
		$this->assertEquals(3, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		PushHistory(); // Test Push redundant Entry.
		$this->assertEquals(3, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		$_POST['caller'] = 'Vault-Tec';
		$HTP['back_list'][4]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][4]['QUERY_STRING'] = '&amp;middle=earth&amp;caller=Vault-Tec';
		$HTP['back_list'][4]['session'] = '';
		$HTP['back_list_cnt'] = 4;
		PushHistory(); // Test Push QSV.
		$this->assertEquals(4, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['caller']);
		$_SESSION = $osession;
	}
	public function testPopHistory(){
		$URV = self::$URV.'PopHistory().';
		$osession = $_SESSION;
		$_SERVER['QUERY_STRING'] = '&amp;middle=earth';
		PushHistory(); // Test Push.
		$_POST['caller'] = 'Vault-Tec';
		PushHistory(); // Test Push QSV.
		$SCN = $_SERVER['SCRIPT_NAME'];
		InitArray($HTP['back_list'], 1, 3, '');
		$HTP['back_list'][1]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][1]['QUERY_STRING'] = '';
		$HTP['back_list'][1]['session'] = '';
		$HTP['back_list'][2]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][2]['QUERY_STRING'] = '&amp;front=1';
		$HTP['back_list'][2]['session'] = '';
		$HTP['back_list'][3]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][3]['QUERY_STRING'] = '&amp;middle=earth';
		$HTP['back_list'][3]['session'] = '';
		$HTP['back_list'][4]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][4]['QUERY_STRING'] = '&amp;middle=earth&amp;caller=Vault-Tec';
		$HTP['back_list'][4]['session'] = '';
		$HTP['back_list_cnt'] = 4;
		// Test pre push stack.
		$this->assertEquals(4, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		PopHistory(); // Test Pop.
		unset($HTP['back_list'][3]);
		unset($HTP['back_list'][4]);
		$HTP['back_list_cnt'] = 2;
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['caller']);
		$_SESSION = $osession;
	}
	public function testPushHistoryMaintHistOff() {
		GLOBAL $maintain_history;
		$URV = self::$URV.'PushHistory().';
		$omh = $maintain_history;
		$maintain_history = 0;
		$SCN = $_SERVER['SCRIPT_NAME'];
		InitArray($HTP['back_list'], 1, 3, '');
		$HTP['back_list'][1]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][1]['QUERY_STRING'] = '';
		$HTP['back_list'][1]['session'] = '';
		$HTP['back_list'][2]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][2]['QUERY_STRING'] = '&amp;front=1';
		$HTP['back_list'][2]['session'] = '';
		$HTP['back_list_cnt'] = 2;
		// Test pre push stack.
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		PushHistory(); // Test Push.
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		$maintain_history = $omh;
	}
	public function testPopHistoryMaintHistOff() {
		GLOBAL $maintain_history;
		$URV = self::$URV.'PopHistory().';
		$omh = $maintain_history;
		$maintain_history = 0;
		$SCN = $_SERVER['SCRIPT_NAME'];
		InitArray($HTP['back_list'], 1, 3, '');
		$HTP['back_list'][1]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][1]['QUERY_STRING'] = '';
		$HTP['back_list'][1]['session'] = '';
		$HTP['back_list'][2]['SCRIPT_NAME'] = $SCN;
		$HTP['back_list'][2]['QUERY_STRING'] = '&amp;front=1';
		$HTP['back_list'][2]['session'] = '';
		$HTP['back_list_cnt'] = 2;
		// Test pre push stack.
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		PopHistory(); // Test Pop.
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		$maintain_history = $omh;
	}
	public function testPrintBackButtonMaintHistOff() {
		GLOBAL $maintain_history;
		$URV = self::$URV.'PrintBackButton().';
		$omh = $maintain_history;
		$maintain_history = 0;
		$this->assertEquals('', PrintBackButton(), $URV);
		$maintain_history = $omh;
	}
	public function testPushHistoryNullSession() {
		$URV = self::$URV.'PushHistory().';
		$osession = $_SESSION;
		$_SESSION = NULL;
		PushHistory(); // Test Push.
		$this->assertNull($_SESSION , $URV);
		$_SESSION = $osession;
	}
	public function testPopHistoryNullSession() {
		$URV = self::$URV.'PopHistory().';
		$osession = $_SESSION;
		$_SESSION = NULL;
		PopHistory(); // Test Pop.
		$this->assertNull($_SESSION , $URV);
		$_SESSION = $osession;
	}
	public function testPrintBackButtonNullSession() {
		$URV = self::$URV.'PrintBackButton().';
		$osession = $_SESSION;
		$_SESSION = NULL;
		$this->assertEquals('', PrintBackButton(), $URV);
		$_SESSION = $osession;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
