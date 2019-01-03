<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: executes and prints the query results
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

global $colored_alerts, $debug_mode;
  /* **************** Run the Query ************************************************** */

  /* base_ag_main.php will include this file 
   *  - imported variables: $sql, $cnt_sql
   */

  if ( $printing_ag )
  {
     ProcessCriteria();
     $page = "base_ag_main.php";
     $tmp_page_get = "&amp;ag_action=view&amp;ag_id=$ag_id&amp;submit=x";
     $sql = $save_sql;
  }
  else
  {
     $page = "base_qry_main.php";
     $cnt_sql = "SELECT COUNT(acid_event.cid) FROM acid_event ".$join_sql.$where_sql.$criteria_sql;
     $tmp_page_get = "";
  }

  /* Run the query to determine the number of rows (No LIMIT)*/
  $qs->GetNumResultRows($cnt_sql, $db);
  $et->Mark("Counting Result size");
  /* Setup the Query Results Table */
  $qro = new QueryResultsOutput("$page".$qs->SaveStateGET().$tmp_page_get);

  $qro->AddTitle(qroReturnSelectALLCheck());  
  $qro->AddTitle("ID");

  $qro->AddTitle(_SIGNATURE, 
                "sig_a", " ", " ORDER BY sig_name ASC",
                "sig_d", " ", " ORDER BY sig_name DESC");
  $qro->AddTitle(_TIMESTAMP,
                 "time_a", " ", " ORDER BY timestamp ASC ",
                 "time_d", " ", " ORDER BY timestamp DESC "); 
  $qro->AddTitle(_NBSOURCEADDR, 
                 "sip_a", " ", " ORDER BY ip_src ASC",
                 "sip_d", " ", " ORDER BY ip_src DESC");
  $qro->AddTitle(_NBDESTADDR, 
                 "dip_a", " ", " ORDER BY ip_dst ASC",
                 "dip_d", " ", " ORDER BY ip_dst DESC");
  $qro->AddTitle(_NBLAYER4, 
                 "proto_a", " ", " ORDER BY ip_proto ASC",
                 "proto_d", " ", " ORDER BY ip_proto DESC");



  if ( !$printing_ag )
     $sql = $sql.$join_sql.$where_sql.$criteria_sql;

  /* Apply sort criteria */
  if ( $qs->isCannedQuery() )
     $sql = $sql." ORDER BY timestamp DESC ";
  else
  {
     $sort_sql = $qro->GetSortSQL($qs->GetCurrentSort(), $qs->GetCurrentCannedQuerySort());
     //  3/23/05 BDB   mods to make sort by work for Searches
     $sort_sql = "";
     if (!isset($sort_order)) {
         $sort_order = NULL;
     }

     if ($sort_order == "sip_a")
        { $sort_sql = " ORDER BY ip_src ASC"; }
     if ($sort_order == "sip_d")
        { $sort_sql = " ORDER BY ip_src DESC"; }
     if ($sort_order == "dip_a")
        { $sort_sql = " ORDER BY ip_dst ASC"; }
     if ($sort_order == "dip_d")
        { $sort_sql = " ORDER BY ip_dst DESC"; }
     if ($sort_order == "sig_a")
        { $sort_sql = " ORDER BY sig_name ASC"; }
     if ($sort_order == "sig_d")
        { $sort_sql = " ORDER BY sig_name DESC"; }
     if ($sort_order == "time_a")
        { $sort_sql = " ORDER BY timestamp ASC"; }
     if ($sort_order == "time_d")
        { $sort_sql = " ORDER BY timestamp DESC"; }
     ExportHTTPVar("prev_sort_order", $sort_order);
    
     $sql = $sql." ".$sort_sql;
  }

  if ( $debug_mode > 0 )
   {
     echo "<P>SUBMIT: $submit";
     echo "<P>sort_order: $sort_order";
     echo "<P>SQL (save_sql): $sql";
     echo "<P>SQL (sort_sql): $sort_sql"; 
   }

  /* Run the Query again for the actual data (with the LIMIT) */
  //$result = ""; // $qs->ExecuteOutputQuery($sql, $db);
  $result = $qs->ExecuteOutputQuery($sql, $db);
  $et->Mark("Retrieve Query Data");

  if ( $debug_mode > 0 )
  {
     $qs->PrintCannedQueryList();
     $qs->DumpState();
     echo "$sql<BR>";
  }

  if ( !$printing_ag )
  {
     /* ***** Generate and print the criteria in human readable form */
     echo '<TABLE WIDTH="100%">
           <TR>
             <TD WIDTH="60%" VALIGN=TOP>';

     PrintCriteria($caller);

     echo '</TD>
           <TD WIDTH="40%" VALIGN=TOP>';
      
     PrintFramedBoxHeader(_QSCSUMM, "#669999", "#FFFFFF");
     PrintGeneralStats($db, 1, $show_summary_stats, 
                       "$join_sql ", "$where_sql $criteria_sql"); 
     echo('<BR><LI><A HREF="base_stat_time.php">'._QSCTIMEPROF.'</A> '._QSCOFALERTS . "</LI>");
     PrintFramedBoxFooter();

     echo ' </TD>
           </TR>
          </TABLE>';
  }

    /* Clear the old checked positions */
    for ( $i = 0; $i < $show_rows; $i++)  
    { 
        $action_lst[$i] = "";  
        $action_chk_lst[$i] = ""; 
    }

  /* Print the current view number and # of rows */
  $qs->PrintResultCnt();
  $qro->PrintHeader();

    $i = 0;
    while ( ($myrow = $result->baseFetchRow()) && ($i < $qs->GetDisplayRowCnt() ) )
    {
      $current_sip32 = $myrow[4];
      $current_sip = baseLong2IP($current_sip32);
      $current_dip32 = $myrow[5];
      $current_dip = baseLong2IP($current_dip32); 
      $current_proto = $myrow[6];
      if ($debug_mode > 1)
      {
        SQLTraceLog("\n\n");
        SQLTraceLog(__FILE__ . ":" . __LINE__ . ":\n############## <calls to BuildSigByID> ##################");
      }
      $current_sig = BuildSigByID($myrow[2], $db);
      $current_sig_txt = BuildSigByID($myrow[2], $db, 2);
      if ($debug_mode > 1)
      {
        SQLTraceLog(__FILE__ . ":" . __LINE__ . ":\n################ </calls to BuildSigByID> ###############");
        SQLTraceLog("\n\n");
      }
      $current_sport = $current_dport = "";

      if ($portscan_payload_in_signature == 1) {
                /* fetch from payload portscan open port number */
                if (stristr($current_sig_txt, "(portscan) Open Port")) {
                          $sql2 = "SELECT data_payload FROM data WHERE sid='".$myrow[0]."' AND cid='".$myrow[1]."'";
                          $result2 = $db->baseExecute($sql2);
                          $myrow_payload = $result2->baseFetchRow();
                          $result2->baseFreeRows();
                          $myrow_payload = PrintCleanHexPacketPayload($myrow_payload[0], 2);
                          $current_sig = $current_sig . str_replace("Open Port", "", $myrow_payload);
                }
                /* fetch from payload portscan port range */
                else if ( stristr($current_sig_txt, "(portscan) TCP Portscan") || 
                          stristr($current_sig_txt, "(portscan) UDP Portscan")) {
                          $sql2 = "SELECT data_payload FROM data WHERE sid='".$myrow[0]."' AND cid='".$myrow[1]."'";
                          $result2 = $db->baseExecute($sql2);
                          $myrow_payload = $result2->baseFetchRow();
                          $result2->baseFreeRows();
                          $myrow_payload = PrintCleanHexPacketPayload($myrow_payload[0], 2);
                          $current_sig = $current_sig . stristr(stristr($myrow_payload, "Port/Proto Range"), ": ");
                }
      }

      $current_sig = GetTagTriger($current_sig, $db, $myrow[0], $myrow[1]);

      qroPrintEntryHeader( (($colored_alerts == 1) ?
                GetSignaturePriority($myrow[2], $db) : $i),
                $colored_alerts);


      $tmp_rowid = "#".(($qs->GetCurrentView() * $show_rows)+$i).
                   "-(".$myrow[0]."-".$myrow[1].")";

      qroPrintEntry('<INPUT TYPE="checkbox" NAME="action_chk_lst['.$i.']" VALUE="'.
                    htmlspecialchars($tmp_rowid).'">');
      echo '    <INPUT TYPE="hidden" NAME="action_lst['.$i.']" VALUE="'.htmlspecialchars($tmp_rowid).'">';

	/** Fix for bug #1116034 -- Input by Tim Rupp, original solution and code by Alejandro Flores **/
	$temp = "<A HREF='base_qry_alert.php?submit=".rawurlencode($tmp_rowid)."&amp;sort_order=";
	$temp .= ($qs->isCannedQuery()) ? $qs->getCurrentCannedQuerySort() : $qs->getCurrentSort();
	$temp .= "'>".$tmp_rowid."</a>";
	qroPrintEntry($temp);
	$temp = "";

      qroPrintEntry($current_sig, "left");
      qroPrintEntry($myrow[3]);

      $tmp_iplookup = 'base_qry_main.php?sig%5B0%5D=%3D'.
                          '&amp;num_result_rows=-1'.
                          '&amp;time%5B0%5D%5B0%5D=+&amp;time%5B0%5D%5B1%5D=+'.
                          '&amp;submit='._QUERYDBP.'&amp;current_view=-1&amp;ip_addr_cnt=2';

      /* TCP or UDP show the associated port # */ 
      if ( ($current_proto == TCP) || ($current_proto == UDP) )
         $result4 = $db->baseExecute("SELECT layer4_sport, layer4_dport FROM acid_event ".
                                     "WHERE sid='".$myrow[0]."' AND cid='".$myrow[1]."'");
      
      if ( ($current_proto == TCP) || ($current_proto == UDP) )
      {
         $myrow4 = $result4->baseFetchRow();

         if ( $myrow4[0] != "" )  $current_sport = ":".$myrow4[0];
         if ( $myrow4[1] != "" )  $current_dport = ":".$myrow4[1];
      }
      
      if ( $current_sip32 != "" )
      {
         qroPrintEntry('<A HREF="base_stat_ipaddr.php?ip='.$current_sip.'&amp;netmask=32">'.
                       $current_sip.
                       '</A><FONT SIZE="-1">'.$current_sport.'</FONT>');
      }
      else
      {
        /* if no IP address was found check if this is a spp_portscan message
         * and try to extract a source IP
         * - contrib: Michael Bell <michael.bell@web.de>
         */
        if ( stristr($current_sig_txt, "portscan") )
        {
           $line = split (" ", $current_sig_txt);
           foreach ($line as $ps_element) 
           {
             if ( ereg("[0-9]*\.[0-9]*\.[0-9]*\.[0-9]", $ps_element)  )
             {
                $ps_element = ereg_replace (":", "", $ps_element);
                qroPrintEntry("<A HREF=\"base_stat_ipaddr.php?ip=".$ps_element."&amp;netmask=32\">".
                              $ps_element."</A>");
             }
           }
        }
        else
           qroPrintEntry('<A HREF="'.$BASE_urlpath.'/help/base_app_faq.php#1">'._UNKNOWN.'</A>');
      }

      if ( $current_dip32 != "" )
         qroPrintEntry('<A HREF="base_stat_ipaddr.php?ip='.$current_dip.'&amp;netmask32">'.
                       $current_dip.
                       '</A><FONT SIZE="-1">'.$current_dport.'</FONT>');
       else
         qroPrintEntry('<A HREF="'.$BASE_urlpath.'/help/base_app_faq.php#1">'._UNKNOWN.'</A>');    

      qroPrintEntry('<FONT>'.IPProto2str($current_proto).'</FONT>');

      qroPrintEntryFooter();

      $i++;
      if ( ($current_proto == 6) || ($current_proto == 17) )
      {
         $result4->baseFreeRows();
         $myrow4[0] = $myrow4[1] = "";
      }
    }
    $result->baseFreeRows();

  $qro->PrintFooter();

  $qs->PrintBrowseButtons();
  $qs->PrintAlertActionButtons();
?>








