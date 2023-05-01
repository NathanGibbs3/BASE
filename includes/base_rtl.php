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
//            Purpose: BASE Runtime. Routines not specifically tied to BASE,
//                     that can be used anywhere.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$BRTL_Ver = '0.0.2';

if( !function_exists('LoadedString') ){
	// Returns true if var is a string containing data.
	function LoadedString( $var ){
		$Ret = false;
		if ( is_string($var) && !empty($var)){
			$Ret = true;
		}
		return $Ret;
	}
}

if( !function_exists('SetConst') ){
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
}

SetConst('BASE_RTL', $BRTL_Ver);

// Returns Newline, requested # of tabs, & $Item as a string.
function NLI ( $Item = '', $Count = 0 ){
	if ( !is_int($Count) ){
		$Count = 0;
	}
	return "\n".str_repeat ("\t", $Count).$Item;
}

// Prints Newline, requested # of tabs, & $Item to STDOUT
function NLIO ( $Item = '', $Count = 0 ){
	print NLI ($Item, $Count);
}

// Returns Semantic PHP Version
function GetPHPSV (){
	$phpv = phpversion();
	$phpv = explode('.', $phpv);
	// Account for x.x.xXX subversions possibly having text like 4.0.4pl1
	if( is_numeric(substr($phpv[2], 1, 1)) ){ // No Text
		$phpv[2] = substr($phpv[2], 0, 2);
	}else{
		$phpv[2] = substr($phpv[2], 0, 1);
	}
	return implode('.', $phpv);
}

// @codeCoverageIgnoreStart
if( !function_exists('HTTP_header') ){
	// Send HTTP header if clear to do so.
	function HTTP_header( $url ){
		if ( !headers_sent() ){
			header($url);
			exit;
		}
	}
}

if( !function_exists('KML') ){
	// Mini KML mocking shim for testing code that calls the real KML.
	function KML ( $msg = '', $lvl = 0 ){
		if ( LoadedString($msg) ){
			if ( !is_int($lvl) || $lvl < 0 ){
				$lvl = 0;
			}
			error_log($msg);
		}
	}
}
// @codeCoverageIgnoreEnd

if( !function_exists('ChkAccess') ){
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
}

// Returns true when key is in array, false otherwise.
function is_key( $SKey, $SArray ){ // PHP Version Agnostic.
	$Ret = false;
	if( is_array($SArray) && count($SArray) > 0 ){
		$PHPVer = GetPHPSV();
		// Use built in functions when we can.
		if(
			$PHPVer[0] > 4 || ($PHPVer[0] == 4 && $PHPVer[1] > 0 )
			|| ($PHPVer[0] == 4 && $PHPVer[1] == 0 && $PHPVer[2] > 6)
		){ // PHP > 4.0.7
			$Ret = array_key_exists( $SKey, $SArray );
		// @codeCoverageIgnoreStart
		// PHPUnit test only covers this code path on PHP < 4.0.7
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

function ErrorMessage ($message, $color = '#ff0000', $br = 0 ){
	GLOBAL $BCR, $debug_mode, $BASE_VERSION, $BASE_installID;
	if (
		!getenv('TRAVIS')
		&& !(
			$BASE_VERSION == '0.0.0 (Joette)'
			&& $BASE_installID == 'Test Runner'
		)
	){
		$UIM = 'Knl'; // Default UI Mode Under Boot.
	}else{
		$UIM = 'Web'; // Default UI Mode Under Test.
	}
	if ( isset($BCR) && is_object($BCR) ){
		$UIM = $BCR->GetCap('UIMode'); // Running System Sets UI Mode.
	}
	switch( $UIM ){
		case 'Gfx';
		case 'Knl';
			KML($message, $debug_mode);
			break;
		case 'Con';
			NLI($message);
			break;
		case 'Web';
		default;
			print returnErrorMessage($message, $color, $br);
	}
}

function returnErrorMessage ($message, $color = "#ff0000", $br = 0 ){
	if ( HtmlColor($color) == false ){
		// Default to Red if we are passed something odd.
		$color = "#ff0000";
	}
	$error = "<font color='$color'>$message</font>";
	if ( is_numeric($br) && $br == 1 ){ // Issue #160
		$error .= '<br/>';
	}
	return $error;
}

// Function: XSSPrintSafe()
// @doc Converts unsafe html special characters to print safe
//      equivalents as an Anti XSS defense.
// @return a sanitized version of the passed variable.
function XSSPrintSafe($item){
	if ( !isset($item) ){ // Unset Value.
		return $item;
	}else{
		if ( is_array($item) ){ // Array.
			// Recursively convert array elements.
			// Works with both Keyed & NonKeyed arrays.
			foreach ($item as $key => $value) {
				$item[$key] = XSSPrintSafe($value);
			}
			return $item;
		}else{ // Single Value.
			return htmlspecialchars($item);
		}
	}
}

// Returns true if color is valid html color code.
function HtmlColor ( $color ){
	$color = strtolower($color);
	$wsc = array(
		'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
		'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal',
		'aqua'
	);
	$Ret = false;
	if (
		in_array($color, $wsc) // Web Safe Color.
		|| preg_match("/^#?[0-9A-F]{6}$/i", $color) // Hex RGB Color Code.
	){
		$Ret = true;
	}
	return ($Ret);
}

?>
