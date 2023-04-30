<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_state_common.inc.php
/**
  * @covers ::CleanVariable
  * @covers ::ExportHTTPVar
  * @covers ::InitArray
  * @covers ::SetSessionVar
  * @covers ::filterSql
  * @uses ::ChkAccess
  * @uses ::ChkArchive
  * @uses ::ChkCookie
  * @uses ::ChkLib
  * @uses ::ErrorMessage
  * @uses ::HtmlColor
  * @uses ::LoadedString
  * @uses ::NewBASEDBConnection
  * @uses ::NLI
  * @uses ::NLIO
  * @uses ::SetConst
  * @uses ::XSSPrintSafe
  * @uses ::returnErrorMessage
  * @uses ::returnExportHTTPVar
  * @uses baseCon
  */
class state_commonTest extends TestCase {
	// Pre Test Setup.
	protected static $db;
	protected static $CVT;
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
		$alert_host, $alert_user, $alert_password, $alert_port,
		$db_connect_method, $db, $archive_dbname, $archive_host,
		$archive_port, $archive_user, $archive_password, $BCR;
		$tf = __FUNCTION__;
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
				if ($debug_mode > 1) {
					LogTC($tf,'DB',"$alert_dbname@$alert_host:$alert_port");
				}
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
		}
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$db = $db;
		self::$CVT = '0Az ./()_@~!#$%^&*=<>+:;,?-|';
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$db = null;
		self::$CVT = null;
		self::$UOV = null;
		self::$URV = null;
	}

	// Tests go here.
	public function testInitArrayinvalid1Dim() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim1() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,1,'1'),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayinvalid2Dim2() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertFalse(InitArray($a,'1',1),$URV);
		$this->assertFalse(is_array($a),$URV);
	}
	public function testInitArrayDefaults() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(1, count($a,1), $URV);
		$this->assertNull($a[0],$URV);
	}
	public function testInitArray1Dim8() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEmpty($a[$i],$URV);
		}
	}
	public function testInitArray1Dim8Valued() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,0,'Test'),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(8, count($a,1), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			$this->assertEquals('Test',$a[$i],$URV);
		}
	}
	public function testInitArray2Dim8x2() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,2),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(16, count($a,1)-count($a), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			for ( $j = 0; $j < 2; $j++ ){
				$this->assertNull($a[$i][$j],$URV);
			}
		}
	}
	public function testInitArray2Dim8x2Valued() {
		$a = NULL;
		$URV = self::$URV.'InitArray().';
		$this->assertTrue(InitArray($a,8,2,'Test'),$URV);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals(16, count($a,1)-count($a), $URV);
		for ( $i = 0; $i < 8; $i++ ){
			for ( $j = 0; $j < 2; $j++ ){
				$this->assertEquals('Test',$a[$i][$j],$URV);
			}
		}
	}
	public function testSetSessionVarDefaults() {
		$URV = self::$URV.'SetSessionVar().';
		$a = NULL;
		$this->assertEmpty(SetSessionVar($a),$URV);
	}
	public function testSetSessionVarGET() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$oget = $_GET;
		$debug_mode = 1;
		$a = 'Test';
		$_GET['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"<font color='black'>SetSessionVar(): Importing GET var '$a'"
			. '</font><br/>'
			. "<font color='black'>SetSessionVar(): $a: $a</font><br/>",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_GET = $oget;
		$debug_mode = $odb;
	}
	public function testSetSessionVarPOST() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$opost = $_POST;
		$debug_mode = 1;
		$a = 'Test';
		$_POST['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"<font color='black'>SetSessionVar(): Importing POST var '$a'"
			. '</font><br/>'
			. "<font color='black'>SetSessionVar(): $a: $a</font><br/>",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_POST = $opost;
		$debug_mode = $odb;
	}
	public function testSetSessionVarSESSION() {
		GLOBAL $debug_mode;
		$odb = $debug_mode;
		$osession = $_SESSION;
		$debug_mode = 1;
		$a = 'Test';
		$_SESSION['Test'] = $a;
		$URV = self::$URV.'SetSessionVar().';
		$UOV = self::$UOV.'SetSessionVar().';
		$this->expectOutputString(
			"<font color='black'>SetSessionVar(): Importing SESSION var '$a'"
			. '</font><br/>'
			. "<font color='black'>SetSessionVar(): $a: $a</font><br/>",
			$Ret = SetSessionVar($a),$UOV
		);
		$this->assertNotEmpty($Ret, $URV);
		$this->assertEquals($a, $Ret, $URV);
		$_SESSION = $osession;
		$debug_mode = $odb;
	}
	public function testCleanVariableNullReturnsNull() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertNull(CleanVariable(NULL),$URV);
	}
	public function testCleanVariableValueReturnsNotNull() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertNotNull(CleanVariable('Value'),$URV);
	}
	public function testCleanVariableNoTransformValue() {
		$URV = self::$URV.'CleanVariable().';
		$this->assertEquals('Value',CleanVariable('Value'),$URV);
	}
	public function testCleanVariableNoTransformArray() {
		$URV = self::$URV.'CleanVariable().';
		InitArray($a,1,0,1);
		$this->assertTrue(is_array($a),$URV);
		$this->assertEquals($a,CleanVariable($a,VAR_DIGIT),$URV);
		$this->assertTrue(is_array($a),$URV);
	}
	public function testCleanVariableExceptionHit() {
		$URV = self::$URV.'CleanVariable().';
		$a = 1;
		InitArray($b,1,0,1);
		$this->assertEquals($a,CleanVariable($a,VAR_LETTER,$b),$URV);
	}
	public function testCleanVariableExceptionHitNoValidData() {
		$URV = self::$URV.'CleanVariable().';
		$a = 1;
		InitArray($b,1,0,1);
		$this->assertEquals($a,CleanVariable($a,'',$b),$URV);
	}
	public function testCleanVariableExceptionMiss() {
		$URV = self::$URV.'CleanVariable().';
		$a = 2;
		InitArray($b,1,0,1);
		$this->assertNotEquals($a,CleanVariable($a,VAR_LETTER,$b),$URV);
	}
	public function testCleanVariableExceptionMissNoValidData() {
		$URV = self::$URV.'CleanVariable().';
		$a = ' ';
		InitArray($b,1,0,1);
		$this->assertEquals('',CleanVariable($a,'',$b),$URV);
	}
	public function testCleanVariableGetDigit() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('0',CleanVariable($Value,VAR_DIGIT),$URV);
	}
	public function testCleanVariableGetLetters() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('Az',CleanVariable($Value,VAR_LETTER),$URV);
	}
	public function testCleanVariableGetLettersCaps() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('A',CleanVariable($Value,VAR_ULETTER),$URV);
	}
	public function testCleanVariableGetLettersNonCaps() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('z',CleanVariable($Value,VAR_LLETTER),$URV);
	}
	public function testCleanVariableGetAlphNum() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('0Az',CleanVariable($Value,VAR_ALPHA),$URV);
	}
	public function testCleanVariableGetSpace() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(' ',CleanVariable($Value,VAR_SPACE),$URV);
	}
	public function testCleanVariableGetPeriod() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('.',CleanVariable($Value,VAR_PERIOD),$URV);
	}
	public function testCleanVariableGetFSlash() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('/',CleanVariable($Value,VAR_FSLASH),$URV);
	}
	public function testCleanVariableGetOpenParam() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('(',CleanVariable($Value,VAR_OPAREN),$URV);
	}
	public function testCleanVariableGetCloseParam() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(')',CleanVariable($Value,VAR_CPAREN),$URV);
	}
	public function testCleanVariableGetBOOL() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('!&=|',CleanVariable($Value,VAR_BOOLEAN),$URV);
	}
	public function testCleanVariableGetOp() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(
			'/()!%^&*=<>+-|',CleanVariable($Value,VAR_OPERATOR),$URV
		);
	}
	public function testCleanVariableGetUnderscore() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('_',CleanVariable($Value,VAR_USCORE),$URV);
	}
	public function testCleanVariableGetAt() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('@',CleanVariable($Value,VAR_AT),$URV);
	}
	public function testCleanVariableGetPunc() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals(
			' .()_~!#$%^&*=+:;,?-',CleanVariable($Value,VAR_PUNC),$URV
		);
	}
	public function testCleanVariableGetDash() {
		$URV = self::$URV.'CleanVariable().';
		$Value = self::$CVT;
		$this->assertEquals('-',CleanVariable($Value,VAR_SCORE),$URV);
	}
	public function testCleanVariableInvalidMask() {
		GLOBAL $debug_mode;
		$URV = self::$URV.'CleanVariable().';
		$UOV = self::$UOV.'CleanVariable().';
		$Value = self::$CVT;
		$odb = $debug_mode;
		$debug_mode = 1;
		$EOM = "<font color='#ff0000'>CleanVariable(): Invalid Mask</font><br/>";
		$this->expectOutputString(
			$EOM, $Ret = CleanVariable($Value,'a') ,$UOV
		);
		$this->assertNotNull( $Ret, $URV );
		$this->assertEquals( $Value, $Ret, $URV);
		$debug_mode = $odb;
	}
	public function testfilterSQLNullReturnsNull() {
		$URV = self::$URV.'filterSQL().';
		$this->assertNull(filterSQL(NULL),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLValueReturnsNotNull() {
		$URV = self::$URV.'filterSQL().';
		$this->assertNotNull(filterSQL('Value'),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLNoTransformValue() {
		$URV = self::$URV.'filterSQL().';
		$this->assertEquals('Value',filterSQL('Value'),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformValue() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = "O'Niell";
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals($Ret,filterSQL($Value),$URV);
	}
	public function testfilterSQLNoTransformNonKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$Value = array (1,2,3,4);
		$this->assertEquals(array(1,2,3,4),filterSQL($Value),$URV);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformNonKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = array ("O'Niell",1,2,3,4);
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals(
			array("$Ret",1,2,3,4),filterSQL($Value),$URV
		);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLNoTransformKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$Value = array (
			'key1' => 0,
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '0',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			filterSQL($Value),$URV
		);
	}
	/**
	 * @backupGlobals disabled
	 */
	public function testfilterSQLTransformKeyedArray() {
		$URV = self::$URV.'filterSQL().';
		$db = self::$db;
		$dbt = $db->DB_type;
		$Value = array (
			'key1' => "O'Niell",
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		if ( $dbt == 'mysql' || $dbt == 'mysqlt' || $dbt == 'maxsql' ){
			$Ret = "O\'Niell";
		}
		if ( $dbt == 'postgres' ){
			$Ret = "O''Niell";
		}
		$this->assertEquals(
			array(
				'key1' => $Ret,
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			filterSQL($Value),$URV
		);
	}
	public function testExportHTTPVarDefaults() {
		$URV = self::$URV.'ExportHTTPVar().';
		$this->assertFalse(ExportHTTPVar(),$URV);
	}
	public function testExportHTTPVarNameInvalid() {
		$URV = self::$URV.'ExportHTTPVar().';
		$UOV = self::$UOV.'ExportHTTPVar().';
		$this->expectOutputString( '', $Ret = ExportHTTPVar(1), $UOV );
		$this->assertFalse( $Ret, $URV );
	}
	public function testExportHTTPVarNameValid() {
		$URV = self::$URV.'ExportHTTPVar().';
		$UOV = self::$UOV.'ExportHTTPVar().';
		$this->expectOutputString(
			"\n\t\t\t<input type='hidden' name='Test' value=''/>",
			$Ret = ExportHTTPVar('Test'), $UOV
		);
		$this->assertTrue( $Ret, $URV );
	}
	public function testExportHTTPVarNameValue() {
		$URV = self::$URV.'ExportHTTPVar().';
		$UOV = self::$UOV.'ExportHTTPVar().';
		$this->expectOutputString(
			"\n\t\t\t<input type='hidden' name='Test' value='TestVal'/>",
			$Ret = ExportHTTPVar('Test', 'TestVal'), $UOV
		);
		$this->assertTrue( $Ret, $URV );
	}
	public function testExportHTTPVarTabInvalid() {
		$URV = self::$URV.'ExportHTTPVar().';
		$UOV = self::$UOV.'ExportHTTPVar().';
		$this->expectOutputString(
			"\n\t\t\t<input type='hidden' name='Test' value=''/>",
			$Ret = ExportHTTPVar('Test', '', 'String'), $UOV 
		);
		$this->assertTrue( $Ret, $URV );
	}
	public function testExportHTTPVarTabValid() {
		$URV = self::$URV.'ExportHTTPVar().';
		$UOV = self::$UOV.'ExportHTTPVar().';
		$this->expectOutputString(
			"\n\t\t\t\t<input type='hidden' name='Test' value=''/>",
			$Ret = ExportHTTPVar('Test', '', 4), $UOV 
		);
		$this->assertTrue( $Ret, $URV );
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
