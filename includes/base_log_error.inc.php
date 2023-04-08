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

function DivErrorMessage ($message, $Count = 0 ){
	NLIO ("<div class='errorMsg' align='center'>$message</div>",$Count);
}

function ErrorMessage ($message, $color = "#ff0000", $br = 0 ){
	print returnErrorMessage($message, $color, $br);
}

function returnErrorMessage ($message, $color = "#ff0000", $br = 0 ){
	if ( HtmlColor($color) == false ){
		// Default to Red if we are passed something odd.
		$color = "#ff0000";
	}
	$error = "<font color='$color'>$message</font>";
	if ( is_numeric($br) && $br == 1 ){ // Issue #160
		$error .= '<br/>';
	}
	return $error;
}

function BuildError ($message = '', $fmessage = '' ){
	if ( LoadedString($message) == true ){
		ErrorMessage(_ERRPHPERROR.':',0,1);
		ErrorMessage($message, 'black', 1);
		print '<br/>';
	}
	// @codeCoverageIgnoreStart
	if ( LoadedString($fmessage) == true ){
		FatalError($fmessage);
	}
	// @codeCoverageIgnoreEnd
}

function LibIncError (
		$Desc, $Loc, $Lib, $message = '', $LibName = '', $URL = '', $Fatal = 0,
		$Pear = 0
	){
	// Translation data this msg when we get to _ERRSQLDBALLOAD1 on Issue#11
	$msg = "<b>Error loading the $Desc library:</b> ".
	XSSPrintSafe('from "'.$Loc.'".');
	if ( LoadedString($LibName) == true ){
		$msg .= '<br/>';
		// Translation data this msg when we get to _ERRSQLDBALLOAD2 on Issue#11
		$msg .= "The underlying $Desc library currently used is $LibName";
		if ( LoadedString($URL) == true ){
			$URL = XSSPrintSafe($URL);
			$msg .= ', that can be downloaded at ';
			$msg .= "<a href='$URL'>$URL</a>";
		}
		$msg .= '.';
	}
	ErrorMessage($msg,'black',1);
	if ( LoadedString($message) == true ){
		ErrorMessage($message,'black',1);
	}
	$FLib = $Lib;
	if ($Pear == 1){
		$EMsg = "Check your Pear::$LibName installation!<br/>";
		$EMsg .= 'Make sure PEAR libraries can be found by PHP.';
		$EMsg .= '<pre>';
		$EMsg .= XSSPrintSafe('pear config-show | grep "PEAR directory"'."\n");
		$EMsg .= XSSPrintSafe('PEAR directory      php_dir     /usr/share/pear');
		$EMsg .= '</pre>';
		$EMsg .= 'This path must be part of the include path of php (cf. /etc/php.ini).';
		$EMsg .= '<pre>';
		$EMsg .= XSSPrintSafe('php -i | grep "include_path"');
		$EMsg .= XSSPrintSafe(
			'include_path => .:/usr/share/pear:/usr/share/php => .:/usr/share/pear:/usr/share/php'
		);
		$EMsg .= '</pre>';
		if ( ini_get('safe_mode') ){
			$EMsg .= XSSPrintSafe(
				'In "safe_mode" it must also be part of safe_mode_include_dir in /etc/php.ini'
			);
		}
		ErrorMessage($EMsg,'black',1);
		$FLib = $LibName;
	}
	$tmp = "PHP setup incomplete: $FLib required.";
	if ($Fatal == 0){
		ErrorMessage($tmp, 0,1);
	}else{
		// @codeCoverageIgnoreStart
		FatalError($tmp);
		// @codeCoverageIgnoreEnd
	}
}

// @codeCoverageIgnoreStart
function FatalError ($message){
	print returnErrorMessage('<b>'._ERRBASEFATAL.'</b>',0,1)."\n".$message;
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

function PrintPageHeader(){
	GLOBAL $DBtype, $ADODB_vers, $Use_Auth_System;
	if ( !AuthorizedPage('(base_denied|index)') ){
		// Additional app info allowed everywhere but landing pages.
		$tmp = session_encode();
		$request_uri = XSSPrintSafe($_SERVER['REQUEST_URI']);
		$http_referer = '';
		if ( base_array_key_exists('HTTP_REFERER', $_SERVER) ){
			$http_referer = XSSPrintSafe($_SERVER['HTTP_REFERER']);
		}
		$http_user_agent = XSSPrintSafe($_SERVER['HTTP_USER_AGENT']);
		$server_software = XSSPrintSafe($_SERVER['SERVER_SOFTWARE']);
		$query_string = XSSPrintSafe($_SERVER['QUERY_STRING']);
   echo "<PRE>
         <B>URL:</B> '".$request_uri."'
         (<B>referred by:</B> '".$http_referer."')
         <B>PARAMETERS:</B> '".$query_string."'
         <B>CLIENT:</B> ".$http_user_agent;
if ( $Use_Auth_System == 1 && AuthorizedRole(1) ){ // Issue #146 Fix
print "\n         <B>SERVER:</B> ".$server_software."
         <B>SERVER HW:</B> ".php_uname()."
         <B>DATABASE TYPE:</B> $DBtype  <B>DB ABSTRACTION VERSION:</B> $ADODB_vers
         <B>PHP VERSION:</B> ".phpversion()."  <B>PHP API:</B> ".php_sapi_name();
}
print "\n         <B>BASE VERSION:</B> ".$GLOBALS['BASE_VERSION']."
         <B>SESSION ID:</B> ".session_id()."( ".strlen($tmp)." bytes )
         <B>SCRIPT :</B> ".XSSPrintSafe($_SERVER['SCRIPT_NAME'])."
         </PRE>"; 
	}
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
?>
