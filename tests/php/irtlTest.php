<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: includes/base_rtl.php

/**
  * Code Coverage Directives.
  * @covers ::ChkAccess
  * @covers ::CCS
  * @covers ::GetPHPSV
  * @covers ::Htmlcolor
  * @covers ::LoadedString
  * @covers ::NLI
  * @covers ::NLIO
  * @covers ::NMHC
  * @covers ::SetConst
  * @covers ::XSSPrintSafe
  * @covers ::VS2SV
  * @covers ::ipconvert
  * @covers ::ipdeconvert
  * @covers ::is_ip
  * @covers ::is_ip4
  * @covers ::is_ip6
  * @covers ::is_key
  * @covers ::netmask
  */

class base_rtlTest extends TestCase {
	// Pre Test Setup.
	protected static $TA;
	protected static $NMHCv4;
	protected static $NMHCv6;
	protected static $TAIPInvalid;
	protected static $TAIPv4;
	protected static $TAIPv4cR;
	protected static $TAIPv6;
	protected static $TAIPv6cR;
	protected static $TAIPv6dR;
	protected static $PHPUV;
	protected static $UOV;
	protected static $URV;

	public static function setUpBeforeClass(){
		GLOBAL $BCR;
		// Shim for testing functions that access the BaseCapsRegestry Class
		// via the global $BCR var, which is not defined under test conditions.
		if ( !isset($BCR) ){
			$BCR = 'Temp';
		}
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
		// PHPUnit Version
		$PHPUV = GetPHPUV();
		if (version_compare($PHPUV, '9.0', '<')){ // PHPUnit < 9x
			self::$PHPUV = 1;
		}else{ // PHPUnit 9+
			self::$PHPUV = 2;
		}
		self::$TA = array (
			null => 'null',
			23 => 'int',
			'test' => 'string',
			'array' => array ()
		);
		self::$TAIPInvalid = array (
			'256.256.256.256', '::ffff:256.256.256.256'
		);
		self::$TAIPv4 = array (
			'192.168.000.001', '192.168.0.1',
			'192.168.129.1', '192.168.128.1',
			'0.0.0.0', '255.255.255.255'
		);
		self::$TAIPv4cR = array (
			'192.168.000.001' => '3232235521',
			'192.168.0.1' => '3232235521',
			'192.168.129.1' => '3232268545',
			'192.168.128.1' => '3232268289',
			'0.0.0.0' => '0',
			'255.255.255.255' => '4294967295'
		);
		self::$TAIPv6 = array (
			 '::1', '::', '0000:0000:0000:0000:0000:0000:0000:0000',
			'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff',
			'1050:0000:0000:0000:0005:0600:300c:326b',
			'1050:0:0:0:5:600:300c:326b',
			'1050::5:600:300c:326b',
			'ff06:0:0:0:0:0:0:c3',
			'ff06::c3',
			'0:0:0:0:0:ffff:192.001.56.10',
			'0:0:0:0:0:ffff:192.1.56.10',
			'::ffff:192.001.56.10',
			'::ffff:192.1.56.10',
		);
		self::$TAIPv6cR = array (
			 '::1' => '1',
			 '::' => '0',
			 '0000:0000:0000:0000:0000:0000:0000:0000' => '0',
			'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'
				=> '340282366920938463463374607431768211455',
			'1050:0000:0000:0000:0005:0600:300c:326b'
				=> '21683031681241440176744766643582546539',
			'1050:0:0:0:5:600:300c:326b'
				=> '21683031681241440176744766643582546539',
			'1050::5:600:300c:326b'
				=> '21683031681241440176744766643582546539',
			'ff06:0:0:0:0:0:0:c3' => '338984292706304756556241983349463187651',
			'ff06::c3' => '338984292706304756556241983349463187651',
			'0:0:0:0:0:ffff:192.001.56.10' => '281473903048714',
			'0:0:0:0:0:ffff:192.1.56.10' => '281473903048714',
			'::ffff:192.001.56.10' => '281473903048714',
			'::ffff:192.1.56.10' => '281473903048714',
		);
		self::$TAIPv6dR = array (
			 '1' => '0.0.0.1',
			 '0' => '0.0.0.0',
			'21683031681241440176744766643582546539'
				=> '1050::5:600:300c:326b',
			'338984292706304756556241983349463187651' => 'ff06::c3',
			'281473903048714' => '::ffff:192.1.56.10',
			'340282366920938463463374607431768211455'
				=> 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff',
		);
		self::$NMHCv4 = array (
			0 => '0',
			1 => '2147483648',
			2 => '1073741824',
			3 => '536870912',
			4 => '268435456',
			5 => '134217728',
			6 => '67108864',
			7 => '33554432',
			8 => '16777216',
			9 => '8388608',
			10 => '4194304',
			11 => '2097152',
			12 => '1048576',
			13 => '524288',
			14 => '262144',
			15 => '131072',
			16 => '65536',
			17 => '32768',
			18 => '16384',
			19 => '8192',
			20 => '4096',
			21 => '2048',
			22 => '1024',
			23 => '512',
			24 => '256',
			25 => '128',
			26 => '64',
			27 => '32',
			28 => '16',
			29 => '8',
			30 => '4',
			31 => '2',
			32 => '1',
		);
		self::$NMHCv6 = array (
			0 => '0',
			1 => '170141183460469231731687303715884105728',
			2 => '85070591730234615865843651857942052864',
			3 => '42535295865117307932921825928971026432',
			4 => '21267647932558653966460912964485513216',
			5 => '10633823966279326983230456482242756608',
			6 => '5316911983139663491615228241121378304',
			7 => '2658455991569831745807614120560689152',
			8 => '1329227995784915872903807060280344576',
			9 => '664613997892457936451903530140172288',
			10 => '332306998946228968225951765070086144',
			11 => '166153499473114484112975882535043072',
			12 => '83076749736557242056487941267521536',
			13 => '41538374868278621028243970633760768',
			14 => '20769187434139310514121985316880384',
			15 => '10384593717069655257060992658440192',
			16 => '5192296858534827628530496329220096',
			17 => '2596148429267413814265248164610048',
			18 => '1298074214633706907132624082305024',
			19 => '649037107316853453566312041152512',
			20 => '324518553658426726783156020576256',
			21 => '162259276829213363391578010288128',
			22 => '81129638414606681695789005144064',
			23 => '40564819207303340847894502572032',
			24 => '20282409603651670423947251286016',
			25 => '10141204801825835211973625643008',
			26 => '5070602400912917605986812821504',
			27 => '2535301200456458802993406410752',
			28 => '1267650600228229401496703205376',
			29 => '633825300114114700748351602688',
			30 => '316912650057057350374175801344',
			31 => '158456325028528675187087900672',
			32 => '79228162514264337593543950336',
			33 => '39614081257132168796771975168',
			34 => '19807040628566084398385987584',
			35 => '9903520314283042199192993792',
			36 => '4951760157141521099596496896',
			37 => '2475880078570760549798248448',
			38 => '1237940039285380274899124224',
			39 => '618970019642690137449562112',
			40 => '309485009821345068724781056',
			41 => '154742504910672534362390528',
			42 => '77371252455336267181195264',
			43 => '38685626227668133590597632',
			44 => '19342813113834066795298816',
			45 => '9671406556917033397649408',
			46 => '4835703278458516698824704',
			47 => '2417851639229258349412352',
			48 => '1208925819614629174706176',
			49 => '604462909807314587353088',
			50 => '302231454903657293676544',
			51 => '151115727451828646838272',
			52 => '75557863725914323419136',
			53 => '37778931862957161709568',
			54 => '18889465931478580854784',
			55 => '9444732965739290427392',
			56 => '4722366482869645213696',
			57 => '2361183241434822606848',
			58 => '1180591620717411303424',
			59 => '590295810358705651712',
			60 => '295147905179352825856',
			61 => '147573952589676412928',
			62 => '73786976294838206464',
			63 => '36893488147419103232',
			64 => '18446744073709551616',
			65 => '9223372036854775808',
			66 => '4611686018427387904',
			67 => '2305843009213693952',
			68 => '1152921504606846976',
			69 => '576460752303423488',
			70 => '288230376151711744',
			71 => '144115188075855872',
			72 => '72057594037927936',
			73 => '36028797018963968',
			74 => '18014398509481984',
			75 => '9007199254740992',
			76 => '4503599627370496',
			77 => '2251799813685248',
			78 => '1125899906842624',
			79 => '562949953421312',
			80 => '281474976710656',
			81 => '140737488355328',
			82 => '70368744177664',
			83 => '35184372088832',
			84 => '17592186044416',
			85 => '8796093022208',
			86 => '4398046511104',
			87 => '2199023255552',
			88 => '1099511627776',
			89 => '549755813888',
			90 => '274877906944',
			91 => '137438953472',
			92 => '68719476736',
			93 => '34359738368',
			94 => '17179869184',
			95 => '8589934592',
			96 => '4294967296',
			97 => '2147483648',
			98 => '1073741824',
			99 => '536870912',
			100 => '268435456',
			101 => '134217728',
			102 => '67108864',
			103 => '33554432',
			104 => '16777216',
			105 => '8388608',
			106 => '4194304',
			107 => '2097152',
			108 => '1048576',
			109 => '524288',
			110 => '262144',
			111 => '131072',
			112 => '65536',
			113 => '32768',
			114 => '16384',
			115 => '8192',
			116 => '4096',
			117 => '2048',
			118 => '1024',
			119 => '512',
			120 => '256',
			121 => '128',
			122 => '64',
			123 => '32',
			124 => '16',
			125 => '8',
			126 => '4',
			127 => '2',
			128 => '1',
		);
	}
	public static function tearDownAfterClass(){
		GLOBAL $BCR;
		if ( $BCR == 'Temp' ){ // EventTiming Shim clean up.
			unset($BCR);
		}
		self::$UOV = null;
		self::$URV = null;
		self::$TA = null;
		self::$NMHCv4 = null;
		self::$NMHCv6 = null;
		self::$TAIPInvalid = null;
		self::$TAIPv4 = null;
		self::$TAIPv4cR = null;
		self::$TAIPv6 = null;
		self::$TAIPv6cR = null;
		self::$TAIPv6dR = null;
		self::$PHPUV = null;
	}

	// Tests go here.
	public function testreturnChkAccessDirectoryTypeInvalid(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . 'custom';
		$this->assertEquals(-1, ChkAccess($Testfile), $URV);
	}
	public function testreturnChkAccessDirectoryNotExist(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$Testfile = "$BASE_path$sc" . "custom$sc" . 'notthere';
		$this->assertEquals(-1, ChkAccess($Testfile,'d'), $URV);
	}
	public function testreturnChkAccessFileNotExist(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'doesnotexist.htm';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals( -1, ChkAccess($Testfile), $URV );
	}
	public function testreturnChkAccessFileExists(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$this->assertEquals( 1, ChkAccess($Testfile), $URV );
	}
	public function testreturnChkAccessSafeModeCutoutDirectoryExists(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testdir.notexec';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 1, ChkAccess($Testfile,'d'), $URV );
		}
	}
	public function testreturnChkAccessSafeModeCutoutFileExists(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testCASE.HTML';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.1.4', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 1, ChkAccess($Testfile), $URV );
		}
	}
	public function testreturnChkAccessSafeModeCutoutDirectoryExec(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testdir.isexec';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.2.0', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 2, ChkAccess($Testfile,'d'), $URV );
		}
	}
	public function testreturnChkAccessSafeModeCutoutFileExec(){
		GLOBAL $BASE_path;
		$URV = self::$URV.'ChkAccess().';
		$sc = DIRECTORY_SEPARATOR;
		$file = 'testexec';
		$Testfile = "$BASE_path$sc" . "custom$sc$file";
		$PHPV = GetPHPV();
		if (version_compare($PHPV, '5.2.0', '>')){
			$this->assertTrue(true,'Passing Test.');
		}else{
			$this->assertTrue(ini_get("safe_mode"),'PHP SafeMode: Off');
			$this->assertEquals( 2, ChkAccess($Testfile), $URV );
		}
	}
	public function testLoadedStringNotSet(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(null), $URV );
	}
	public function testLoadedStringBool(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(false), $URV );
	}
	public function testLoadedStringInt(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(1), $URV );
	}
	public function testLoadadStringEmpty(){
		$URV = self::$URV.'LoadedString().';
		$this->assertFalse( LoadedString(''), $URV );
	}
	public function testLoadedStringFull(){
		$URV = self::$URV.'LoadedString().';
		$this->assertTrue( LoadedString('Valid'), $URV );
	}
	public function testSetConstSetNotString() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst(1,'Valid'), $URV );
	}
	public function testSetConstSetEmpty() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst('','Valid'), $URV );
	}
	public function testSetConstSetValid() {
		$URV = self::$URV.'SetConst().';
		$this->assertTrue( SetConst('Valid','Valid'), $URV );
	}
	public function testSetConstSetSuccess() {
		$URV = self::$URV.'SetConst().';
		$this->assertFalse( SetConst('Valid','Valid'), $URV );
	}
	public function testSetConstSetSuccessValue() {
		$this->assertTrue(defined('Valid'),'CONST not defined');
	}
	public function testis_keyDefaultReturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$this->assertFalse( is_key('notthere', $TA), $URV );
	}
	public function testis_keyNonArrayreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$Idx = 'array';
		$this->assertFalse( is_key($Idx, $Idx), $URV );
	}
	public function testis_keyNullreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = null;
		$msg = 'null';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyEmptyreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = '';
		$msg = 'null';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyIntreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 23;
		$msg = 'int';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyStringreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 'test';
		$msg = 'string';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertEquals( $msg, $TA[$Idx], $URV );
	}
	public function testis_keyArrayreturnsExpected(){
		$URV = self::$URV.'is_key().';
		$TA = self::$TA;
		$Idx = 'array';
		$this->assertTrue( is_key($Idx, $TA), $URV );
		$this->assertTrue( is_array($TA[$Idx]), $URV );
	}
	public function testNLIBlankReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n", NLI(), $URV );
	}
	public function testNLIInvalidCountReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>','string'), $URV );
	}
	public function testNLINoIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n<td>", NLI('<td>'), $URV );
	}
	public function testNLIIndentReturnsExpected() {
		$URV = self::$URV.'NLI().';
		$this->assertEquals( "\n\t\t\t\t\t<td>", NLI('<td>',5), $URV );
	}
	public function testNLIOBlankOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n", NLIO(), $UOV );
	}
	public function testNLIOInvalidCountOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>','string'), $UOV );
	}
	public function testNLIONoIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n<td>", NLIO('<td>'), $UOV );
	}
	public function testNLIOIndentOutputsExpected() {
		$UOV = self::$UOV.'NLIO().';
		$this->expectOutputString( "\n\t\t\t\t\t<td>", NLIO('<td>',5), $UOV );
	}
	public function testGetPHPSV(){
		$URV = self::$URV.'GetPHPV().';
		$TV = GetPHPSV();
		$this->assertEquals( PHP_MAJOR_VERSION, $TV[0], $URV );
		$this->assertEquals( PHP_MINOR_VERSION, $TV[1], $URV );
		$this->assertEquals( PHP_RELEASE_VERSION, $TV[2], $URV );
	}
	public function testRTLConstant() {
		IsSetConst($this, 'BASE_RTL');
	}
	public function testXSSPrintSafeNullReturnsNull() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertNull(XSSPrintSafe(NULL),$URV);
	}
	public function testXSSPrintSafeValueReturnsNotNull() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertNotNull(XSSPrintSafe('Value'),$URV);
	}
	public function testXSSPrintSafeNoTransformValue() {
		$URV = self::$URV.'XSSPrintSafe().';
		$this->assertEquals('Value',XSSPrintSafe('Value'),$URV);
	}
	public function testXSSPrintSafeTransformValue() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = '&"<>';
		$this->assertEquals('&amp;&quot;&lt;&gt;',XSSPrintSafe($Value),$URV);
	}
	public function testXSSPrintSafeNoTransformNonKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array (1,2,3,4);
		$this->assertEquals(array(1,2,3,4),XSSPrintSafe($Value),$URV);
	}
	public function testXSSPrintSafeTransformNonKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array ('&"<>',1,2,3,4);
		$this->assertEquals(
			array('&amp;&quot;&lt;&gt;',1,2,3,4),XSSPrintSafe($Value),$URV
		);
	}
	public function testXSSPrintSafeNoTransformKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
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
			XSSPrintSafe($Value),$URV
		);
	}
	public function testXSSPrintSafeTransformKeyedArray() {
		$URV = self::$URV.'XSSPrintSafe().';
		$Value = array (
			'key1' => '&"<>',
			'key2' => 1,
			'key3' => 2,
			'key4' => 3,
			'key5' => 4
		);
		$this->assertEquals(
			array(
				'key1' => '&amp;&quot;&lt;&gt;',
				'key2' => '1',
				'key3' => '2',
				'key4' => '3',
				'key5' => '4'
			),
			XSSPrintSafe($Value),$URV
		);
	}
	public function testHtmlColorWSC (){
		$URV = self::$URV.'HtmlColor().';
		$wsc = array(
			'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
			'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue',
			'teal', 'aqua'
		);
		foreach($wsc as $tc){
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorPfxHex (){
		$URV = self::$URV.'HtmlColor().';
		for ($i = 0; $i < 257; $i++ ){
			$tc = substr("00".dechex($i),-2);
			$tc = str_repeat ($tc, 3);
			$tc = '#' . $tc;
			$this->assertTrue( HtmlColor($tc), $URV );
		}
	}
	public function testHtmlColorInvalidWSC (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#yellow'), $URV );
	}
	public function testHtmlColorInvalidHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('af'), $URV );
	}
	public function testHtmlColorInvalidPfHex (){
		$URV = self::$URV.'HtmlColor().';
		$this->assertFalse( HtmlColor('#af'), $URV );
	}
	public function testCCS(){
		$URV = self::$URV.'CCS().';
		$tmp = CCS();
		$this->assertFalse( $tmp[0], $URV );
		$this->assertEmpty( $tmp[1], $URV );
	}
	public function testCCSSvrFLAG(){
		$URV = self::$URV.'CCS().';
		$tgs = $_SERVER;
		$_SERVER['HTTPS'] = 'On';
		$tmp = CCS();
		$this->assertTrue( $tmp[0], $URV );
		$this->assertEQuals( 'SVR-FLAG', $tmp[1], $URV );
		$_SERVER = $tgs;
	}
	public function testCCSSvrPort(){
		$URV = self::$URV.'CCS().';
		$tgs = $_SERVER;
		$_SERVER['SERVER_PORT'] = 443;
		$tmp = CCS();
		$this->assertTrue( $tmp[0], $URV );
		$this->assertEQuals( 'SVR-PORT', $tmp[1], $URV );
		$_SERVER = $tgs;
	}
	public function testCCSProxyProt(){
		$URV = self::$URV.'CCS().';
		$tgs = $_SERVER;
		$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
		$tmp = CCS();
		$this->assertTrue( $tmp[0], $URV );
		$this->assertEQuals( 'PRX-PROT', $tmp[1], $URV );
		$_SERVER = $tgs;
	}
	public function testCCSProxySSL(){
		$URV = self::$URV.'CCS().';
		$tgs = $_SERVER;
		$_SERVER['HTTP_X_FORWARDED_SSL'] = 'On';
		$tmp = CCS();
		$this->assertTrue( $tmp[0], $URV );
		$this->assertEQuals( 'PRX-SSL', $tmp[1], $URV );
		$_SERVER = $tgs;
	}
	public function testCCSProxyPort(){
		$URV = self::$URV.'CCS().';
		$tgs = $_SERVER;
		$_SERVER['HTTP_X_FORWARDED_PORT'] = 443;
		$tmp = CCS();
		$this->assertTrue($tmp[0], $URV);
		$this->assertEQuals('PRX-PORT', $tmp[1], $URV);
		$_SERVER = $tgs;
	}
	public function testis_ipEmpty(){
		$URV = self::$URV.'is_ip().';
		$this->assertFalse(is_ip(), $URV);
	}
	public function testis_ipInvalid(){
		$URV = self::$URV.'is_ip().';
		$TAIP = self::$TAIPInvalid;
		foreach( $TAIP as $IP ){
			$this->assertFalse(is_ip($IP), $URV);
		}
	}
	public function testis_ipFilled(){
		$URV = self::$URV.'is_ip().';
		$TAv4 = self::$TAIPv4;
		$TAv6 = self::$TAIPv6;
		foreach( $TAv4 as $IP ){
			$this->assertTrue(is_ip($IP), $URV);
		}
		foreach( $TAv6 as $IP ){
			$this->assertTrue(is_ip($IP), $URV);
		}
	}
	public function testis_ip4Empty(){
		$URV = self::$URV.'is_ip4().';
		$this->assertFalse(is_ip4(), $URV);
	}
	public function testis_ipv4Invalid(){
		$URV = self::$URV.'is_ip4().';
		$TAIP = self::$TAIPInvalid;
		foreach( $TAIP as $IP ){
			$this->assertFalse(is_ip4($IP), $URV);
		}
	}
	public function testis_ip4Filled(){
		$URV = self::$URV.'is_ip4().';
		$TAv4 = self::$TAIPv4;
		$TAv6 = self::$TAIPv6;
		foreach( $TAv4 as $IP ){
			$this->assertTrue(is_ip4($IP), $URV);
		}
		foreach( $TAv6 as $IP ){
			$this->assertFalse(is_ip4($IP), $URV);
		}
	}
	public function testis_ip6Empty(){
		$URV = self::$URV.'is_ip6().';
		$this->assertFalse(is_ip6(), $URV);
	}
	public function testis_ipv6Invalid(){
		$URV = self::$URV.'is_ip6().';
		$TAIP = self::$TAIPInvalid;
		foreach( $TAIP as $IP ){
			$this->assertFalse(is_ip6($IP), $URV);
		}
	}
	public function testis_ip6Filled(){
		$URV = self::$URV.'is_ip6().';
		$TAv4 = self::$TAIPv4;
		$TAv6 = self::$TAIPv6;
		foreach( $TAv4 as $IP ){
			$this->assertFalse(is_ip6($IP), $URV);
		}
		foreach( $TAv6 as $IP ){
			$this->assertTrue(is_ip6($IP), $URV);
		}
	}
	public function testnetmaskEmpty(){
		$URV = self::$URV.'netmask().';
		$this->assertEQuals(0, netmask(), $URV);
	}
	public function testnetmaskNegNum(){
		$URV = self::$URV.'netmask().';
		$this->assertEQuals(0, netmask('/-24'), $URV);
	}
	public function testnetmaskOverload(){
		$URV = self::$URV.'netmask().';
		$this->assertEQuals(128, netmask('/129'), $URV);
	}
	public function testnetmaskFilled(){
		$URV = self::$URV.'netmask().';
		$TAv4 = self::$TAIPv4;
		$TAv6 = self::$TAIPv6;
		for( $tmp = 0 ; $tmp != 129 ; $tmp++ ){
			foreach( $TAv4 as $IP ){
				$this->assertEQuals($tmp, netmask("$IP/$tmp"), $URV);
			}
			foreach( $TAv6 as $IP ){
				$this->assertEQuals($tmp, netmask("$IP/$tmp"), $URV);
			}
		}
	}
	public function testipconvertEmpty(){
		$URV = self::$URV.'ipconvert().';
		$this->assertEQuals(0, ipconvert(), $URV);
	}
	public function testipconvertInvalid(){
		$URV = self::$URV.'ipconvert().';
		$TAIP = self::$TAIPInvalid;
		foreach( $TAIP as $IP ){
			$this->assertEQuals(0, ipconvert($IP), $URV);
		}
	}
	public function testipconvertIPv4(){
		$URV = self::$URV.'ipconvert().';
		$TAv4 = self::$TAIPv4;
		$TAv4R = self::$TAIPv4cR;
		foreach( $TAv4 as $IP ){
			$this->assertEQuals($TAv4R[$IP], ipconvert($IP), $URV);
		}
	}
	public function testipconvertIPv6(){
		$URV = self::$URV.'ipconvert().';
		$TAv6 = self::$TAIPv6;
		$TAv6R = self::$TAIPv6cR;
		if( IPv6i() ){ // Can RTL do IPv6 on this install?
			foreach( $TAv6 as $IP ){
				$this->assertEQuals($TAv6R[$IP], ipconvert($IP), $URV);
			}
		}else{
			self::markTestSkipped();
		}
	}
	public function testipdeconvertEmpty(){
		$URV = self::$URV.'ipdeconvert().';
		$this->assertEQuals(0, ipdeconvert(), $URV);
	}
	public function testipdeconvertIPv4(){
		$URV = self::$URV.'ipdeconvert().';
		$TAv4 = self::$TAIPv4;
		$TAv4R = self::$TAIPv4cR;
		$TAv4R = array_flip($TAv4R);
		foreach( $TAv4R as $key => $IP ){
			$this->assertEQuals($IP, ipdeconvert($key), $URV);
		}
	}
	public function testipdeconvertIPv6(){
		$URV = self::$URV.'ipdeconvert().';
		$TAv6 = self::$TAIPv6;
		$TAv6R = self::$TAIPv6dR;
		if( IPv6i() ){ // Can RTL do IPv6 on this install?
			foreach( $TAv6R as $key => $IP ){
				$this->assertEQuals($IP, ipdeconvert($key), $URV);
			}
		}else{
			self::markTestSkipped();
		}
	}
	public function testNMHCEmpty(){
		$URV = self::$URV.'NMHC().';
		$this->assertEQuals(0, NMHC(), $URV);
	}
	public function testNMHCInvalidv6Flag(){
		$URV = self::$URV.'NMHC().';
		$this->assertEQuals(0, NMHC(0,'Test'), $URV);
	}
	public function testNMHCIpv4(){
		$URV = self::$URV.'NMHC().';
		$NMHC = self::$NMHCv4;
		for( $tmp = 0 ; $tmp != 33 ; $tmp++ ){
			$this->assertEQuals($NMHC[$tmp], NMHC($tmp), $URV);
		}
	}
	public function testNMHCIpv6(){
		$URV = self::$URV.'NMHC().';
		$NMHC = self::$NMHCv6;
		if( IPv6i() ){ // Can RTL do IPv6 on this install?
			for( $tmp = 0 ; $tmp != 129 ; $tmp++ ){
				$this->assertEQuals($NMHC[$tmp], NMHC($tmp, true), $URV);
			}
		}else{
			self::markTestSkipped();
		}
	}

	public function testVS2SV_VSH(){ // Version String Hell.
		$URV = self::$URV.'VS2SV().';
		$Chars = array(' ', '.', ',', ':', ';', '-', '_');
		$Prods = array('Prod', 'Prod2');
		$Vers = array('v', 'ver', 'version');
		$tmp = $Vers;
		foreach($Vers as $Val){ // Version transforms
			array_push($tmp, strtoupper($Val)); // Upper
			array_push($tmp, ucfirst($Val)); // Upper First
		}
		$Vers = $tmp;
		$tmp = $Vers;
		foreach($Vers as $Val){ // Version transforms
			foreach($Chars as $CVal){
				array_push($tmp, "$Val$CVal"); // Terminations
			}
		}
		$Vers = $tmp;
		$tmp = array();
		foreach($Prods as $Val){ // Product transforms
			foreach($Chars as $CVal){
				array_push($tmp, "$Val$CVal"); // Terminations
			}
		}
		$Prods = array_merge($tmp, $Vers);
		$x = strval(mt_rand(0, 10000)); // Major
		$y = strval(mt_rand(0, 10000)); // Minor
		$z = strval(mt_rand(0, 10000)); // Rev
		$PV = "$x.$y"; // Partial Minor
		$SV = "$x.$y.$z"; // Semantic Version
		$this->assertEQuals("$x.0.0", implode('.', VS2SV($x)), "$URV $x");
		$this->assertEQuals("$PV.0", implode('.', VS2SV($PV)), "$URV $PV");
		$this->assertEQuals($SV, implode('.', VS2SV($SV)), "$URV $SV");
		foreach($Prods as $Val){ // Products
			$TV1 = "$Val$x";
			$TV2 = "$Val$PV";
			$TV3 = "$Val$SV";
			$this->assertEQuals(
				"$x.0.0", implode('.', VS2SV($TV1)), "$URV ($TV1)->($Val)($x)"
			);
			$this->assertEQuals(
				"$PV.0", implode('.', VS2SV($TV2)), "$URV ($TV2)->($Val)($PV)"
			);
			$this->assertEQuals(
				$SV, implode('.', VS2SV($TV3)), "$URV ($TV3)->($Val)($SV)"
			);
			foreach($Vers as $VVal){ // Version Strings
				$TV1 = "$Val$VVal$x";
				$TV2 = "$Val$VVal$PV";
				$TV3 = "$Val$VVal$SV";
				$this->assertEQuals(
					"$x.0.0", implode('.', VS2SV($TV1)),
					"$URV ($TV1)->($Val)($VVal)($x)"
				);
				$this->assertEQuals(
					"$PV.0", implode('.', VS2SV($TV2)),
					"$URV ($TV2)->($Val)($VVal)($PV)"
				);
				$this->assertEQuals(
					$SV, implode('.', VS2SV($TV3)),
					"$URV ($TV3)->($Val)($VVal)($SV)"
				);
			}
		}
	}
	public function testVS2SVEdgeCase(){
		$URV = self::$URV.'VS2SV().';
		$this->assertEQuals('1.2.0', implode('.', VS2SV('1.2.aa')), $URV);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
