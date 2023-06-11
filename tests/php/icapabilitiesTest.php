<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_capabilities.php

/**
  * Code Coverage Directives.
  * @backupGlobals disabled
  * @covers BaseCapsRegistry
  * @uses ::ChkAccess
  * @uses ::ChkLib
  * @uses ::GetPHPSV
  * @uses ::LoadedString
  * @uses ::PearInc
  * @uses ::SetConst
  * @uses ::is_key
  */

class capabilitiesTest extends TestCase {
	// Pre Test Setup.
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;
	protected static $tc;

	public static function setUpBeforeClass(){
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		$PHPUV = GetPHPUV(); // PHPUnit Version
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
	}
	public static function tearDownAfterClass() {
		self::$UOV = null;
		self::$URV = null;
		self::$PHPUV = null;
		self::$tc = null;
	}

	// Tests go here.
	// Tests for Class BaseCapsRegistry
	public function testClassBaseCapsRegistryConstruct(){
		$URV = self::$URV.'Construct().';
		$this->assertInstanceOf(
			'BaseCapsRegistry',
			$tc = new BaseCapsRegistry(),
			'Class Not Initialized.'
		);
		self::$tc = $tc;
		$this->assertTrue(is_array($tc->BCReg), $URV);
		$this->assertTrue(is_array($tc->BCReg['PHP']), $URV);
		$this->assertTrue(is_array($tc->BCReg['BASE']), $URV);
	}
	public function testClassBaseCapsRegistryReleasInfo(){
		$tc = self::$tc;
		$PHPUV = self::$PHPUV;
		$URV = self::$URV.'Release Info.';
		$this->assertEquals('lilias', $tc->GetCap('BASE_Lady'), $URV);
		if ( $tc->GetCap('BASE_Dev') ){
			$EOM = '^1\.4\.5-0\.0\.1 \(Jayme\)\+2023-06-11';
			if ( $PHPUV > 1 ){ // PHPUnit 9+
				$this->assertMatchesRegularExpression(
					'/'.$EOM.'/', $tc->GetCap('BASE_Ver'), $URV
				);
			}else{ // Legacy PHPUnit
				$this->assertRegExp(
					'/'.$EOM.'/', $tc->GetCap('BASE_Ver'), $URV
				);
			}
		}else{
			$this->assertEquals(
				'1.4.5 (lilias)', $tc->GetCap('BASE_Ver'), $URV
			);
		}
	}
	public function testClassBaseCapsRegistryGetCapEmpty(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertFalse($tc->GetCap(), $URV);
	}
	public function testClassBaseCapsRegistryGetCapNotExists(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertFalse($tc->GetCap('NotExist'), $URV);
	}
	public function testClassBaseCapsRegistryGetCapSubReg(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertTrue(is_array($tc->GetCap('BASE')), $URV);
	}
	public function testClassBaseCapsRegistryGetCapSubRegVal(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertFalse(is_array($tc->GetCap('BASE_Ver')), $URV);
		$this->assertTrue(is_string($tc->GetCap('BASE_Ver')), $URV);
		$this->assertTrue(is_bool($tc->GetCap('BASE_Dev')), $URV);
	}
	public function testClassBaseCapsRegistryAddCapEmpty(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertFalse($tc->AddCap(), $URV);
	}
	public function testClassBaseCapsRegistryAddCapNotExists(){
		$tc = self::$tc;
		$URV = self::$URV.'AddCap().';
		$this->assertFalse($tc->GetCap('NotExist'), $URV);
		$this->assertTrue($tc->AddCap('NotExist'), $URV);
		$this->assertTrue($tc->GetCap('NotExist'), $URV);
	}
	public function testClassBaseCapsRegistryAddCapOverwritePublic(){
		$tc = self::$tc;
		$URV = self::$URV.'AddCap().';
		$this->assertTrue($tc->GetCap('NotExist'), $URV);
		$this->assertTrue($tc->AddCap('NotExist', 'YES'), $URV);
		$this->assertEquals('YES', $tc->GetCap('NotExist'), $URV);
	}
	public function testClassBaseCapsRegistryAddCapOverwriteProtected(){
		$PHPUV = self::$PHPUV;
		$tc = self::$tc;
		$UOV = self::$UOV.'AddCap().';
		$URV = self::$URV.'AddCap().';
		$EOM = ' BASE Security Alert AddCap: SubReg: .* tampering detected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->AddCap('BASE_Ver', 'YES'), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}
	}
	public function testClassBaseCapsRegistryDelCapEmpty(){
		$tc = self::$tc;
		$URV = self::$URV.'GetCap().';
		$this->assertFalse($tc->DelCap(), $URV);
	}
	public function testClassBaseCapsRegistryDelCapExists(){
		$tc = self::$tc;
		$URV = self::$URV.'AddCap().';
		$this->assertEquals('YES', $tc->GetCap('NotExist'), $URV);
		$this->assertTrue($tc->DelCap('NotExist'), $URV);
		$this->assertFalse($tc->GetCap('NotExist'), $URV);
	}
	public function testClassBaseCapsRegistryDelCapNotExistsPublic(){
		$PHPUV = self::$PHPUV;
		$tc = self::$tc;
		$UOV = self::$UOV.'AddCap().';
		$URV = self::$URV.'AddCap().';
		$EOM = ' BASE Security Alert DelCap: Reg: .* tampering detected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->DelCap('NotExist'), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}
	}
	public function testClassBaseCapsRegistryDelCapNotExistsProtected(){
		$PHPUV = self::$PHPUV;
		$tc = self::$tc;
		$UOV = self::$UOV.'AddCap().';
		$URV = self::$URV.'AddCap().';
		$EOM = ' BASE Security Alert DelCap: SubReg: .* tampering detected.';
		$cur_e_l = ini_get( 'error_log' ); // Shim error_log output On
		$capture = tmpfile();
		$tmp = stream_get_meta_data($capture);
		ini_set('error_log', $tmp['uri']);
		$this->assertTrue($tc->DelCap('BASE_Ver'), $URV);
		unset ($_COOKIE['archive']);
		ini_set( 'error_log', $cur_e_l ); // Shim error_log output Off
		$elOutput = stream_get_contents($capture);
		if ( $PHPUV > 1 ){ // PHPUnit 9+
			$this->assertMatchesRegularExpression(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}else{ // Legacy PHPUnit
			$this->assertRegExp(
				'/'.$EOM.'/', $elOutput, $UOV
			);
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
