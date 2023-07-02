<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in includes/base_state_criteria.inc.php

/**
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  * @covers ::PrintBackButton
  * @covers CriteriaState
  * @uses BaseCriteria
  * @uses IPAddressCriteria
  * @uses MultipleElementCriteria
  * @uses SignatureCriteria
  */

class state_criteriaSPTest extends TestCase {
	// Pre Test Setup.
	protected static $HTT;
	protected static $UIL;
	protected static $URV;
	protected static $db;
	protected static $files;
	protected static $langs;
	protected static $omh;
	protected static $opv;
	protected static $osv;
	protected static $tc;

	public static function setUpBeforeClass() {
		GLOBAL $BASE_path, $DBlib_path, $DBtype, $debug_mode, $alert_dbname,
			$alert_host, $alert_user, $alert_password, $alert_port,
			$db_connect_method, $db, $maintain_history;
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
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
		if ( class_exists('UILang') ){
			// Setup UI Language Object
			// Will throw error during TD transition.
			// Use error suppression @ symbol.
			self::assertInstanceOf('UILang',self::$UIL = @new UILang($ll),
				"Class for $ll not created."
			);
		}else{
			self::$files = $file;
		}
		// Setup DB System.
		$TRAVIS = getenv('TRAVIS');
		if( !$TRAVIS ){ // Running on Local Test System.
			// Default Debian/Ubuntu location.
			$DBlib_path = '/usr/share/php/adodb';
			$DB = 'mysql';
		}else{
			$ADO = getenv('ADODBPATH');
			if( !$ADO ){
				self::markTestIncomplete('Unable to setup ADODB');
			}else{
				$DBlib_path = "build/adodb/$ADO";
			}
			$DB = getenv('DB');
		}
		if( !$DB ){
			self::markTestIncomplete('Unable to get DB Engine.');
		}elseif( $DB == 'mysql' ){
			require('./tests/phpcommon/DB.mysql.RI.php');
		}elseif( $DB == 'postgres' ){
			require('./tests/phpcommon/DB.pgsql.php');
		}elseif( $DB == 'mssql' ){
			require('./tests/phpcommon/DB.mssql.php');
		}else{
			self::markTestSkipped("CI Support unavialable for DB: $DB.");
		}
		if (!isset($DBtype)){
			self::markTestIncomplete("Unable to Set DB: $DB.");
		}else{ // Setup DB Connection
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
				'baseCon', $db, 'DB Object Not Initialized.'
			);
			self::$db = $db;
		}
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
		unset($_SERVER['QUERY_STRING']);
		self::$URV = 'Unexpected Return Value: ';
		self::$HTT['sig_type'] = '';
		self::$HTT['sig_class'] = '';
		self::$HTT['ag'] = '';
		self::$HTT['sensor'] = '';
		self::$HTT['time'] = null;
		self::$HTT['time_cnt'] = 0;
		self::$HTT['ip_addr'] = null;
		self::$HTT['ip_addr_cnt'] = 0;
		self::$HTT['layer4'] = '';
		self::$HTT['ip_field'] = null;
		self::$HTT['ip_field_cnt'] = 0;
		self::$HTT['tcp_port'] = null;
		self::$HTT['tcp_port_cnt'] = 0;
		self::$HTT['tcp_field'] = null;
		self::$HTT['tcp_field_cnt'] = 0;
		self::$HTT['udp_port'] = null;
		self::$HTT['udp_port_cnt'] = 0;
		self::$HTT['udp_field'] = null;
		self::$HTT['udp_field_cnt'] = 0;
		self::$HTT['icmp_field'] = null;
		self::$HTT['icmp_field_cnt'] = 0;
		self::$HTT['rawip_field'] = null;
		self::$HTT['rawip_field_cnt'] = 0;
		self::$HTT['data'] = null;
		self::$HTT['data_cnt'] = 0;
		self::$HTT['data_encode'] = array ('', '');
	}
	public static function tearDownAfterClass() {
		GLOBAL $maintain_history;
		$maintain_history = self::$omh;
		$_POST = self::$opv;
		$_SERVER = self::$osv;
		self::$HTT = null;
		self::$UIL = null;
		self::$URV = null;
		self::$db = null;
		self::$files = null;
		self::$langs = null;
		self::$omh = null;
		self::$opv = null;
		self::$osv = null;
		self::$tc = null;
	}

	// Tests go here.
	public function testClassCriteriaStateConstruct(){
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$URV = self::$URV.'Construct().';
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
		$TSC = array( // Test SubCriteria
			'sig' => 'Signature',
			'sig_class' => 'SignatureClassification',
			'sig_priority' => 'SignaturePriority',
			'ag' => 'AlertGroup',
			'sensor' => 'Sensor',
			'time' => 'Time',
			'ip_addr' => 'IPAddress',
			'layer4' => 'Layer4',
			'ip_field' => 'IPField',
			'tcp_port' => 'TCPPort',
			'tcp_flags' => 'TCPFlags',
			'tcp_field' => 'TCPField',
			'udp_port' => 'UDPPort',
			'udp_field' => 'UDPField',
			'icmp_field' => 'ICMPField',
			'rawip_field' => 'TCPField',
			'data' => 'Data',
		);
		foreach( $TSC as $key => $val ){
			$tmp = $val . 'Criteria';
			$this->assertInstanceOf($tmp, $tc->criteria[$key], $CNI);
		}
	}
	public function testGetBackLink() {
		$URV = self::$URV.'GetBackLink().';
		$CNI = 'Class Not Initialized.';
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
		$this->assertEquals(
			"<a class='menuitem' href=\"".$_SERVER["SCRIPT_NAME"].
			'?back=1&">Back</a>',
			$tc->GetBackLink(), $URV
		);
	}
	public function testPrintBackButtonOK() {
		$URV = self::$URV.'PrintBackButton().';
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$this->assertEquals(
			"<a class='menuitem' href=\"".$_SERVER["SCRIPT_NAME"].
			'?back=1&">Back</a>',
			PrintBackButton(), $URV
		);
	}

	public function testReadStateNotAcIt(){
		$URV = self::$URV.'ReadState().';
		$HTT = self::$HTT;
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
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
		$HTP['back_list'][3]['session'] = 'sig_type|s:0:"";sig_class|s:0:"";'
		. 'ag|s:0:"";sensor|s:0:"";time|N;time_cnt|i:0;ip_addr|N;'
		. 'ip_addr_cnt|i:0;layer4|s:0:"";ip_field|N;ip_field_cnt|i:0;'
		. 'tcp_port|N;tcp_port_cnt|i:0;tcp_field|N;tcp_field_cnt|i:0;'
		. 'udp_port|N;udp_port_cnt|i:0;udp_field|N;udp_field_cnt|i:0;'
		. 'icmp_field|N;icmp_field_cnt|i:0;rawip_field|N;rawip_field_cnt|i:0;'
		. 'data|N;data_cnt|i:0;data_encode|a:2:{i:0;s:0:"";i:1;s:0:"";}';
		$HTP['back_list_cnt'] = 3;
		$HTP = array_merge($HTP, $HTT);
		$_SERVER['QUERY_STRING'] = '&amp;middle=earth';
		$tc->ReadState(); // Test Push.
		$this->assertEquals(3, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['caller']);
		$_SESSION = $osession;
	}

	public function testReadStateAcItSel(){
		$URV = self::$URV.'ReadState().';
		$HTT = self::$HTT;
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
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
		$_POST['submit'] = _SELECTED;
		$tc->ReadState(); // Test Push.
		$HTP = array_merge($HTP, $HTT);
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['submit']);
		$_SESSION = $osession;
	}
	public function testReadStateAcItScreen(){
		$URV = self::$URV.'ReadState().';
		$HTT = self::$HTT;
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
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
		$_POST['submit'] = _ALLONSCREEN;
		$tc->ReadState(); // Test Push.
		$HTP = array_merge($HTP, $HTT);
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['submit']);
		$_SESSION = $osession;
	}
	public function testReadStateAcItQry(){
		$URV = self::$URV.'ReadState().';
		$HTT = self::$HTT;
		GLOBAL $UIL;
		if ( is_object(self::$UIL) ){
			$UIL = self::$UIL;
		}else{
			GLOBAL $BASE_installID;
			include_once(self::$files);
		}
		$CNI = 'Class Not Initialized.';
		$this->assertInstanceOf( // Create Criteria State Object.
			'CriteriaState', $tc = new CriteriaState('Unit_Test'), $CNI
		);
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
		$_POST['submit'] = _ENTIREQUERY;
		$tc->ReadState(); // Test Push.
		$HTP = array_merge($HTP, $HTT);
		$this->assertEquals(2, $_SESSION['back_list_cnt'], $URV);
		$this->assertEquals($HTP, $_SESSION, $URV);
		unset($_SERVER['QUERY_STRING']);
		unset($_POST['submit']);
		$_SESSION = $osession;
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
