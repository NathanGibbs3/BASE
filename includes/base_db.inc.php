<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Database abstraction layer
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

class baseCon {
	var $DB = NULL; // ADOdb DB driver specific object when set.
	var $DB_class = NULL; // DB Class.
	var $DB_type = NULL; // ADOdb DB Driver.
	var $DB_name = NULL; // DB.
	var $DB_host = NULL; // DB Server.
	var $DB_port = NULL; // DB Server Port.
	var $DB_username = NULL; // DB User.
	var $DBF_RI = false; // DB Feature Flag - Referential Integrity.
	var $DBF_TS = false; // DB Feature Flag - Transaction Support.
	var $lastSQL = ''; // Last SQL statement execution request.
	var $version = 0; // Default to Schema v0 on Init.
	var $sql_trace = NULL; // SQL Trace file handle.
	var $Role = NULL; // Object Role Flag.
	var $FLOP = NULL; // FLoP Extended DB Flag.

	function __construct($type) { // PHP 5+ constructor Shim.
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

	function baseCon($type) { // PHP 4x constructor.
		$this->DB_type = $type;
		// Mysql DB type? Note it in Class structure.
		if( $type == 'mysql' || $type == 'mysqlt' || $type == 'maxsql' ){
			$this->DB_class = 1;
		}else{
			$this->DB_class = 0;
		}
	}

	function baseDBConnect(
		$method, $database, $host, $port, $username, $password, $force = 0
	){
		GLOBAL $use_referential_integrity, $debug_mode, $et, $archive_dbname,
		$archive_host, $archive_port, $archive_user, $archive_password;
		$EMPfx = __FUNCTION__ . ': ';
		// Check archive cookie to see if we need to use the archive tables.
		// Only honnor cookie if not forced to use specified database.
		if ( $force != 1 && ChkArchive() ){ // Connect to archive DB.
			$DBDesc = 'Archive'; // Need to TD this in Issue #11 branch.
			$this->Role = $DBDesc; // Set Object Role.

      if ( $method == DB_CONNECT )
        $this->baseConnect($archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password);
      else
        $this->basePConnect($archive_dbname, $archive_host, $archive_port, $archive_user, $archive_password);

		}else{ // Connect to the main alert tables
			$DBDesc = 'Alert'; // Need to TD this in Issue #11 branch.
			$this->Role = $DBDesc; // Set Object Role.

      if ( $method == DB_CONNECT )
        $this->baseConnect($database, $host, $port, $username, $password);
      else
        $this->basePConnect($database, $host, $port, $username, $password);

		}
		if( $this->baseGetDBversion() > 105 ){
			// FLoPS released after Schema v106
			$this->baseSetFLOP(); // Detect FLoP Extended DB.
		}
		$this->baseSetRI(); // Detect Referential Integrity.
		// Need to TD these in Issue #11 branch.
		KML($EMPfx . "DB Connect: $DBDesc.", 3);
		if( is_object($et) && $debug_mode > 1 ){
			$et->Mark("DB Connect: $DBDesc.");
			$et->Mark('DB RI set to ' . var_export($this->DBF_RI, true) . '.');
		}
	}

	function baseConnect ( $database, $host, $port, $username, $password ){
		GLOBAL $sql_trace_mode, $sql_trace_file;
		$this->DB = NewADOConnection();
		$this->DB_name = $database;
		$this->DB_host = $host;
		$this->DB_port = $port;
		$this->DB_username = $username;
		$DSN = $this->DB_host;
		$tdp = $this->DB_port;
		if ( LoadedString($tdp) ){
			$DSN = "$DSN:$tdp";
		}
		$tmp = $this->DB_name . '@' . $DSN;

     if ( $sql_trace_mode > 0 )
     {
        $this->sql_trace = fopen($sql_trace_file,"a");
        if ( !$this->sql_trace )
        {
           ErrorMessage(_ERRSQLTRACE." '".$sql_trace_file."'");
           die();
        }
     }

		$db = $this->DB->Connect($DSN, $username, $password, $database);
		if( !$db ){
        echo '<P><B>'._ERRSQLCONNECT.' </B>'.
             $tmp ._ERRSQLCONNECTINFO;

        echo $this->baseErrorMessage();
        die();
     } 

		$this->baseSetDBversion(); // Set Object DB schema version number.
     if ( $sql_trace_mode > 0 )
     {
        fwrite($this->sql_trace, 
              "\n--------------------------------------------------------------------------------\n");  
        fwrite($this->sql_trace, "Connect [".$this->DB_type."] ".$database."@".$host.":".$port." as ".$username."\n");
        fwrite($this->sql_trace, "[".date ("M d Y H:i:s", time())."] ".$_SERVER["SCRIPT_NAME"]." - db version ".$this->version);
        fwrite($this->sql_trace, 
              "\n--------------------------------------------------------------------------------\n\n");
        fflush($this->sql_trace);
     }     

		return $db;
	}

	function basePConnect ( $database, $host, $port, $username, $password ){
		GLOBAL $sql_trace_mode, $sql_trace_file;
		$this->DB = NewADOConnection();
		$this->DB_name = $database;
		$this->DB_host = $host;
		$this->DB_port = $port;
		$this->DB_username = $username;
		$DSN = $this->DB_host;
		$tdp = $this->DB_port;
		if ( LoadedString($tdp) ){
			$DSN = "$DSN:$tdp";
		}
		$tmp = $this->DB_name . '@' . $DSN;

     if ( $sql_trace_mode > 0 )
     {
        $this->sql_trace = fopen($sql_trace_file,"a");
        if ( !$this->sql_trace )
        {
           ErrorMessage(_ERRSQLTRACE." '".$sql_trace_file."'");
           die();
        }
     }

		$db = $this->DB->PConnect($DSN, $username, $password, $database);
		if( !$db ){
        echo '<P><B>'._ERRSQLPCONNECT.' </B>'.
             $tmp ._ERRSQLCONNECTINFO;

        echo $this->baseErrorMessage();
        die();
     } 

		$this->baseSetDBversion(); // Set Object DB schema version number.
     if ( $sql_trace_mode > 0 )
     {
        fwrite($this->sql_trace, 
              "\n--------------------------------------------------------------------------------\n");  
        fwrite($this->sql_trace, "PConnect [".$this->DB_type."] ".$database."@".$host.":".$port." as ".$username."\n");
        fwrite($this->sql_trace, "[".date ("M d Y H:i:s", time())."] ".$_SERVER["SCRIPT_NAME"]." - db version ".$this->version);
        fwrite($this->sql_trace, 
              "\n--------------------------------------------------------------------------------\n\n");
        fflush($this->sql_trace);
     } 

		return $db;
	}

	function baseClose (){
		$this->DB->Close();
		// Issue #204
		$this->DB_name = NULL; // DB.
		$this->DB_host = NULL; // DB Server.
		$this->DB_port = NULL; // DB Server Port.
		$this->DB_username = NULL; // DB User.
		$this->DBF_RI = false; // DB Feature Flag - Referential Integrity.
		$this->DBF_TS = false; // DB Feature Flag - Transaction Support.
		$this->lastSQL = ''; // Last SQL statement execution request.
		$this->version = 0; // Default to Schema v0 on Init.
		$this->Role = NULL; // Object Role Flag.
		$this->FLOP = NULL; // FLoP Extended DB Flag.
	}

	function baseisDBUp( $LogError = false ){
		$PHPVer = GetPHPSV();
		// @codeCoverageIgnoreStart
		if( $PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 3) ){
			$tmp = debug_backtrace(0, 2); // PHP 5.4+ Limit backtrace.
		}else{
			$tmp = debug_backtrace(0);
		}
		// @codeCoverageIgnoreEnd
		$EMPfx = $tmp[1]['function'] . ': ';
		$Ret = false;
		if( !is_bool($LogError) ){ // Input Validation
			$DS = false;
		}
		if( !is_null($this->DB) && $this->DB->isConnected() ){
			$Ret = true;
		}else{
			if( $LogError ){
				KML($EMPfx . 'DB not connected.', 3);
			}
		}
		return $Ret;
	}

	function baseExecute(
		$sql, $start_row = 0, $num_rows = -1, $hard_error = true
	){
		GLOBAL $debug_mode, $sql_trace_mode, $db_connect_method,
			$alert_password, $archive_dbname, $archive_host, $archive_port,
			$archive_user, $archive_password;
		$EPfx = 'BASE DB ';
		$tdt = $this->DB_type;
		$tdn = $this->DB_name;
		$DSN = $this->DB_host;
		$tdp = $this->DB_port;
		$tdu = $this->DB_username;
		$rs = false; // Default returns failure.
		if (
			$DSN == $archive_host && $tdp == $archive_port
			&& $tdn == $archive_dbname && $tdu == $archive_user
		){
			$tdpw = $archive_password;
		}else{
			$tdpw = $alert_password;
		}
		if ( LoadedString($tdp) ){
			$DSN = "$DSN:$tdp";
		}
		$TDSN = $this->DB_name . '@' . $DSN;
		// Begin DB specific SQL fix-up.
		// @codeCoverageIgnoreStart
		// We have no way of testing Oracle or MsSQL functionality.
		if ( $this->DB_type == 'mssql' ){
			$sql = preg_replace("/''/i", "NULL", $sql);
		}elseif ( $this->DB_type == 'oci8' ){
			if (!strpos($sql, 'TRIGGER')){
				if (substr($sql, strlen($sql)-1, strlen($sql))==';'){
					$sql=substr($sql, 0, strlen($sql)-1);
				}
			}
		}
		// @codeCoverageIgnoreEnd
		if( !$this->DB->isConnected() ){
			// Check for connection before executing query.
			// Try to reconnect of DB connection is down.
			// Found via CI. Might be related to PHP 5.2x not supporting
			// persistant DB connections.
			error_log($EPfx."Disconnected: $tdt $TDSN");
			error_log($EPfx."Reconnecting: $tdt $TDSN");
			if ( $db_connect_method == DB_CONNECT ){
				$db = $this->DB->Connect( $DSN, $tdu, $tdpw, $tdn);
			}else{
				$db = $this->DB->PConnect( $DSN, $tdu, $tdpw, $tdn);
			}
			if( !$this->DB->isConnected() ){
				FatalError("$EPfx Reconnect Failed");
			}else{
				error_log("$EPfx Reconnected");
			}
		}
		$this->lastSQL = $sql;
		$limit_str = '';
		if ( is_int($start_row) & is_int($num_rows) ){ // Issue #169
			if ( $num_rows != -1 ){ // Do we add a LIMIT / TOP / ROWNUM clause.
				if ( $this->DB_class == 1 ){
					$limit_str = " LIMIT ".$start_row.", ".$num_rows;
				// @codeCoverageIgnoreStart
				// We have no way of testing Oracle functionality.
				}elseif ( $this->DB_type == "oci8" ){
					// $limit_str = " LIMIT ".$start_row.", ".$num_rows;
					// Why, we don't use it.
				// @codeCoverageIgnoreEnd
				}elseif ( $this->DB_type == "postgres" ){
					$limit_str = " LIMIT ".$num_rows." OFFSET ".$start_row;
				}
			}
		}else{ // Log error & quit.
			$msg = $EPfx.'Query Halt: Invalid LIMIT.';
			error_log($msg);
			return $rs;
		}
		$qry = $sql.$limit_str;
		if ( $debug_mode > 1 ){
			// See: https://github.com/NathanGibbs3/BASE/issues/113
			// Some legecy code has " 1 = 1 " in the query string. Log it here.
			if ( strstr($qry, ' 1 = 1 ') ){
				error_log("Issue #113 $qry");
				error_log('See: https://github.com/NathanGibbs3/BASE/issues/113');
			}
		}
		// See: https://github.com/NathanGibbs3/BASE/issues/67
		// Legacy code assumed $this->DB->Execute() returns a valid recordset.
		// It returns false on error. Catch it here.
		$result = $this->DB->Execute($qry);
		if( $result ){
			$rs = new baseRS($result, $this->DB_type);
		}
		// @codeCoverageIgnoreStart
		// We have no way of testing this functionality on these DB's
		if ( $num_rows != -1 && $limit_str == '' && $rs != false ){
			// DB's which do not support LIMIT (e.g. MS SQL) natively must
			// emulated it by walking the current row from the start of
			// rowset to the desired start row.
			$i = 0;
			while ( ($i < $start_row) && $rs ){
				if ( !$rs->row->EOF ){
					$rs->row->MoveNext();
				}
				$i++;
			}
		}
		// @codeCoverageIgnoreEnd
     if ( $sql_trace_mode > 0 )
     {
        fputs($this->sql_trace, $sql."\n");
        fflush($this->sql_trace);
     }
		$tmp = $this->baseErrorMessage();
		if ( (!$rs || $tmp != '') && $hard_error ){
			$msg = $EPfx . 'Query Fail: ';
			if ( !$rs ){
				$msg .= 'NULL Recordset ';
			}
			if ( $tmp !='' ){
				$msg .= $tmp;
			}else{
				$msg .= 'NO ADOdb Error Msg';
			}
			$msg = returnErrorMessage($msg,0,1);
			if ( $debug_mode > 0
				// Issue #5 Info Shim
				|| (
					getenv('TRAVIS')
					&& version_compare(PHP_VERSION, "5.3.0", "<")
				)
			){
				$msg .= "<p>DB Engine: $tdt DB: $TDSN</p>";
				$msg .= '<p>SQL QUERY: <code>'.$qry.'</code></p>';
			}
			FatalError($msg);
		}else{
			return $rs;
		}
	}

	function baseErrorMessage(){
		GLOBAL $debug_mode;
		$msg = '';
		$tmp = $this->DB->ErrorMsg();
		if ( $tmp ){
			$msg = '<b>'._ERRSQLDB.'</b> ';
			$msg .= $tmp;
			if ( $debug_mode > 0 ){
				$msg .= '<p><code>'.$this->lastSQL.'</code></p>';
			}
			// @codeCoverageIgnoreStart
			// We have no way of testing MsSQL functionality.
			// MsSQL Error messages that are not issues.
			if ( $this->DB_type == 'mssql' && preg_match(
				"/Changed (databas|languag)e (context|setting) to/", $tmp
			)){
				$msg = '';
			}
			// @codeCoverageIgnoreEnd
		}
		return $msg;
	}

	function baseSetFLOP ( ){ // Detect FLoP Extended DB.
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = false;
		if( $this->baseisDBUp() ){
			if(
				$this->baseFieldExists('schema', 'full_payload')
				&& $this->baseFieldExists('schema', 'reference')
				&& $this->baseFieldExists('event', 'reference')
				&& $this->baseFieldExists('data', 'pcap_header')
				&& $this->baseFieldExists('data', 'data_header')
			){
				KML($EMPfx . 'FLoP DB detected', 1);
				$Ret = true;
			}
			$this->FLOP = $Ret;
		}
		return $Ret;
	}

	function baseGetFLOP ( ){
		$Ret = false;
		if( !is_null($this->FLOP) ){
			$Ret = $this->FLOP;
		}
		return $Ret;
	}

	function baseSetRI( $DS = true ){ // DB Referential Integrity Control.
		GLOBAL $use_referential_integrity, $BCR;
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = false; // Return Value
		$RIF = false; // Referential Integrity Flag.
		// @codeCoverageIgnoreStart
		if( isset($BCR) && is_object($BCR) ){
			$RIF = $BCR->GetCap('BASE_SSRI');
		}else{
			if( intval($use_referential_integrity) == 1 ){
				$RIF = true;
			}
		}
		// @codeCoverageIgnoreEnd
		if( $RIF && $this->baseisDBUp(true) ){
			if( !is_bool($DS) ){ // Lock Invalid Desired State Flag.
				$DS = true;
			}
			$RItbls = array(
				'iphdr', 'tcphdr', 'udphdr', 'icmphdr', 'opt', 'data',
				'acid_ag_alert', 'acid_event'
			);
			$SE = true; // Step Execution Flag Assume Success
			foreach( $RItbls as $val ){ // Build Constraint list.
				$EPfx = "$EMPfx$val ";
				if( $this->baseTableExists($val) ){ // Table Exists?
					$RIcl[$val] = $val . '_fkey_sid_cid';
				}else{
					// @codeCoverageIgnoreStart
					KML($EPfx . 'does not exist.', 3);
					KML($EMPfx . 'DB damaged.', 3);
					$SE = false;
					break;
					// @codeCoverageIgnoreEnd
				}
			}
			if( $SE ){ // Ready to take RI Action.
				$MariaDB = false; // MariaDB Flag.
				$tmp = $this->DB->serverInfo();
				if(
					$this->DB_class == 1
					&& preg_match( "/MariaDB/", $tmp['description'])
				){ // MariaDB Check
					$MariaDB = true;
				}
				$tmp = $tmp['version'];
				$DBSV = VS2SV($tmp);
				if( $DS ){ // Set RI if possible.
					$QF = false; // Query Flag.
					$RIE = false; // RI Enable Flag.
					$RSC = false; // RI Structure Corrupt Flag.
					// Check DB Server Version.
					if( $this->DB_class == 1 ){
						if(
							$DBSV[0] > 3 || ($DBSV[0] == 3 && $DBSV[1] > 22)
						){ // Mysql / MariaDB 3.23+
							foreach( $RItbls as $val ){
								// Check Tables for InnoDB or NDB SE.
								if(
									!preg_match (
										"/^(Inno|N)DB/", $this->baseTSE($val)
									)
								){ // Table failed SE check.
									$SE = false;
									break;
								}
							}
							$QF = $SE; // Step Execute to QF.
						}
					}else{
						switch( $this->DB_type ){
							case 'postgres';
								if( $DBSV[0] > 6 ){ // PostgreSQL 7+
									$QF = true;
								}
								break;
								// @codeCoverageIgnoreStart
								// We have no way of testing Oracle or MsSQL
								// functionality.
							case 'mssql';
								if(
									$DBSV[0] > 8
									|| (
										$DBSV[0] == 8 && $DBSV[1] == 0
										&& $DBSV[2] > 193
									)
								){ // MsSQL Server 2000+ (8.0.194)+
									$QF = true;
								}
								break;
							case 'oci8'; // Have no idea.
								// Until we can get some definitive data on
								// Issue #103 RI support for Oracle will be
								// disabled.
							default:
								// @codeCoverageIgnoreEnd
						}
					}
					if( $QF ){ // Query Info Schema for RI Information.
						$sqlPfx = 'SELECT ';
						$LPfx = '';
						$RPfx = 'referenced_';
						if( $this->DB_type == 'postgres' ){
							$LPfx = 'kcu.';
							$RPfx = 'ccu.';
							$sqlPfx .= $LPfx . 'COLUMN_NAME, ' . $RPfx
							. 'COLUMN_NAME AS REFERENCED_COLUMN_NAME FROM '
							. 'information_schema.key_column_usage AS kcu JION'
							. ' information_schema.constraint_column_usage'
							. " AS ccu ON $RPfx" . 'constraint_name = '
							. $LPfx . 'constraint_name';
						}else{
							$sqlPfx .= $LPfx . 'COLUMN_NAME, ' . $RPfx
							. 'COLUMN_NAME FROM '
							. 'information_schema.key_column_usage';
						}
						$sqlPfx .= " WHERE $RPfx" . "table_name = 'event'"
						. " AND $LPfx" . "TABLE_SCHEMA = '" . $this->DB_name
						. "' AND $LPfx" . "TABLE_NAME = '";
						foreach( $RItbls as $val ){
							$EPfx = "$EMPfx$val ";
							$Cval = $RIcl[$val];
							$sql = "$sqlPfx$val' AND $LPfx"
							. "CONSTRAINT_NAME = '"
							. $Cval . "'";
							DumpSQL($sql, 3);
							$rs = $this->DB->Execute($sql);
							if(
								$rs != false && $this->baseErrorMessage() == ''
							){ // Error Check
								if( $rs->RecordCount() > 0 ){
									$tmp = '';
									if( $val  == 'acid_ag_alert' ){
										$tmp = 'ag_';
									}
									// RI setup in DB table, Verify Structure.
									while( !$rs->EOF ){
										$myrow = $rs->fields;
										$myrow[0] = preg_replace(
											'/^' . $tmp . '/', '', $myrow[0]
										);
										if( $myrow[0] != $myrow[1] ){
											// @codeCoverageIgnoreStart
											$rs->Close(); // Corrupt Structure.
											$RSC = true; // Restructure
											break 2;
											// @codeCoverageIgnoreEnd
										}
										$rs->MoveNext();
									}
									$rs->Close();
								}else{ // RI Not setup in DB table.
									$RSC = true; // Restructure
									break;
								}
							}else{ // Transient DB Error.
								// @codeCoverageIgnoreStart
								KML($EPfx . 'access error.', 3);
								$SE = false; // Failure
								break;
								// @codeCoverageIgnoreEnd
							}
						}
						if( $RSC ){ // Clear DB RI Structure
							KML($EPfx . 'RI Structure Corrupt', 3);
							$this->baseSetRI(false);
							$RIE = true;
						}
					}
					if( $RIE ){ // Enable RI in DB.
						$SE = true; // Assume Success
						foreach( $RItbls as $val ){
							$EPfx = "$EMPfx$val ";
							$Cval = $RIcl[$val];
							$tmp = '';
							if( $val  == 'acid_ag_alert' ){
								$tmp = 'ag_';
							}
							$sql = "ALTER TABLE $val ADD CONSTRAINT $Cval "
							. "FOREIGN KEY ($tmp" . "sid, $tmp"
							. 'cid) REFERENCES event (sid, cid) ON DELETE '
							. 'CASCADE ON UPDATE CASCADE';
							DumpSQL($sql, 3);
							$rs = $this->DB->Execute($sql);
							if (
								$rs != false && $this->baseErrorMessage() == ''
							){ // Error Check
								$rs->Close();
								KML($EPfx . 'RI enabled.', 3);
							}else{ // Transient DB Error.
								// @codeCoverageIgnoreStart
								KML($EPfx . 'RI enable error.', 3);
								$SE = false;
								break;
								// @codeCoverageIgnoreEnd
							}
						}
					}
				}else{ // Clear RI.
					$SE = false; // Return Value.
					$tmp = 'CONSTRAINT';
					$tmp2 = 'IF EXISTS ';
					// @codeCoverageIgnoreStart
					if( $this->DB_class == 1 ){
						// As of MySQL 8.0.19, ALTER TABLE permits more general
						// (and SQL standard) syntax for dropping and altering
						// existing constraints of any type,
						// https://dev.mysql.com/doc/refman/8.0/en/alter-table.html
						if(
							$DBSV[0] < 8
							|| (
								$DBSV[0] == 8 && $DBSV[1] == 0 && $DBSV[2] < 19
							)
						){ // Mysql / MariaDB < 8.0.19
							$tmp = 'FOREIGN KEY';
						}
						// No IF EXISTS for ALTER TABLE on MySQL
						if( !$MariaDB ){ // MySQL
							$tmp2 = '';
						}
					}
					// @codeCoverageIgnoreEnd
					foreach( $RItbls as $val ){
						$EPfx = "$EMPfx$val ";
						$Cval = $RIcl[$val];
						$sql = "ALTER TABLE $val DROP $tmp $tmp2$Cval";
						DumpSQL($sql, 3);
						$rs = $this->DB->Execute($sql);
						if(
							$rs != false && $this->baseErrorMessage() == ''
						){ // Error Check
							$rs->Close();
							KML($EPfx . 'RI disabled.', 3);
						}else{ // Transient DB Error.
							// @codeCoverageIgnoreStart
							KML($EPfx . 'access error.', 3);
							break;
							// @codeCoverageIgnoreEnd
						}
					}
				}
				KML($EMPfx . 'DB RI set to ' . var_export($SE, true) . '.', 3);
				$this->DBF_RI = $SE;
			}
			$Ret = $SE;
		}
		return $Ret;
	}

	function baseGetRI ( ){
		$Ret = false;
		if( is_bool($this->DBF_RI) ){
			$Ret = $this->DBF_RI;
		}
		return $Ret;
	}

	function baseTSE( $table = '' ){ // Get Table Storage Engine.
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = '';
		if( $this->baseisDBUp(true) ){
			if( $this->DB_class == 1 ){ // Mysql / MariaDB.
				if( !LoadedString($table) ){
					$table = '';
				}
				$EMPfx .= "$table ";
				if( $this->baseTableExists($table) ){ // Get Table SE.
					$sql = 'SELECT ENGINE FROM information_schema.TABLES '
					. "WHERE TABLE_SCHEMA = '" . $this->DB_name
					. "' AND TABLE_NAME = '" . $table . "'";
					DumpSQL($sql, 3);
					$rs = $this->DB->Execute($sql);
					if (
						$rs != false && $this->baseErrorMessage() == ''
						&& $rs->RecordCount() > 0
					){ // Error Check
						$myrow = $rs->fields;
						$Ret = $myrow[0];
						$rs->Close();
					}else{ // Transient DB Error.
						// @codeCoverageIgnoreStart
						KML($EMPfx . 'access error.', 3);
						// @codeCoverageIgnoreEnd
					}
				}else{
					KML($EMPfx . 'does not exist.', 3);
				}
			}
		}
		return $Ret;
	}

	function baseFieldExists( $table = '', $field = '' ){
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = false;
		if( $this->baseisDBUp(true) ){
			if( !LoadedString($table) ){
				$table = '';
			}
			if( !LoadedString($field) ){
				$field = '';
			}
			if( $this->baseTableExists($table) ){
				if( in_array($field, $this->DB->metacolumnNames($table)) ){
					$Ret = true;
				}
			}
		}
		return $Ret;
	}

	function baseTableExists( $table = '' ){
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = false;
		if( $this->baseisDBUp(true) ){
			if( !LoadedString($table) ){
				$table = '';
			}
			// @codeCoverageIgnoreStart
			// We have no way of testing Oracle functionality.
			if( $this->DB_type == 'oci8' ){
				$table=strtoupper($table);
			}
			// @codeCoverageIgnoreEnd
			if( in_array($table, $this->DB->MetaTables()) ){
				$Ret = true;
			}
		}
		return $Ret;
	}

	// This function is not used anywhere.
	function baseIndexExists( $table = '', $index_name = '' ){
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = false;
		if( $this->baseisDBUp(true) ){
			if( !LoadedString($table) ){
				$table = '';
			}
			if( !LoadedString($index_name) ){
				$index_name = '';
			}
			if( $this->baseTableExists($table) ){
				$tmp = $this->DB->MetaIndexes($table);
				if( $tmp != false ){
					foreach ($tmp as $key => $value) { // Iterate Index List
						if( is_key('columns', $value) ){
							if(
								in_array(
									$index_name,
									array_values($value['columns'])
								)
							){
								$Ret = true;
							}
						}
					}
				}
			}
		}
		return $Ret;
	}

	function baseInsertID( $table = '', $field = '' ){
		$Ret = -1;
		if( $this->baseisDBUp(true) ){
			if( !LoadedString($table) ){
				$table = '';
			}
			if( !LoadedString($field) ){
				$field = '';
			}
			// Getting the insert ID fails on certain databases
			// (e.g. postgres), but we may use it on the DB's it works on.
			// This function returns -1 if the dbtype is postgres, then we can
			// run a kludge query to get  the insert ID. That query may vary
			// depending upon which table you  are looking at and what
			// variables you have set at the current  point, so it can't be
			// here. It needs to be in the actual script after calling this
			// function.  srh (02/01/2001)
			$DALV = GetDALSV(); // ADOdb Version
			if( $DALV[0] > 5 || ($DALV[0] == 5 && $DALV[1] > 20) ){
				// Use Insert_ID everywhere on ADOdb 5.21+
				if(
					$this->DB_type == 'postgres'
					&& (
						($DALV[0] == 5 && $DALV[1] == 22 && $DALV[2] < 6)
						|| ($DALV[0] == 5 && $DALV[1] == 21 && $DALV[2] < 5)
					)
				){ // Catch ADOdb #978 - ADOdb 5.21x < 5.21.5 & 5.22x < 5.22.6
					$Ret = @$this->DB->Insert_ID($table, $field);
				}else{
					$Ret = $this->DB->Insert_ID($table, $field);
				}
			}else{ // ADOdb < 5.21x
				if( $DALV[0] > 3 || ($DALV[0] == 3 && $DALV[1] > 93) ){
					if ($this->DB_type != 'oci8' ){
						// Everywhere but Oracle on ADOdb 3.94+
						if(
							$this->DB_type == 'postgres'
							&& (
								(
									$DALV[0] == 5 && $DALV[1] == 20
									&& $DALV[2] < 22
								)
								|| ($DALV[0] == 5 && $DALV[1] > 17)
							)
						){ // Catch ADOdb #978 - ADOdb 5.18 - 5.20.21
							$Ret = @$this->DB->Insert_ID($table, $field);
						}else{
							$Ret = $this->DB->Insert_ID($table, $field);
						}
					}
				}else{ // Only MySQL && MsSQL on ADOdb < 3.94x
					// @codeCoverageIgnoreStart
					if( $this->DB_class == 1 || $this->DB_type == 'mssql' ){
						$Ret = $this->DB->Insert_ID($table, $field);
					}
					// @codeCoverageIgnoreEnd
				}
			}
			if( $Ret == false ){ // No Insert or DB does not support InsertID.
				$Ret = -1;
			}
		}
		return $Ret;
	}

  function baseTimestampFmt($timestamp)
  {
    // Not used anywhere????? -- Kevin
     return $this->DB->DBTimeStamp($timestamp);
  }

  function baseSQL_YEAR($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 || $this->DB_type == "mssql" )
        return " YEAR($func_param) $op $timestamp ";
     else if( $this->DB_type == "oci8" )
        return " to_number( to_char( $func_param, 'RRRR' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('year', $func_param) $op $timestamp ";  
  }

  function baseSQL_MONTH($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 || $this->DB_type == "mssql" )
        return " MONTH($func_param) $op $timestamp ";
     else if( $this->DB_type == "oci8" )
        return " to_number( to_char( $func_param, 'MM' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('month', $func_param) $op $timestamp "; 
  }

  function baseSQL_DAY($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 )
        return " DAYOFMONTH($func_param) $op $timestamp ";
     else if($this->DB_type == "oci8")
        return " to_number( to_char( $func_param, 'DD' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('day', $func_param) $op $timestamp "; 
     else if ( $this->DB_type == "mssql" )
        return " DAY($func_param) $op $timestamp ";        
  }

  function baseSQL_HOUR($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 )
        return " HOUR($func_param) $op $timestamp ";
     else if($this->DB_type == "oci8")
        return " to_number( to_char( $func_param, 'HH' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('hour', $func_param) $op $timestamp "; 
     else if ( $this->DB_type == "mssql" )
        return " DATEPART(hh, $func_param) $op $timestamp ";
  }

  function baseSQL_MINUTE($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 )
        return " MINUTE($func_param) $op $timestamp ";
     else if($this->DB_type == "oci8")
        return " to_number( to_char( $func_param, 'MI' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('minute', $func_param) $op $timestamp "; 
     else if ( $this->DB_type == "mssql" )
        return " DATEPART(mi, $func_param) $op $timestamp ";
  }

  function baseSQL_SECOND($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 )
        return " SECOND($func_param) $op $timestamp ";
     else if($this->DB_type == "oci8")
        return " to_number( to_char( $func_param, 'SS' ) ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
        return " DATE_PART('second', $func_param) $op $timestamp "; 
     else if ( $this->DB_type == "mssql" )
        return " DATEPART(ss, $func_param) $op $timestamp ";
  }

  function baseSQL_UNIXTIME($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 ) {
        return " UNIX_TIMESTAMP($func_param) $op $timestamp ";
     }
     else if($this->DB_type == "oci8")
        return " to_number( $func_param ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
     {
        if ( ($op == "") && ($timestamp == "") )
           /* Catches the case where I want to get the UNIXTIME of a constant
            *   i.e. DATE_PART('epoch', timestamp) > = DATE_PART('epoch', timestamp '20010124')
            *                                            (This one /\ )
            */
           return " DATE_PART('epoch', $func_param::timestamp) ";
        else
           return " DATE_PART('epoch', $func_param::timestamp) $op $timestamp ";
     } 
     else if ($this->DB_type == "mssql")
     {
           return " DATEDIFF(ss, '1970-1-1 00:00:00', $func_param) $op $timestamp ";
     }
     
  }

  function baseSQL_TIMESEC($func_param, $op, $timestamp)
  {
	if ( $this->DB_class == 1 )
        return " TIME_TO_SEC($func_param) $op $timestamp ";
     else if($this->DB_type == "oci8")
        return " to_number( $func_param ) $op $timestamp ";
     else if ( $this->DB_type == "postgres" )
     {
    
        if ( ($op == "") && ($timestamp == "") )
           return " DATE_PART('second', DATE_PART('day', '$func_param') ";
        else
           return " DATE_PART('second', DATE_PART('day', $func_param) ) $op $timestamp ";
     } 
     else if ( $this->DB_type == "mssql" )
     {
        if ( ($op == "") && ($timestamp == "") )
           return " DATEPART(ss, DATEPART(dd, $func_parm) ";
        else
           return " DATEPART(ss, DATE_PART(dd, $func_param) ) $op $timestamp ";
 
     }
     
  }

	function baseSetDBversion(){
		$EMPfx = __FUNCTION__ . ': ';
		$Ret = 0;
		if( $this->baseisDBUp(true) ){
			$EMPfx .= $this->Role . ' DB Schema ';
			if( $this->baseFieldExists('schema', 'vseq') ){
				// Get the database schema version number.
				$tmp = 'schema';
				if( $this->DB_class == 1 ){ // Mysql drivers.
					$tmp = "`$tmp`";
				}else{
					// @codeCoverageIgnoreStart
					// We have no way of testing MsSQL functionality.
					if( $this->DB_type == 'mssql'){ // MsSQL driver.
						$tmp = "[$tmp]";
					}
					// @codeCoverageIgnoreEnd
				}
				$sql = "SELECT vseq FROM $tmp";
				$rs = $this->DB->Execute($sql);
				if (
					$rs != false
					&& $this->baseErrorMessage() == ''
					&& $rs->RecordCount() > 0
				){ // Error Check
					$myrow = $rs->fields;
					$Ret = intval($myrow[0]);
					$rs->Close();
				}else{
					// @codeCoverageIgnoreStart
					// Transient DB Error.
					KML($EMPfx . 'access error.', 3);
					// @codeCoverageIgnoreEnd
				}
			}else{
				KML($EMPfx . 'undefined.', 3);
			}
			KML($EMPfx . "set to $Ret", 3);
			$this->version = $Ret;
		}
		return $Ret;
	}

	function baseGetDBversion(){
		return $this->version;
	}

	function getSafeSQLString($str){
   $t = str_replace("\\", "\\\\", $str);
   if ($this->DB_type != "mssql" && $this->DB_type != "oci8" )
     $t = str_replace("'", "\'", $t);
   else
     $t = str_replace("'", "''", $t);
   $t = str_replace("\"", "\\\\\"", $t);

   return $t;
	}
}

class baseRS {
	var $row;
	var $DB_type;
	var $DB_class;

	function __construct($id, $type) { // PHP 5+ constructor Shim.
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
	function baseRS($id, $type) {
		$this->row = $id;
		$this->DB_type = $type;
		// Are we a Mysql type? Note it in Class structure.
		if( $type == 'mysql' || $type == 'mysqlt' || $type == 'maxsql' ){
			$this->DB_class = 1;
		}else{
			$this->DB_class = 0;
		}
	}
	function baseFetchRow(){
		GLOBAL $debug_mode;
		$Ret = '';
		if ( !is_object($this->row) ){
			// Workaround for the problem, that the database may contain NULL
			// whereas "NOT NULL" has been defined, when it was created.
			if ( $debug_mode > 1 ){
         echo "<BR><BR>" . __FILE__ . ':' . __LINE__ . ": ERROR: \$this->row is not an object (1)<BR><PRE>";
         debug_print_backtrace();
         echo "<BR><BR>";
         echo "var_dump(\$this):<BR>";
         var_dump($this);
         echo "<BR><BR>";
         echo "var_dump(\$this->row):<BR>";
         var_dump($this->row);
         echo "</PRE><BR><BR>";
			}
		}else{
			if ( !$this->row->EOF ){
				$Ret = $this->row->fields;
				$this->row->MoveNext();
			}
		}
		return $Ret;
	}
  function baseColCount()
  {
    // Not called anywhere???? -- Kevin
     return $this->row->FieldCount();
  }

  function baseRecordCount()
  {  
    GLOBAL $debug_mode;

    if (!is_object($this->row))
    {
      if ($debug_mode > 1)
      {
        echo '<BR><BR>';
        echo __FILE__ . ':' . __LINE__ . ': ERROR: $this->row is not an object (2).';
        echo '<BR><PRE>';
        debug_print_backtrace();
        echo '<BR><BR>var_dump($this):<BR>';
        var_dump($this);
        echo '<BR><BR>var_dump($this->row):<BR>';
        var_dump($this->row);
        echo '</PRE><BR><BR>';
      }

      return 0;
    }
 
     // Is This if statement necessary?  -- Kevin
     /* MS SQL Server 7, MySQL, Sybase, and Postgres natively support this function */ 
	if ( $this->DB_class == 1 ||
          ($this->DB_type == "mssql") || ($this->DB_type == "sybase") || ($this->DB_type == "postgres") || ($this->DB_type == "oci8"))
        return $this->row->RecordCount();

     /* Otherwise we need to emulate this functionality */
     else 
     {
          $i = 0;
          while ( !$this->row->EOF )
          {
             ++$i;
             $this->row->MoveNext();
          }

          return $i;
     }
  }

  function baseFreeRows()
  {
    GLOBAL $debug_mode;

    /* Workaround for the problem, that the database may contain NULL,
     * although "NOT NULL" had been defined when it had been created. 
     * In such a case there's nothing to free(). So we can ignore this
     * row and don't have anything to do. */
    if (!is_object($this->row))
    {
      if ($debug_mode > 1)
      {
        echo '<BR><BR>';
        echo __FILE__ . ':' . __LINE__ . ': ERROR: $this->row is not an object (3).';
        echo '<BR><PRE>';
        debug_print_backtrace();
        echo '<BR><BR>var_dump($this):<BR>';
        var_dump($this);
        echo '<BR><BR>var_dump($this->row):<BR>';
        var_dump($this->row);
        echo '</PRE><BR><BR>';
      }
    }
    else
    {
      $this->row->Close();
    }
  }
}

function NewBASEDBConnection( $path, $type ){
	GLOBAL $debug_mode, $et;
	$PHPVer = GetPHPSV();
	$Wtype = NULL; // Working type.
	$EMPfx = __FUNCTION__ . ': ';
	$AXtype = $type;
	if( LoadedString($type) ){ // Normalize DB type.
		$type = strtolower($type);
		if( preg_match("/^p(ostgres(s)?|(g|ostgre)sql)$/", $type) ){
			$type = 'postgres';
		}elseif( preg_match("/^oracle/", $type) ){
			$type = 'oci8';
		}elseif( preg_match("/^m(s|icrosoft)/", $type) ){
			$type = 'mssql';
		}
		$Wtype = $type; // Set DB driver type.
		if( $type == 'mysql' || $type == 'mysqlt' || $type == 'maxsql' ){
			// On PHP 5.5+, use mysqli ADODB driver & gracefully deprecate
			// the mysql, mysqlt & maxsql drivers.
			if ( $PHPVer[0] > 5 || ( $PHPVer[0] == 5 && $PHPVer[1] > 4) ){
				mysqli_report(MYSQLI_REPORT_OFF); // Issue #162 temp fix.
				$Wtype = "mysqli";
			}
		}
		KML($EMPfx . "DB Type Req: $AXtype Type FIN: $type Driver: $Wtype", 3);
	}
	if (
		!LoadedString($Wtype) ||
		!preg_match("/^(m(y|s|ax)sql|mysqlt|postgres|oci8)$/", $type)
	){
		$msg = "<b>" . _ERRSQLDBTYPE . "</b>" . "<p>:" . _ERRSQLDBTYPEINFO1
		. "<code>'" .XSSPrintSafe($AXtype) . "'</code>. ". _ERRSQLDBTYPEINFO2;
		FatalError ($msg);
	}
	$sc = DIRECTORY_SEPARATOR;
	if ( !LoadedString($path) ){ // Setup default for PHP module include.
		$path = 'adodb';
		KML($EMPfx . "Def DAL path = '$path'", 3);
	}else{ // We are given a path.
		KML($EMPfx . "Req DAL path = '$path'", 3);
		if ( $path != 'adodb' ){ // Export ADODB_DIR for use by ADODB.
			SetConst('ADODB_DIR', $path);
		}
	}
	$GLOBALS['ADODB_DIR'] = ADODB_DIR;
	SetConst('ADODB_ERROR_HANDLER_TYPE',E_USER_NOTICE);
//	Unit Tests had ADODB error logging in their output.
//	Solution Make ADODB error logging configurable.
//	See: https://github.com/NathanGibbs3/BASE/issues/68
//	Commented out this line for now.
//	SetConst('ADODB_ERROR_LOG_TYPE',0);
	// Load ADODB Error Handler.
	$LibFile = 'adodb-errorhandler.inc';
	$Lib = implode( $sc, array($path, $LibFile) ) . '.php';
	if( $debug_mode > 1 ){ // Issue 11 avoidance Test shim.
		KML($EMPfx . _DBALCHECK . " '$Lib'", 3);
	}
	if ( $path != 'adodb' ){
		$tmp = ChkLib($path, '' , $LibFile);
	}else{
		$tmp = ChkLib('', $path , $LibFile);
	}
	$DEH = false;
	if ( LoadedString($tmp) == true ){
		$DEH = include_once($tmp);
		KML($EMPfx . "DAL Load: '$path$sc$LibFile" . ".php'", 3);
	}
	// Load ADODB Library.
	$LibFile = 'adodb.inc';
	$Lib = implode( $sc, array($path, $LibFile) ) . '.php';
	if( $debug_mode > 1 ){ // Issue 11 avoidance Test shim.
		KML($EMPfx . _DBALCHECK . " '$Lib'", 3);
	}
	if ( $path != 'adodb' ){
		$tmp = ChkLib($path, '' , $LibFile);
	}else{
		$tmp = ChkLib('', $path , $LibFile);
	}
	$DAL = false;
	if ( LoadedString($tmp) == true ){
		$DAL = include_once($tmp);
		KML($EMPfx . "DAL Load: '$path$sc$LibFile" . ".php'", 3);
	}
	if( $DEH == false || $DAL == false ){
		// @codeCoverageIgnoreStart
		$tmp = 'https://';
		if( $PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 1) ){
			$tmp .= 'github.com/ADOdb/ADOdb';
		}else{
			$tmp .= 'sourceforge.net/projects/adodb';
		}
		// TD this msg when we get to _ERRSQLDBALLOAD2 on Issue#11
		$msg = 'Check the DB abstraction library variable <code>$DBlib_path'
		. '</code> in <code>base_conf.php</code>.';
		// TD the first param when we get to _ERRSQLDBALLOAD1 on Issue#11
		LibIncError('DB Abstraction', $path, $Lib, $msg, 'ADOdb', $tmp, 1);
		// @codeCoverageIgnoreEnd
	}
	ADOLoadCode($Wtype);
	if( is_object($et) && $debug_mode > 2 ){
		$et->Mark('DB Object Created.'); // TD this in Issue #11 branch.
	}
	return new baseCon($type);
}

function MssqlKludgeValue( $text ){
	$Ret = '';
	for ( $i = 0; $i < strlen($text); $i++ ){
		$Ret .= '[' . substr($text,$i, 1) . ']';
	}
	return $Ret;
}
function RepairDBTables($db)
{
  /* This function was completely commented in original....
    I will be searching to see where it was called from if at all */
}

// @codeCoverageIgnoreStart
// Don't Unit Test this.
function ClearDataTables( $db ){
  $db->baseExecute("DELETE FROM acid_event");
  $db->baseExecute("DELETE FROM data");
  $db->baseExecute("DELETE FROM event");
  $db->baseExecute("DELETE FROM icmphdr");
  $db->baseExecute("DELETE FROM iphdr");
  $db->baseExecute("DELETE FROM reference");
  $db->baseExecute("DELETE FROM sensor");
  $db->baseExecute("DELETE FROM sig_class");
  $db->baseExecute("DELETE FROM sig_reference");
  $db->baseExecute("DELETE FROM signature");
  $db->baseExecute("DELETE FROM tcphdr");
  $db->baseExecute("DELETE FROM udphdr");
}
// @codeCoverageIgnoreEnd

// Get Max Length of field in table.
function GetFieldLength( $db, $table, $field ){
	$EMPfx = __FUNCTION__ . ': ';
	$Emsg = '';
	$Ret = 0;
	if(
		is_object($db) && get_class($db) == 'baseCon' && $db->baseisDBUp(true)
	){
		$EMPfx .= 'Invalid ';
		if( !(LoadedString($table) && $db->baseTableExists($table)) ){
			$Emsg = 'Table';
		}elseif(
			!(LoadedString($field) && $db->baseFieldExists($table, $field))
		){
			$Emsg = 'Field';
		}
		if( LoadedString($Emsg) ){
			KML("$EMPfx$Emsg.", 3);
		}else{
			$wresult = $db->DB->metacolumns($table);
			$wf = strtoupper($field);
			$tmp = $wresult[$wf];
			$Ret = $tmp->max_length;
		}
	}
	return $Ret;
}

// Function: filterSql()
// @doc Filters the input string so that it can be safely used in SQL queries.
// @param $item            value of the variable to filter
// @param $force_alert_db  (default 0 - use current db)
// @return a sanitized version of the passed variable.
function filterSql( $item, $force_alert_db=0, $db = '' ){
	GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname,
	$alert_host, $alert_port, $alert_user, $alert_password;
	if( !isset($item) ){ // Unset Value.
		return $item;
	}else{
		if( is_array($item) ){ // Array.
			// Recursively convert array elements.
			// Works with both Keyed & NonKeyed arrays.
			foreach( $item as $key => $value ){
				$item[$key] = filterSql( $value, $force_alert_db );
			}
			return $item;
		}else{
			$Dbcf = 0; // DB Object creation Flag.
			if( is_object($db) && get_class($db) == 'baseCon' ){
				// Need to Add check for baseCon Role, so we can follow the
				// force flag on passed objects.
				$tdb = $db; // DB Onject passed.
			}else{
				$tdb = NewBASEDBConnection($DBlib_path, $DBtype);
				$Dbcf = 1; // DB Onject created.
				$tdb->baseDBConnect(
					$db_connect_method, $alert_dbname, $alert_host, $alert_port,
					$alert_user, $alert_password, $force_alert_db
				);
			}
			$PHPVer = GetPHPSV();
			if( $PHPVer[0] > 5 || ($PHPVer[0] == 5 && $PHPVer[1] > 3) ){
				$Qh = 0;
			}else{ // Figure out quote handling on PHP < 5.4.
				$Qh = get_magic_quotes_runtime();
			}
			$item = $tdb->DB->qstr($item, $Qh);
			if( $Dbcf == 1 ){ // Close it, only if we created it.
				$tdb->baseClose();
			}
			// Cut off first and last character, (quotes added by qstr()).
			$item = substr($item, 1, strlen($item)-2);
			return $item;
		}
	}
}

function GetDALSV (){ // Returns ADOdb Semantic Version Array
	return VS2SV(strval(ADOConnection::version()));
}

function DumpSQL( $sql = '', $lvl = 0 ){
	// Modeled on the BASE KML, Dump SQL Exec item to appropriate destination.
	GLOBAL $debug_mode;
	if ( LoadedString($sql) ){
		if ( !is_int($lvl) || $lvl < 0 ){
			$lvl = 0;
		}
		if ( $debug_mode >= $lvl ){
			ErrorMessage("SQL Executed: $sql", 'black', 1);
		}
	}
}

?>
