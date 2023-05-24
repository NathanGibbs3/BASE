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

$BRTL_Ver = '0.0.8';

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

if( !function_exists('GetPHPSV') ){
	function GetPHPSV (){ // Returns Semantic PHP Version
		$phpv = phpversion();
		$phpv = explode('.', $phpv);
		// @codeCoverageIgnoreStart
		// Account for x.x.xXX subversions possibly having text like 4.0.4pl1
		if( is_numeric(substr($phpv[2], 1, 1)) ){ // No Text
			$phpv[2] = substr($phpv[2], 0, 2);
		}else{
			$phpv[2] = substr($phpv[2], 0, 1);
		}
		// @codeCoverageIgnoreEnd
		return $phpv;
	}
}

// @codeCoverageIgnoreStart
if( !function_exists('HTTP_header') ){
	// Send HTTP header if clear to do so.
	function HTTP_header( $url = '', $status = 200 ){
		if( !is_int($status) ){ // Default to OK.
			$status = 200;
		}
		if( preg_match ('/^Location\: /', $url) ){
			$status = 302;
		}
		if ( !headers_sent() ){
			header($_SERVER['SERVER_PROTOCOL'] . " $status");
			if( LoadedString($url) ){
				header($url,true,$status);
			}
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
		if( LoadedString($path) ){
			$type = strtolower($type);
			$rcf = 0;
			$Ret = -1; // Type Error
			if( $type == 'f' ){
				if( is_file($path) ){
					$rcf = 1;
				}
			}elseif( $type == 'd' ){
				if( is_dir($path) ){
					$rcf = 1;
				}
			}
			if( $rcf == 1 ){
				$Ret = -2; // Readable Error
				$PHPVer = GetPHPSV();
				// PHP Safe Mode cutout.
				//    Added: 2005-03-25 for compatabibility with PHP 4x & 5.0x
				//      See: https://sourceforge.net/p/secureideas/bugs/47
				// PHP Safe Mode w/o cutout successful.
				// Verified: 2019-05-31 PHP 5.3.29 via CI & Unit Tests.
				//      See: https://github.com/NathanGibbs3/BASE/issues/34
				// May work: PHP > 5.1.4.
				//      See: https://www.php.net/manual/en/function.is-readable.php
				if(
					$PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 1)
					|| ($PHPVer[0] == 5 && $PHPVer[1] == 1 && $PHPVer[2] > 4)
					|| ini_get("safe_mode") != true
				){
					if( is_readable($path) ){
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
			$PHPVer[0] > 4 || ($PHPVer[0] == 4 && $PHPVer[1] > 0)
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
	return $Ret;
}

function CCS(){
	$Ret = false;
	$Stat = '';
	if( is_key('HTTPS', $_SERVER) ){ // Check the server first.
		$tmp = $_SERVER['HTTPS'];
		if( LoadedString($tmp) && strtolower($tmp) == 'on' ){
			$Stat = 'SVR-FLAG';
		}
	}elseif( is_key('SERVER_PORT', $_SERVER) ){ // Assume secure on port 443.
		$tmp = $_SERVER['SERVER_PORT'];
		if( intval($tmp) == 443 ){
			$Stat = 'SVR-PORT';
		}
	}else{ // Check for Load Balancer / Reverse Proxy.
		if( is_key('HTTP_X_FORWARDED_PROTO', $_SERVER) ){
			$tmp = $_SERVER['HTTP_X_FORWARDED_PROTO'];
			if( LoadedString($tmp) && strtolower($tmp) == 'https' ){
				$Stat = 'PRX-PROT';
			}
		}elseif( is_key('HTTP_X_FORWARDED_SSL', $_SERVER) ){
			$tmp = $_SERVER['HTTP_X_FORWARDED_SSL'];
			if( LoadedString($tmp) && strtolower($tmp) == 'on' ){
				$Stat = 'PRX-SSL';
			}
		}elseif( is_key('HTTP_X_FORWARDED_PORT', $_SERVER) ){
			$tmp = $_SERVER['HTTP_X_FORWARDED_PORT'];
			if( intval($tmp) == 443 ){
				$Stat = 'PRX-PORT';
			}
		}
	}
	if( LoadedString($Stat) ){
		$Ret = true;
	}
	return array($Ret, $Stat);
}

function is_ip ( $ip = '' ){
	$Ret = false;
	if( LoadedString($ip) ){
		if( is_ip4($ip) || is_ip6($ip) ){
			$Ret = true;
		}
	}
	return $Ret;
}

function is_ip4 ( $ip = '' ){
	$Ret = false;
	if( LoadedString($ip) ){
		$ReOc = '\d{1,3}';
		$ReIp = str_repeat("$ReOc\.",3) . $ReOc;
		if( preg_match ('/^'. $ReIp .'$/', $ip) ){
			$Ret = true;
		}
	}
	return $Ret;
}

function is_ip6 ( $ip = '' ){
	$Ret = false;
	if( LoadedString($ip) ){
		$ReOc = '\d{1,3}';
		$ReIp = str_repeat("$ReOc\.",3) . $ReOc;
		$ReOc6 = '[[:xdigit:]]{1,4}';
		$ReIp6 = "\:?(\:?$ReOc6){0,6}" . "\:($ReIp|($ReOc6)?\:$ReOc6)?";
		if( preg_match ('/^'. $ReIp6 .'$/', $ip) ){
			$Ret = true;
		}
	}
	return $Ret;
}

function netmask ( $ip = '' ){
	$Ret = 0;
	if( LoadedString($ip) ){
		$MaskRE = '\/\d{1,3}';
		if( preg_match ('/'. $MaskRE .'$/', $ip , $Snm) ){
			$Snm = $Snm[0];
			$Ret = preg_replace( '/^'. '\/' .'/', '', $Snm );
			if ( $Ret > 128 ){ // Lock down max value.
				$Ret = 128;
			}
		}
	}
	return $Ret;
}

function ipdeconvert ( $ip = '' ){
	$Ret = 0;
	if( is_numeric($ip) ){
		$ip = trim($ip);
		$PHPVer = GetPHPSV();
		$SF = false;
		$OCA = array();
		$t4 = 0;
		$t6 = 0;
		if( $ip < pow(256, 4) ){ // IPv4
			$t4 = 1;
			$tl = 4;
		}else{ // IPv6
			$t6 = 1;
			$tl = 16;
			if(
				defined('GMP_VERSION')
				&& (
					$PHPVer[0] > 5
					|| ($PHPVer[0] == 5 && $PHPVer[1] == 6 && $PHPVer[2] > 0)
				)
			){ // Fast way on PHP 5.6.1+
				$SF = true;
				$tmp = str_pad(gmp_export($ip), 16, "\0", STR_PAD_LEFT);
			}
		}
		if( !$SF ){
			for ( $i = $tl; $i > 0 ; $i-- ){
				$pwr = $i - 1;
				if ( $t6 ){ // IPv6 Use Gmp lib.
					$tmp = gmp_strval(gmp_pow(256, $pwr));
					$res = gmp_div_qr($ip, $tmp);
					$tt = gmp_intval($res[0]);
					$ip = gmp_strval($res[1]);
				}else{ // IPv4 Use PHP
					$tmp = pow(256, $pwr);
					$tt = intval($ip / $tmp);
					$ip = $ip - ($tmp * $tt);
				}
				array_push($OCA, $tt);
			}
		}
		if( $PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 0) ){
			// Use built in functions.
			if( !$SF ){
				$tmp = '';
				foreach ($OCA as $val) {
					$tt = pack('C*', $val);
					$tmp .= $tt;
				}
			}
			$Ret = inet_ntop($tmp);
		}else{ // Figure it out.
			// @codeCoverageIgnoreStart
			$Sep = '.';
			if( $t6 ){
				$Sep = ':';
			}
			$i = 1;
			foreach ($OCA as $val) {
				$tt = $val;
				$SPF = true;
				if( $t6 ){
					$tt = str_pad(dechex($tt), 2, '0', STR_PAD_LEFT);
					if( ($i % 2) != 0 ){
						$SPF = false;
					}
				}
				if( $SPF && $i < $tl ){
					$tt .= $Sep;
				}
				$i++;
				$tmp .= $tt;
			}
			$Ret = $tmp;
			// @codeCoverageIgnoreEnd
		}
	}
	return $Ret;
}

function ipconvert ( $ip = '' ){
	$Ret = 0;
	if( LoadedString($ip) ){
		$ip = trim($ip);
		$PHPVer = GetPHPSV();
		$SF = false;
		$OCA = array();
		$ReOc = '\d{1,3}';
		$ReIp = str_repeat("$ReOc\.",3) . $ReOc;
		$ReOc6 = '[[:xdigit:]]{1,4}';
		$ReIp6 = "\:?(\:?$ReOc6){0,6}" . "\:($ReIp|($ReOc6)?\:$ReOc6)?";
		$t4 = preg_match ('/^'. $ReIp .'$/', $ip, $t4m);
		if ( $t4 ){ // IPv4 Data Normalization.
			$OCA = explode('.',$t4m[0]);
			foreach ($OCA as $key => $val) {
				$OCA[$key] = intval($val);
			}
			$ip = implode('.', $OCA);
		}
		$t6 = preg_match ('/^'. $ReIp6 .'$/', $ip, $t6m);
		if ( $t6 ){ // IPv6 Data Normalization.
			$t6mTmp = '';
			$t6m = $t6m[0];
			$t6t4 = preg_match ('/'. $ReIp .'$/', $t6m, $t6t4m);
			if ( $t6t4 ){
				$t6mTmp = preg_replace(
					'/'. preg_quote(':' . $t6t4m[0]) .'$/', '', $t6m
				);
				$t6t4m = explode('.', $t6t4m[0]);
				foreach ($t6t4m as $key => $val) {
					$t6t4m[$key] = intval($val);
				}
				$t6m = $t6mTmp;
				$OCA = $t6t4m;
				$ip = $t6mTmp . ':' . implode('.',$t6t4m);
			}
		}
		$tl = 0;
		if( $PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 0) ){
			$tmp = inet_pton($ip); // Use built in functions.
			if(
				$PHPVer[0] > 5
				|| ($PHPVer[0] == 5 && $PHPVer[1] == 6 && $PHPVer[2] > 0)
			){ // Fast way on PHP 5.6.1+
				if( $t6 && defined('GMP_VERSION') ){
					$SF = true;
					$Ret = gmp_strval(gmp_import($tmp));
				}
			}
		}else{ // Figure it out.
			// @codeCoverageIgnoreStart
			if ( $t6 ){ // IPv6 Address
				$Snm = 128 - (count($OCA) * 8);
				// Process Standard IPv6 Notation
				while( $Snm > 0 ){
					$t6Oc = preg_match (
						'/'. "\:?($ReOc6)" .'$/', $t6m, $t6Ocm
					);
					if ( $t6Oc ){
						$t6Ocr = $t6Ocm[0];
						$t6Ocm = $t6Ocm[1];
						for ( $i = 4; $i > 0; $i = $i - 2 ){
							$tmp = substr($t6Ocm, strlen($t6Ocm) - 2, 2);
							if( !LoadedString($tmp) ){
								$tmp = '00';
							}
							array_unshift($OCA, intval(hexdec($tmp)));
							$t6Ocm = preg_replace( '/' . $tmp . '$/', '', $t6Ocm );
						}
						$Snm = $Snm - 16;
						$t6m = preg_replace( '/' . preg_quote($t6Ocr) . '$/', '', $t6m );
					}else{
						$TOL = $Snm / 16;
						$t6Oc = preg_match_all (
							'/'. "$ReOc6\:" .'/', $t6m, $t6Ocm
						);
						$t6m = preg_replace( '/' . '\:' . '$/', '', $t6m );
						if ( $t6Oc !== false ){
							$tmp = '00';
							$TOL = $TOL * 2;
							$t6Oc = $t6Oc * 2;
							for ( $i = $TOL; $i > $t6Oc; $i-- ){
								array_unshift($OCA, intval(hexdec($tmp)));
								$Snm = $Snm - 8;
							}
						}
					}
				}
			}
			$tmp = '';
			foreach ($OCA as $val) {
				$tt = pack('C', $val);
				$tmp .= $tt;
			}
			// @codeCoverageIgnoreEnd
		}
		if( !$SF ){
			$t1 = '';
			foreach (unpack('C*', $tmp) as $byte) {
				$t1 .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
			}
			if( $t4 ){ // IPv4
				$Ret = base_convert(ltrim($t1, '0'), 2, 10);
			}else{ // IPv6 returns 0 if gmp is not available.
				if( defined('GMP_VERSION') ){
					$Ret = gmp_strval(gmp_init($t1, 2));
				}
			}
		}
	}
	return $Ret;
}

function NMHC ( $Snm = 0, $v6 = false ){ // Get host Count from netmask.
	$Ret = 0;
	if( !is_bool($v6) ){
		$v6 = false;
	}
	if( is_numeric($Snm) ){
		$Snm = intval($Snm);
		$Top = 0;
		$Floor = 32;
		if( $v6 ){ // Specifically handle netmasks below 33 as IPv6
			$Floor = 0;
		}
		if( $Snm > $Floor && $Snm < 129 ){ // IPv6
			if( defined('GMP_VERSION') ){
				$Top = gmp_pow(256, 16);
				$Ret = gmp_strval(gmp_div($Top, gmp_pow(2, $Snm)));
			}
		}elseif( $Snm > 0 && $Snm < 33 ){ // IPv4
			$Top = pow(256, 4);
			$Ret = $Top / pow(2, $Snm);
		}
	}
	return $Ret;
}

?>
