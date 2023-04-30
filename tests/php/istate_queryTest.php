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
	protected static $tc;
	protected static $URV;

	public static function setUpBeforeClass(){
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$tc = null;
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
		self::$tc = $tc;
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
	public function testClassQueryStateAddValidActionArchiveDBOff(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$tc->valid_action_list = array();
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
	public function testClassQueryStateAddValidActionArchiveDBOn(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$tc->valid_action_list = array();
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
		$tc = self::$tc;
		$tc->valid_action_list = array();
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
	// Issue #185 Verifications Class Init / 185 Active / 185 Cleared
	public function testClassQueryStateisCannedQueryInit(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isCannedQuery(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryCntInit(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals(0, $tc->GetCurrentCannedQueryCnt(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryDescInit(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('', $tc->GetCurrentCannedQueryDesc(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQuerySortInit(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('', $tc->GetCurrentCannedQuerySort(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryInit(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryInitCallerEmpty(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(''), $URV);
	}
	public function testClassQueryStateisValidCannedQueryInitCallerLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery('caller'), $URV);
	}
	// Test Under Issue #185 Conditions.
	public function testClassQueryStateisCannedQueryIssue185(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$tc->current_canned_query = 'Issue #185';
		$this->assertFalse($tc->isCannedQuery(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryCntIssue185(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals(0, $tc->GetCurrentCannedQueryCnt(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryDescIssue185(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('', $tc->GetCurrentCannedQueryDesc(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQuerySortIssue185(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('', $tc->GetCurrentCannedQuerySort(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryIssue185(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryIssue185CallerEmpty(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(''), $URV);
	}
	public function testClassQueryStateisValidCannedQueryIssue185CallerLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery('caller'), $URV);
	}
	// Clear Issue #185 Conditions - Add a 'Canned Query'.
	public function testClassQueryStateisCannedQueryCCLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$tc->AddCannedQuery('Issue #185', 'c_num', 'c_desc', 'c_sort');
		$this->assertTrue($tc->isCannedQuery(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryCntCCLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('c_num', $tc->GetCurrentCannedQueryCnt(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQueryDescCCLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('c_num c_desc', $tc->GetCurrentCannedQueryDesc(), $URV);
	}
	public function testClassQueryStateGetCurrentCannedQuerySortCCLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertEquals('c_sort', $tc->GetCurrentCannedQuerySort(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryCCLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(), $URV);
	}
	public function testClassQueryStateisValidCannedQueryCCLoadedCallerEmpty(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertFalse($tc->isValidCannedQuery(''), $URV);
	}
	public function testClassQueryStateisValidCannedQueryCCLoadedCallerLoaded(){
		$URV = self::$URV.'AddValidAction().';
		$tc = self::$tc;
		$this->assertTrue($tc->isValidCannedQuery('Issue #185'), $URV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
