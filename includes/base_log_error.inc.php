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
** Purpose: debugging and logging routines   
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

function ErrorMessage ($message, $color = "#ff0000", $br = 0 ){
	print returnErrorMessage($message, $color, $br);
}

function returnErrorMessage ($message, $color = "#ff0000", $br = 0 ){
	$color = strtolower($color);
	$wsc = array(
		'black', 'silver', 'gray', 'white', 'maroon', 'red', 'pruple',
		'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal',
		'aqua'
	);
	if ( !in_array($color, $wsc) ){
		if ( !preg_match("/^#[0-9A-F]{6}$/i", $color) ){
			// Default to Red if we are passed something odd.
			$color = "#ff0000";
		}
	}
	$error = '<font color="'.$color.'">'.$message.'</font>';
	if ($br != 0){
		$error .= '<br/>';
	}
	return $error;
}

// @codeCoverageIgnoreStart
function FatalError ($message){
	print returnErrorMessage('<b>'._ERRBASEFATAL.'</b>',0,1)."\n $message";
	$message = preg_replace("/\//", '', $message);
	$message = preg_replace("/<br>/i", ' ', $message);
	$message = strip_tags($message)."\n";
	error_log($message);
	trigger_error($message, E_USER_ERROR);
}
// @codeCoverageIgnoreEnd

function PrintServerInformation()
{
   echo '';
}

function PrintPageHeader()
{
     GLOBAL $DBtype, $ADODB_vers;

     $tmp = session_encode();
     $php_version = phpversion();
     $ver = $php_version[0]; 

     $request_uri = XSSPrintSafe($_SERVER['REQUEST_URI']);
     if (
          ($ver >= 5) ||
          (
            ($ver == 4) &&
            ($php_version[1] >= 1)
          )
        )
     {
       if (array_key_exists("HTTP_REFERER", $_SERVER))
       {
         $http_referer = XSSPrintSafe($_SERVER['HTTP_REFERER']);
       }
       else
       {
         $http_referer = "";
       }
     }
     else
     {
       if (key_exists("HTTP_REFERER", $_SERVER))
       {
         $http_referer = XSSPrintSafe($_SERVER['HTTP_REFERER']);
       }
       else
       {
         $http_referer = "";
       }
     }
      $http_user_agent = XSSPrintSafe($_SERVER['HTTP_USER_AGENT']);
      $server_software = XSSPrintSafe($_SERVER['SERVER_SOFTWARE']);
      $query_string = XSSPrintSafe($_SERVER['QUERY_STRING']);

   echo "<PRE>
         <B>URL:</B> '".$request_uri."'
         (<B>referred by:</B> '".$http_referer."')
         <B>PARAMETERS:</B> '".$query_string."'
         <B>CLIENT:</B> ".$http_user_agent."
         <B>SERVER:</B> ".$server_software."
         <B>SERVER HW:</B> ".php_uname()."
         <B>DATABASE TYPE:</B> $DBtype  <B>DB ABSTRACTION VERSION:</B> $ADODB_vers
         <B>PHP VERSION:</B> ".phpversion()."  <B>PHP API:</B> ".php_sapi_name()."
         <B>BASE VERSION:</B> ".$GLOBALS['BASE_VERSION']."
         <B>SESSION ID:</B> ".session_id()."( ".strlen($tmp)." bytes )
         </PRE>"; 
}

function PrintHTTPPost()
{
   echo "<BR><B>HTTP POST Variables</B><PRE>";
   XSSPrintSafe($_POST);
   echo "</PRE>";
}


function SQLTraceLog($message)
{
  GLOBAL $sql_trace_mode, $sql_trace_file;


  if ($sql_trace_mode < 1)
  // then fallback to http server's error log:
  {
    error_log($message);
  }
  else
  // preferred
  {
    if (($sql_trace_file != "") && file_exists($sql_trace_file))
    {
      $fd = fopen($sql_trace_file, "a");
      if ($fd)
      {
        fputs($fd, $message);
        fputs($fd, "\n");
        fflush($fd);
        fclose($fd);
      }
      else
      {
        ErrorMessage("ERROR: Could not open " . $sql_trace_file);
      }
    }
    else
    {
      error_log($message);
    }
  }
}
// vim:tabstop=2:shiftwidth=2:expandtab
?>
