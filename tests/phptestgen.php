<?php

// CM PHPUnit Test Generate (phptestgen)
// Copyright (C) 2019 Nathan Gibbs
//
// For license info: See the file LICENSE
//     Project Lead: Nathan Gibbs
//          Purpose: Generate test sets for multiple PHPUnit Versions.
//                   Using a canonical test set of PHPUnit Test files.
//                   Can generate multiple test sets to be run based on
//                   installed PHP version or a single test set based on
//                   supplied PHPUnit version.
//
//        Author(s): Nathan Gibbs
//
//          History: While working on the BASE project
//                   https://github.com/NathanGibbs3/BASE, specifically
//                   Issue11 https://github.com/NathanGibbs3/BASE/issues/11,
//                   We ended up with test sets for 3 different versions of
//                   PHPUnit. The gaol is to accommodate the PHP/PHPUnit
//                   combinations run by default on travis-ci while building
//                   with all available PHP X.Y versions. At least 90% of the
//                   test set code was identical. What differed were the
//                   details in, Class defines, Namespaced vs. Non-Namespaced;
//                   and test fixture defines, Typehinted vs. Non-Typehinted.
//
//                   Occasionally, builds failed or did not test the same code
//                   on a specific PHPUnit as maintaining the test sets was
//                   done manualy. Local testing in VM's also raised new
//                   issues with PHP/PHPUnit combinations not seen via CI. Our
//                   solution, a tool to build, from a canonical test set, the
//                   test set that PHPUnit actually runs.
//

// Cmd Line Options ( space sepearated ).
// All Locations relative to execution dir.
//	Location of Master Test Set ( defaults to tests/php );
//	Location of Test Build ( defaults to tests/PhpUnit );
//		If option is set to "multi" will search for multiple Test Set
//		Locations formated as tests/phpX.X
//		Dir tests/php5.2 Tests built as Non-Ns Non-Th
//		Dir tests/php7.1 Tests built as Ns Th
//		Anythng else defaults to Ns Non-Th
//	PHPUnit Version ( defaults to 0.0.0 )
//
// Examples:
// php -f ./tests/phptestgen.php
//    Looks for Master test set in ./tests/php
//      Builds PHPUnit test set in ./tests/PhpUnit
// php -f ./tests/phptestgen.php LocA
//    Looks for Master test set in ./tests/LocA
//      Builds PHPUnit test set in ./tests/PhpUnit
// php -f ./tests/phptestgen.php LocA LocB
//    Looks for Master test set in ./tests/LocA
//      Builds PHPUnit test set in ./tests/LocB
// php -f ./tests/phptestgen.php LocA multi
//    Looks for Master test set in ./tests/LocA
//      Builds PHPUnit test set in ./tests/php*.* with customizations.
//
// Any of the above with an additional PHPUnit option given as X.Y.Z will write
// files as specified above with customization.
// When PHPUnit version is <  4.8.28, tests built as Non-Ns Non-Th.
// When PHPUnit vesrion is between, tests built as Ns Non-Th.
// When PHPUnit version is >= 7.0, tests built as Ns Th.


$debug = 0; // Debuging output information level.
$Testing = 0; // Run in testing mode. Do not write to the filse system.

$BD = getcwd ();
if ($debug > 0) {
	print '     Running: '.__FILE__."\n";
	print "  Running in: $BD\n";
	print "With Options:\n";
	print_r($argv);
}

// Set defaults.
$Ver = '0.0.1'; // Release Version
$Mts = 'tests/php'; // Master Test Set
$tsl = array("$BD/tests/PhpUnit"); // Test Set Build List
$PHPUV = '0.0.0'; // PHP Unit Ver.
$Idx = 0;

if (isset($argv[1])) {
	if ( preg_match("/^\d\.\d(\.\d+)?$/", $argv[1])) {
		$Idx = 1;
	}else{
		if (is_dir($argv[1])) {
			$Mts = preg_replace("/^\.\//", '', $argv[1]);
		}else{
			print "     No Test Set: $argv[1]\n";
			print "        Fallback: $Mts\n";
		}
	}
}
$Mts = "$BD/$Mts";
$tfl = testfiles($Mts); // Master Test List.
if (isset($argv[2])) {
	if ($argv[2] == 'multi') { // Multiple Test Set Build
		$tsl = testsets("$BD/tests");
	}else{
		if ( preg_match("/^\d\.\d(\.\d+)?$/", $argv[2])) {
			$Idx = 2;
		}else{
			// Single Test Set Build ( Optionally Customized by next param )
				$tsl = array("$BD/".$argv[2]);
			}
		}
		if ($argc == 4 ) {
			$Idx = 3;
		}
}
if (isset($argv[$Idx])&&preg_match("/^\d\.\d(\.\d+)?$/", $argv[$Idx])) {
	$PHPUV = $argv[$Idx];
}
$PHPV = GetPHPV(); // PHP Version.

print "CM PHPUnit Test Generate $Ver (c) 2019 Nathan Gibbs.\n\n";
print "Master Test Set: $Mts\n";
print "    PHP Version: $PHPV\n";
print "PHPUnit Version: $PHPUV\n";
if ($tfl == NULL) {
	print "No Test Files. Exiting.\n";
	exit;
}elseif ($tsl == NULL) {
	print "No Test Sets. Exiting.\n";
	exit;
}else{
	$fcm = octdec('755');
	$Mtc = preg_replace("/\//","\/",$Mts); // Escape Pathseps.
	if ($debug > 1) {
		print "MTS Regex: $Mtc\n";
	}
	foreach($tsl as $gts){ // Process Test Sets.
		if (preg_match("/^".$Mtc."$/", $gts) ) {
			// Drop Master Test Set.
			print "Skip Master Set: $gts\n";
		}else{
			$TgNs = 1;
			$TgTh = 0;
			print " Build Test Set: $gts\n";
			if ( $PHPUV == '0.0.0' ){
				if(preg_match("/tests\/php5.2/", $gts)) {
					$TgNs = 0;
				}elseif(preg_match("/tests\/php7.1/", $gts)) {
					$TgTh = 1;
				}else{
					$TgNs = 1;
					$TgTh = 0;
				}
			}else{ // PHPUnit Ver Customization
				if (version_compare($PHPUV, '4.8.28', '<')) {
					$TgNs = 0;
				}
				if (version_compare($PHPUV, '7.0', '>=')) {
					$TgTh = 1;
				}
			}
			print "           Opts: (Namespace: $TgNs Typehint: $TgTh)\n";
			if (!is_dir($gts)) {
				if ( $Testing == 0 ) {
					if ($debug > 0) {
						print "Creating test directory: $gts\n";
					}
					mkdir ($gts,$fcm);
				}else{
					print "Would create test directory: $gts\n";
				}
			}
			foreach($tfl as $gtf){ // Process Test files.
				$tfc = file("$Mts/$gtf");
				$dtf = preg_replace( "/\.php/", "", $gtf);
				$gtfn = "$gts/$gtf";
				print "           Test: $dtf\n";
				$ntfc = array();
				if ($TgNs == 0 ) {
					$Act = 'Del';
				}else{
					$Act = 'Add';
				}
				$ntfc = TtNs($Act,$tfc);
				if ( $TgTh == 0 ) {
					$Act = 'Del';
				}else{
					$Act = 'Add';
				}
				$ntfc = TtTh($Act,$ntfc);
				if ( $Testing == 0 ) {
					$tmp = implode('', $ntfc);
					if (!$handle = fopen($gtfn, 'w')) {
						print "Cannot open file ($gtf)";
						exit;
					}
					if (fwrite($handle, $tmp) === FALSE) {
						print "Cannot write to file ($filename)";
						exit;
					}
					fclose($handle);
				}else{
					if ($debug > 2) {
						print_r($tfc);
						print_r($ntfc);
					}
					print "Would write test file: $gtfn\n";
				}
			}
		}
	}
}

// Test Transform Functions
function TtNs ($Action, $lines){ // NameSpace Transforms.
	$tmp = array();
	$Tc = 'TestCase';
	$Tcl = "PHPUnit_Framework_$Tc";
	$UL = 'use '.str_replace( '_', "\\", $Tcl ).';';
	$ULR = str_replace( "\\", "\\\\", $UL );
	$pfx = 'extends ';
	if ($Action == 'Add') {
		$Spt = "/$pfx$Tcl/";
		$Rpt = "$pfx$Tc";
	}elseif ($Action == 'Del') {
		$Spt = "/$pfx$Tc/";
		$Rpt = "$pfx$Tcl";
	}else{
		return -1;
	}
	$lin = preg_replace($Spt,$Rpt,$lines);
	foreach ($lin as $i => $line) { // Process Array
		if( $Action == 'Del' &&
			preg_match( "/^".$ULR."$/", $line )
		) { // Drop use line.
			continue;
		}elseif ( $Action == 'Add' &&
			preg_match( "/^<\?php/", $line ) &&
			in_array($UL, $lin)
		) { // Add use line.
			array_push($tmp,$line);
			$line = $UL;
		}
		array_push($tmp,$line);
	}
	return $tmp;
}
function TtTh ($Action, $lines){ // TypeHint Transforms.
	$tmp = array();
	$Spt = array();
	$Rpt = array();
	$Cs = 'Class';
	$vs = ': void';
	$Tc = '()';
	$RTc = str_replace( '()', '\(\)',$Tc);
	if ($Action == 'Add') {
		$Tc .= $vs;
	}elseif ($Action == 'Del') {
		$RTc .= $vs;
	}else{
		return -1;
	}
	$Cc = "$Cs$Tc";
	$RCc = "$Cs$RTc";
	$Spa = array( 'setUp', 'tearDown' );
	foreach ($Spa as $i => $Item) {
		if ($Item == 'setUp'){
			array_push($Spt,"/$Item$RTc/");
			array_push($Rpt,$Item.$Tc);
			array_push($Spt,"/$Item"."Before$RCc/");
			array_push($Rpt,$Item."Before$Cc");
		}elseif ($Item == 'tearDown'){
			array_push($Spt,"/$Item$RTc/");
			array_push($Rpt,$Item.$Tc);
			array_push($Spt,"/$Item"."After$RCc/");
			array_push($Rpt,$Item."After$Cc");
		}else{
			return -2;
		}
	}
	$tmp = preg_replace($Spt,$Rpt,$lines);
	return $tmp;
}

function testfiles($path) { // Returns array of Test Files
	GLOBAL $debug;
	$ll = array();
	$path .= '/';
	$prefix = "$path*Test.php";
	$files = glob("$prefix");
	if(is_array($files) && !empty($files)){
		if ($debug > 1) {
			print "Found Test Files:\n";
		}
		$path = preg_replace("/\//","\/",$path);
		foreach($files as $match){
			$match = preg_replace( "/$path/", "", $match);
			$ll[]=$match;
			if ($debug > 1) {
				print "\t$match\n";
			}
		}
	}else{
		$ll = NULL;
		if ($debug > 1) {
			print "Found No Test Files.\n";
		}
	}
	return $ll;
}
function testsets($path) { // Returns array of Test Sets.
	GLOBAL $debug;
	$ll = array();
	$path .= '/';
	// Match "$testpath/php#.#
	$prefix = $path."php[0-9].[0-9]";
	$files = glob("$prefix");
	if(is_array($files) && !empty($files)){
		if ($debug > 1) {
			print "Found Test Sets:\n";
		}
		foreach($files as $match){
			$ll[]=$match;
			if ($debug > 1) {
				print "\t$match\n";
			}
		}
	}else{
		$ll = NULL;
		if ($debug > 1) {
			print "Found No Test Sets\n";
		}
	}
	return $ll;
}

// Test Support Functions.
function GetPHPV () { // Get PHP Version
	$current_php_version = phpversion();
	$version = explode(".", $current_php_version);
	// Account for x.x.xXX subversions possibly having text like 4.0.4pl1
	if ( is_numeric(substr($version[2], 1, 1)) ) {
		$version[2] = substr($version[2], 0, 2);
	}else{
		$version[2] = substr($version[2], 0, 1);
	}
	return "$version[0].$version[1].$version[2]";
}
function GetPHPUV () { // Get PHPUnit Version
	if ( method_exists('PHPUnit_Runner_Version','id')) {
		$Ret = PHPUnit_Runner_Version::id();
	}elseif (method_exists('PHPUnit\Runner\Version','id')) {
		$Ret = PHPUnit\Runner\Version::id();
	}else{
		$Ret = 0.0;
	}
	return $Ret;
}
function LogTC ($cf,$Item,$Value) { // Output to Test Console
	GLOBAL $debug;
	if ($debug > 0) {
		print "\n$cf Testing $Item: $Value";
	}
}
?>
