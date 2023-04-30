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
//            Purpose: This file contains all of the common functions for the
//                     BASE setup routine.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

class BaseSetup{
	var $file;

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

    function BaseSetup($filename)
    {
        // Passes in the filename... This is for the CheckConfig
        $this->file = $filename;
    }
    
    function CheckConfig($distConfigFile)
    {
        // Compares variables in distConfigFile to $this->file
    }
    
    function writeConfig()
    {
        //writes the config file
    }
    
    function displayConfig()
    {
        /*displays current config
         * Not to be confused with the end display on the
         * set up pages!
         */
    }

}
?>
