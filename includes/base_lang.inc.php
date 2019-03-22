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
//            Purpose: UI Language abstraction layer.
//
//          Author(s): Nathan Gibbs

defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class UILang{
	var $UI_Lang;

	function __construct($UILang) { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
			$SCargs = func_get_args();
			call_user_func_array(array($this, $SCname), $SCargs);
		}else{
			trigger_error("Class: $SCname No Legacy Constructor.\n");
		}
	}
	function UILang($UILang) { // PHP 4x constructor.
		$this->UI_Lang = $UILang;
	}
}

?>
