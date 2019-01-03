<?php
/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Leads: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: Common Functions
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

function GetSensorIDs($db)
{
   $result = $db->baseExecute("SELECT sid FROM sensor;");

   while( $myrow = $result->baseFetchRow() ) {
	$sensor_ids[] = $myrow[0];
   }

   $result->baseFreeRows();

   return $sensor_ids;
}

function GetSensorName($sid, $db)
{
    $name = "";

    $temp_sql = "SELECT sid, hostname, interface, filter FROM sensor WHERE sid='".$sid."'";
    $tmp_result = $db->baseExecute($temp_sql);
    if ( $tmp_result )
    {
       $myrow = $tmp_result->baseFetchRow();
       $name = $myrow[1].':'.$myrow[2];
       if ( $myrow[3] != "" )
          $name = $name.':'.$myrow[3];
    }
    $tmp_result->baseFreeRows();

    return $name;
}

function GetVendor($mac)
{
    $mac = str_replace(":", "", $mac);
    $mac = substr($mac, 0, 6);
    $vendor = 'unknown';

    if (@$fp = fopen("base_mac_prefixes.map", "r")) {
       while (!feof($fp)) {
           $line = fgets($fp);
           if (strcmp($mac, substr($line, 0, 6)) == 0)
               $vendor = substr($line, 7, strlen($line)-8);
       }
       fclose($fp);
    } 
    else 
       return "can't open vendor map";

    return $vendor;
}

function InputSafeSQL (&$SQLstr)
/* Removes the escape sequence of \' => ' which arise when a variable containing a '-character is passed
   through a POST query.  This is needed since otherwise the MySQL parser complains */
{
   $SQLstr = str_replace("\'", "'", $SQLstr);
   $SQLstr = str_replace("\\\"", "\"", $SQLstr);
}


function PrintProtocolProfileGraphs ($db)
{
   $tcp_cnt = TCPPktCnt($db);
   $udp_cnt = UDPPktCnt($db);
   $icmp_cnt = ICMPPktCnt($db);
   $portscan_cnt = PortscanPktCnt($db);
   $layer4_cnt = $tcp_cnt + $udp_cnt + $icmp_cnt + $portscan_cnt;

   if ( $tcp_cnt > 0 )
   {  
      $tcp_percent = round($tcp_cnt/$layer4_cnt*100);
      if ( $tcp_percent == 0 )  
         $tcp_percent_show = "&lt; 1";
      else
         $tcp_percent_show = $tcp_percent; 
   }  
   else 
   {
      $tcp_percent = 0;
      $tcp_percent_show = "0";
   }

   if ( $udp_cnt > 0 )
   {  
      $udp_percent = round($udp_cnt/$layer4_cnt*100);
      if ( $udp_percent == 0 )  
         $udp_percent_show = "&lt; 1";  
      else 
         $udp_percent_show = $udp_percent;
   }   
   else
   { 
      $udp_percent = 0;
      $udp_percent_show = "0";
   }

   if ( $icmp_cnt > 0 ) 
   {
      $icmp_percent = round($icmp_cnt/$layer4_cnt*100); 
      if ( $icmp_percent == 0 )  
         $icmp_percent_show = "&lt; 1";
       else
         $icmp_percent_show = $icmp_percent;
   }
   else
   { 
      $icmp_percent = 0;
      $icmp_percent_show = 0;
   }

   if ( $portscan_cnt > 0 )
   {  
      $portscan_percent = round($portscan_cnt/$layer4_cnt*100);
      if ( $portscan_percent == 0 )  
         $portscan_percent_show = "&lt; 1";
      else
         $portscan_percent_show = $portscan_percent; 
   }  
   else 
   {
      $portscan_percent = 0;
      $portscan_percent_show = "0";
   }

   if ( $tcp_percent > 0 )   $color = "#FF0000";  else  $color="#CCCCCC";
   $rem_percent=100-$tcp_percent;
   echo '<TABLE WIDTH="100%" BORDER=0>
         <TR><TD>TCP<A HREF="base_qry_main.php?new=1'.
                           '&amp;layer4=TCP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                           ('.$tcp_percent_show.'%)</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>
                  <TR><TD ALIGN=CENTER BGCOLOR="'.$color.'" WIDTH="'.$tcp_percent.'%">&nbsp;</TD>';
                      if ( $tcp_percent > 0 )  echo '<TD BGCOLOR="#CCCCCC" WIDTH="'.$rem_percent.'%">&nbsp;</TD>';
		      echo '</TR></TABLE>';

    if ( $udp_percent > 0 )   $color = "#FF0000";  else  $color="#CCCCCC";
    $rem_percent=100-$udp_percent;
    echo '<TABLE WIDTH="100%" BORDER=0>
          <TR><TD>UDP<A HREF="base_qry_main.php?new=1'.
                            '&amp;layer4=UDP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                            ('.$udp_percent_show.'%)</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>
                  <TR><TD ALIGN=CENTER BGCOLOR="'.$color.'" WIDTH="'.$udp_percent.'%">&nbsp;</TD>';
                      if ( $udp_percent > 0 ) echo '<TD BGCOLOR="#CCCCCC" WIDTH="'.$rem_percent.'%">&nbsp;</TD>';
		      echo '</TR></TABLE>';

     if ( $icmp_percent > 0 )   $color = "#FF0000";  else  $color="#CCCCCC";
     $rem_percent=100-$icmp_percent;
     echo '<TABLE WIDTH="100%" BORDER=0>
           <TR><TD>ICMP<A HREF="base_qry_main.php?new=1'.
                              '&amp;layer4=ICMP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                              ('.$icmp_percent_show.'%)</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>
                  <TR><TD ALIGN=CENTER BGCOLOR="'.$color.'" WIDTH="'.$icmp_percent.'%">&nbsp;</TD>';
                      if ( $icmp_percent > 0 ) echo '<TD BGCOLOR="#CCCCCC" WIDTH="'.$rem_percent.'%">&nbsp;</TD>';
		      echo '</TR></TABLE>';
    
     echo '<CENTER><HR NOSHADE WIDTH="70%"></CENTER>';

     if ( $portscan_percent > 0 )   $color = "#FF0000";  else  $color="#CCCCCC";
     $rem_percent=100-$portscan_percent;
     echo '<TABLE WIDTH="100%" BORDER=0>
           <TR><TD>'._PORTSCAN.'
               <A HREF="base_qry_main.php?new=1'.
'&amp;layer4=RawIP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">('.$portscan_percent_show.'%)</A>
                    </TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>
                  <TR><TD ALIGN=CENTER BGCOLOR="'.$color.'" WIDTH="'.$portscan_percent.'%">&nbsp;</TD>';
                      if ( $portscan_percent > 0 )  echo '<TD BGCOLOR="#CCCCCC" WIDTH="'.$rem_percent.'%">&nbsp;</TD>';
		      echo '</TR></TABLE>';
}

function BuildIPFormVars($ipaddr)
{
    return ''.
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=OR'.
    '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst&amp;ip_addr%5B1%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B1%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+';
}

function BuildSrcIPFormVars($ipaddr)
{
    return ''.
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
}

function BuildDstIPFormVars($ipaddr)
{
    return ''.
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
}

function BuildUniqueAddressLink($addr_type, $raw = "" )
{
   return '<A HREF="base_stat_uaddr.php?addr_type='.$addr_type.$raw.'">';
}

function BuildUniqueAlertLink($raw)
{
   return '<A HREF="base_stat_alerts.php'.$raw.'">';
}

function BuildAddressLink($ipaddr, $netmask)
{
   return '<A HREF="base_stat_ipaddr.php?ip='.rawurlencode($ipaddr).
                                       '&amp;netmask='.$netmask.'">';
}

/* Adds another blank row to a given criteria element */
function AddCriteriaFormRow ( &$submit, $submit_value, &$cnt, &$criteria_array, $max )
{
   $submit = $submit_value;

  ++$cnt;
  InitArray($criteria_array[$cnt-1], $max, 0, "");   
}

function IPProto2str($ipproto_code)
{
   switch($ipproto_code)
   {
      case 0:
          return "IP";
      case 1:
          return "ICMP";
      case 2:
          return "IGMP";
      case 4:
          return "IPIP tunnels";
      case 6:
          return "TCP";
      case 8:
          return "EGP";
      case 12:
          return "PUP";
      case  17:
          return "UDP";
      case 22:
          return "XNS UDP";
      case 29:
          return "SO TP Class 4";
      case 41:
          return "IPv6 header";
      case 43:
          return "IPv6 routing header";
      case 44:
          return "IPv6 fragmentation header";
      case 46: 
          return "RSVP";
      case 47:
          return "GRE";
      case 50: 
          return "IPSec ESP";
      case 51: 
          return "IPSec AH";
      case 58: 
          return "ICMPv6";
      case 59: 
          return "IPv6 no next header";
      case 60:
          return "IPv6 destination options";
      case 92:
          return "MTP";
      case 98:
          return "Encapsulation header";
      case 103: 
          return "PIM";
      case 108:
          return "COMP";
      case 255: 
          return "Raw IP";
      default:
          return $ipproto_code;
   }
} 

function TCPOption2str($tcpopt_code)
/* per RFC 1072, 1323, 1644 */
{
   switch($tcpopt_code)
   {
      case 2:                  /* TCPOPT_MAXSEG - maximum segment*/ 
          return "(2) MSS";
      case 0:                  /* TCPOPT_EOL */
          return "(0) EOL";
      case 1:                  /* TCPOPT_NOP */
          return "(1) NOP";
      case 3:                  /* TCPOPT_WSCALE (rfc1072)- window scale factor */
          return "(3) WS";
      case 5:                  /* TCPOPT_SACK (rfc1072)- selective ACK */
          return "(5) SACK";
      case 4:                  /* TCPOPT_SACKOK (rfc1072)- selective ACK OK */
          return "(4) SACKOK";
      case 6:                  /* TCPOPT_ECHO (rfc1072)- echo */
          return "(6) Echo";
      case 7:                  /* TCPOPT_ECHOREPLY (rfc1072)- echo reply */
          return "(7) Echo Reply";
      case 8:                  /* TCPOPT_TIMESTAMP (rfc1323)- timestamps */
          return "(8) TS";
      case 9:                  /* RFC1693 */
          return "(9) Partial Order Connection Permitted";
      case 10:                  /* RFC1693 */ 
          return "(10) Partial Order Service Profile";
      case 11:                 /* TCPOPT_CC (rfc1644)- CC options */
          return "(11) CC";
      case 12:                 /* TCPOPT_CCNEW (rfc1644)- CC options */
          return "(12) CCNEW";
      case 13:                 /* TCPOPT_CCECHO (rfc1644)- CC options */
          return "(13) CCECHO";
      case 14:                 /* RFC1146 */
          return "(14) TCP Alternate Checksum Request";
      case 15:                 /* RFC1146 */
          return "(15) TCP Alternate Checksum Data";
      case 16:
          return "(16) Skeeter";
      case 17:
          return "(17) Bubba";
      case 18:                 /* Subbu and Monroe */
          return "(18) Trailer Checksum Option";
      case 19:                 /* Subbu and Monroe */
          return "(19) MD5 Signature";
      case 20:                 /* Scott */
          return "(20) SCPS Capabilities";
      case 21:                /* Scott */
          return "(21) Selective Negative Acknowledgements";
      case 22:                /* Scott */
          return "(22) Record Boundaries";
      case 23:                /* Scott */
          return "(23) Corruption Experienced";
      case 24:                /* Sukonnik */
          return "(24) SNAP";
      case 25:
          return "(25) Unassigned";
      case 26:                /* Bellovin */
          return "(26) TCP Compression Filter";
      default:
          return $tcpopt_code;
   }
}

function IPOption2str($ipopt_code)
{
   switch($ipopt_code)
   {
      case 7:              /* IPOPT_RR */
          return "RR";
      case 0:              /* IPOPT_EOL */
          return "EOL";
      case 1:              /* IPOPT_NOP */
          return "NOP";
      case 0x44:           /* IPOPT_TS */
          return "TS";
      case 0x82:           /* IPOPT_SECURITY */
          return "SEC";
      case 0x83:           /* IPOPT_LSRR */
          return "LSRR";
      case 0x84:           /* IPOPT_LSRR_E */
          return "LSRR_E";
      case 0x88:           /* IPOPT_SATID */
          return "SID";
      case 0x89:           /* IPOPT_SSRR */
          return "SSRR";
  }
}

function ICMPType2str($icmp_type)
{
  switch ($icmp_type)
  {
      case 0:                             /* ICMP_ECHOREPLY */
          return "Echo Reply";
      case 3:                             /* ICMP_DEST_UNREACH */
          return "Destination Unreachable";
      case 4:                             /* ICMP_SOURCE_QUENCH */
          return "Source Quench";
      case 5:                             /* ICMP_REDIRECT */
          return "Redirect";
      case 8:                             /* ICMP_ECHO */
          return "Echo Request";
      case 9:
          return "Router Advertisement";
      case 10:
          return "Router Solicitation"; 
      case 11:                            /* ICMP_TIME_EXCEEDED */
          return "Time Exceeded";
      case 12:                            /* ICMP_PARAMETERPROB */
          return "Parameter Problem";
      case 13:                            /* ICMP_TIMESTAMP */
          return "Timestamp Request";
      case 14:                            /* ICMP_TIMESTAMPREPLY */
          return "Timestamp Reply";
      case 15:                            /* ICMP_INFO_REQUEST */
          return "Information Request";
      case 16:                            /* ICMP_INFO_REPLY */
          return "Information Reply";
      case 17:                            /* ICMP_ADDRESS */
          return "Address Mask Request";
      case 18:                            /* ICMP_ADDRESSREPLY */
          return "Address Mask Reply";
      case 19:
          return "Reserved (security)";
      case 20:
          return "Reserved (robustness)";
      case 21:
          return "Reserved (robustness)";
      case 22:
          return "Reserved (robustness)";
      case 23:
          return "Reserved (robustness)";
      case 24:
          return "Reserved (robustness)";
      case 25:
          return "Reserved (robustness)";
      case 26:
          return "Reserved (robustness)";
      case 27:
          return "Reserved (robustness)";
      case 28:
          return "Reserved (robustness)";
      case 29:
          return "Reserved (robustness)";
      case 30:
          return "Traceroute";
      case 31:
          return "Datagram Conversion Error";
      case 32:
          return "Mobile Host Redirect";
      case 33:
          return "IPv6 Where-Are-You";
      case 34:
          return "IPv6 I-Am-Here";
      case 35:
          return "Mobile Registration Request";
      case 36:
          return "Mobile Registration Reply";
      case 37:
          return "Domain Name Request";
      case 38:
          return "Domain Name Reply";
      case 39:
          return "SKIP";
      case 40:
          return "Photuris";
      default:
          return $icmp_type;
  }
}

function ICMPCode2str($icmp_type, $icmp_code)
{
  if ( $icmp_type == 3 )
  {
     switch ($icmp_code)
     {
        case 0:                                    /* ICMP_NET_UNREACH */
            return "Network Unreachable";
        case 1:                                    /* ICMP_HOST_UNREACH */
            return "Host Unreachable";
        case 2:                                    /* ICMP_PROT_UNREACH */
            return "Protocol Unreachable";
        case 3:                                    /* ICMP_PORT_UNREACH */
            return "Port Unreachable";
        case 4:                                    /* ICMP_FRAG_NEEDED */
            return "Fragmentation Needed/DF set";
        case 5:                                    /* ICMP_SR_FAILED */
            return "Source Route failed";
        case 6:                                    /* ICMP_NET_UNKNOWN */
            return "Network Unknown";
        case 7:                                    /* ICMP_HOST_UNKNOWN */
            return "Host Unknown";
        case 8:                                    /* ICMP_HOST_ISOLATED */
            return "Host Isolated";
        case 9:                                    /* ICMP_NET_ANO */
            return "Network ANO";
        case 10:                                   /* ICMP_HOST_ANO */
            return "Host ANO";
        case 11:                                   /* ICMP_NET_UNR_TOS */
            return "Network Unreach TOS";
        case 12:                                   /* ICMP_HOST_UNR_TOS */
            return "Host Unreach TOS";
        case 13:                                   /* ICMP_PKT_FILTERED */
            return "Packet Filtered";
        case 14:                                   /* ICMP_PREC_VIOLATION */
            return "Precedence violation";
        case 15:                                   /* ICMP_PREC_CUTOFF */
            return "Precedence cut off";
        default:
            return $icmp_code;
     }
  } 
  elseif ( $icmp_type == 5 )
  {
     switch ($icmp_code)
     {
        case 0:
            return "Redirect datagram for network/subnet";
        case 1:
            return "Redirect datagram for host";
        case 2:
            return "Redirect datagram for ToS and network";
        case 3:
            return "Redirect datagram for Tos and host";
        default:
            return $icmp_code;
      }
   }
   elseif ( $icmp_type == 9 ) 
   {
      switch ($icmp_code)
      {
         case 0:
             return "Normal router advertisement";
         case 16:
             return "Does not route common traffic";
         default:
             return $icmp_code;
      }
   }
   elseif ( $icmp_type == 11 )
   {
      switch ($icmp_code)
      {
         case 0:
             return "TTL exceeded in transit";
         case 1:
             return "Fragment reassembly time exceeded";
         default:
             return $icmp_code;
      }
   }
   elseif ( $icmp_type == 12 ) 
   {
      switch ($icmp_code)
      {
         case 0:
             return "Pointer indicates error";
         case 1:
             return "Missing a required option";
         case 2:
             return "Bad length";
         default:
             return $icmp_code;
      }
   }
   elseif ( $icmp_type == 40 )
   {
      switch ($icmp_code)
      {
         case 0:
            return "Bad SPI";
         case 1:
            return "Authentication failed";
         case 2:
            return "Decompression failed";
         case 3:
            return "Decryption failed";
         case 4:
            return "Need authentication";
         case 5:
            return "Need authorization";
         default:
             return $icmp_code;
      }
   }
  else
     return $icmp_code;
}

function PrintPayloadChar( $char, $output_type )
{
   if ( $char >= 32 && $char <= 127 )
   {
      if ( $output_type == 2 )
         return chr($char);
      else
         return htmlspecialchars(chr($char));
   }
   else
      return '.';
}

function PrintBase64PacketPayload ( $encoded_payload, $output_type )
{
     /* strip out the <CR> at the end of each block */
     $encoded_payload = str_replace("\n", "", $encoded_payload);

     $payload = base64_decode($encoded_payload);
     $len = strlen($payload);
     $s = " "._LENGTH." = ".strlen($payload)."\n";

     for ($i = 0; $i < strlen($payload); $i++ )
     {
          if ( $i % 16 == 0 )
          {
             /* dump the ASCII characters */
             if ( $i != 0 )
             {
                $s = $s.'  ';
                for ($j = $i-16; $j < $i; $j++ )
                   $s = $s.PrintPayloadChar(ord($payload[$j]), $output_type);
             }
             $s = $s.sprintf("\n%03x : ", $i);
          }

          $s = $s.sprintf("%s ", bin2hex($payload[$i]) );
     }

     /* print the remained of any ASCII chars */
     if ( ($i % 16) != 0 )
     {
        for ( $j = 0; $j < 16 - ($i % 16); $j++)
            $s = $s.'   ';

        $s = $s.'  ';
        
        for ( $j = $len - ($i % 16); $j < $len; $j++ )
           $s = $s.PrintPayloadChar(ord($payload[$j]), $output_type);
     } else {
	$s = $s.'  ';
	for ( $j = $len - 16; $j < $len && $j > 0; $j++ )
           $s = $s.PrintPayloadChar(ord($payload[$j]), $output_type);
     }

     return $s;
}

function PrintAsciiPacketPayload ( $encoded_payload, $output_type )
{
   return wordwrap($encoded_payload, 70);
}

function PrintHexPacketPayload ( $encoded_payload, $output_type )
{
     /* strip out the <CR> at the end of each block */
     $encoded_payload = str_replace("\n", "", $encoded_payload);
     $payload = $encoded_payload;

     $len = strlen($payload);
     $s = " "._LENGTH." = ".(strlen($payload)/2)."\n";

     for ($i = 0; $i < strlen($payload); $i += 2 )
     {
          if ( $i % 32 == 0 )
          {
             /* dump the ASCII characters */
             if ( $i != 0 )
             {
                $s = $s.'  ';
                for ($j = $i-32; $j < $i; $j+=2 )
                {
                    $t = hexdec($payload[$j].$payload[$j+1]);
                    $s = $s.PrintPayloadChar($t, $output_type);
                }
             }
             $s = $s.sprintf("\n%03x : ", $i/2);
          }
          $s = $s.sprintf("%s%s ", $payload[$i], $payload[$i+1] );
     }

     /* space through to align end of hex dump */
     if ( $i % 32 )
        for ( $j = 0; $j < 32 - ($i % 32); $j+=2)
           $s = $s.'   ';

     $s = $s.'  ';

     /* print the ASCII decode */
     if ( $i % 32 )
        $start = $len - ($i % 32);
     else
        $start = $len - 32;

     for ( $j = $start; $j < $i; $j+=2 )
     {
        $t = hexdec($payload[$j].$payload[$j+1]);
        $s = $s.PrintPayloadChar($t, $output_type);
     } 

     return $s;
}

// ************************************************************************************
function PrintCleanHexPacketPayload( $encoded_payload, $output_type )
{
     $len = strlen($encoded_payload);
     $s = '';
     $count = 0;
     for ($i = 0; $i < $len; $i += 2 )
     {
         /* dump the ASCII characters */
         $t = hexdec($encoded_payload[$i].$encoded_payload[$i+1]);
         $s_tmp = PrintCleanPayloadChar($t, $output_type);

         /* Join together several sequential non-ASCII characters displaying their count
          * in one line. It makes easyer to look through payload in plain display mode.
          * If current character is '<br>' and this is not last character of payload
          * increment counter, else output non-ASCII count and flush counter.
          */
         if ( ($s_tmp == '<br>') && !($i+2 == $len) ) {
             $count++;
         } else {
             if ($count > 1)
                 $s .= '<DIV class="nonascii">['. $count .' non-ASCII characters]</DIV>';
             elseif ($count == 1)
                 $s .= '<br>';
             $s .= $s_tmp;
             $count = 0;
         }
     }
     return $s;
}

function PrintCleanPayloadChar( $char, $output_type )
{
   if ( $char >= 32 && $char <= 127 )
   {
      if ( $output_type == 2 )
         return chr($char);
      else
         return htmlspecialchars(chr($char));
   }
   else
      return '<br>';
}

// ************************************************************************************

function PrintPacketPayload($data, $encode_type, $output_type)
{
     if ( $output_type == 1 )
        printf("\n<PRE>\n");

     /* print the packet based on encoding type */;
     if ( $encode_type == "1" )
         $payload = PrintBase64PacketPayload($data, $output_type);
     else if ($encode_type == "0" )
     {
         if ( isset($_GET['asciiclean']) && ($_GET['asciiclean'] == 1) || ( (isset($_COOKIE['asciiclean']) && $_COOKIE['asciiclean'] == "clean") && (!isset($_GET['asciiclean'])) ) )
	 {
	    // Print clean ascii display
            $payload = PrintCleanHexPacketPayload($data, $output_type);
	 }
	 else
	 {
	    $payload = PrintHexPacketPayload($data, $output_type);
	 }
     }
     else if ($encode_type == "2" )
         $payload = PrintAsciiPacketPayload($data, $output_type); 
   
     if ( $output_type == 1 )
        echo "$payload\n</PRE>\n";
     
     return $payload;
}

function GetQueryResultID($submit, &$seq, &$sid, &$cid)
{
  /* extract the sid and cid from the $submit variable of the form
     #XX-(XX-XX) 
      |   |  |
      |   |  |--- cid
      |   |------ sid
      |---------- sequence number of DB lookup
  */

  $submit = strstr($submit, "#");
  $submit = str_replace("#", "", $submit);
  $submit = str_replace("(", "", $submit);
  $submit = str_replace(")", "", $submit);
  $tmp = explode("-", $submit);
  /* Since the submit variable is not cleaned do so here: */
  $seq = CleanVariable($tmp[0], VAR_DIGIT);
  $sid = CleanVariable($tmp[1], VAR_DIGIT);
  $cid = CleanVariable($tmp[2], VAR_DIGIT);
}

function ExportPacket($sid, $cid, $db)
{
  GLOBAL $action, $action_arg;

  /* Event */
  $sql2 = "SELECT signature, timestamp FROM acid_event WHERE sid='".$sid."' AND cid='".$cid."'";
  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();

  $s = "------------------------------------------------------------------------------\n";
  $s = $s."#($sid - $cid) [$myrow2[1]] ".BuildSigByID($myrow2[0], $db, 2)."\r\n";

  $sql4 = "SELECT hostname, interface, filter FROM sensor  WHERE sid='".$sid."'";
  $result4 = $db->baseExecute($sql4);
  $myrow4 = $result4->baseFetchRow();

  $result4->baseFreeRows();
  $result2->baseFreeRows();

  /* IP */
  $sql2 = "SELECT ip_src, ip_dst, ".
          "ip_ver, ip_hlen, ip_tos, ip_len, ip_id, ip_flags, ip_off, ip_ttl, ip_csum, ip_proto". 
          " FROM iphdr  WHERE sid='".$sid."' AND cid='".$cid."'";

  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();
  $layer4_proto = $myrow2[11];

  if ( $myrow2[0] != "" )
  {
    $sql3 = "SELECT * FROM opt  WHERE sid='".$sid."' AND cid='".$cid."' AND opt_proto='0'";
    $result3 = $db->baseExecute($sql3);
    $num_opt = $result3->baseRecordCount();

    $s = $s."IPv$myrow2[2]: ".
          baseLong2IP($myrow2[0])." -> ".
          baseLong2IP($myrow2[1])."\n".
          "      hlen=$myrow2[3] TOS=$myrow2[4] dlen=$myrow2[5] ID=$myrow2[6]".
          " flags=$myrow2[7] offset=$myrow2[8] TTL=$myrow2[9] chksum=$myrow2[10]\n";

    if ( $num_opt > 0 )
    {
      $s = $s."    Options\n";
      for ( $i = 0; $i < $num_opt; $i++)
      {
         $myrow3 = $result3->baseFetchRow();
         $s = $s."      #".($i+1)." - ".IPOption2str($myrow3[4])." len=$myrow3[5]";
         if ( $myrow3[5] != 0 )
            $s = $s." data=$myrow3[6]";
         $s = $s."\n";  
       }
    }
                       
    $result3->baseFreeRows();
  }
  $result2->baseFreeRows();

  /* TCP */
  if ( $layer4_proto == "6" )  
  {
     $sql2 = "SELECT tcp_sport, tcp_dport, tcp_seq, tcp_ack, tcp_off, tcp_res, tcp_flags, tcp_win, ".
             "       tcp_csum, tcp_urp FROM tcphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     $sql3 = "SELECT * FROM opt  WHERE sid='".$sid."' AND cid='".$cid."' AND opt_proto='6'";
     $result3 = $db->baseExecute($sql3);
     $num_opt = $result3->baseRecordCount();

     $s = $s."TCP:  port=$myrow2[0] -> dport: $myrow2[1]  flags=";

      if ( ($myrow2[6] & 128) != 0 )  $s = $s.'2';  else $s = $s.'*';
      if ( ($myrow2[6] & 64 ) != 0 )  $s = $s.'1';  else $s = $s.'*';
      if ( ($myrow2[6] & 32) != 0  )  $s = $s.'U';  else $s = $s.'*';
      if ( ($myrow2[6] & 16 ) != 0 )  $s = $s.'A';  else $s = $s.'*';
      if ( ($myrow2[6] & 8) != 0   )  $s = $s.'P';  else $s = $s.'*';
      if ( ($myrow2[6] & 4 ) != 0  )  $s = $s.'R';  else $s = $s.'*';
      if ( ($myrow2[6] & 2 ) != 0  )  $s = $s.'S';  else $s = $s.'*';
      if ( ($myrow2[6] & 1 ) != 0  )  $s = $s.'F';  else $s = $s.'*';

      $s = $s." seq=$myrow2[2]\n".
              "      ack=$myrow2[3] off=$myrow2[4] res=$myrow2[5] win=$myrow2[7] urp=$myrow2[9] ".
              "chksum=$myrow2[8]\n";

      if ( $num_opt != 0)
      {
         $s = $s."      Options:\n";
         for ( $i = 0; $i < $num_opt; $i++)
         {
             $myrow3 = $result3->baseFetchRow();
             $s = $s."       #".($i+1)." - ".TCPOption2str($myrow3[4])." len=$myrow3[5]";
             if ( $myrow3[5] != 0 )
                $s = $s." data=".$myrow3[6];
            $s = $s."\n";           
         }
      }

      $result2->baseFreeRows();
      $result3->baseFreeRows();
  }

  /* UDP */
  if ( $layer4_proto == "17" )
  {

     $sql2 = "SELECT * FROM udphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     $s = $s."UDP:  port=$myrow2[2] -> dport: $myrow2[3] len=$myrow2[4]\n";

     $result2->baseFreeRows();
  }

  /* ICMP */
  if ( $layer4_proto == "1" )
  {
     $sql2 = "SELECT icmp_type, icmp_code, icmp_csum, icmp_id, icmp_seq FROM icmphdr ".
             "WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     $s = $s."ICMP: type=".ICMPType2str($myrow2[0])." code=".ICMPCode2str($myrow2[0],$myrow2[1])."\n".
             "      checksum=$myrow2[2] id=$myrow2[3] seq=$myrow2[4]\n";

     $result2->baseFreeRows();
  }

  /* Print the Payload */
  $sql2 = "SELECT data_payload FROM data WHERE sid='".$sid."' AND cid='".$cid."'";
  $result2 = $db->baseExecute($sql2);

  /* get encoding information and detail_level on the payload */
  $sql3 = 'SELECT encoding, detail FROM sensor WHERE sid='.$sid;
  $result3 = $db->baseExecute($sql3);
  $myrow3 = $result3->baseFetchRow();  
  
  $s = $s."Payload: ";

  $myrow2 = $result2->baseFetchRow();
  if ( $myrow2 )
  {
     /* print the packet based on encoding type */
     $s = $s.PrintPacketPayload($myrow2[0], $myrow3[0], 2)."\n";

     $result3->baseFreeRows();     
  }
  else
  {
     /* Don't have payload so lets print out why by checking the detail level */

     /* if have fast detail level */
     if ( $myrow3[1] == "0" )
        $s = $s."Fast logging used so payload was discarded\n";
     else
        $s = $s."none\n";
  }

  $result2->baseFreeRows();
 
  return $s; 
}

function ExportPacket_summary($sid, $cid, $db, $export_type = 0)
{
  GLOBAL $action, $action_arg;

  /* Event */
  $sql2 = "SELECT signature, timestamp FROM acid_event WHERE sid='".$sid."' AND cid='".$cid."'";
  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();

  $alert_timestamp = $myrow2[1];
  $alert_sig = BuildSigByID($myrow2[0], $db, 2);

  $result2->baseFreeRows();

  /* IP */
  $src_ip = $dst_ip = $src_port = $dst_port = "";
  $sql2 = "SELECT ip_src, ip_dst, ip_proto". 
          " FROM iphdr  WHERE sid='".$sid."' AND cid='".$cid."'";

  $result2 = $db->baseExecute($sql2);
  $myrow2 = $result2->baseFetchRow();

  $layer4_proto = "";
  if ( $myrow2[0] != "" )
  {
     $src_ip = baseLong2IP($myrow2[0]);
     $dst_ip = baseLong2IP($myrow2[1]);
     $layer4_proto = $myrow2[2];
  }
  $result2->baseFreeRows();

  /* TCP */
  if ( $layer4_proto == "6" )  
  {
     $sql2 = "SELECT tcp_sport, tcp_dport FROM tcphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     if ( $export_type == 0 )
     {
        $src_port = ":".$myrow2[0]." -> ";
        $dst_port = ":".$myrow2[1];
     }  
     else
     {
        $src_port = $myrow2[0];
        $dst_port = $myrow2[1];
     }   

     $result2->baseFreeRows();
  }

  /* UDP */
  if ( $layer4_proto == "17" )
  {

     $sql2 = "SELECT * FROM udphdr  WHERE sid='".$sid."' AND cid='".$cid."'";
     $result2 = $db->baseExecute($sql2);
     $myrow2 = $result2->baseFetchRow();

     if ( $export_type == 0 )
     {
       $src_port = ":".$myrow2[2]." -> ";
       $dst_port = ":".$myrow2[3];
     }
     else
     {
       $src_port = $myrow2[2];
       $dst_port = $myrow2[3];
     }

     $result2->baseFreeRows();
  }

  /* ICMP */
  if ( $layer4_proto == "1" )
  {
     if ( $export_type == 0 )
       $src_ip = $src_ip." -> ";
     $src_port = $dst_port = "";
  }
  
 /* Portscan Traffic */
   if ( $layer4_proto == "255" )
   {
      if ( $export_type == 0 )
         $src_ip = $src_ip." -> ";
   }

  if ( $export_type == 0 )
  {
    $s = sprintf("#%d-%d| [%s] %s%s%s%s %s\r\n",
                 $sid, $cid, $alert_timestamp, 
                 $src_ip, $src_port, $dst_ip, $dst_port,
                 $alert_sig); 
  }
  else /* CSV format */
  {
    $s = sprintf("\"%d\", \"%d\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\"\r\n",
                 $sid, $cid, $alert_timestamp,
                 $src_ip, $src_port, $dst_ip, $dst_port,
                 $alert_sig);
  }

  return $s; 
}

function base_header($url) {
    header($url);
    exit;
}


function base_microtime()
{
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
