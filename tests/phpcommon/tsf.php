<?php
// Test Support Functions.

function GetPHPV (){ // Get PHP Version
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

function GetPHPUV (){ // Get PHPUnit Version
	if ( method_exists('PHPUnit\Runner\Version','id') ){
		// Fix for No NameSpaces on PHP 5.2 or lower.
		$Ret = 0.0; // Default if eval fails.
		eval('$Ret = PHPUnit\Runner\Version::id();');
	}elseif ( method_exists('PHPUnit_Runner_Version','id') ){
		$Ret = PHPUnit_Runner_Version::id();
	}else{
		$Ret = 0.0;
	}
	return $Ret;
}

function LogTC ( $cf,$Item,$Value ){ // Output to Test Console
	GLOBAL $debug_mode;
	if ( $debug_mode > 0 ){
		print "\n$cf Testing $Item: $Value";
	}
}

function ValidateConst( $obj, $Name, $Value ){
	$EPfx = "CONST $Name ";
	IsSetConst($obj, $Name);
	$obj->assertEquals($Value,constant($Name),$EPfx.'unexpected value.');
}

function IsSetConst( $obj, $Name ){
	$EPfx = "CONST $Name ";
	$obj->assertTrue(defined($Name),$EPfx.'not defiend.');
}

?>
