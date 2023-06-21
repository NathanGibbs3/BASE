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

$BK_Ver = '0.0.7';
$BASE_path = dirname(__FILE__);
$sc = DIRECTORY_SEPARATOR;
$ReqRE =  "\\".$sc.'includes.*';
$BK_Path = preg_replace('/'.$ReqRE.'/', '', $BASE_path);
$BASE_path = $BK_Path;
$file = "$BASE_path$sc" . 'base_conf.php'; // BASE Conf File.

$debug_mode = 0;
if( isset($argv[1]) ){ // Debug lvl
	$debug_mode = intval($argv[1]);
}

$tmp = ChkAccess($file);
if( $tmp > 0 && filesize($file) > 10 ){
	KML("BASE Conf Set: $file");
	require_once($file);
	SetConst('BASE_Conf', $file);
	SetConst('BASE_SecMsg', 'BASE Security Alert ');
	SetConst('_BASE_INC', 1); // Include Load Flag.
	$BASE_path = $BK_Path; // Restore Kernel path in case of Legacy Conf.
	SetConst('BASE_Path', $BK_Path);
	KML("BASE Path Set: $BK_Path", 2);
	$BKI_Path = "$BASE_path$sc" . "includes$sc";
	SetConst('BASE_IPath', $BKI_Path);
	KML("BASE Include Path Set: $BKI_Path", 2);
	include_once(BASE_IPath . 'base_auth.inc.php'); // BASE Auth System.
	KML("Load: Auth System", 2);
	$BK_SDL = "(base_(denied|logout|main)|index)";
	// Start legacy base_conf.php support
	if ( AuthorizedPage($BK_SDL) && LoadedString(session_id()) ){
		session_unset(); // Destroy pre existing session.
		session_destroy();
		session_write_close();
	}
	// End legacy base_conf.php
	SetConst('BASE_KERNEL', $BK_Ver); // Basic kernel Initialized.
	include_once(BASE_IPath . 'base_rtl.php'); // BASE Runtime.
	KML("Load: RTL", 2);
	KML("BASE kernel $BK_Ver Runtime $BRTL_Ver");
	if( !AuthorizedClient() ){ // Issue #175
		KML(BASE_SecMsg . 'Krnl(): Unauthorized Client: '
		. $_SERVER['REMOTE_ADDR']);
		HTTP_header('', 403);
		exit;
	}
	include_once("$BASE_path$sc" . "base_common.php"); // BASE Common.
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
	include_once(BASE_IPath . 'base_capabilities.php'); // BASE Caps System.
	$BCR = new BaseCapsRegistry();
	KML("Load: Caps Registry", 2);
	include_once(BASE_IPath . 'base_constants.inc.php'); // BASE Constants.
	KML("Load: Constants", 2);
	include_once(BASE_IPath . 'base_db.inc.php'); // BASE DB System.
	KML("Load: DB System", 2);
	include_once(BASE_IPath . 'base_log_timing.inc.php'); // BASE Telemetry.
	$et = new EventTiming($BCR->GetCap('BASE_UIDiagTime'));
	$et->Mark('Starting BASE: ' . $BCR->GetCap('BASE_Ver'));
	KML("Load: Telemetry", 2);
	$Lang = $BCR->GetCap('BASE_UILang');
	$Act = 'Set';
	if( !LoadedString($Lang) ){
		$Act = 'Default';
		$Lang = 'english';
		$BCR->AddCap('BASE_UILang', $Lang);
	}
	KML("BASE Lang $Act: $Lang", 2);
	$LA = '';
	$tmp = ChkAccess("$BASE_path$sc" . "languages$sc$Lang" . '.lang.php');
	if( $tmp < 1 ){
		$LA = 'not ';
		if( $tmp == 0 ){
			$LA .= 'file';
		}elseif( $tmp == -1 ){
			$LA .= 'found';
		}elseif( $tmp == -2 ){
			$LA .= 'readable';
		}
		$LA .= '.';
	}
	if( LoadedString($LA) ){ // Display error to user.
		KML("BASE Lang File: $LA", 2);
		$BCR->AddCap('UIMode', 'Web');
		ErrorMessage("BASE Lang File: $LA");
		exit;
	}
	KML('BASE Lang File: accessible.', 2);
	$tmp = $BASE_urlpath; // Issue #190
	if( LoadedString($tmp) ){
		$ReqRE = 'http(s)?' . preg_quote('://','/')
		. '[0-9A-Za-z\.\-]+(\:[0-9]+)?';
		$tmp1 = $tmp;
		$tmp = preg_replace('/^' . $ReqRE . '/', '', $tmp);
		if( $tmp1 != $tmp ){
			KML(BASE_SecMsg . 'Krnl(): Issue #190 attack blocked.');
		}
		if( $tmp == '/' ){
			$tmp = '';
		}
		$BASE_urlpath = $tmp;
	}
	if( is_key('SCRIPT_NAME', $_SERVER) ){
		$tmp = $_SERVER['SCRIPT_NAME'];
		KML("Start: $tmp");
	}
}else{
	$EMsg = "BASE Conf access error: $file not ";
	if( $tmp == 0 ){
		$EMsg .= 'file';
	}elseif( $tmp == -1 ){
		$EMsg .= 'found';
	}elseif( $tmp == -2 ){
		$EMsg .= 'readable';
	}
	$EMsg .= '.';
	KML($EMsg);
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

function VS2SV ( $VS = '' ){ // Returns false or Semantic Version Array.
	// Convert Version String to Semantic Version Array.
	$Ret = false;
	if( LoadedString($VS) ){
		$VS = trim($VS);
		$VSP = '[ \.\,\:\;\_\-]?';
		$REProd ="[[:alnum:]]+$VSP"; // Product RE
		$REVer = "V(ER)?(SION)?$VSP"; // Version RE
		$VS = preg_replace('/^' . "($REVer){1,2}" . '/i', '', $VS);
		if( !preg_match('/^\d+(\.(\d+)?){0,2}/', $VS) ){
			$VS = preg_replace('/^' . "$REProd($REVer)?" . '/i', '', $VS);
		}
		if( preg_match('/\./', $VS) ){
			$DALV = explode('.', $VS, 3);
		}else{
			preg_match('/\d+/', $VS, $DALV);
		}
		for( $i = 0; $i < 3; $i++ ){
			if( isset($DALV[$i]) ){
				if( preg_match('/\d+/', $DALV[$i], $tmp) ){ // Normalize Data.
					$DALV[$i] = $tmp[0];
				}
			}else{
				$DALV[$i] = '0';
			}
			if( !is_numeric($DALV[$i]) ){ // Strange Edge Case.
				$DALV[$i] = '0';
			}
		}
		$Ret = $DALV; // Array of ( Major, Minor, Revision ).
	}
	return $Ret;
}

function GetPHPSV (){ // Returns Semantic PHP Version Array.
	return VS2SV(phpversion());
}

// @codeCoverageIgnoreStart
// Send HTTP header if clear to do so.
function HTTP_header( $url = '', $status = 200 ){
	if( !is_int($status) ){ // Default to OK.
		$status = 200;
	}
	if( preg_match ('/^Location\: /', $url) ){
		$status = 302;
	}
	if ( !headers_sent() ){
		if( is_key('SERVER_PROTOCOL', $_SERVER) ){
			header($_SERVER['SERVER_PROTOCOL'] . " $status");
		}
		if( LoadedString($url) ){
			header($url,true,$status);
		}
		exit;
	}
}
// @codeCoverageIgnoreEnd

// Returns > 0 if file or directory passes access checks.
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
			$PHPVer = GetPHPSV();
			// Is_Readable Check.
			// PHP Safe Mode w/o cutout successful.
			// Verified: 2019-05-31 PHP 5.3.29 via CI & Unit Tests.
			// See: https://github.com/NathanGibbs3/BASE/issues/34
			// May work: PHP > 5.1.4.
			// See: https://bugs.php.net/bug.php?id=38724
			if(
				$PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 1)
				|| ($PHPVer[0] == 5 && $PHPVer[1] == 1 && $PHPVer[2] > 4)
				|| ini_get("safe_mode") != true
			){
				if( is_readable($path) ){
					$Ret = 1;
				}
			}else{ // PHP Safe Mode cutout.
				// @codeCoverageIgnoreStart
				// Added: 2005-03-25 for compatabibility with PHP 4x & 5.0x
				// See: https://sourceforge.net/p/secureideas/bugs/47
				// PHPUnit test only covers this code path on PHP < 5.1.5
				// Unable to validate in CI.
				$Ret = 1;
				// @codeCoverageIgnoreEnd
			}
			if( $Ret == 1 ){ // Is_Executable Check.
				if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
					// @codeCoverageIgnoreStart
					// Windows cutout.
					// Returns unreliable data for directories & most file
					// types. Unable to validate in CI.
					$Ret = 2;
				}else{
					// PHP Safe Mode w/o cutout successful.
					// Verified: 2023-06-09 PHP 5.2.17 via CI & Unit Tests.
					// See: https://github.com/NathanGibbs3/BASE/issues/210
					// May work: PHP > 5.2.0
					// See: https://bugs.php.net/bug.php?id=29840
					if(
						$PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 2)
						|| (
							$PHPVer[0] == 5 && $PHPVer[1] == 2 && $PHPVer[2] > 0
						)
						|| ini_get("safe_mode") != true
					){
						if( is_executable($path) ){
							$Ret = 2;
						}
					}else{ // PHP Safe Mode cutout.
						// @codeCoverageIgnoreStart
						// PHPUnit test only covers this code path on
						// PHP < 5.2.1 Unable to validate in CI.
						$Ret = 2;
						// @codeCoverageIgnoreEnd
					}
				}
			}
		}
	}
	return $Ret;
}

// Returns true when key is in array, false otherwise.
function is_key( $SKey, $SArray ){ // PHP Version Agnostic.
	$Ret = false;
	if( is_array($SArray) && count($SArray) > 0 ){
		$PHPVer = GetPHPSV();
		// Use built in functions when we can.
		if(
			$PHPVer[0] > 4 || ($PHPVer[0] == 4 && $PHPVer[1] > 0)
			|| ($PHPVer[0] == 4 && $PHPVer[1] == 0 && $PHPVer[2] > 6)
		){ // PHP > 4.0.7
			$Ret = array_key_exists( $SKey, $SArray );
		// @codeCoverageIgnoreStart
		// PHPUnit tests woruld only covers this code path on PHP < 4.0.7
		// Unable to validate in CI.
		}elseif(
			$PHPVer[0] == 4 && $PHPVer[1] == 0 && $PHPVer[2] > 5
		){ // PHP > 4.0.5
			$Ret = key_exists($SKey, $SArray);
		}else{ // No built in functions, PHP Version agnostic.
			$Ret = in_array($SKey, array_keys($SArray) );
		}
		// @codeCoverageIgnoreEnd
	}
	return $Ret;
}

?>
