<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Creates a user preferences object.
//                     This object will allow the system to track the
//                     preferences of the user. It will also provide basic
//                     functions like change password, etc.....
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class BaseUserPrefs {
	var $db;

	function __construct() { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			trigger_error("Class: $SCname No Legacy Constructor.\n");
		}
	}
	function BaseUserPrefs() { // PHP 4x constructor.
		GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname,
		$alert_host, $alert_port, $alert_user, $alert_password;
		$db = NewBASEDBConnection($DBlib_path, $DBtype);
		$db->baseDBConnect(
			$db_connect_method, $alert_dbname, $alert_host, $alert_port,
			$alert_user, $alert_password
		);
		$this->db = $db;
	}
}

?>
