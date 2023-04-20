<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_query.inc.php
/**
  * Code Coverage Directives.
  * @covers QueryState
  * @uses ::ChkArchive
  * @uses ::ChkCookie
  * @uses ::CleanVariable
  * @uses ::ChkGET
  * @uses ::ImportHTTPVar
  * @uses ::LoadedString
  */

class state_queryTest extends TestCase {
	// Pre Test Setup.
	protected static $URV;

	public static function setUpBeforeClass(){
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$URV = null;
	}

	// Tests go here.
	// Tests for Class QueryState
	public function testClassQueryStateConstruct(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'QueryState',
			$tc = new QueryState(),
			'Class Not Initialized.'
		);
		$this->assertNull($tc->canned_query_list, $URV);
		$this->assertEquals(-1, $tc->num_result_rows, $URV);
		$this->assertEmpty($tc->current_canned_query, $URV);
		$this->assertEmpty($tc->current_sort_order, $URV);
		$this->assertEquals(-1, $tc->current_view, $URV);
		$this->assertEquals(-1, $tc->show_rows_on_screen, $URV);
		$this->assertTrue(is_array($tc->valid_action_list), $URV);
		$this->assertEmpty($tc->action, $URV);
		$this->assertTrue(is_array($tc->valid_action_op_list), $URV);
		$this->assertEmpty($tc->action_arg, $URV);
		$this->assertEmpty($tc->action_lst, $URV);
		$this->assertEmpty($tc->action_chk_lst, $URV);
		$this->assertEmpty($tc->action_sql, $URV);
	}
	public function testClassQueryStateAddValidActionArchiveDBOn(){
		$URV = self::$URV.'AddValidAction().';
		$this->assertInstanceOf(
			'QueryState',
			$tc = new QueryState(),
			'Class Not Initialized.'
		);
		$TA = array(
			'ag_by_id', 'ag_by_name', 'add_new_ag', 'clear_alert',
			'del_alert', 'email_alert', 'email_alert2', 'csv_alert',
			'archive_alert', 'archive_alert2'
		);
		GLOBAL $archive_exists;
		$ogv = $archive_exists;
		$archive_exists = 1;
		foreach( $TA as $val ){
			$this->assertTrue($tc->AddValidAction($val));
		}
		$archive_exists = $ogv;
		$this->assertEquals($TA, $tc->valid_action_list, $URV);
	}
	public function testClassQueryStateAddValidActionArchiveDBInUse(){
		$URV = self::$URV.'AddValidAction().';
		$this->assertInstanceOf(
			'QueryState',
			$tc = new QueryState(),
			'Class Not Initialized.'
		);
		$TA = array(
			'ag_by_id', 'ag_by_name', 'add_new_ag', 'clear_alert',
			'del_alert', 'email_alert', 'email_alert2', 'csv_alert'
		);
		$TB = array( 'archive_alert', 'archive_alert2' );
		GLOBAL $archive_exists;
		$ogv = $archive_exists;
		$archive_exists = 1;
		$_COOKIE['archive'] = 1;
		foreach( $TA as $val ){
			$this->assertTrue($tc->AddValidAction($val));
		}
		foreach( $TB as $val ){
			$this->assertFalse($tc->AddValidAction($val));
		}
		unset ($_COOKIE['archive']);
		$archive_exists = $ogv;
		$this->assertEquals($TA, $tc->valid_action_list, $URV);
	}
	public function testClassQueryStateAddValidActionArchiveDBOff(){
		$URV = self::$URV.'AddValidAction().';
		$this->assertInstanceOf(
			'QueryState',
			$tc = new QueryState(),
			'Class Not Initialized.'
		);
		$TA = array(
			'ag_by_id', 'ag_by_name', 'add_new_ag', 'clear_alert',
			'del_alert', 'email_alert', 'email_alert2', 'csv_alert'
		);
		$TB = array( 'archive_alert', 'archive_alert2' );
		foreach( $TA as $val ){
			$this->assertTrue($tc->AddValidAction($val));
		}
		foreach( $TB as $val ){
			$this->assertFalse($tc->AddValidAction($val));
		}
		$this->assertEquals($TA, $tc->valid_action_list, $URV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
