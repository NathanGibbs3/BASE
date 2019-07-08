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
//            Purpose: Application Constants
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

/* IP encapsulated layer4 proto */
SetConst("UDP", 17);
SetConst("TCP", 6);
SetConst("ICMP", 1);
SetConst("SOURCE_PORT", 1);
SetConst("DEST_PORT", 2);
SetConst("SOURCE_IP", 1);
SetConst("DEST_IP", 2);

// Page ID
SetConst("PAGE_QRY_ALERTS", 1);
SetConst("PAGE_STAT_ALERTS", 2);
SetConst("PAGE_STAT_SENSOR", 3);
SetConst("PAGE_QRY_AG", 4);
SetConst("PAGE_ALERT_DISPLAY", 5);
SetConst("PAGE_STAT_IPLINK", 6);
SetConst("PAGE_STAT_CLASS", 7);
SetConst("PAGE_STAT_UADDR", 8);
SetConst("PAGE_STAT_PORTS", 9);

SetConst("NULL_IP", "256.256.256.256");

// Criteria Field count
SetConst("IPADDR_CFCNT", 11);
SetConst("TIME_CFCNT", 10);
SetConst("PROTO_CFCNT", 6);
SetConst("TCPFLAGS_CFCNT", 9);
SetConst("PAYLOAD_CFCNT", 5);

// DB connection method
SetConst("DB_CONNECT", 2);
SetConst("DB_PCONNECT", 1);

// CleanVariable Mask
SetConst("VAR_DIGIT",       1);
SetConst("VAR_LETTER",      2);
SetConst("VAR_ULETTER",     4);
SetConst("VAR_LLETTER",     8);
SetConst("VAR_ALPHA",      16);
SetConst("VAR_PUNC",       32);
SetConst("VAR_SPACE",      64);
SetConst("VAR_FSLASH",    128);
SetConst("VAR_PERIOD",    256);
SetConst("VAR_OPERATOR",  512);
SetConst("VAR_OPAREN",   1024);  /*  (   */
SetConst("VAR_CPAREN",   2048);  /*  )   */
SetConst("VAR_USCORE",   4096);
SetConst("VAR_AT",       8192);
SetConst("VAR_SCORE",   16384);
SetConst("VAR_BOOLEAN", 32768);

function SetConst($var,$val){
	$Ret = false;
	if (is_string($var)){
		if (!empty($var)){
			if (!defined($var)){
				$Ret = define($var, $val);
			}
		}
	}
	return $Ret;
}

?>
