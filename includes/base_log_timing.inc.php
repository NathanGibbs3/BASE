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
//            Purpose: Generates timing information
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson
// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

class EventTiming {
	var $start_time;
	var $num_events;
	var $event_log;
	var $verbose;

	function __construct( $verbose ){ // PHP 5+ constructor Shim.
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

	function EventTiming( $verbose ){ // PHP 4x constructor.
		GLOBAL $BCR;
		$this->num_events = 0;
		$this->verbose = 0; // Secure Default.
		if( ARC(50) ){ // AG-Editor
			if( !is_int($verbose) ){
				$verbose = 0;
			}
			$this->verbose = $verbose;
		}
		$this->start_time = time();
		// @codeCoverageIgnoreStart
		// Tie in to capabilities Registry.
		if ( isset($BCR) && is_object($BCR) ){
			$tmp = 'BASE Kernel: ' . $BCR->GetCap('BASE_Kernel')
			. ' Initialized. Runtime: ' . $BCR->GetCap('BASE_RTL');
		}else{
			$tmp = 'Page Load';
		}
		// @codeCoverageIgnoreEnd
		$this->Mark("$tmp.");
	}
	function Mark( $desc = '' ){
		if ( !LoadedString($desc) ){
			$desc = 'MARK';
		}
		$this->event_log[$this->num_events++] = array ( time(), $desc );
	}

	function PrintTiming(){
		if ( $this->verbose > 0 ){
			$ttime = $this->start_time;
			$rt = time() - $ttime;
			$ESfx = '</span>';
			$Sfx = ' '._SECONDS.']<br/>';
			$tmp = $this->Classify($rt);
			NLIO('<!-- Timing Information -->', 6);
			NLIO("<div class='systemdebug'>", 6);
			NLIO($tmp . _LOADEDIN . "$ESfx [$rt$Sfx", 7);
			if ( $this->verbose > 1 ){
				NLIO ('Event Log:<br/>', 7);
				for ( $i = 0; $i < $this->num_events; $i++ ){
					$ct = $i + 1;
					if ( $i != 0 ){
						$ttime = $this->event_log[$i-1][0];
					}
					$ttime = $this->event_log[$i][0] - $ttime;
					$tmp = $this->Classify($ttime)
					. $this->event_log[$i][1] . $ESfx;
					NLIO("$ct $tmp [$ttime$Sfx", 7);
				}
			}
			NLIO ('</div>', 6);
		}
	}

	function Classify( $Time ){
		$Ret = '';
		if ( is_int($Time) ){
			$Ret = '<span';
			$clr = '';
			if ( $Time > 59 ){
				$clr = 'red';
			}elseif( $Time > 29 ){
				$clr = 'yellow';
			}elseif( $Time == 0 ){
				$clr = 'green';
			}
			if ( LoadedString($clr) ){
				$Ret .= " style='color: $clr;'";
			}
			$Ret .= '>';
		}
		return $Ret;
	}

}
?>
