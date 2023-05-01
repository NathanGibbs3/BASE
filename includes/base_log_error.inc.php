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
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

function DivErrorMessage ($message, $Count = 0 ){
	NLIO ("<div class='errorMsg' align='center'>$message</div>",$Count);
}

function returnBuildError( $Desc, $Opt ){ // Standardiazed PHP build error.
	if ( LoadedString($Desc) && LoadedString($Opt) ){
		$Desc = XSSPrintSafe($Desc);
		$Opt = XSSPrintSafe($Opt);
		$Ret = returnErrorMessage(_ERRPHPERROR.':',0,1);
		// TD this.
		$Ret .=
		NLI("<b>PHP build incomplete</b>: $Desc support required.<br/>")
		. NLI("Recompile PHP with $Desc support (<code>$Opt</code>) .<br/>");
		return $Ret;
	}
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

// Debug Data Table
function DDT (
	$Items, $Desc = array(), $title = NULL, $tab = 3, $wd = 75, $vf = 0
){
	if ( is_array($Items) ){ // Input Validation
		if ( !is_array($Desc) ){
			$Desc = array($Desc);
		}
		if ( !is_int($tab) ){
			$tab = 3;
		}
		if ( !is_int($wd) ){
			$wd = 75;
		}
		if ( !is_int($vf) ){
			$vf = 0;
		}
		if ( !LoadedString($title) ){
			$title = 'Debug Data Table';
		}
		$title = XSSPrintSafe($title);
		$Desc = XSSPrintSafe($Desc);
		$Items = XSSPrintSafe($Items);
		PrintFramedBoxHeader($title, 'red', 0, $tab, '', $wd);
		$icnt = count($Items);
		$DF = false;
		if ( $icnt <= count($Desc) ){ // Do we have Descriptions?
			$DF = true;
		}
		if ( $icnt > 0 ){
			$style = '';
			if ( $vf == 1 && $DF ){ // Vertical Dsiplay
				$style = " class='sectiontitle' style='text-align: right;".
				" padding-right: 10px; width: 10%'";
			}
			NLIO("<td$style>", $tab + 2);
			if ( $vf == 0 ){
				if ( $DF ){
					for ( $i = 0; $i < $icnt; $i++){
						NLIO($Desc[$i], $tab + 3);
						if ( $i != $icnt - 1 ){
							NLIO('</td><td>', $tab + 2);
						}
					}
					PrintTblNewRow( 1, '', $tab + 2 );
				}
			}
			for ( $i = 0; $i < $icnt; $i++){
				if ( $vf == 0 ){
					NLIO($Items[$i], $tab + 3);
					if ( $i != $icnt - 1 ){
						NLIO('</td><td>', $tab + 2);
					}
				}else{
					if ( $DF ){
						NLIO($Desc[$i].': ', $tab + 3);
						NLIO("</td><td style='padding-left:10px;'>", $tab + 2);
					}
					NLIO($Items[$i], $tab + 3);
					if ( $i != $icnt -1 ){
						PrintTblNewRow( 0, '', $tab + 2 );
						NLIO("<td$style>", $tab + 2);
					}
				}
			}
			NLIO('</td>', $tab + 2);
		}
		PrintFramedBoxFooter(0, $tab);
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
	GLOBAL $DBtype, $ADODB_vers, $Use_Auth_System, $BCR;
	if( !AuthorizedPage('(base_denied|index)') ){
		$BV = $BCR->GetCap('BASE_Ver');
		// Additional app info allowed everywhere but landing pages.
		$AdminAuth = AuthorizedRole(1); // Issue #146 Fix
		if( $AdminAuth ){ // Issue #146 Fix
			if( is_key('SERVER_SOFTWARE', $_SERVER) ){
				$SW_Svr = $_SERVER['SERVER_SOFTWARE'];
			}else{
				$SW_Svr = 'unknown';
			}
			$tmp = session_encode();
			$SW_Svr = XSSPrintSafe($SW_Svr);
		}
		$request_uri = XSSPrintSafe($_SERVER['REQUEST_URI']);
		if( is_key('HTTP_USER_AGENT', $_SERVER) ){
			$SW_Cli = $_SERVER['HTTP_USER_AGENT'];
		}else{
			$SW_Cli = 'unknown';
		}
		if( is_key('HTTP_REFERER', $_SERVER) ){
			$http_referer = XSSPrintSafe($_SERVER['HTTP_REFERER']);
		}else{
			$http_referer = '';
		}
		$SW_Cli = XSSPrintSafe($SW_Cli);
		$query_string = XSSPrintSafe($_SERVER['QUERY_STRING']);
		// TD these labels from Issue #11 at some point.
   echo "<PRE>
         <B>URL:</B> '".$request_uri."'
         (<B>referred by:</B> '".$http_referer."')
         <B>PARAMETERS:</B> '".$query_string."'
         <B>CLIENT:</B> ".$SW_Cli;
if ( $Use_Auth_System == 1 && AuthorizedRole(1) ){ // Issue #146 Fix
print "\n         <B>SERVER:</B> ".$SW_Svr."
         <B>SERVER HW:</B> ".php_uname();
print "\n         <B>PHP VERSION:</B> ".phpversion();
print "\n         <B>PHP API:</B> ".php_sapi_name();
print "\n         <B>DB TYPE:</B> ".$DBtype;
print "\n         <B>DB ABSTRACTION VERSION:</B> ".$ADODB_vers;
}
print "\n         <B>BASE VERSION:</B> $BV
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
		}else{
			error_log($message);
		}
	}
}
?>
