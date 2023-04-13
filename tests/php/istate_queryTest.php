<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_query.inc.php
/**
  * Code Coverage Directives.
  * @covers QueryState
  * @uses ::CleanVariable
  * @uses ::ImportHTTPVar
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

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
