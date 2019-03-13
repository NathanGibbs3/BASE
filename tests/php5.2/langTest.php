<?php

// Will test for specific defines in /languages/*.lang.php
// Verify that all the data for a complete translation is present.
// Does not verify the accuracy of the translation. :-)

class langTest extends PHPUnit_Framework_TestCase {
	// Pre Test Setup.
	var $files;
	var $lf;
	// Tests go here.
	public function testAll() {
		$files = langfiles();
		if(is_array($files) && !empty($files)){
			foreach($files as $match){
				$this->lf = $match;
				$Tobj = new Lto($this);
				$Tobj->Test();
				unset($Tobj);
				// Bail out here as the language files are constant based
				// instead if variable based. Can't do constant redefines.
				// Another Issue to fix, but at least we have the test
				// foundation in place. :-)
				return;
			}
		}else{
			$this->fail('Empty Test Set');
		}
	}
	// Add code to a function if needed.
	// Stop here and mark test incomplete.
	//$this->markTestIncomplete(
	//	'Incomplete Test.'
	//);
}

class Lto { // Lang Test Object.
	var $obj;
	var $tobj;
	function __construct ($Tobj) {
		GLOBAL $BASE_path, $BASE_installID, $debug_mode;
		$file = $Tobj->lf;
		$this->obj = $file;
		$this->tobj = $Tobj;
		if ($debug_mode > 0) {
			print "Testing  file: $BASE_path/languages/$file\n";
		}
		include_once("$BASE_path/languages/$file");
	}
	public function Test() {
		// Test Locale
		tLocale($this->tobj);
		// Add more tests here.
	}
}

// Individual Section Tests.
function tLocale($obj) {
	//locale
	// DEFINE('_LOCALESTR1', 'eng_ENG.ISO8859-1');
	// DEFINE('_LOCALESTR2', 'eng_ENG.utf-8');
	// DEFINE('_LOCALESTR3', 'english');
	// DEFINE('_STRFTIMEFORMAT','%a %B %d, %Y %H:%M:%S'); //see strftime() sintax
	$obj->assertTrue(defined('_LOCALESTR1'),'Locale ISO not defined');
	$obj->assertTrue(defined('_LOCALESTR2'),'Locale UTF-8 not defined');
	$obj->assertTrue(defined('_LOCALESTR3'),'Language not defined');
	$obj->assertTrue(defined('_STRFTIMEFORMAT'),'Time Format not defined');
}

function langfiles() { // Returns array of lang files.
	GLOBAL $BASE_path, $debug_mode;
	$testfiles = array();
	$prefix = "$BASE_path/languages/*.lang.php";
	$files = glob("$prefix");
	if(is_array($files) && !empty($files)){
		if ($debug_mode > 0) {
			print "Will test the following files:\n";
		}
		foreach($files as $match){
			$bpt= preg_replace("/\//","\/",$BASE_path);
			$match = preg_replace( "/$bpt\/languages\//", "", $match);
			$testfiles[]=$match;
			if ($debug_mode > 0) {
				print "$match\n";
			}
		}
	}else{
		if ($debug_mode > 0) {
			print "Empty Test Set\n";
		}
	}
	return $testfiles;
}

?>
