<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_state_citems.inc.php

/**
  * @covers BaseCriteria
  * @covers SingleElementCriteria
  * @covers MultipleElementCriteria
  * @covers ProtocolFieldCriteria
  * @covers SignatureCriteria
  * @covers SignaturePriorityCriteria
  * @covers TimeCriteria
  * @covers TCPFlagsCriteria
  * @uses ::InitArray
  * @uses ::SetSessionVar
  */
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
	// Tests for Class BaseCriteria
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
	// Test CTIFD Function;
	public function testClassBaseCriteriaCTIFDDefaults(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			"CTIFD: Test<br/>\nCriteria Type: NULL<br/>\n", $UOV
		);
		$tc->CTIFD();
		$debug_mode = $odb;
	}
	public function testClassBaseCriteriaCTIFDAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			"Test: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Test: Allowed.<br/>\n",
			$UOV
		);
		$tc->CTIFD($cs,true);
		$debug_mode = $odb;
	}
	public function testClassBaseCriteriaCTIFDDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			"Test: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Test: Denied.<br/>\n",
			$UOV
		);
		$tc->CTIFD($cs,false);
		$debug_mode = $odb;
	}
	// These functions in the foundation class are NoOps.
	// Call them for Code Coverage purposes.
	public function testClassBaseCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'BaseCriteria',
			$tc = new BaseCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
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
	// Tests for Class SingelElementCriteria
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
	// This function in this class is a NoOp.
	// Call it for Code Coverage purposes.
	public function testClassSingleElementCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'SingleElementCriteria',
			$tc = new SingleElementCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Sanitize();
	}
	public function testClassSingleElementCriteriaFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SingleElementCriteria',
			$tc = new SingleElementCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$this->assertTrue($tc->isEmpty(),$URV); // isEmtpy True
		$tc->Set(1); // Set
		$this->assertEquals(1,$tc->criteria,$URV); // Verify Set
		$this->assertEquals(1,$tc->Get(),$URV); // Verify Set via Get
		$this->assertFalse($tc->isEmpty(),$URV); // isEmpty False
		$this->assertEquals(-1,$tc->GetFormItemCnt(),$URV); // GetFormItemCnt
		// Test Import Function.
		$osession = $_SESSION;
		$_SESSION['Test'] = $cs;
		$tc->Import();
		$_SESSION = $osession;
		$this->assertEquals('Test',$tc->criteria,$URV);
	}
	public function testClassMultipleElementCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
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
	// This function in this class is a NoOp.
	// Call it for Code Coverage purposes.
	public function testClassMultipleElementCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$tc->SanitizeElement(1);
	}
	public function testClassMultipleElementCriteriaFuncsCritCount(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$this->assertTrue($tc->isEmpty(),$URV); // isEmtpy True
		$tc->SetFormItemCnt(1); // SetFormItemCnt
		$this->assertEquals(1, $tc->criteria_cnt, $URV);
		// Verify SetFormItemCnt
		$this->assertEquals(1,$tc->GetFormItemCnt(),$URV);
		// Verify SetFormItemCnt via GetFormItemCnt
		$this->assertFalse($tc->isEmpty(),$URV); // isEmtpy False
	}
	public function testClassMultipleElementCriteriaFuncsCompact(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$this->assertNull($tc->criteria, $URV);
		$this->assertTrue($tc->isEmpty(),$URV); // isEmtpy True
		$osession = $_SESSION;
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertFalse($tc->isEmpty(),$URV); // isEmtpy False
		$tc->criteria_cnt = 0;
		$this->assertTrue($tc->isEmpty(),$URV); // isEmtpy True
		$tc->Compact();
		$this->assertNull($tc->criteria, $URV);
		$this->assertNull($_SESSION[$tc->export_name], $URV);
		$_SESSION = $osession;
	}
	public function testClassMultipleElementCriteriaFuncSetDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			"Set: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Set: Denied.<br/>\n",
			$UOV
		);
		$tc->Set('');
		$debug_mode = $odb;
		$this->assertNull($tc->criteria, $URV);
	}
	public function testClassMultipleElementCriteriaFuncSetAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$ta = array();
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$debug_mode = 1;
		$this->expectOutputString(
			"Set: Test<br/>\nCriteria Type: array<br/>\n".
			"Criteria Set: Allowed.<br/>\n",
			$UOV
		);
		$tc->Set($ta);
		$debug_mode = $odb;
		$this->assertTrue(is_array($tc->criteria), $URV); // Verfiy Set
		$this->assertTrue(is_array($tc->Get()), $URV); // Verify Set via Get
	}
	public function testClassMultipleElementCriteriaFuncImportDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$cc = $cs.'_cnt';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = '';
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Import: Denied.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$_SESSION = $osession;
		$this->assertEquals('', $tc->criteria_cnt, $URV);
		$this->assertFalse(is_array($tc->criteria), $URV);
	}
	public function testClassMultipleElementCriteriaFuncImportAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$cc = $cs.'_cnt';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = array(0 => '1', 1 => '2');
		$_SESSION[$cc] = 1;
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Importing SESSION var 'Test_cnt'<br/>\n".
			"Import: Test<br/>\nCriteria Type: array<br/>\n".
			"Criteria Import: Allowed.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$this->assertEquals(1, $tc->criteria_cnt, $URV);
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(array(0 => '1', 1 => '2'),$tc->criteria, $URV);
		$_SESSION = $osession;
	}
	public function testClassMultipleElementCriteriaFuncInitDefault(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$osession = $_SESSION;
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(1, $tc->criteria_cnt, $URV);
		$this->assertEquals(1, $_SESSION['Test_cnt'], $URV);
		$this->assertEquals(10, count($tc->criteria,1)-count($tc->criteria), $URV);
		for ( $i = 0; $i < 10; $i++ ){
			for ( $j = 0; $j < $tc->criteria_cnt; $j++ ){
				$this->assertEquals('',$tc->criteria[$i][$j],$URV);
			}
		}
		$_SESSION = $osession;
	}
	public function testClassMultipleElementCriteriaFuncInitMR20(){
		GLOBAL $MAX_ROWS;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'MultipleElementCriteria',
			$tc = new MultipleElementCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$osession = $_SESSION;
		if ( isset($MAX_ROWS) ){
			$omr = $MAX_ROWS;
		}
		$MAX_ROWS = 20;
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(1, $tc->criteria_cnt, $URV);
		$this->assertEquals(1, $_SESSION['Test_cnt'], $URV);
		$this->assertEquals(20, count($tc->criteria,1)-count($tc->criteria), $URV);
		for ( $i = 0; $i < 20; $i++ ){
			for ( $j = 0; $j < $tc->criteria_cnt; $j++ ){
				$this->assertEquals('',$tc->criteria[$i][$j],$URV);
			}
		}
		if ( isset($omr) ){
			$MAX_ROWS = $omr;
		}else{
			unset($GLOBALS['MAX_ROWS']);
		}
		$_SESSION = $osession;
	}
	// Test Class ProtocolFieldCriteria
	public function testClassProtocolFieldCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'ProtocolFieldCriteria',
			$tc = new ProtocolFieldCriteria($db, $cs, 'Test', 1),
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
	// Tests for Class SignatureCriteria
	public function testClassSignatureCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SignatureCriteria',
			$tc = new SignatureCriteria($db, $cs, 'Test'),
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
	// These functions in this class are NoOps.
	// Call them for Code Coverage purposes.
	public function testClassSignatureCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'SignatureCriteria',
			$tc = new SignatureCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Clear();
		$tc->ToSQL();
	}
	public function testClassSignatureCriteriaFuncImportDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$cc = $cs.'_cnt';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'SignatureCriteria',
			$tc = new SignatureCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = '';
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Import: Denied.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$_SESSION = $osession;
		$this->assertEquals('', $tc->sig_type, $URV);
		$this->assertFalse(is_array($tc->criteria), $URV);
	}
	public function testClassSignatureCriteriaFuncImportAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'SignatureCriteria',
			$tc = new SignatureCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = array(0 => '', 1 => '');
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: array<br/>\n".
			"Criteria Import: Allowed.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$this->assertEquals('', $tc->sig_type, $URV);
		$this->assertEquals('', $_SESSION['sig_type'], $URV);
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(array(0 => '', 1 => ''),$tc->criteria, $URV);
		$_SESSION = $osession;
	}
	public function testClassSignatureCriteriaFuncInitDefault(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SignatureCriteria',
			$tc = new SignatureCriteria($db, $cs, 'Test', 1),
			'Class Not Initialized.'
		);
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals('', $tc->sig_type, $URV);
		$this->assertEquals(4, count($tc->criteria), $URV);
		for ( $i = 0; $i < 4; $i++ ){
			$this->assertEquals('',$tc->criteria[$i],$URV);
		}
	}
	// Tests for Class SignaturePriorityCriteria
	public function testClassSignaturePriorityCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SignaturePriorityCriteria',
			$tc = new SignaturePriorityCriteria($db, $cs, 'Test'),
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
	// These functions in this class are NoOps.
	// Call them for Code Coverage purposes.
	public function testClassSignaturePriorityCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'SignaturePriorityCriteria',
			$tc = new SignaturePriorityCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Clear();
		$tc->ToSQL();
	}
	public function testClassSignaturePriorityCriteriaFuncImportDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$cc = $cs.'_cnt';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'SignaturePriorityCriteria',
			$tc = new SignaturePriorityCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = '';
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Import: Denied.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$_SESSION = $osession;
		$this->assertFalse(is_array($tc->criteria), $URV);
	}
	public function testClassSignaturePriorityCriteriaFuncImportAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'SignaturePriorityCriteria',
			$tc = new SignaturePriorityCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = array(0 => '', 1 => '');
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: array<br/>\n".
			"Criteria Import: Allowed.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(array(0 => '', 1 => ''),$tc->criteria, $URV);
		$_SESSION = $osession;
	}
	public function testClassSignaturePriorityCriteriaFuncInitDefault(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'SignaturePriorityCriteria',
			$tc = new SignaturePriorityCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(2, count($tc->criteria), $URV);
		for ( $i = 0; $i < 2; $i++ ){
			$this->assertEquals('',$tc->criteria[$i],$URV);
		}
	}
	// Tests for Class TimeCriteria
	public function testClassTimeCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'TimeCriteria',
			$tc = new TimeCriteria($db, $cs, 'Test',1),
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
	// These functions in this class are NoOps.
	// Call them for Code Coverage purposes.
	public function testClassTimeCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'TimeCriteria',
			$tc = new TimeCriteria($db, $cs, 'Test',1),
			'Class Not Initialized.'
		);
		$tc->Clear();
		$tc->ToSQL();
	}
	// Tests for Class TCPFlagsCriteria
	public function testClassTCPFlagsCriteriaConstruct(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'TCPFlagsCriteria',
			$tc = new TCPFlagsCriteria($db, $cs, 'Test'),
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
	// These functions in this class are NoOps.
	// Call them for Code Coverage purposes.
	public function testClassTCPFlagsCriteriaNoOpFuncs(){
		$db = self::$db;
		$cs = 'Test';
		$this->assertInstanceOf(
			'TCPFlagsCriteria',
			$tc = new TCPFlagsCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Clear();
		$tc->ToSQL();
	}
	public function testClassTCPFlagsCriteriaFuncImportDenied(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$cc = $cs.'_cnt';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'TCPFlagsCriteria',
			$tc = new TCPFlagsCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = '';
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: NULL<br/>\n".
			"Criteria Import: Denied.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$_SESSION = $osession;
		$this->assertFalse(is_array($tc->criteria), $URV);
	}
	public function testClassTCPFlagsCriteriaFuncImportAllowed(){
		GLOBAL $debug_mode;
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$UOV = 'Unexpected Output.';
		$this->assertInstanceOf(
			'TCPFlagsCriteria',
			$tc = new TCPFlagsCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$_SESSION[$cs] = array(0 => '', 1 => '');
		$this->expectOutputString(
			"Importing SESSION var 'Test'<br/>\n".
			"Importing SESSION var 'Test'<br/>\n".
			"Import: Test<br/>\nCriteria Type: array<br/>\n".
			"Criteria Import: Allowed.<br/>\n",
			$UOV
		);
		$tc->Import();
		$debug_mode = $odb;
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(array(0 => '', 1 => ''),$tc->criteria, $URV);
		$_SESSION = $osession;
	}
	public function testClassTCPFlagsCriteriaFuncInitDefault(){
		$db = self::$db;
		$cs = 'Test';
		$URV = 'Unexpected Return Value.';
		$this->assertInstanceOf(
			'TCPFlagsCriteria',
			$tc = new TCPFlagsCriteria($db, $cs, 'Test'),
			'Class Not Initialized.'
		);
		$tc->Init();
		$this->assertTrue(is_array($tc->criteria), $URV);
		$this->assertEquals(9, count($tc->criteria), $URV);
		for ( $i = 0; $i < 9; $i++ ){
			$this->assertEquals('',$tc->criteria[$i],$URV);
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
