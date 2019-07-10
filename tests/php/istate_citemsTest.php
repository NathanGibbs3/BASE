<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_state_citems.inc.php

class state_citemsTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $langs;
	protected static $UIL;
	protected static $db;

	// We are using a Single TD file.
	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db;
		$tf = __FUNCTION__;
		$ll = 'english';
		self::$langs = $ll;
		$lf = "$ll.lang.php";
		self::$files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			LogTC($tf,'language',$ll);
			LogTC($tf,'TD file',$file);
		}
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if (!$TRAVIS){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			require('../database.php');
		}else{
			$ADO = getenv('ADODBPATH');
			if (!$ADO) {
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
			$DB = getenv('DB');
			if (!$DB){
				self::markTestIncomplete('Unable to get DB Engine.');
			}elseif ($DB == 'mysql' ){
				require('./tests/phpcommon/DB.mysql.php');
			}elseif ($DB == 'postgres' ){
				require('./tests/phpcommon/DB.pgsql.php');
			}else{
				self::markTestSkipped("CI Support unavialable for DB: $DB.");
			}
		}
		if (!isset($DBtype)){
			self::markTestIncomplete("Unable to Set DB: $DB.");
		}else{
			$alert_dbname='snort';
			// Setup DB Connection
			$db = NewBASEDBConnection($DBlib_path, $DBtype);
			// Check ADODB Sanity.
			// See: https://github.com/NathanGibbs3/BASE/issues/35
			if (ADODB_DIR != $DBlib_path ){
				self::markTestIncomplete(
					"Expected ADODB in location: $DBlib_path\n".
					"   Found ADODB in location: ".ADODB_DIR
				);
			}else{
				$db->baseDBConnect(
					$db_connect_method, $alert_dbname, $alert_host,
					$alert_port, $alert_user, $alert_password
				);
			}
			self::assertInstanceOf(
				'baseCon',
				$db,
				'DB Object Not Initialized.'
			);
			self::$db = $db;
		}
	}
	public static function tearDownAfterClass() {
		self::$UIL = null;
		self::$langs = null;
		self::$files = null;
		self::$db = null;
	}

	// Tests go here.
	public function testClassBaseCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$this->assertEquals('Test', $tc->cs, $URV);
		$this->assertEquals('Test', $tc->export_name, $URV);
		$this->assertNull($tc->criteria, $URV);
		$this->assertNull($tc->value, $URV);
		$this->assertNull($tc->value1, $URV);
		$this->assertNull($tc->value2, $URV);
		$this->assertNull($tc->value3, $URV);
	}
	public function testClassBaseCriteriaFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		// These functions in the foundation class are NoOps.
		// Call them for Code Coverage purposes.
		$tc->Init();
		$tc->Import();
		$tc->Clear();
		$tc->Sanitize();
		$tc->SanitizeElement('');
		$tc->PrintForm('', '', '');
		$tc->AddFormItem($cs, '');
		$tc->GetFormItemCnt();
		$tc->SetFormItemCnt('');
		$tc->Set('');
		$tc->Get();
		$tc->ToSQL();
		$tc->Description('');
		$tc->isEmpty();
	}
	public function testClassSingleElementCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SingleElementCriteria',
			$tc = new SingleElementCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$this->assertEquals('Test', $tc->cs, $URV);
		$this->assertEquals('Test', $tc->export_name, $URV);
		$this->assertNull($tc->criteria, $URV);
		$this->assertNull($tc->value, $URV);
		$this->assertNull($tc->value1, $URV);
		$this->assertNull($tc->value2, $URV);
		$this->assertNull($tc->value3, $URV);
	}
	public function testClassMultipleElementCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1, array()),
			'Class Not Initialized.'
		);
		$this->assertEquals('Test', $tc->cs, $URV);
		$this->assertEquals('Test', $tc->export_name, $URV);
		$this->assertNull($tc->criteria, $URV);
		$this->assertNull($tc->value, $URV);
		$this->assertNull($tc->value1, $URV);
		$this->assertNull($tc->value2, $URV);
		$this->assertNull($tc->value3, $URV);
		$this->assertEquals(1, $tc->element_cnt, $URV);
		$this->assertEquals(0, $tc->criteria_cnt, $URV);
		$this->assertTrue(is_array($tc->valid_field_list), $URV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
