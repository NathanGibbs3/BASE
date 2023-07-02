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
//            Purpose: database schema manipulation
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

function createDBIndex($db, $table, $field, $index_name)
{
   $sql = 'CREATE INDEX '.$index_name.' ON '.$table.' ('.$field.')';

   $db->baseExecute($sql, -1, -1, false);
   if ( $db->baseErrorMessage() != "" )
      ErrorMessage(_ERRDBINDEXCREATE." '".$field."' : ".$db->baseErrorMessage());
   else
      ErrorMessage(_DBINDEXCREATE." '".$field."'");
}

function verify_db($db, $alert_dbname, $alert_host){
  $msg = '<B>'._ERRSNORTVER1.' '.$alert_dbname.'@'.$alert_host.' '._ERRSNORTVER2.'</B>';

  $sql = "SELECT ip_src FROM iphdr";
  $result = $db->baseExecute($sql, 0, 1, false);

  if ( $db->baseErrorMessage() != "" )
     return $msg.'<BR>'.$db->baseErrorMessage().'
            <P>'._ERRSNORTVER;

  $base_table = array ("acid_ag",
                       "acid_ag_alert",
                       "acid_ip_cache",
                       "acid_event",
                       "base_users",
                       "base_roles");

  for ( $i = 0; $i < count($base_table); $i++)
  { 
     if ( !$db->baseTableExists($base_table[$i]) )
       return $msg.'.  <P>'._ERRDBSTRUCT1.' 
              (table: '.$base_table[$i].')'._ERRDBSTRUCT2;
  }
	return '';
}

function verify_php_build( $DBtype ){
	// Checks that the necessary libraries are built into PHP.
	$Ret = ''; // Default return Value.
	$PHPVer = GetPHPSV(); // Check PHP version >= 4.0.4
	// @codeCoverageIgnoreStart
	if(
		$PHPVer[0] < 4
		|| ( $PHPVer[0] == 4 && $PHPVer[1] == 0 && $PHPVer[2] < 4 )
	){ // Only executes on PHP < 4.0.4. Cannot test in CI.
		return '<b>' . _ERRPHPERROR1 . '</b>: ' . _ERRVERSION
		. ' ' . phpversion() . ' ' . _ERRPHPERROR2;
	}
	// @codeCoverageIgnoreEnd
	if( $DBtype == 'mysql' || $DBtype == 'mysqlt' || $DBtype == 'maxsql' ){
		// On PHP 5.5+, use mysqli ADODB driver & gracefully deprecate the
		// mysql, mysqlt & maxsql drivers.
		if( $PHPVer[0] > 5 || ( $PHPVer[0] == 5 && $PHPVer[1] > 4) ){
			if( !extension_loaded('mysqli') ){
				$Ret = returnBuildError('MySQLi', '--with-mysqli');
			}
		}else{
			if( !(function_exists("mysql_connect")) ){
				return _ERRPHPMYSQLSUP;
			}
		}
	}elseif( $DBtype == 'postgres' ){
		if( !(function_exists("pg_connect")) ){
			return _ERRPHPPOSTGRESSUP;
		}
	// @codeCoverageIgnoreStart
	}elseif( $DBtype == 'mssql' ){
		// On PHP 5.3+, use mssqlnative ADODB driver & gracefully deprecate
		// the mssql driver.
		if( $PHPVer[0] > 5 || ( $PHPVer[0] == 5 && $PHPVer[1] > 2) ){
			if( !extension_loaded('sqlsrv') ){
				$Ret = returnBuildError(
					'MS SQL Server', '--enable-sqlsrv', 'php_sqlsrv.dll'
				);
			}
		}else{
			if( !(function_exists("mssql_connect")) ){
				return _ERRPHPMSSQLSUP;
			}
		}
	}elseif( $DBtype == "oci8" ){
		if( !(function_exists("ocilogon")) ){
			return _ERRPHPORACLESUP;
		}
	// @codeCoverageIgnoreEnd
	}else{ // Additional DB Support would tie in here.
		return '<b>' . _ERRSQLDBTYPE . '</b>: ' . _ERRSQLDBTYPEINFO1
		. "'$DBtype'." . _ERRSQLDBTYPEINFO2;
	}
	if( LoadedString($Ret) ){
		$Ret .= NLI('Unable to read ALERT DB.<br/>'); // TD This.
	}
	return $Ret;
}

/* ******************* DB Query Routines ************************************ */
function EventsByAddr($db, $i, $ip)
{
   $ip32 = baseIP2long($ip);

   $result = $db->baseExecute("SELECT signature FROM acid_event (ip_src='$ip32') OR (ip_dst='$ip32')");

   while ( $myrow = $result->baseFetchRow() ) 
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig[$i];
}

function EventCntByAddr($db, $ip)
{
   $ip32 = baseIP2long($ip);

   $result = $db->baseExecute("SELECT count(ip_src) FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   $myrow = $result->baseFetchRow();
   $event_cnt = $myrow[0];
   $result->baseFreeRows();

   return $event_cnt;
}

function UniqueEventsByAddr($db, $i, $ip)
{
     $ip32 = baseIP2long($ip);
     $result = $db->baseExecute("SELECT DISTINCT signature FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   while ($myrow = $result->baseFetchRow())
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig[$i];
}

function UniqueEventCntByAddr($db, $ip)
{
     $ip32 = baseIP2long($ip);
     $result = $db->baseExecute("SELECT DISTINCT signature FROM acid_event WHERE ".
                  "(ip_src='$ip32') OR (ip_dst='$ip32')");

   while ($myrow = $result->baseFetchRow())
      $sig[] = $myrow[0];

   $result->baseFreeRows();

   return $sig;
}

function UniqueEventTotalsByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT count(signature) FROM acid_event WHERE ".
                  "( (ip_src='$ip32' OR ip_dst='$ip32') AND signature='$current_event')"); 

   $myrow = $result->baseFetchRow();
   $tmp = $myrow[0];
   
   $result->baseFreeRows();
   return $tmp;
}

function UniqueSensorCntByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT DISTINCT sid FROM acid_event WHERE ".
                  "( (ip_src='$ip32' OR ip_dst='$ip32') AND signature='$current_event')");

   while ($myrow = $result->baseFetchRow())
      $sid[] = $myrow[0];

   $count = count($sid);
   $result->baseFreeRows();

   return $count;
}

function StartTimeForUniqueEventByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT min(timestamp) FROM acid_event WHERE ".
                  "((ip_src='$ip32' OR ip_dst='$ip32') AND signature = '$current_event');");
   $myrow = $result->baseFetchRow();
   $start_time = $myrow[0];

   $result->baseFreeRows();
   return $start_time;
}

function StopTimeForUniqueEventByAddr($db, $ip, $current_event)
{
   $ip32 = baseIP2long($ip);
   $result = $db->baseExecute("SELECT max(timestamp) FROM acid_event WHERE ".
                  "((ip_src='$ip32' OR ip_dst='$ip32') AND signature = '$current_event');");

   $myrow = $result->baseFetchRow();
   $stop_time = $myrow[0];

   $result->baseFreeRows();
   return $stop_time;
}

?>
