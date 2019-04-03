<?php
use PHPUnit\Framework\TestCase;

// These tests should init the UILang class with a legacy
// TD (Translation Data) file.

// The legacy TD format is constant based, not variable based.
// This makes iterative testing problematic as constants can't be redefined.

// The UILang class is designed to work with either legacy
// TD Files ( _CONSTANSTS ), or new TD Files ( $variables ).

// While switching the BASE code to use UILang and migrating the underlying
// TD format, we want to ensure that UILang can gracefully work with an
// unmodified legecy TD file.

// Test Phase 1
// Will test for specific defines in a legacy format /languages/*.lang.php
// Verifying that all the data for a complete translation is present.
// Does not verify the accuracy of the translation. :-)

// Test Phase 2
// Iterate through the tests in langTest on a single instance of UILang.

/**
 * @preserveGlobalState disabled
 * @runTestsInSeparateProcesses
 */
class legacylangTest extends TestCase {
	// Pre Test Setup.
	var $files;
	var $langs;
	var $UIL;
	protected function setUp(): void {
		GLOBAL $BASE_path, $BASE_installID, $debug_mode;
		$ll = 'legacy-english';
		$this->langs = $ll;
		$lf = "$ll.lang.php";
		$this->files = $lf;
		$file = "$BASE_path/languages/$lf";
		if ($debug_mode > 1) {
			print "\nTesting  file: $file";
		}
		$tmp = "UI$ll";
		copy ("$BASE_path/tests/$lf","$BASE_path/languages/$lf");
		$this->assertInstanceOf(
			'UILang',
			$this->UIL = new UILang($ll),
			"Class for $ll not created."
		);
		unlink ("$BASE_path/languages/$lf");
	}
	// Tests go here.
	// Tests Phase 1.
	public function testLocale() { // Test Locale info.
		// DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1');
		// DEFINE('_LOCALESTR2', 'eng_ENG.utf-8');
		// DEFINE('_LOCALESTR3', 'english');
		// DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //see strftime() sintax
		$this->assertTrue(defined('_LOCALESTR1'),'Locale ISO not defined');
		$this->assertTrue(defined('_LOCALESTR2'),'Locale UTF-8 not defined');
		$this->assertTrue(defined('_LOCALESTR3'),'Locale Language not defined');
		$this->assertTrue(defined('_STRFTIMEFORMAT'),'Locale Time Format not defined');
	}
	public function testCommonPhrases() { // Test common phrases.
		// DEFINE('_CHARSET','iso-8859-1');
		// DEFINE('_TITLE','Basic Analysis and Security Engine (BASE) '.$BASE_installID);
		// DEFINE('_FRMLOGIN','Login:');
		// DEFINE('_FRMPWD','Password:');
		// DEFINE('_SOURCE','Source');
		// DEFINE('_SOURCENAME','Source Name');
		// DEFINE('_DEST','Destination');
		// DEFINE('_DESTNAME','Dest. Name');
		// DEFINE('_SORD','Src or Dest');
		// DEFINE('_EDIT','Edit');
		// DEFINE('_DELETE','Delete');
		// DEFINE('_ID','ID');
		// DEFINE('_NAME','Name');
		// DEFINE('_INTERFACE','Interface');
		// DEFINE('_FILTER','Filter');
		// DEFINE('_DESC','Description');
		// DEFINE('_LOGIN','Login');
		// DEFINE('_ROLEID','Role ID');
		// DEFINE('_ENABLED','Enabled');
		// DEFINE('_SUCCESS','Successful');
		// DEFINE('_SENSOR','Sensor');
		// DEFINE('_SENSORS','Sensors');
		// DEFINE('_SIGNATURE','Signature');
		// DEFINE('_TIMESTAMP','Timestamp');
		// DEFINE('_NBSOURCEADDR','Source&nbsp;Address');
		// DEFINE('_NBDESTADDR','Dest.&nbsp;Address');
		// DEFINE('_NBLAYER4','Layer&nbsp;4&nbsp;Proto');
		// DEFINE('_PRIORITY','Priority');
		// DEFINE('_EVENTTYPE','event type');
		// DEFINE('_JANUARY','January');
		// DEFINE('_FEBRUARY','February');
		// DEFINE('_MARCH','March');
		// DEFINE('_APRIL','April');
		// DEFINE('_MAY','May');
		// DEFINE('_JUNE','June');
		// DEFINE('_JULY','July');
		// DEFINE('_AUGUST','August');
		// DEFINE('_SEPTEMBER','September');
		// DEFINE('_OCTOBER','October');
		// DEFINE('_NOVEMBER','November');
		// DEFINE('_DECEMBER','December');
		// DEFINE('_LAST','Last');
		// DEFINE('_FIRST','First');
		// DEFINE('_TOTAL','Total');
		// DEFINE('_ALERT','Alert');
		// DEFINE('_ADDRESS','Address');
		// DEFINE('_UNKNOWN','unknown');
		// DEFINE('_AND','AND');
		// DEFINE('_OR','OR');
		// DEFINE('_IS','is');
		// DEFINE('_ON','on');
		// DEFINE('_IN','in');
		// DEFINE('_ANY','any');
		// DEFINE('_NONE','none');
		// DEFINE('_HOUR','Hour');
		// DEFINE('_DAY','Day');
		// DEFINE('_MONTH','Month');
		// DEFINE('_YEAR','Year');
		// DEFINE('_ALERTGROUP','Alert Group');
		// DEFINE('_ALERTTIME','Alert Time');
		// DEFINE('_CONTAINS','contains');
		// DEFINE('_DOESNTCONTAIN','does not contain');
		// DEFINE('_SOURCEPORT','source port');
		// DEFINE('_DESTPORT','dest port');
		// DEFINE('_HAS','has');
		// DEFINE('_HASNOT','has not');
		// DEFINE('_PORT','Port');
		// DEFINE('_FLAGS','Flags');
		// DEFINE('_MISC','Misc');
		// DEFINE('_BACK','Back');
		// DEFINE('_DISPYEAR','{ year }');
		// DEFINE('_DISPMONTH','{ month }');
		// DEFINE('_DISPHOUR','{ hour }');
		// DEFINE('_DISPDAY','{ day }');
		// DEFINE('_DISPTIME','{ time }');
		// DEFINE('_ADDADDRESS','ADD Addr');
		// DEFINE('_ADDIPFIELD','ADD IP Field');
		// DEFINE('_ADDTIME','ADD TIME');
		// DEFINE('_ADDTCPPORT','ADD TCP Port');
		// DEFINE('_ADDTCPFIELD','ADD TCP Field');
		// DEFINE('_ADDUDPPORT','ADD UDP Port');
		// DEFINE('_ADDUDPFIELD','ADD UDP Field');
		// DEFINE('_ADDICMPFIELD','ADD ICMP Field');
		// DEFINE('_ADDPAYLOAD','ADD Payload');
		// DEFINE('_MOSTFREQALERTS','Most Frequent Alerts');
		// DEFINE('_MOSTFREQPORTS','Most Frequent Ports');
		// DEFINE('_MOSTFREQADDRS','Most Frequent IP addresses');
		// DEFINE('_LASTALERTS','Last Alerts');
		// DEFINE('_LASTPORTS','Last Ports');
		// DEFINE('_LASTTCP','Last TCP Alerts');
		// DEFINE('_LASTUDP','Last UDP Alerts');
		// DEFINE('_LASTICMP','Last ICMP Alerts');
		// DEFINE('_QUERYDB','Query DB');
		// DEFINE('_QUERYDBP','Query+DB'); //Equals to _QUERYDB where spaces are '+'s. 
		//                //Should be something like: DEFINE('_QUERYDBP',str_replace(" ", "+", _QUERYDB));
		// DEFINE('_SELECTED','Selected');
		// DEFINE('_ALLONSCREEN','ALL on Screen');
		// DEFINE('_ENTIREQUERY','Entire Query');
		// DEFINE('_OPTIONS','Options');
		// DEFINE('_LENGTH','length');
		// DEFINE('_CODE','code');
		// DEFINE('_DATA','data');
		// DEFINE('_TYPE','type');
		// DEFINE('_NEXT','Next');
		// DEFINE('_PREVIOUS','Previous');
		$this->assertTrue(defined('_CHARSET'),'Charset not defined');
		$this->assertTrue(defined('_TITLE'),'Title not defined');
		$this->assertTrue(defined('_FRMLOGIN'),'Login: not defined');
		$this->assertTrue(defined('_FRMPWD'),'Password: not defined');
		$this->assertTrue(defined('_SOURCE'),'Source not defined');
		$this->assertTrue(defined('_SOURCENAME'),'Source Name not defined');
		$this->assertTrue(defined('_DEST'),'Destination not defined');
		$this->assertTrue(defined('_DESTNAME'),'Dest. Name not defined');
		$this->assertTrue(defined('_SORD'),'Src or Dest not defined');
		$this->assertTrue(defined('_EDIT'),'Edit not defined');
		$this->assertTrue(defined('_DELETE'),'Delete not defined');
		$this->assertTrue(defined('_ID'),'ID not defined');
		$this->assertTrue(defined('_NAME'),'Name not defined');
		$this->assertTrue(defined('_INTERFACE'),'Interface not defined');
		$this->assertTrue(defined('_FILTER'),'Filter not defined');
		$this->assertTrue(defined('_DESC'),'Description not defined');
		$this->assertTrue(defined('_LOGIN'),'Login not defined');
		$this->assertTrue(defined('_ROLEID'),'Role ID not defined');
		$this->assertTrue(defined('_ENABLED'),'Enabled not defined');
		$this->assertTrue(defined('_SUCCESS'),'Successful not defined');
		$this->assertTrue(defined('_SENSOR'),'Sensor not defined');
		$this->assertTrue(defined('_SENSORS'),'Sensors not defined');
		$this->assertTrue(defined('_SIGNATURE'),'Signature not defined');
		$this->assertTrue(defined('_TIMESTAMP'),'Timestamp not defined');
		$this->assertTrue(defined('_NBSOURCEADDR'),'Source&nbsp;Address not defined');
		$this->assertTrue(defined('_NBDESTADDR'),'Dest.&nbsp;Address not defined');
		$this->assertTrue(defined('_NBLAYER4'),'Layer&nbsp;4&nbsp;Proto not defined');
		$this->assertTrue(defined('_PRIORITY'),'Priority not defined');
		$this->assertTrue(defined('_EVENTTYPE'),'event type not defined');
		$this->assertTrue(defined('_JANUARY'),'January not defined');
		$this->assertTrue(defined('_FEBRUARY'),'February not defined');
		$this->assertTrue(defined('_MARCH'),'March not defined');
		$this->assertTrue(defined('_APRIL'),'April not defined');
		$this->assertTrue(defined('_MAY'),'May not defined');
		$this->assertTrue(defined('_JUNE'),'June not defined');
		$this->assertTrue(defined('_JULY'),'July not defined');
		$this->assertTrue(defined('_AUGUST'),'August not defined');
		$this->assertTrue(defined('_SEPTEMBER'),'September not defined');
		$this->assertTrue(defined('_OCTOBER'),'October not defined');
		$this->assertTrue(defined('_NOVEMBER'),'November not defined');
		$this->assertTrue(defined('_DECEMBER'),'December not defined');
		$this->assertTrue(defined('_LAST'),'Last not defined');
		$this->assertTrue(defined('_FIRST'),'First not defined');
		$this->assertTrue(defined('_TOTAL'),'Total not defined');
		$this->assertTrue(defined('_ALERT'),'Alert not defined');
		$this->assertTrue(defined('_ADDRESS'),'Address not defined');
		$this->assertTrue(defined('_UNKNOWN'),'unknown not defined');
		$this->assertTrue(defined('_AND'),'AND not defined');
		$this->assertTrue(defined('_OR'),'OR not defined');
		$this->assertTrue(defined('_IS'),'is not defined');
		$this->assertTrue(defined('_ON'),'on not defined');
		$this->assertTrue(defined('_IN'),'in not defined');
		$this->assertTrue(defined('_ANY'),'any not defined');
		$this->assertTrue(defined('_NONE'),'none not defined');
		$this->assertTrue(defined('_HOUR'),'Hour not defined');
		$this->assertTrue(defined('_DAY'),'Day not defined');
		$this->assertTrue(defined('_MONTH'),'Month not defined');
		$this->assertTrue(defined('_YEAR'),'Year not defined');
		$this->assertTrue(defined('_ALERTGROUP'),'Alert Group not defined');
		$this->assertTrue(defined('_ALERTTIME'),'Alert Time not defined');
		$this->assertTrue(defined('_CONTAINS'),'contains not defined');
		$this->assertTrue(defined('_DOESNTCONTAIN'),'does not contain not defined');
		$this->assertTrue(defined('_SOURCEPORT'),'source port not defined');
		$this->assertTrue(defined('_DESTPORT'),'dest port not defined');
		$this->assertTrue(defined('_HAS'),'has not defined');
		$this->assertTrue(defined('_HASNOT'),'has not not defined');
		$this->assertTrue(defined('_PORT'),'Port not defined');
		$this->assertTrue(defined('_FLAGS'),'Flags not defined');
		$this->assertTrue(defined('_MISC'),'Misc not defined');
		$this->assertTrue(defined('_BACK'),'Back not defined');
		$this->assertTrue(defined('_DISPYEAR'),'{ year } not defined');
		$this->assertTrue(defined('_DISPMONTH'),'{ month } not defined');
		$this->assertTrue(defined('_DISPHOUR'),'{ hour } not defined');
		$this->assertTrue(defined('_DISPDAY'),'{ day } not defined');
		$this->assertTrue(defined('_DISPTIME'),'{ time } not defined');
		$this->assertTrue(defined('_ADDADDRESS'),'ADD Addr not defined');
		$this->assertTrue(defined('_ADDIPFIELD'),'ADD IP Field not defined');
		$this->assertTrue(defined('_ADDTIME'),'ADD TIME not defined');
		$this->assertTrue(defined('_ADDTCPPORT'),'ADD TCP Port not defined');
		$this->assertTrue(defined('_ADDTCPFIELD'),'ADD TCP Field not defined');
		$this->assertTrue(defined('_ADDUDPPORT'),'ADD UDP Port not defined');
		$this->assertTrue(defined('_ADDUDPFIELD'),'ADD UDP Field not defined');
		$this->assertTrue(defined('_ADDICMPFIELD'),'ADD ICMP Field not defined');
		$this->assertTrue(defined('_ADDPAYLOAD'),'ADD Payload not defined');
		$this->assertTrue(defined('_MOSTFREQALERTS'),'Most Frequent Alerts not defined');
		$this->assertTrue(defined('_MOSTFREQPORTS'),'Most Frequent Ports not defined');
		$this->assertTrue(defined('_MOSTFREQADDRS'),'Most Frequent IP addresses not defined');
		$this->assertTrue(defined('_LASTALERTS'),'Last Alerts not defined');
		$this->assertTrue(defined('_LASTPORTS'),'Last Ports not defined');
		$this->assertTrue(defined('_LASTTCP'),'Last TCP Alerts not defined');
		$this->assertTrue(defined('_LASTUDP'),'Last UDP Alerts not defined');
		$this->assertTrue(defined('_LASTICMP'),'Last ICMP Alerts not defined');
		$this->assertTrue(defined('_QUERYDB'),'Query DB not defined');
		$this->assertTrue(defined('_QUERYDBP'),'Query+DB not defined');
		$this->assertTrue(defined('_SELECTED'),'Selected not defined');
		$this->assertTrue(defined('_ALLONSCREEN'),'ALL on Screen not defined');
		$this->assertTrue(defined('_ENTIREQUERY'),'Entire Query not defined');
		$this->assertTrue(defined('_OPTIONS'),'Options not defined');
		$this->assertTrue(defined('_LENGTH'),'length not defined');
		$this->assertTrue(defined('_CODE'),'code not defined');
		$this->assertTrue(defined('_DATA'),'data not defined');
		$this->assertTrue(defined('_TYPE'),'type not defined');
		$this->assertTrue(defined('_NEXT'),'Next not defined');
		$this->assertTrue(defined('_PREVIOUS'),'Previous not defined');
	}
	public function testMenuItems() { // Test Menu items
		// DEFINE('_HOME','Home');
		// DEFINE('_SEARCH','Search');
		// DEFINE('_AGMAINT','Alert Group Maintenance');
		// DEFINE('_USERPREF','User Preferences');
		// DEFINE('_CACHE','Cache & Status');
		// DEFINE('_ADMIN','Administration');
		// DEFINE('_GALERTD','Graph Alert Data');
		// DEFINE('_GALERTDT','Graph Alert Detection Time');
		// DEFINE('_USERMAN','User Management');
		// DEFINE('_LISTU','List users');
		// DEFINE('_CREATEU','Create a user');
		// DEFINE('_ROLEMAN','Role Management');
		// DEFINE('_LISTR','List Roles');
		// DEFINE('_CREATER','Create a Role');
		// DEFINE('_LISTALL','List All');
		// DEFINE('_CREATE','Create');
		// DEFINE('_VIEW','View');
		// DEFINE('_CLEAR','Clear');
		// DEFINE('_LISTGROUPS','List Groups');
		// DEFINE('_CREATEGROUPS','Create Group');
		// DEFINE('_VIEWGROUPS','View Group');
		// DEFINE('_EDITGROUPS','Edit Group');
		// DEFINE('_DELETEGROUPS','Delete Group');
		// DEFINE('_CLEARGROUPS','Clear Group');
		// DEFINE('_CHNGPWD','Change password');
		// DEFINE('_DISPLAYU','Display user');
		$this->assertTrue(defined('_HOME'),'Home not defined');
		$this->assertTrue(defined('_SEARCH'),'Search not defined');
		$this->assertTrue(defined('_AGMAINT'),'Alert Group Maintenance not defined');
		$this->assertTrue(defined('_USERPREF'),'User Preferences not defined');
		$this->assertTrue(defined('_CACHE'),'Cache & Status not defined');
		$this->assertTrue(defined('_ADMIN'),'Administration not defined');
		$this->assertTrue(defined('_GALERTD'),'Graph Alert Data not defined');
		$this->assertTrue(defined('_GALERTDT'),'Graph Alert Detection Time not defined');
		$this->assertTrue(defined('_USERMAN'),'User Management not defined');
		$this->assertTrue(defined('_LISTU'),'List users not defined');
		$this->assertTrue(defined('_CREATEU'),'Create a user not defined');
		$this->assertTrue(defined('_ROLEMAN'),'Role Management not defined');
		$this->assertTrue(defined('_LISTR'),'List Roles not defined');
		$this->assertTrue(defined('_CREATER'),'Create a Role not defined');
		$this->assertTrue(defined('_LISTALL'),'List All not defined');
		$this->assertTrue(defined('_CREATE'),'Create not defined');
		$this->assertTrue(defined('_VIEW'),'View not defined');
		$this->assertTrue(defined('_CLEAR'),'Clear not defined');
		$this->assertTrue(defined('_LISTGROUPS'),'List Groups not defined');
		$this->assertTrue(defined('_CREATEGROUPS'),'Create Group not defined');
		$this->assertTrue(defined('_VIEWGROUPS'),'View Group not defined');
		$this->assertTrue(defined('_EDITGROUPS'),'Edit Group not defined');
		$this->assertTrue(defined('_DELETEGROUPS'),'Delete Group not defined');
		$this->assertTrue(defined('_CLEARGROUPS'),'Clear Group not defined');
		$this->assertTrue(defined('_CHNGPWD'),'Change password not defined');
		$this->assertTrue(defined('_DISPLAYU'),'Display user not defined');
	}
	public function testOneLiners() { // TD File sections with one line data.
		//base_footer.php
			// DEFINE('_FOOTER',' (by <A class="largemenuitem" href="mailto:base@secureideas.net">Kevin Johnson</A> and the <A class="largemenuitem" href="http://sourceforge.net/project/memberlist.php?group_id=103348">BASE Project Team</A><BR>Built on ACID by Roman Danyliw )');
			$this->assertTrue(defined('_FOOTER'),'Footer Text not defined.');
		//index.php --Log in Page
			// DEFINE('_LOGINERROR','User does not exist or your password was incorrect!<br>Please try again');
			$this->assertTrue(defined('_LOGINERROR'),'Login Error Text not defined.');
	}
	public function testTDforfilebase_main_php() { // Test base_main.php
	}

	// Tests Phase 2.
	public function testSetUILocale() {
		GLOBAL $debug_mode;
		$lang = $this->langs;
		$tmp = "UI$lang";
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing language: $lang";
		}
		$$tmp = $this->UIL;
		// Will not run until TD is transitioned.
		$file = $$tmp->TDF;
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing TD file: $file";
		}
		// Test Locale
		if (is_null($$tmp->Locale) ) {
			if ( !$$tmp->SetUILocale() ){
				$this->markTestSkipped(
					'Locale not implemented or locale(s) do not exist.'
				);
			}
		}else{
			$this->assertTrue(
				is_array($$tmp->Locale),
				"Locales not defined in $file."
			);
		}
		$this->assertNotNull($$tmp->Locale, 'Locale Not Set');
	}
	public function testSetUITimefmt() {
		GLOBAL $debug_mode;
		$lang = $this->langs;
		$tmp = "UI$lang";
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing language: $lang";
		}
		$$tmp = $this->UIL;
		// Will not run until TD is transitioned.
		$file = $$tmp->TDF;
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing TD file: $file";
		}
		$this->assertTrue(
			isset($$tmp->Timefmt),
			"Time Format not set in $file"
		);
	}
	public function testSetUICharset() {
		GLOBAL $debug_mode;
		$lang = $this->langs;
		$tmp = "UI$lang";
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing language: $lang";
		}
		$$tmp = $this->UIL;
		// Will not run until TD is transitioned.
		$file = $$tmp->TDF;
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing TD file: $file";
		}
		$this->assertTrue(
			isset($$tmp->Charset),
			"HTML Charset not set in $file"
		);
	}
	public function testSetUITitle() {
		GLOBAL $debug_mode;
		$lang = $this->langs;
		$tmp = "UI$lang";
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing language: $lang";
		}
		$$tmp = $this->UIL;
		// Will not run until TD is transitioned.
		$file = $$tmp->TDF;
		if ($debug_mode > 0) {
			print "\n" . __FUNCTION__ . " Testing TD file: $file";
		}
		$this->assertTrue(
			isset($$tmp->Title),
			"HTML Title not set in $file"
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}
?>
