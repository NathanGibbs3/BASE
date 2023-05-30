<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2023 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Check the release data in the source code against the
//                     latest merge to the master branch of the project
//                     repo. In the event that we merge to master without
//                     updating the source, this program will exit with a
//                     posix error code. Putting this early in the build chain
//                     allows us to fail the build immediately.
//
//          Author(s): Nathan Gibbs

$sc = DIRECTORY_SEPARATOR;
$tmp = dirname(__FILE__);
$ReqRE = preg_quote($sc.'phpcommon', '/').'.*';
$tmp = preg_replace('/'.$ReqRE.'/', '', $tmp);
require_once("$tmp$sc" . "phpcommon$sc" . 'base_TEkrnl.php');

$BASE_installID = 'GH API Check';
$tcc = new BaseCapsRegistry();
$Lv = $tcc->GetCap('PHP_Ver');
$Av = $tcc->GetCap('BASE_Ver');

$ExitCode = 1; // Assume failure.
$User = 'NathanGibbs3';
$Repo = 'BASE';
$tmp = '';

print " PHP $Lv\n";
print "BASE $Av\n";

if ( $debug_mode == 0 ){ // GH API Search Endpoints.
	$api = 'https://api.github.com/search/commits?q=';
}elseif( $debug_mode == 1 ){
	$api = 'https://api.github.com/repos/NathanGibbs3/BASE/branches/devel?q=';
}
// From GitHub docs:
// "When you search for commits, only the default branch of a repository is
// searched."
// We are querying the master branch.

// API Search term
$apis = rawurlencode("repo:$User/$Repo merge:true sort:committer-date-desc");
$api .= $apis; // Final URI

// Http Client Options.
$opts = array('http' =>
	array(
		'user_agent' => 'BASE/' . $tcc->GetCap('BASE_Ver')
	)
);

if ( getenv('TRAVIS') && version_compare($Lv, '5.3.0', '<') ){
	// Cutout for lack of SSL support in PHP 5.2 on Travis CI.
	// This is why we have a Unit Test that will catch this as well.
	$ExitCode = 0; // Success
}else{
	$context = stream_context_create($opts);
	$stream = fopen($api, 'r', false, $context);
	if ( $stream !== false ){
		$lc = stream_get_contents($stream);
		if ( $lc !== false ){
			$branch = json_decode($lc, true);
			$tmp = $tcc->GetCap('BASE_LPM');
			if ( $debug_mode == 0 ){
				$cmsg = $branch['items'][0]['commit']['message'];
				$cdate = substr(
					$branch['items'][0]['commit']['committer']['date'], 0, 10
				);
				if ( $tmp  == $cdate ){
					$ExitCode = 0; // Success
					print "Date match: $tmp\n";
				}else{ // Merged to master without changing LPN date. :-0
					print "Date Error\n";
					print "Master Branch: $cdate LM Msg: $cmsg\n";
					print "BASE LPM: $tmp\n";
				}
			}elseif( $debug_mode == 1 ){
				$cmsg = $branch['commit']['commit']['message'];
				$cdate = $branch['commit']['commit']['committer']['date'];
				print "Deval Branch: $cdate LM Msg: $cmsg\n";
				print "BASE LPM: $tmp\n";
				$ExitCode = 0; // Success
			}
		}else{
			print "API stream Error\n";
		}
	}else{
		print "API Open Error\n";
	}
}
exit($ExitCode);
?>
