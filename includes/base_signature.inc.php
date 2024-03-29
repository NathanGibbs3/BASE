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
** Purpose: Handles signatures and references in the 
**          Snort signature language
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded. Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

function GetSignatureName( $sig_id, $db ){
   $name = "";

   $temp_sql = "SELECT sig_name FROM signature WHERE sig_id='". addslashes($sig_id) . "'";
   $tmp_result = $db->baseExecute($temp_sql);
   if ( $tmp_result )
   {
      $myrow = $tmp_result->baseFetchRow();
      $name = $myrow[0];
      $tmp_result->baseFreeRows();
   }
   else
      $name = "["._ERRSIGNAMEUNK."]";

   return $name;
}

function GetSignaturePriority($sig_id, $db)
{
   $priority = "";

   $temp_sql = "SELECT sig_priority FROM signature WHERE sig_id='". addslashes($sig_id) . "'";
   $tmp_result = $db->baseExecute($temp_sql);
   if ( $tmp_result )
   {
     $myrow = $tmp_result->baseFetchRow();
     $priority = $myrow[0];

     $tmp_result->baseFreeRows();
   }
   else
     $priority = "["._ERRSIGPROIRITYUNK."]";

   return $priority;
}

function GetSignatureID($sig_id, $db)
{
   $id = "";
  
   if ( $sig_id == "" )
      return $id;

   $temp_sql = "SELECT sig_id FROM signature WHERE sig_name='". addslashes($sig_id) . "'";
   if ($db->DB_type == "mssql")
     $temp_sql = "SELECT sig_id FROM signature WHERE sig_name LIKE '".MssqlKludgeValue($sig_id)."' ";

   $tmp_result = $db->baseExecute($temp_sql);
   if ( $tmp_result )
   {
      $myrow = $tmp_result->baseFetchRow();
      $id = $myrow[0];
      $tmp_result->baseFreeRows();
   }

   return $id;
}

function GetRefSystemName($ref_system_id, $db)
{
   if ( $ref_system_id == "" )
      return "";

   $ref_system_name = "";

	if ( $db->DB_class == 1 ) { /* Mysql & MariaDB */
		$tmp_sql = "SELECT ref_system_name FROM reference_system WHERE `ref_system_id`='".$ref_system_id."'";
	}else{ /* Everyone else */
		$tmp_sql = "SELECT ref_system_name FROM reference_system WHERE ref_system_id='".$ref_system_id."'";
	}
   $tmp_result = $db->baseExecute($tmp_sql);
   if ( $tmp_result )
   {
      $myrow = $tmp_result->baseFetchRow();
      $ref_system_name = $myrow[0];
      $tmp_result->baseFreeRows();
   }

   return trim($ref_system_name);
}

function GetSingleSignatureReference( $ref_system, $ref_tag, $style ){
	GLOBAL $BASE_urlpath, $debug_mode;
	$TRSName = strtolower($ref_system); // Temp Ref Sys Name.
	$RRF = true; // Return Reference Flag.
	if( is_key($TRSName, $GLOBALS['external_sig_link']) ){
		if ($TRSName == 'local_rules_dir'){
			// Custom handling of local rules ESR.
			$dir = $GLOBALS['external_sig_link'][$TRSName][0];
			$to_look_for = $ref_tag;
			if( file_exists($dir) ){
				if( $style == 1 ){
          	$result = "<FONT SIZE = -1>[" .
                "<A HREF = \"$BASE_urlpath/base_local_rules.php?sid=" . $to_look_for . 
                        "\" TARGET = \"_ACID_ALERT_DESC\">" . 
                "rule" . 
                "</A>]</FONT> ";

					return $result;
				}else{
					return "[local rules dir: sid:" . $ref_tag . ";]";
				}
			}
		}elseif( $style == 1 ){ // All other ESRs.
         if ($TRSName == "snort")
         {
			if (preg_match("/([0-9]+):([0-9]+)/", $ref_tag, $backref))
           {
             if ($backref[1] == "1")
             {
               $ref_tag_number = sprintf("%d", $backref[2]);
							 /* print "ref_tag_numbr = $ref_tag_number<BR>\n"; */
             }else{
               $ref_tag_number = 0;
             }
           }
           /* The following pattern tries to catch those bogus
              ref_tags, as can appear, when barnyard does not
              deliver a proper $sig_gid. See below the line
              "Hack to fix blank gid from barnyard -- Kevin Johnson"
           */
           elseif (preg_match("/^[\t ]*([0-9]+)[\t ]*$/", $ref_tag, $backref))
           {
             if ($backref[1] != "")
             {
               $ref_tag_number = sprintf("%d", $backref[1]);
             }else{
               $ref_tag_number = 0;
             }
           }
           else
           {
             $ref_tag_number = 0;
           }


           if ($ref_tag_number >= 2000000 && $ref_tag_number < 10000000)
           /* then we assume it is actually emerging threats rather than snort */
           {
              return "<FONT SIZE=-1>" .
                     "[<A HREF=\"" . $GLOBALS['external_sig_link']["EmThreats"][0] . $ref_tag_number . "\" " .
                             "TARGET=\"_ACID_ALERT_DESC\">" . "EmThreats</a>]";
           }
           else
           {
             return "<FONT SIZE=-1>[".
                    "<A HREF=\"".$GLOBALS['external_sig_link'][$TRSName][0].
                             $ref_tag.
                             $GLOBALS['external_sig_link'][$TRSName][1]."\" ".
                             "TARGET=\"_ACID_ALERT_DESC\">".$ref_system."</A>".
                    "]</FONT> ";
           }
         }else{
           return "<FONT SIZE=-1>[".
                "<A HREF=\"".$GLOBALS['external_sig_link'][$TRSName][0].
                             $ref_tag.
                             $GLOBALS['external_sig_link'][$TRSName][1]."\" ".
                             "TARGET=\"_ACID_ALERT_DESC\">".$ref_system."</A>".
                 "]</FONT> ";
        }
		}elseif( $style == 2 ){ // All other ESRs.
			return "[".$ref_system."/$ref_tag] ";
		}
	}else{ // Not in ESRs.
		return $ref_system;
	}
}

function GetSignatureReference( $sig_id, $db, $style ){
	GLOBAL $BASE_display_sig_links, $debug_mode;
	$ref = '';
	if( $BASE_display_sig_links == 1 ){
      $temp_sql = "SELECT ref_seq, ref_id FROM sig_reference WHERE sig_id='". addslashes($sig_id) ."'";
      $tmp_sig_ref = $db->baseExecute($temp_sql);
   
		if ( $tmp_sig_ref )
      {
         $num_references = $tmp_sig_ref->baseRecordCount();
         for ( $i = 0; $i < $num_references; $i++)
         {
            $mysig_ref = $tmp_sig_ref->baseFetchRow();

			if( $db->DB_class == 1 ){ // Mysql & MariaDB.
				$temp_sql = "SELECT `ref_system_id`, ref_tag FROM reference WHERE ref_id='".$mysig_ref[1]."'";
			}else{ // Everyone else.
				$temp_sql = "SELECT ref_system_id, ref_tag FROM reference WHERE ref_id='".$mysig_ref[1]."'";
			}

            $tmp_ref_tag = $db->baseExecute($temp_sql);
   
            if ( $tmp_ref_tag )
            {
               $myrow = $tmp_ref_tag->baseFetchRow();
               $ref_tag = $myrow[1];
               $ref_system = GetRefSystemName($myrow[0], $db);
            }
   
            $ref = $ref.GetSingleSignatureReference($ref_system, $ref_tag, $style);
   
            /* Automatically add an ICAT reference if a CVE reference exists */
            if ( $ref_system == "cve" )
                $ref = $ref.GetSingleSignatureReference("icat", $ref_tag, $style);
          
            $tmp_ref_tag->baseFreeRows();
         }
         $tmp_sig_ref->baseFreeRows();
      }
   
      if ( $db->baseGetDBversion() >= 103 )
      {
         if ( $db->baseGetDBversion() >= 107 )
            $tmp_sql = "SELECT sig_sid, sig_gid FROM signature WHERE sig_id='". addslashes($sig_id) ."'";
         else
            $tmp_sql = "SELECT sig_sid FROM signature WHERE sig_id='". addslashes($sig_id) ."'";
   
         $tmp_sig_sid = $db->baseExecute($tmp_sql);
   
         if ( $tmp_sig_sid )
         {
            $myrow = $tmp_sig_sid->baseFetchRow();
            $sig_sid = $myrow[0];
            if ( $db->baseGetDBversion() >= 107 )
               $sig_gid = $myrow[1];
   
         }

		}else{
			$sig_sid = '';
		}

			if (is_numeric($sig_id) && (is_numeric($sig_sid )))
			{
				// 0 - 1,999,999: http://www.snort.org/
				// 2,000,000 - 99,999,999: http://www.emergingthreats.net/
				// 100,000,000 - ...: Former so-called "Community Rules, distributed
				//                    by http://www.snort.org/
				if ($sig_sid < 2000000 || $sig_sid >= 100000000)
				{
					$bp = dirname(__FILE__);

      		if ($sig_sid >= 103) 
					// then we assume it is a rule based alert:
					{						
						$docu_file = "$bp/../signatures/$sig_sid.txt"; 

				if ($debug_mode > 1 ){
					 error_log("sig_sid = $sig_sid; docu_file = $docu_file");
				}

						if (file_exists($docu_file)) 
						{
        	 		$ref = $ref.GetSingleSignatureReference("local", $sig_sid, $style);
						}
      		} 
					else 
        	// then we assume it is a preprocessor alert:
					{
						$docu_file = "$bp/../signatures/$sig_gid" . '-' . "$sig_sid.txt";
						if (file_exists($docu_file))
						{
							$ref = $ref.GetSingleSignatureReference("local", $sig_gid . '-' . $sig_sid, $style); 
						}
   				}
      	} // if ($sig_sid < 2000000 || $sig_sid >= 100000000)


			if( $sig_sid >= 103 ){
				  $local_rules_dir = $GLOBALS['external_sig_link']['local_rules_dir'][0];
          if (!empty($local_rules_dir)) {
            if (is_dir($local_rules_dir)) {
              if (is_readable($local_rules_dir)) {
		            $ref = $ref . GetSingleSignatureReference("local_rules_dir", $sig_sid, $style);
              }
            }

				}
			} // if ($sig_sid >= 103)
		} // if (is_numeric($sig_id) && (is_numeric($sig_sid )))
		// snort.org should be documenting all official signatures, so
		// automatically add a link.
		if( $sig_sid != '' ){
			if ( $db->baseGetDBversion() >= 107 ){
				// Fix for barnyard < 2.1.13 lacks support for schema 107+.
				// Blank gid issue. -- Kevin Johnson
				if( $sig_gid != ''){ // Barnyard 2.1.13+
					$ref .= GetSingleSignatureReference(
						'snort', $sig_gid .'-'. $sig_sid, $style
					);
				}else{ // Barnyard < 2.1.13
					$ref .= GetSingleSignatureReference(
						'snort', $sig_sid, $style
					);
				}
			}else{ // Schema < 107
				$ref .= GetSingleSignatureReference(
					'snort', $sig_sid, $style
				);
			}
		}
	}
	return $ref;
}

function check_string($str){
	GLOBAL $debug_mode;
	if ( !isset($str) || empty($str) || !is_string($str) ){
		if ($debug_mode > 0){
			$Epfx = __FILE__ . ":" . __FUNCTION__ . ": ERROR: ";
			$msg = $Epfx . "\$str is not defined OR is empty OR is not a string.";
			error_log($msg);
			if ($debug_mode > 1){
				SQLTraceLog( $Epfx . "\$msg == \"" . var_dump($str) . "\".");
			}
		}
		return 0;
	}else{
		return 1;
	}
}

function BuildSigLookup( $signature, $style = 1 ){
	// style: how should the signature be returned?
	// - 1: (default) HTML
	// - 2: text
	//
	// - Paul Harrington <paul@pizza.org> : reference URL links
	// - Michael Bell <michael.bell@web.de> : links for IP address in
	//   spp_portscan alerts
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = '';
	if ( !is_int($style) ){
		$style = 1;
	}
	if( !LoadedString($signature) ){
		if( $debug_mode > 1 ){
			$msg = $EMPfx . "\$signature == \"" . var_dump($signature) . "\". Returning with empty string.";
			NLIO(XSSPrintSafe($msg));
			SQLTraceLog($msg);
		}
		return $Ret;
	}elseif( $style == 2 ){
		return $signature;
	}
	if( $debug_mode > 1 ){
		NLIO($EMPfx . 'Sig: ' . XSSPrintSafe($signature) . '<br/>');
	}
	// Create hyperlinks for references.

	// Arachnids sig referneces. Supports mixed styles in same sig. The old
	// code worked, but nested <A> tags, really, seriously. :-)
	if(preg_match("/(IDS)(\d+)/",$signature,$matches)){
		$replace = "<A HREF=\"http://www.whitehats.com/".$matches[1];
		$target = " TARGET=\"_ACID_ALERT_DESC\">".$matches[1];

// We are stripping the leading 0 out of the url, the target shouldn't matter
// Why did we get all complicated with this?

		$ts = $signature;
		preg_match_all("/IDS\d+/",$signature,$MC);

// -- We put the loop in to catch multiple
// arachnids refs in the same rule.

		for ($i = 0; $i < count($MC[0]); $i++){
			$pattern = '/'.$MC[0][$i].'/';

// ---

			if(preg_match("/(0+)(\d+)/",$pattern,$hit)){
				// Anarchnids Leading Zero Sig URL Shim
				$tmp = $hit[2];
				$tt = $target.$hit[0];
			}else{
				// Anarchnids Default Numeric URL
				$tmp = $matches[2];
				$tt = $target.$tmp;
			}
			$tr = $replace."/$tmp\"$tt</A>";
			$ts = preg_replace($pattern, $tr, $ts);
		}

//--

		$tmp2 = $ts;
	}else{
		$tmp2 = $signature;
	}
  if (!check_string($tmp2))
  {
    print "<td bgcolor=\"white\">" . __FILE__ . ":" . __LINE__ . ": ERROR: \$tmp2 = " . var_dump($tmp2) . "</td>";
    return $tmp1;
  }

	if( is_key('external_sig_link', $GLOBALS) ){ // Issue #27
  $pattern = "/BUGTRAQ ID (\d+)/";
  $replace = "<A HREF=\"".$GLOBALS['external_sig_link']['bugtraq'][0]."\\1\" TARGET=\"_ACID_ALERT_DESC\">BUGTRAQ ID \\1</A>";
  $tmp3 = preg_replace($pattern, $replace, $tmp2);
  if (!check_string($tmp3))
  {
    print "<td bgcolor=\"white\">" . __FILE__ . ":" . __LINE__ . ": ERROR: \$tmp3 = " . var_dump($tmp3) . "</td>";
    return $tmp2;
  }


  $pattern = "/MCAFEE ID (\d+)/";
  $replace = "<A HREF=\"".$GLOBALS['external_sig_link']['mcafee'][0]."\\1\" TARGET=\"_ACID_ALERT_DESC\">MCAFEE ID \\1</A>";
  $tmp4 = preg_replace($pattern, $replace, $tmp3);
  if (!check_string($tmp4))
  {
    print "<td bgcolor=\"white\">" . __FILE__ . ":" . __LINE__ . ": ERROR: \$tmp4 = " . var_dump($tmp4) . "</td>";
    return $tmp3;
  }


  $pattern = "/(CVE-\d+-\d+)/";
  $replace = "<A HREF=\"".$GLOBALS['external_sig_link']['cve'][0]."\\1\" TARGET=\"_ACID_ALERT_DESC\">\\1</A>";
  $msg = preg_replace($pattern, $replace, $tmp4);
  if (!check_string($msg))
  {
    print "<td bgcolor=\"white\">ERROR: \$msg = " . var_dump($msg) . "</td>";
    return $tmp4;
  }
	}else{
		$msg = $tmp2;
	}
	// fixup portscan message strings
  if ( stristr($msg, "spp_portscan") )
  {
    if ($debug_mode > 1)
    {
      SQLTraceLog(__FILE__ . ":" . __LINE__ . ":" . __FUNCTION__ . ": Before fixup portscan message strings");
    }


      /* replace "spp_portscan: portscan status" => "spp_portscan"  */
      $msg = preg_replace("/spp_portscan: portscan status/", "spp_portscan", $msg);

      /* replace "spp_portscan: PORTSCAN DETECTED" => "spp_portscan detected" */
      $msg = preg_replace("/spp_portscan: PORTSCAN DETECTED/", "spp_portscan detected", $msg);

      /* create hyperlink for IP addresses in portscan alerts */
      $msg = preg_replace("/([0-9]*\.[0-9]*\.[0-9]*\.[0-9]*)/",
                          "<A HREF=\"base_stat_ipaddr.php?ip=\\1&amp;netmask=32\">\\1</A>",
                          $msg);

    if ($debug_mode > 1)
    {
      SQLTraceLog(__FILE__ . ":" . __LINE__ . ":" . __FUNCTION__ . ": After fixup portscan message strings");
    }
  }
	return $msg;
}

function BuildSigByID( $sig_id, $db, $style = 1 ){
	// sig_id: DB schema dependent
	// - < v100: a text string of the signature
	// - > v100: an ID (key) of a signature
	// db : database handle
	// style: how should the signature be returned?
	// - 1: (default) HTML
	// - 2: text
	// RETURNS: a formatted signature and the associated references
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	if ( $db->baseGetDBversion() >= 100 ){
		// Catch odd instance of $sig_id still being an alert text string
		// despite using normalized signature as of DB version 100.
		if ( !is_numeric($sig_id) ){
			return $sig_id;
		}
		if( $debug_mode > 1 ){
			SQLTraceLog($EMPfx . 'Before GetSignatureName()');
		}
		$sig_name = GetSignatureName($sig_id, $db);
		if( $debug_mode > 1 ){
			SQLTraceLog($EMPfx . 'After GetSignatureName()');
		}
		if( LoadedString($sig_name) ){
       //return GetSignatureReference($sig_id, $db, $style)." ".BuildSigLookup($sig_name, $style);
			if( $debug_mode > 1 ){
				SQLTraceLog($EMPfx . "Before BuildSigLookup() with \$sig_name == \"" . $sig_name . "\"");
			}
			$buf1 = BuildSigLookup($sig_name, $style);
			if( !LoadedString($buf1) ){
				$msg = "$EMPfx BuildSigLookup() has failed on "
				. "\$sig_bane == \"" . var_dump($buf1) . '"Returning with'
				. ' empty string.';
				if( $debug_mode > 1 ){
					NLIO(XSSPrintSafe($msg));
					SQLTraceLog($msg);
				}
				return "($sig_id) " . _ERRSIGNAMEUNK;
			}
			if( $debug_mode > 1 ){
				SQLTraceLog($EMPfx . "After BuildSigLookup() and before GetSignatureReference()");
			}
       $buf2 = GetSignatureReference($sig_id, $db, $style)." " . $buf1;
			if( $debug_mode > 1 ){
				SQLTraceLog($EMPfx . "After GetSignatureReference() and about to return.");
			}
       return $buf2;
		}else{
        if ( $style == 1 )
           return "($sig_id)<I>"._ERRSIGNAMEUNK."</I>";
        else
           return "($sig_id) "._ERRSIGNAMEUNK;

		}
	}else{
		if( $debug_mode > 1 ){
			SQLTraceLog($EMPfx . 'Before BuildSigLookup()');
		}
    $buf1 = BuildSigLookup($sig_id, $style);
		if( $debug_mode > 1 ){
			SQLTraceLog($EMPfx . 'After BuildSigLookup() and about to return.');
		}
    //return BuildSigLookup($sig_id, $style);
    return $buf1;
	}
}

function GetSigClassID($sig_id, $db)
{
  $sql = "SELECT sig_class_id FROM signature ".
         "WHERE sig_id = '" . addslashes($sig_id) . "'";

  $result = $db->baseExecute($sql);
  $row = $result->baseFetchRow();

  return $row[0]; 
}

function GetSigClassName ( $class_id, $db ){
	GLOBAL $debug_mode;
	$Ret = "<I>"._UNCLASS."</I>";
	$EMPfx = __FUNCTION__ . ': ';
	if ( $class_id == '' ){
		error_log($EMPfx."WARNING: \$class_id is empty. Returning \"unclassified\"");
	}else{
		$sql = "SELECT sig_class_name FROM sig_class ".
			"WHERE sig_class_id = '$class_id'";
		$rs = $db->baseExecute($sql);
		if (
			$rs != false
			&& $db->baseErrorMessage() == ''
			&& $rs->baseRecordCount() > 0
		){ // Error Check
			$result = $rs->baseFetchRow();
			$rs->baseFreeRows();
			if ( isset($result[0]) ){
				$Ret = $result[0];
			}
		}else{
			if ( $debug_mode > 0 ){
				error_log($EMPfx." sql = \"$sql\"");
				error_log($EMPfx."WARNING: BASE DB Query Fail: for \$class_id = \"$class_id\". Returning \"unclassified\"");
			}
		}
	}
	return $Ret;
}

function GetTagTriger($current_sig, $db, $sid, $cid)
{

      /* add to signature name sig_name of tagged alert which trigered this alert -- nikns */
      if (stristr($current_sig, "Tagged Packet")) {

           /* thats possible only if we have FLoP extended db */
           if ( in_array("reference", $db->DB->MetaColumnNames('event')) ) {

                /* get event reference */
                $sql2 = "SELECT signature, reference FROM event ";
                $sql2.= "WHERE sid='".$sid."' AND cid='".$cid."'";
                $result2 = $db->baseExecute($sql2);
                $row2 = $result2->baseFetchRow();
                $result2->baseFreeRows();
                $event_sig = $row2[0];
                $event_reference = $row2[1];

                /* return if we couldn't get event signature or event reference */
                if ( ($event_sig == "") || ($event_reference == "") )
                   return $current_sig;

                /* get triger signature id */
                $sql2 = "SELECT signature, sid, cid FROM event WHERE sid='".$sid."' ";
                $sql2.= "AND reference='".$event_reference."' AND NOT signature='".$event_sig."'";
                $result2 = $db->baseExecute($sql2);
                $row2 = $result2->baseFetchRow();
                $result2->baseFreeRows();
                $triger_sig = $row2[0];
                $triger_sid = $row2[1];
                $triger_cid = $row2[2];

                if ( $triger_sig != "" ) {

                   /* get triger signature name from signature */
                   $sql2 = "SELECT sig_name FROM signature ";
                   $sql2.= "WHERE sig_id='".$triger_sig."'";
                   $result2 = $db->baseExecute($sql2);
                   $row2 = $result2->baseFetchRow();
                   $result2->baseFreeRows();
                   $triger_sig_name = $row2[0];  

                   if ( $triger_sig_name != "" ) {
                      /* return added tagged alert sig_name to signature name */
                      $current_sig.= " <i>(<a href=\"base_qry_alert.php?submit=".rawurlencode("#(0-".$triger_sid."-".$triger_cid.")")."\">";
                      $current_sig.= "#(".$triger_sid."-".$triger_cid."</a>) ".$triger_sig_name.")</i>";

                   }
                }

           }
      }
      return $current_sig;
}

?>
