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
	var $DB = NULL; // ADOdb DB dirver specific object when set.
	var $DB_type = NULL; // ADOdb DB Driver.
	var $DB_name = NULL; // DB.
	var $DB_host = NULL; // DB Server.
	var $DB_port = NULL; // DB Server Port.
	var $DB_username = NULL; // DB User.
	var $lastSQL = ''; // Last SQL statement execution request.
	var $version = 0; // Default to Schema v0 on Init.
	var $sql_trace = NULL; // SQL Trace file handle.
	var $DB_class = NULL; // DB Class.
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
		// Are we a Mysql type? Note it in Class structure.
		if( $type == 'mysql' || $type == 'mysqlt' || $type == 'maxsql' ){
			$this->DB_class = 1;
		}else{
			$this->DB_class = 0;
		}
	}
	function baseDBConnect(
		$method, $database, $host, $port, $username, $password, $force = 0
	){
		GLOBAL $archive_dbname, $archive_host, $archive_port, $archive_user,
		$archive_password, $debug_mode, $et;
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
		if( $this->baseGetDBversion() > 105 ){ // FLoPS released after Schema v106
			$this->baseSetFLOP(); // Detect FLoP Extended DB.
		}
		// Need to TD these in Issue #11 branch.
		KML($EMPfx . "DB Connect: $DBDesc.", 3);
		if( is_object($et) && $debug_mode > 1 ){
			$et->Mark("DB Connect: $DBDesc.");
		}
	}

	function baseConnect ( $database, $host, $port, $username, $password ){
		GLOBAL $sql_trace_mode, $sql_trace_file;
		$this->DB = NewADOConnection();
		$this->DB_name = $database;
		$this->DB_host = $host;
		$this->DB_port = $port;
		$this->DB_username = $username;

     if ( $sql_trace_mode > 0 )
     {
        $this->sql_trace = fopen($sql_trace_file,"a");
        if ( !$this->sql_trace )
        {
           ErrorMessage(_ERRSQLTRACE." '".$sql_trace_file."'");
           die();
        }
     }

     $db = $this->DB->Connect( ( ( $port == "") ? $host : ($host.":".$port) ),
                               $username, $password, $database); 

     if ( !$db )
     {
        $tmp_host = ( $port == "") ? $host : ($host.":".$port);
        echo '<P><B>'._ERRSQLCONNECT.' </B>'.
             $database.'@'. $tmp_host ._ERRSQLCONNECTINFO;

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

     if ( $sql_trace_mode > 0 )
     {
        $this->sql_trace = fopen($sql_trace_file,"a");
        if ( !$this->sql_trace )
        {
           ErrorMessage(_ERRSQLTRACE." '".$sql_trace_file."'");
           die();
        }
     }

     $db = $this->DB->PConnect( ( ( $port == "") ? $host : ($host.":".$port) ),
                               $username, $password, $database); 

     if ( !$db )
     {
        $tmp_host = ( $port == "") ? $host : ($host.":".$port);
        echo '<P><B>'._ERRSQLPCONNECT.' </B>'.
             $database.'@'. $tmp_host ._ERRSQLCONNECTINFO;

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
		$this->DB_host = NULL; // Issue #204
		$this->DB_name = NULL;
		$this->DB_port = NULL;
		$this->DB_username = NULL;
		$this->FLOP = NULL;
		$this->Role = NULL;
		$this->version = 0;
		$this->lastSQL = '';
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
		if ( $tdp != '' ){
			$DSN = "$DSN:$tdp";
		}
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
			error_log($EPfx."Disconnected: $tdt $tdn @ $DSN");
			error_log($EPfx."Reconnecting: $tdt $tdn @ $DSN");
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
			$msg = $EPfx.'Query Fail: ';
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
				$msg .= "<p>DB Engine: $tdt DB: $tdn @ $DSN</p>";
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
		if( !is_null($this->DB) && $this->DB->isConnected() ){
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

	function baseFieldExists ( $table, $field ){
		$Ret = false;
		if( !is_null($this->DB) && $this->DB->isConnected() ){
			if( $this->baseTableExists($table) ){
				if( in_array($field, $this->DB->metacolumnNames($table)) ){
					$Ret = true;
				}
			}
		}
		return $Ret;
	}

	function baseTableExists ( $table ){
		$Ret = false;
		if( !is_null($this->DB) && $this->DB->isConnected() ){
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
	function baseIndexExists ( $table, $index_name ){
		$Ret = false;
		if( !is_null($this->DB) && $this->DB->isConnected() ){
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

function baseInsertID (){
	// Getting the insert ID fails on certain databases (e.g. postgres), but
	// we may use it on the once it works on. This function returns -1 if the
	// dbtype is postgres, then we can run a kludge query to get the insert
	// ID. That query may vary depending upon which table you are looking at
	// and what variables you have set at the current point, so it can't be
	// here and needs to be in the actual script after calling this function.
	// srh (02/01/2001)
	if ( $this->DB_class == 1 || $this->DB_type == "mssql" )
        return $this->DB->Insert_ID();
     else if ($this->DB_type == "postgres" ||($this->DB_type == "oci8"))
        return -1;   

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
		if( !is_null($this->DB) && $this->DB->isConnected() ){
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
					KML($EMPfx . 'Access error.', 3);
				}
			}else{
				KML($EMPfx . 'undefined.', 3);
			}
			KML($EMPfx . "set to $Ret", 3);
			$this->version = $Ret;
		}else{
			KML($EMPfx . 'DB not connected.', 3);
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
	if ( LoadedString($type) ){ // Normalize DB type.
		$type = strtolower($type);
		if ( preg_match("/^(postgres(s)?|(postgre(s)?|pg)sql)$/", $type) ){
			$type = 'postgres';
		}elseif ( preg_match("/^oracle/", $type) ){
			$type = 'oci8';
		}elseif ( preg_match("/^m(s|icrosoft)/", $type) ){
			$type = 'mssql';
		}
		// Set DB driver type.
		$Wtype = $type;
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
function GetFieldLength($db,$table,$field){
	$Epfx = 'BASE ' . __FUNCTION__ . '() ';
	$Emsg = '';
	$Ret = 0;
	if ( !(is_object($db)) ){
		$Emsg = $Epfx."Invalid DB Object.";
	}else{
		if ( !(LoadedString($table) && $db->baseTableExists($table)) ){
			$Emsg = $Epfx."Invalid Table.";
		}elseif (
			!(LoadedString($field) && $db->baseFieldExists($table,$field))
		){
			$Emsg = $Epfx."Invalid Field.";
		}
	}
	if ( $Emsg != ''){
		trigger_error($Emsg);
	}else{
		$wresult = $db->DB->metacolumns($table);
		$wf = strtoupper($field);
		$tmp = $wresult[$wf];
		$Ret = $tmp->max_length;
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
			$item = $tdb->DB->qstr($item,$Qh);
			if( $Dbcf == 1 ){ // Close it, only if we created it.
				$tdb->baseClose();
			}
			// Cut off first and last character, (quotes added by qstr()).
			$item = substr($item, 1, strlen($item)-2);
			return $item;
		}
	}
}

?>
