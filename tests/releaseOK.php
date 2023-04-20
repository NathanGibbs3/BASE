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

$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'tests.*';
$BASE_path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);
define( '_BASE_INC', 1 );
$BASE_installID = 'GH API Check';

include("$BASE_path/includes/base_include.inc.php");
include("$BASE_path/includes/base_capabilities.php");

$tcc = new BaseCapsRegistry();
print 'BASE ' . $tcc->GetCap('BASE_Ver')."\n";

$ExitCode = 1; // Assume failure.
$User = 'NathanGibbs3';
$Repo = 'BASE';
$tmp = '';

$api = 'https://api.github.com/search/commits?q='; // GH API Search Endpoint.
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

$context = stream_context_create($opts);
$stream = fopen($api, 'r', false, $context);
if ( $stream !== false ){
	$lc = stream_get_contents($stream);
	if ( $lc !== false ){
		$branch = json_decode($lc, true);
		$tmp = $tcc->GetCap('BASE_LPM');
		$cmsg = $branch['items'][0]['commit']['message'];
		$cdate = substr(
			$branch['items'][0]['commit']['committer']['date'], 0, 10
		);
		if ( $tmp  == $cdate ){
			$ExitCode = 0; // Success
			print "Date match: $tmp\n";
		}else{ // Merged to master without changing the LPN date in src. :-0
			print "Date Error\n";
			print "Master Branch: $cdate LM Msg: $cmsg\n";
			print "BASE LPM: $tmp\n";
		}
	}else{
		print "API stream Error\n";
	}
}else{
	print "API Open Error\n";
}
exit($ExitCode);
?>
