<?php
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
	if (method_exists('PHPUnit\Runner\Version','id')) {
		// Fix for No NameSpaces on PHP 5.2 or lower.
		$Ret = 0.0; // Default if eval fails.
		eval('$Ret = PHPUnit\Runner\Version::id();');
	}elseif ( method_exists('PHPUnit_Runner_Version','id')) {
		$Ret = PHPUnit_Runner_Version::id();
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
