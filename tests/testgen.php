<?php

// Purpose: Generate tests for multiple PHPUnit Versions
// In the Issue11 branch we now have tests for 3 different iterations of
// PHPUnit. 90% or more of the code in each iteration is identical. What
// differs are the details in:
//	Class defines Namespaced/Non-Namespaced.
//	Fixture defines Typehinted/Non-Typehinted.
// This process would be less error prone if automated.

$Mts = 'php'; // Master Test Set.
$debug_mode = 0;

$Mts = "tests/$Mts";
$tfl = testfiles($Mts); // Master Test List.
$tsl = testsuites(); // Test sets to generate.

if ($tfl == NULL) {
	print 'No Test Files. Exiting.';
	exit;
}elseif ($tsl == NULL) {
	print 'No Test Suites. Exiting.';
	exit;
}else{
	print "Master Test Set: $Mts\n";
	$Mtc = preg_replace("/\//","\/",$Mts); // Escape Pathsep.
	foreach($tsl as $gts){ // Process Test Sets.
		if (preg_match("/^".$Mtc."$/", $gts) ) {
			// Drop Master Test Set.
			print "Dropping Master Test Set: $gts\n";
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
				foreach ($tfc as $i => $line) { // Process Test File.
					$skip = 0;
					if(preg_match("/tests\/php5.2/", $gts)) {
						if(preg_match( // Drop use line.
							"/^use PHPUnit\\\Framework\\\TestCase;$/",
							$line
						)) {
							$skip = 1;
						}else{
							$line = preg_replace( // Non-Namespace Test Class.
								"/extends TestCase/",
								"extends PHPUnit_Framework_TestCase",
								$line
							);
						}
					}elseif(preg_match("/tests\/php7.1/", $gts)) {
					}
					if ($skip == 0) {
						array_push($ntfc,$line);
					}
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
					print "Would write test file: $gtfn";
				}
			}
		}
	}
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
?>
