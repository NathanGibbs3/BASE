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
//            Purpose: BASE Kernel. Standardized startup procedures.
//                     Sets up environemnt before page load, ensuring a
//                     standard set of resources are available to all
//                     parts of the BASE App.
//
//          Author(s): Nathan Gibbs

$BK_Ver = '0.0.1';
$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'includes.*';
$BK_Path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);
$BASE_path = $BK_Path;
$file = "$BASE_path$sc" . 'base_conf.php'; // BASE Conf File.

if ( ChkAccess($file) == 1 && filesize($file) > 10 ){
	KML("Loading Conf from: $file");
	require_once($file);
	SetConst('BASE_Conf', $file);
	SetConst('_BASE_INC', 1); // Include Load Flag.
	$BASE_path = $BK_Path; // Restore Kernel path in case of Legacy Conf.
	SetConst('BASE_Path', $BK_Path);
	KML("BASE PATH Set: $BK_Path", 2);
	include_once("$BASE_path$sc" . "includes$sc" . 'base_auth.inc.php');
	KML("Load: Auth System", 2);
	$BK_SDL = "(base_(denied|logout|main)|index)";
	// Start legacy base_conf.php support
	if ( AuthorizedPage($BK_SDL) && LoadedString(session_id()) ){
		// Destroy preexisting session.
		session_unset();
		session_destroy();
		session_write_close();
	}
	// End legacy base_conf.php
	SetConst('BASE_KERNEL', $BK_Ver); // Basic kernel Initialized.
	// BASE Runtime.
	include_once("$BASE_path$sc" . "includes$sc" . "base_rtl.php");
	KML("Load: RTL", 2);
	KML("BASE kernel $BK_Ver Runtime $BRTL_Ver");
	include_once("$BASE_path$sc" . "base_common.php");
	KML("Load: BASE Common", 2);
	if ( !LoadedString(session_id()) ){ // Start new session.
		if ( LoadedString($BASE_installID) ){
			// Create a unique cookie name for each BASE installation.
			$sessionName = str_replace(
				' ', '_', $BASE_installID . session_name()
			);
			session_name($sessionName);
		}
		RegisterGlobalState(); // Start Session
	}
	include_once("$BASE_path$sc". "includes$sc" . "base_capabilities.php");
	$BCR = new BaseCapsRegistry();
	KML("Load: Caps Registry", 2);
	include_once("$BASE_path$sc" . "includes$sc" . "base_log_timing.inc.php");
	$et = new EventTiming($BCR->GetCap('BASE_UIDiagTime'));
	$et->Mark('Starting BASE: ' . $BCR->GetCap('BASE_Ver'));
	KML("Load: Telemetry", 2);
}else{
	KML("Can't open Conf from: $file.");
	HTTP_header('Location: setup/index.php');
}

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

// BASE mini RTL.
// RTL subset included here to keep the kernel self contained.

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

// @codeCoverageIgnoreStart
// Send HTTP header if clear to do so.
function HTTP_header( $url ){
	if ( !headers_sent() ){
		header($url);
		exit;
	}
}
// @codeCoverageIgnoreEnd

// Returns 1 if file or directory passes access checks.
// Returns < 1 error code otherwise.
function ChkAccess( $path, $type='f' ){
	$Ret = 0; // Path Error
	if ( LoadedString($path) ){
		$type = strtolower($type);
		$rcf = 0;
		$Ret = -1; // Type Error
		if ( $type == 'f' ){
			if ( is_file($path) ){
				$rcf = 1;
			}
		}elseif ( $type == 'd' ){
			if ( is_dir($path) ){
				$rcf = 1;
			}
		}
		if ( $rcf == 1 ){
			$Ret = -2; // Readable Error
			$version = explode('.', phpversion());
			// PHP Safe Mode cutout.
			//    Added: 2005-03-25 for compatabibility with PHP 4x & 5.0x
			//      See: https://sourceforge.net/p/secureideas/bugs/47
			// PHP Safe Mode w/o cutout successful.
			// Verified: 2019-05-31 PHP 5.3.29 via CI & Unit Tests.
			//      See: https://github.com/NathanGibbs3/BASE/issues/34
			// May work: PHP > 5.1.4.
			//      See: https://www.php.net/manual/en/function.is-readable.php
			if (
				$version[0] > 5
				|| ($version[0] == 5 && $version[1] > 1)
				|| ($version[0] == 5 && $version[1] == 1 && $version[2] > 4 )
				|| ini_get("safe_mode") != true
			){
				if ( is_readable($path) ){
					$Ret = 1;
				}
			}else{
				// @codeCoverageIgnoreStart
				// PHPUnit test only covers this code path on PHP < 5.1.5
				// Unable to validate in CI.
				$Ret = 1;
				// @codeCoverageIgnoreEnd
			}
		}
	}
	return $Ret;
}

?>
