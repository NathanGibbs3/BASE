<?php
// Basic Analysis and Security Engine (BASE)
// Copyright (C) 2019-2020 Nathan Gibbs
// Copyright (C) 2004 BASE Project Team
// Copyright (C) 2000 Carnegie Mellon University
//
//   For license info: See the file 'base_main.php'
//
//       Project Lead: Nathan Gibbs
// Built upon work by: Kevin Johnson & the BASE Project Team
//                     Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
//
//            Purpose: Generates timing information
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

class EventTiming {
	var $start_time;
	var $num_events;
	var $event_log;
	var $verbose;

	function __construct($verbose) { // PHP 5+ constructor Shim.
		// Class/Method agnostic shim code.
		$SCname = get_class();
		if ( method_exists($this, $SCname) ) {
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
	function EventTiming( $verbose ){ // PHP 4x constructor.
		$this->num_events = 0;
		$this->verbose = $verbose;
		$this->start_time = time();
		$this->Mark("Page Load");
	}
	function Mark( $desc ){
		$this->event_log[$this->num_events++] = array ( time(), $desc );
	}
	function PrintTiming(){
		if ( $this->verbose > 0 ){
			$ttime = $this->start_time;
			$Sfx = ' '._SECONDS.']<br/>';
			NLIO ('<!-- Timing Information -->', 6);
			NLIO ("<div class='systemdebug'>", 6);
			NLIO ("["._LOADEDIN.' '.(time() - $ttime ).$Sfx, 7);
			if ( $this->verbose > 1 ){
				NLIO ('Event Log:<br/>', 7);
				for ( $i = 0; $i < $this->num_events; $i++ ){
					$ct = $i + 1;
					if ( $i != 0 ){
						$ttime = $this->event_log[$i-1][0];
					}
					$ttime = $this->event_log[$i][0] - $ttime;
					NLIO ("$ct ".$this->event_log[$i][1]." [$ttime$Sfx", 7);
				}
			}
			NLIO ('</div>', 6);
		}
	}
}
?>
