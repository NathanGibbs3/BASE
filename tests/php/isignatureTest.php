<?php
use PHPUnit\Framework\TestCase;

// Test fucntions in /includes/base_signature.inc.php

/**
  * @covers ::BuildSigLookup
  * @covers ::check_string
  */
class signatureTest extends TestCase {

public static function setUpBeforeClass() {
	GLOBAL $external_sig_link, $debug_mode;
	$external_sig_link = array(
		'bugtraq'   => array('http://www.securityfocus.com/bid/', ''),
		'snort'     => array('http://www.snort.org/search/sid/', ''),
		'cve'       => array('http://cve.mitre.org/cgi-bin/cvename.cgi?name=', ''),
		'arachnids' => array('http://www.whitehats.com/info/ids', ''),
		'mcafee'    => array('http://vil.nai.com/vil/content/v_', '.htm'),
		'icat'      => array('http://icat.nist.gov/icat.cfm?cvename=CAN-', ''),
		'nessus'    => array('http://www.nessus.org/plugins/index.php?view=single&amp;id=', ''),
		'url'       => array('http://', ''),
		'local'     => array('signatures/', '.txt'),
		'local_rules_dir' => array('rules/', '.rules'),
		'EmThreats' => array('http://docs.emergingthreats.net/', '')
	);
	$dubug_mode = 0;
}
public static function tearDownAfterClass() {
	GLOBAL $external_sig_link, $debug_mode;
	unset($external_sig_link);
	unset($debug_mode);
}

	// Tests go here.
	public function testcheck_stringNotSet() {
		$this->assertEquals(
			0,
			check_string(null),
			'Unexpected return check_string().'
		);
	}
	public function testcheck_stringEmpty() {
		$this->assertEquals(
			0,
			check_string(''),
			'Unexpected return check_string().'
		);
	}
	public function testcheck_stringNotString() {
		$this->assertEquals(
			0,
			check_string(1),
			'Unexpected return check_string().'
		);
	}
	public function testcheck_stringIsString() {
		$this->assertEquals(
			1,
			check_string('1'),
			'Unexpected return check_string().'
		);
	}

	public function testBuildSigLookupNotSet() {
		$this->assertEquals(
			'',
			BuildSigLookup(null),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupEmpty() {
		$this->assertEquals(
			'',
			BuildSigLookup(''),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupNotString() {
		$this->assertEquals(
			'',
			BuildSigLookup(1),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnText() {
		$this->assertEquals(
			'signature',
			BuildSigLookup('signature',2),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnArachnids() {
		$this->assertEquals(
			'signature <A HREF="http://www.whitehats.com/IDS/55" TARGET="_ACID_ALERT_DESC">IDS55</A>',
			BuildSigLookup('signature IDS55'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnArachnidsLZSigURLShim() {
		$this->assertEquals(
			'signature <A HREF="http://www.whitehats.com/IDS/55" TARGET="_ACID_ALERT_DESC">IDS055</A>',
			BuildSigLookup('signature IDS055'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnBugtraq() {
		$this->assertEquals(
			'signature <A HREF="http://www.securityfocus.com/bid/55" TARGET="_ACID_ALERT_DESC">BUGTRAQ ID 55</A>',
			BuildSigLookup('signature BUGTRAQ ID 55'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnMcAfee() {
		$this->assertEquals(
			'signature <A HREF="http://vil.nai.com/vil/content/v_55" TARGET="_ACID_ALERT_DESC">MCAFEE ID 55</A>',
			BuildSigLookup('signature MCAFEE ID 55'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnCVE() {
		$this->assertEquals(
			'signature <A HREF="http://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-55-55" TARGET="_ACID_ALERT_DESC">CVE-55-55</A>',
			BuildSigLookup('signature CVE-55-55'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupReturnALL() {
		$this->assertEquals(
			'signature'.
			' <A HREF="http://www.whitehats.com/IDS/55" TARGET="_ACID_ALERT_DESC">IDS55</A>'.
			' <A HREF="http://www.whitehats.com/IDS/55" TARGET="_ACID_ALERT_DESC">IDS055</A>'.
			' <A HREF="http://www.securityfocus.com/bid/55" TARGET="_ACID_ALERT_DESC">BUGTRAQ ID 55</A>'.
			' <A HREF="http://vil.nai.com/vil/content/v_55" TARGET="_ACID_ALERT_DESC">MCAFEE ID 55</A>'.
			' <A HREF="http://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-55-55" TARGET="_ACID_ALERT_DESC">CVE-55-55</A>',
			BuildSigLookup('signature IDS55 IDS055 BUGTRAQ ID 55 MCAFEE ID 55 CVE-55-55'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanDefault() {
		$this->assertEquals(
			'signature spp_portscan',
			BuildSigLookup('signature spp_portscan'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanStatus() {
		$this->assertEquals(
			'signature spp_portscan',
			BuildSigLookup('signature spp_portscan: portscan status'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanDetected() {
		$this->assertEquals(
			'signature spp_portscan detected',
			BuildSigLookup('signature spp_portscan: PORTSCAN DETECTED'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanIPv4() {
		$this->assertEquals(
			'signature spp_portscan <A HREF="base_stat_ipaddr.php?ip=192.168.0.0&amp;netmask=32">192.168.0.0</A>',
			BuildSigLookup('signature spp_portscan 192.168.0.0'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanStatusIPv4() {
		$this->assertEquals(
			'signature spp_portscan '.
			'<A HREF="base_stat_ipaddr.php?ip=192.168.0.0&amp;netmask=32">192.168.0.0</A>',
			BuildSigLookup('signature spp_portscan: portscan status 192.168.0.0'),
			'Unexpected return BuildSigLookup().'
		);
	}
	public function testBuildSigLookupPortscanDetectedIPv4() {
		$this->assertEquals(
			'signature spp_portscan detected '.
			'<A HREF="base_stat_ipaddr.php?ip=192.168.0.0&amp;netmask=32">192.168.0.0</A>',
			BuildSigLookup('signature spp_portscan: PORTSCAN DETECTED 192.168.0.0'),
			'Unexpected return BuildSigLookup().'
		);
	}

	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete('Incomplete Test.');
}

?>
