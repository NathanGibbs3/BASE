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
//            Purpose: BASE TE Kernel. Subset of BASE Kernel.
//                     Standardized startup procedures & environemnt for code
//                     that is part of testing environment setup.
//
//          Author(s): Nathan Gibbs

$BK_Ver = '0.0.1';
$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'tests.*';
$BK_Path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);
$BASE_path = $BK_Path;

$debug_mode = 0;
if( isset($argv[1]) ){ // Debug lvl
	$debug_mode = intval($argv[1]);
}

SetConst('_BASE_INC', 1); // Include Load Flag.
SetConst('BASE_Path', $BK_Path);
KML("BASE Path Set: $BK_Path", 1);
$BKI_Path = "$BASE_path$sc" . "includes$sc";
SetConst('BASE_IPath', $BKI_Path);
KML("BASE Include Path Set: $BKI_Path", 1);
include_once(BASE_IPath . 'base_auth.inc.php'); // BASE Auth System.
KML("Load: Auth System", 1);
SetConst('BASE_KERNEL', $BK_Ver); // Basic kernel Initialized.
include_once(BASE_IPath . 'base_rtl.php'); // BASE Runtime.
KML("Load: RTL", 1);
KML("BASE TES kernel $BK_Ver Runtime $BRTL_Ver");
include_once("$BASE_path$sc" . "base_common.php"); // BASE Common.
KML("Load: BASE Common", 1);
include_once(BASE_IPath . 'base_capabilities.php'); // BASE Caps System.
KML("Load: Caps Registry", 1);
include_once(BASE_IPath . 'base_constants.inc.php'); // BASE Constants.
KML("Load: Constants", 1);
include_once(BASE_IPath . 'base_db.inc.php'); // BASE DB System.
KML("Load: DB System", 1);

// BASE Kernel Message Logger.
// Not RTL as it supports a BASE conf val.
function KML ( $msg = '', $lvl = 0 ){
	GLOBAL $debug_mode;
	if ( LoadedString($msg) ){
		if ( !is_int($lvl) || $lvl < 0 ){
			$lvl = 0;
		}
		if ( $debug_mode >= $lvl ){
			error_log($msg);
		}
	}
}

// BASE micro RTL.
// BASE Kernel RTL subset included here to keep the kernel self contained.

// Returns true if var is a string containing data.
function LoadedString( $var ){
	$Ret = false;
	if ( is_string($var) && !empty($var)){
		$Ret = true;
	}
	return $Ret;
}

// Returns true if Constant can be defined, false otherwise..
function SetConst( $const, $val ){
	$Ret = false;
	if ( LoadedString($const) ){
		if (!defined($const)){
			$Ret = define($const, $val);
		}
	}
	return $Ret;
}

?>
