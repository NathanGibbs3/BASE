<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in: base_conf.php & base_conf.php.dist

/**
  * Code Coverage Directives.
  * A necessary evil for tests touching legacy TD.
  * @preserveGlobalState disabled
  * @runTestsInSeparateProcesses
  */

class ConfTest extends TestCase {
	// Pre Test Setup.
	protected static $files;
	protected static $cvars;
	protected static $UOV;
	protected static $URV;

	// Share class instance as common test fixture.
	public static function setUpBeforeClass() {
		// Issue #36 Cutout.
		// See: https://github.com/NathanGibbs3/BASE/issues/36
		$PHPV = GetPHPV();
		$PSM = getenv('SafeMode');
		if (version_compare($PHPV, '5.4', '<') && $PSM == 1){
			self::markTestSkipped();
		}
		self::$files = array ('base_conf.php', 'base_conf.php.dist');
		self::$cvars = array(
			'Use_Auth_System', 'BASE_urlpath', 'BASE_Language',
			'BASE_display_sig_links', 'BASE_installID', 'base_custom_footer',
			'DBlib_path', 'DBtype', 'alert_dbname', 'alert_host', 'alert_port',
			'alert_user', 'alert_password', 'archive_exists', 'archive_dbname',
			'archive_host', 'archive_port', 'archive_user', 'archive_password',
			'db_connect_method', 'use_referential_integrity',
			'action_email_smtp_host', 'action_email_smtp_localhost',
			'action_email_smtp_auth', 'action_email_smtp_user',
			'action_email_smtp_pw', 'action_email_from',
			'action_email_subject', 'action_email_msg', 'action_email_mode',
			'base_style', 'show_rows', 'last_num_alerts', 'last_num_ualerts',
			'last_num_uports', 'last_num_uaddr', 'freq_num_alerts',
			'freq_num_uaddr', 'freq_num_uports', 'max_scroll_buttons',
			'debug_mode', 'debug_time_mode', 'html_no_cache', 'sql_trace_mode',
			'sql_trace_file', 'refresh_stat_page', 'refresh_all_pages',
			'stat_page_refresh_time', 'show_previous_alert',
			'max_script_runtime', 'ip_address_input', 'use_sig_list',
			'resolve_IP', 'show_expanded_query', 'show_summary_stats',
			'dns_cache_lifetime', 'whois_cache_lifetime', 'portscan_file',
			'portscan_payload_in_signature', 'event_cache_auto_update',
			'maintain_history', 'main_page_detail', 'avoid_counts',
			'show_first_last_links', 'external_whois_link',
			'external_dns_link', 'external_all_link', 'external_port_link',
			'external_sig_link', 'use_user_session', 'user_session_path',
			'user_session_function', 'colored_alerts', 'graph_font_name',
		);
		self::$UOV = 'Unexpected Output Value: ';
		self::$URV = 'Unexpected Return Value: ';
	}
	public static function tearDownAfterClass() {
		self::$files = null;
		self::$cvars = null;
		self::$URV = null;
		self::$UOV = null;
	}

	// Tests go here.
	public function testConfVals() {
		GLOBAL $BASE_path;
		$sc = DIRECTORY_SEPARATOR;
		$files = self::$files;
		$cvars = self::$cvars;
		foreach($files as $file){
			if ( ChkAccess($file) == 1 && filesize($file) > 10 ){
				$URV = self::$URV . "$$file.";
				include($file);
				foreach($cvars as $var){
					$this->assertTrue( isset($$var), $URV);
					if( $var == 'BASE_Language' ){
						$tmp = "$BASE_path$sc" . "languages$sc" . $$var
						. '.lang.php';
						$this->assertEquals(
							1, ChkAccess($tmp),
							$URV . " Lang: $$var = $BASE_Language "
							. "TD File: $tmp"
						);
					}elseif( $var == 'BASE_urlpath' ){
						$ReqRE = 'http(s)?' . preg_quote('://','/')
						. '[0-9A-Za-z\.\-]+(\:[0-9]+)?';
						$this->assertEquals(
							$$var,
							preg_replace('/^' . $ReqRE . '/', '', $$var),
							$URV . "URL Path: $$var"
						);
					}
				}
			}
		}
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
