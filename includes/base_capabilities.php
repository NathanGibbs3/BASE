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
//            Purpose: Capabilities registry to identify what functionality
//                     is available on the currently running PHP install.
//                     This will allow us to vary functionality on the fly.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
//                     Chris Shepherd
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

class BaseCapsRegistry{ // Capabilities Registry class definition
	var $BCReg = array();  // Capabilities Registry.

	function __construct(){ // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ){
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			// @codeCoverageIgnoreStart
			// Should never execute.
			trigger_error( // Will need to add this message to the TD.
				"Class: $SCname No Legacy Constructor.\n",
				E_USER_ERROR
			);
			// @codeCoverageIgnoreEnd
		}
	}

	function BaseCapsRegistry(){ // PHP 4x constructor.
		GLOBAL $Use_Auth_System, $BASE_Language, $event_cache_auto_update,
		$colored_alerts, $archive_exists, $BASE_VERSION, $BASE_installID,
		$debug_time_mode, $debug_mode;
		if( $debug_mode > 1 ){
			KML('Init: Caps Registry', 2);
		}
		// Automatically detect capabilities.
		$this->BCReg['PHP'] = array(); // PHP Capabilities.
		$this->BCReg['BASE'] = array(); // BASE Capabilities.
		// PHP
		$this->AddCap('PHP_Ver', GetPHPSV()); // PHP Version
		if( function_exists('mail') ){ // PHP Mail
			$this->AddCap('PHP_Mail');
		}
		if( function_exists('imagecreate') ){ // PHP GD
			$this->AddCap('PHP_GD');
		}
		// BASE Kernel & RTL Registartion
		if ( SetConst('BASE_KERNEL', 'None') ){
			$BKV = NULL;
		}else{
			$BKV = BASE_KERNEL;
		}
		$this->AddCap('BASE_Kernel',$BKV);
		if ( SetConst('BASE_RTL', 'None') ){
			$BRV = NULL;
		}else{
			$BRV = BASE_RTL;
		}
		$this->AddCap('BASE_RTL',$BRV);
		// BASE Version Info, change on new release.
		$Ver = '1.4.5'; // Official Release
		$Lady = 'lilias'; // Official Release Name
		// Last Dev Merge to master branch, change on new merge.
		$LPM = '2023-04-13';
		// Switch this off and update the official release Unit Test when
		// pushing a new release to master.
		$Dev = true; // Is this a Development build?
		if ( $Dev ){
			$BVer = "$Ver-0.0.1 (Jayme)+$LPM";
		}else{
			$BVer = "$Ver ($Lady)";
		}
		// Example Version String Official 1.4.5 (lilias)
		// Example Version String Dev 1.4.5-0.0.1 (Jayme)
		$this->AddCap('BASE_Ver',$BVer);
		$this->AddCap('BASE_Lady',$Lady);
		$this->AddCap('BASE_LPM',$LPM);
		$this->AddCap('BASE_Dev',$Dev);
		// BASE Capabilities Info, loaded from config file.
		if( LoadedString($BASE_installID) ){ // BASE InstallID
			$this->AddCap('BASE_InID', $BASE_installID);
		}
		if( $Use_Auth_System != 0 ){ // Auth system On.
			$this->AddCap('BASE_Auth');
		}
		if( LoadedString($BASE_Language) ){ // UI Lang.
			$this->AddCap('BASE_Lang', $BASE_Language);
		}
		if( $archive_exists != 0 ){ // Archive DB On.
			$this->AddCap('BASE_ADB');
		}
		if( $event_cache_auto_update != 0 ){ // Event Cache Update.
			$this->AddCap('BASE_ECU');
		}
		if( $colored_alerts != 0 ){ // Colored Alerts
			$this->AddCap('BASE_UICA');
		}
		if( $debug_mode != 0 ){ // Debug Mode
			$this->AddCap('BASE_UIDiag', $debug_mode);
		}
		if( $debug_time_mode != 0 ){ // Debug Time Mode
			$this->AddCap('BASE_UIDiagTime', $debug_time_mode);
		}
		$this->AddCap('UIMode', 'Knl');
		// Libs
		if ( PearInc('Mail', '', 'Mail') ){ // PEAR::MAIL
			$this->AddCap('Mail');
		}
		if ( PearInc('Mime', 'Mail', 'mime') ){ // PEAR::MAIL_Mime
			$this->AddCap('Mime');
		}
//		PEAR::DB
//    @include "DB.php";
//    if (class_exists("DB"))
//    {
//      $this->BCReg[CAPA_PEARDB] = true;
//    } else {
//      $this->BCReg[CAPA_PEARDB] = false;
//    }

		// @codeCoverageIgnoreStart
		if (
			!getenv('TRAVIS')
			&& !(
				$BASE_VERSION == '0.0.0 (Joette)'
				&& $BASE_installID == 'Test Runner'
			)
		){ // God awful hack to keep this code from running under test. As
			// Image_Graph is not currently maintained and throws
			//deprecation errors because of PHP 4x constructors.
			if ( PearInc('Graphing', 'Image', 'Graph') ){ // PEAR::Image_Graph
				$this->AddCap('Graph');
			}
		}
		// @codeCoverageIgnoreEnd
		// Add checks here as needed.
	}

	// Caps Reg Management.
	function AddCap( $cap = '', $val = true ){
		$Ret = false;
		$EMPfx = 'BASE Security Alert ' . __FUNCTION__ . ': ';
		if( LoadedString($cap) ){
			$SRF = false; // SubRegistry Flag
			$SRegs = explode('_', $cap);
			if( count($SRegs) > 1 ){ // SubReg?
				$SRF = true;
				$tmp = $SRegs[0];
			}else{
				$tmp = $cap;
			}
			if( base_array_key_exists($tmp, $this->BCReg) ){ // Is Cap?
				if( is_array($this->BCReg[$tmp]) ){ // Is SubReg?
					// This check also limits SubReg overwrites.
					if ( $SRF ){ // Are we using a SubReg Value?
						$Ret = true; // Set PHP & BASE Caps.
						if(
							!base_array_key_exists(
								$SRegs[1], $this->BCReg[$tmp]
							)
						){ // Write Lock
							$this->BCReg[$tmp][$SRegs[1]] = $val;
						}else{
							error_log(
								$EMPfx . "SubReg: $cap tampering detected."
							);
						}
					}else{
						error_log($EMPfx . "SubReg: $tmp tampering detected.");
					}
				}else{ // Cap Overwrite
					$Ret = true;
					$this->BCReg[$cap] = $val;
				}
			}else{ // Cap Add
				$Ret = true;
				$this->BCReg[$cap] = $val;
			}
		}
		return $Ret;
	}

	function DelCap( $cap = '' ){
		$Ret = false;
		$EMPfx = 'BASE Security Alert ' . __FUNCTION__ . ': ';
		if( LoadedString($cap) ){
			$SRF = false; // SubRegistry Flag
			$SRegs = explode('_', $cap);
			if( count($SRegs) > 1 ){ // SubReg?
				$SRF = true;
				$tmp = $SRegs[0];
			}else{
				$tmp = $cap;
			}
			if( base_array_key_exists($tmp, $this->BCReg) ){ // Is Cap?
				if( is_array($this->BCReg[$tmp]) ){ // Is SubReg?
					$Ret = true; // Fake it. :-)
					error_log($EMPfx . "SubReg: $cap tampering detected.");
				}else{ // Cap Delete.
					$Ret = true;
					unset($this->BCReg[$cap]);
				}
			}else{ // Delete non existant Cap.
				$Ret = true; // Fake it. :-)
				error_log($EMPfx . "Reg: $tmp tampering detected.");
			}
		}
		return $Ret;
	}

	// Capability checking functions.
	function GetCap( $cap = '' ){
		$Ret = false;
		if( LoadedString($cap) ){
			$SRF = false; // SubRegistry Flag
			$SRegs = explode('_', $cap);
			if( count($SRegs) > 1 ){ // SubReg?
				$SRF = true;
				$tmp = $SRegs[0];
			}else{
				$tmp = $cap;
			}
			if( base_array_key_exists($tmp, $this->BCReg) ){ // Is Cap?
				if( is_array($this->BCReg[$tmp]) ){ // Is SubReg?
					if ( $SRF ){ // Are we looking for a SubReg Value?
						// Check PHP & BASE Caps.
						if(
							base_array_key_exists(
								$SRegs[1], $this->BCReg[$tmp]
							)
						){
							$Ret = $this->BCReg[$tmp][$SRegs[1]];
						}
					}else{ // Return Entire SubReg.
						$Ret = $this->BCReg[$tmp];
					}
				}else{
					$Ret = $this->BCReg[$cap];
				}
			}
		}
		return $Ret;
	}

	// @codeCoverageIgnoreStart
	// This output will be installation dependent.
	// Testing would be problematic.

	function DumpCaps(){
		$DI = array();
		$DD = array();
		$Libs = array();
		foreach( $this->BCReg as $key => $val ){
			if( is_array($this->BCReg[$key]) ){
				continue;
			}
			$Libs[$key] = $val;
		}
		foreach( $this->GetCap('PHP') as $key => $val ){
			array_push($DD, $key);
			array_push($DI, $val);
		}
		DDT($DI, $DD, 'PHP Caps', '', '', 1);
		$DI = array();
		$DD = array();
		foreach( $this->GetCap('BASE') as $key => $val ){
			array_push($DD, $key);
			array_push($DI, $val);
		}
		DDT($DI, $DD, 'BASE Caps', '', '', 1);
		$DI = array();
		$DD = array();
		foreach( $Libs as $key => $val ){
			array_push($DD, $key);
			array_push($DI, $val);
		}
		DDT($DI, $DD, 'PEAR Libs', '', '', 1);
	}

	// @codeCoverageIgnoreEnd

}
?>
