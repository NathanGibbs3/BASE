<?php

// Purpose: Generate tests for multiple PHPUnit Versions
// In the Issue11 branch we now have tests for 3 different iterations of
// PHPUnit. 90% or more of the code in each iteration is identical. What
// differs are the details in:
//	Class defines Namespaced/Non-Namespaced.
//	Fixture defines Typehinted/Non-Typehinted.
// This process would be less error prone if automated.

$Mts = 'php5.3'; // Master Test Set.
$debug_mode = 0;

$Mts = "tests/$Mts";
$tfl = testfiles($Mts); // Master Test List.
$tsl = testsuites(); // Test sets to generate.
$PHPV = GetPHPV(); // PHP Version.

if ($tfl == NULL) {
	print "No Test Files. Exiting.\n";
	exit;
}elseif ($tsl == NULL) {
	print "No Test Suites. Exiting.\n";
	exit;
}else{
	print "Master Test Set: $Mts\n";
	$Mtc = preg_replace("/\//","\/",$Mts); // Escape Pathsep.
	foreach($tsl as $gts){ // Process Test Sets.
		if (preg_match("/^".$Mtc."$/", $gts) ) {
			// Drop Master Test Set.
			print "Skip Master Test Set: $gts\n";
		}else{
			$tfwflag = 0;
			$dts = preg_replace( "/tests\//", "", $gts);
			print " Build Test Set: $dts\n";
			print "\tOpts: (Namespace: ";
			if(preg_match("/tests\/php5.2/", $gts)) {
				print "No Typehint: No";
				$tfwflag = 1;
			}elseif(preg_match("/tests\/php7.1/", $gts)) {
				print "Yes Typehint: Yes";
				$tfwflag = 1;
			}else{
				print "Yes Typehint: No";
			}
			print ")\n";
			foreach($tfl as $gtf){ // Process Test files.
				$tfc = file("$Mts/$gtf");
				$dtf = preg_replace( "/\.php/", "", $gtf);
				$gtfn = "$gts/$gtf";
				print "\tTest: $dtf\n";
				$ntfc = array();
				if(preg_match("/tests\/php5.2/", $gts)) {
					$ntfc = TtNs('Del',$tfc);
				}elseif(preg_match("/tests\/php7.1/", $gts)) {
					$ntfc = TtTh('Add',$tfc);
				}
				if ( $tfwflag == 1 ) {
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
					print_r($tfc);
					print_r($ntfc);
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
			preg_match( "/^<\?php/", $line )
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

function testfiles($path) { // Returns array of testfiles
	GLOBAL $debug_mode;
	$ll = array();
	$path .= '/';
	$prefix = "$path*Test.php";
	$files = glob("$prefix");
	if(is_array($files) && !empty($files)){
		if ($debug_mode > 1) {
			print "\nThe following testfiles were found:";
		}
		$path = preg_replace("/\//","\/",$path);
		foreach($files as $match){
			$match = preg_replace( "/$path/", "", $match);
			$ll[]=$match;
			if ($debug_mode > 1) {
				print "\n$match";
			}
		}
	}else{
		$ll = NULL;
		if ($debug_mode > 1) {
			print "\nEmpty testsfile List";
		}
	}
	return $ll;
}

function testsuites() { // Returns array of testsuites.
	GLOBAL $debug_mode;
	$ll = array();
	// Match "tests/php#.#
	$prefix = "tests/php*[0-9].*[0-9]";
	$files = glob("$prefix");
	if(is_array($files) && !empty($files)){
		if ($debug_mode > 1) {
			print "\nThe following testsuites were found:";
		}
		foreach($files as $match){
			$ll[]=$match;
			if ($debug_mode > 1) {
				print "\n$match";
			}
		}
	}else{
		$ll = NULL;
		if ($debug_mode > 1) {
			print "\nEmpty testsuite List";
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
	GLOBAL $debug_mode;
	if ($debug_mode > 0) {
		print "\n$cf Testing $Item: $Value";
	}
}
?>
