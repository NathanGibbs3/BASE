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
//            Purpose: Common Functions
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

function GetSensorIDs( $db ){
	$result = $db->baseExecute("SELECT sid FROM sensor;");
	while( $myrow = $result->baseFetchRow() ){
		$sensor_ids[] = $myrow[0];
	}
	$result->baseFreeRows();
	return $sensor_ids;
}

function GetSensorName( $sid, $db ){
	$name = '';
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

function GetVendor( $mac ){
	$mac = str_replace(':', '', $mac);
	$mac = substr($mac, 0, 6);
	$vendor = 'unknown';
	$file = 'base_mac_prefixes.map';
	if( ChkAccess($file) > 0 ){
		$fp = fopen($file, 'r');
		while( !feof($fp) ){
			$line = fgets($fp);
			if( strcmp($mac, substr($line, 0, 6)) == 0 ){
				$vendor = substr($line, 8, strlen($line)-9);
			}
		}
		fclose($fp);
	}else{
		return "Can't open vendor map.";
	}
	return $vendor;
}

function InputSafeSQL( &$SQLstr ){
	// Removes the escape sequence of \' => ' which arise when a variable
	// containing a '-character is passed through a POST query. This is
	// needed since otherwise the MySQL parser complains.
	$SQLstr = str_replace("\'", "'", $SQLstr);
	$SQLstr = str_replace("\\\"", "\"", $SQLstr);
}

function PrintProtocolProfileGraphs( $db ){
	$tcp_cnt = TCPPktCnt($db);
	$udp_cnt = UDPPktCnt($db);
	$icmp_cnt = ICMPPktCnt($db);
	$portscan_cnt = PortscanPktCnt($db);
	$layer4_cnt = $tcp_cnt + $udp_cnt + $icmp_cnt + $portscan_cnt;
	$tcp_percent_show = HtmlPercent($tcp_cnt,$layer4_cnt);
	$udp_percent_show = HtmlPercent($udp_cnt,$layer4_cnt);
	$icmp_percent_show = HtmlPercent($icmp_cnt,$layer4_cnt);
	$portscan_percent_show = HtmlPercent($portscan_cnt,$layer4_cnt);
   echo '<TABLE WIDTH="100%" BORDER=0>
         <TR><TD>TCP<A HREF="base_qry_main.php?new=1'.
                           '&amp;layer4=TCP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                           ('.$tcp_percent_show.')</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>';
	print '<tr>' . HBarGraph($tcp_cnt,$layer4_cnt,'ff0000','cccccc');
	PrintFramedBoxFooter(0,2);

    echo '<TABLE WIDTH="100%" BORDER=0>
          <TR><TD>UDP<A HREF="base_qry_main.php?new=1'.
                            '&amp;layer4=UDP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                            ('.$udp_percent_show.')</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>';
	print '<tr>' . HBarGraph($udp_cnt,$layer4_cnt,'ff0000','cccccc');
	PrintFramedBoxFooter(0,2);

     echo '<TABLE WIDTH="100%" BORDER=0>
           <TR><TD>ICMP<A HREF="base_qry_main.php?new=1'.
                              '&amp;layer4=ICMP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">
                              ('.$icmp_percent_show.')</A></TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>';
	print '<tr>' . HBarGraph($icmp_cnt,$layer4_cnt,'ff0000','cccccc');
	PrintFramedBoxFooter(0,2);

     echo '<CENTER><HR NOSHADE WIDTH="70%"></CENTER>';

     echo '<TABLE WIDTH="100%" BORDER=0>
           <TR><TD>'._PORTSCAN.'
               <A HREF="base_qry_main.php?new=1'.
'&amp;layer4=RawIP&amp;num_result_rows=-1&amp;sort_order=time_d&amp;submit='._QUERYDBP.'">('.$portscan_percent_show.')</A>
                    </TD><TD></TD></TR></TABLE>
                  <TABLE class="summarygraph" WIDTH="100%" BORDER=1 CELLSPACING=0 CELLPADDING=0>';
	print '<tr>' . HBarGraph($portscan_cnt,$layer4_cnt,'ff0000','cccccc');
	PrintFramedBoxFooter(0,2);
}

function BuildIPFormVars( $ipaddr ){
	return '' .
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=OR'.
    '&amp;ip_addr%5B1%5D%5B0%5D=+&amp;ip_addr%5B1%5D%5B1%5D=ip_dst&amp;ip_addr%5B1%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B1%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B1%5D%5B8%5D=+&amp;ip_addr%5B1%5D%5B9%5D=+';
}

function BuildSrcIPFormVars( $ipaddr ){
	return '' .
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_src&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
}

function BuildDstIPFormVars( $ipaddr ){
	return '' .
    '&amp;ip_addr%5B0%5D%5B0%5D=+&amp;ip_addr%5B0%5D%5B1%5D=ip_dst&amp;ip_addr%5B0%5D%5B2%5D=%3D'.
    '&amp;ip_addr%5B0%5D%5B3%5D='.$ipaddr.
    '&amp;ip_addr%5B0%5D%5B8%5D=+&amp;ip_addr%5B0%5D%5B9%5D=+';
}

function BuildUniqueAddressLink( $addr_type, $raw = '' ){
	return '<A HREF="base_stat_uaddr.php?addr_type=' . $addr_type . $raw . '">';
}

function BuildUniqueAlertLink( $raw ){
	return '<A HREF="base_stat_alerts.php' . $raw . '">';
}

function BuildAddressLink( $ipaddr, $netmask ){
	return '<A HREF="base_stat_ipaddr.php?ip=' . rawurlencode($ipaddr)
	. '&amp;netmask=' . $netmask . '">';
}

// Add blank row to given criteria element.
function AddCriteriaFormRow(
	&$submit, $submit_value, &$cnt, &$criteria_array, $max
){
	$submit = $submit_value;
	++$cnt;
	InitArray($criteria_array[$cnt-1], $max, 0, '');
}

function IPProto2str( $ipproto_code ){
	switch( $ipproto_code ){
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

function TCPOption2str( $tcpopt_code ){ // per RFC(s) 1072, 1323, 1644
	switch( $tcpopt_code ){
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

function IPOption2str( $ipopt_code ){
	switch( $ipopt_code ){
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

function ICMPType2str( $icmp_type ){
	switch ( $icmp_type ){
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

function ICMPCode2str( $icmp_type, $icmp_code ){
	if ( $icmp_type == 3 ){
		switch ( $icmp_code ){
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
	}elseif ( $icmp_type == 5 ){
		switch ( $icmp_code ){
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
	}elseif ( $icmp_type == 9 ){
		switch ( $icmp_code ){
         case 0:
             return "Normal router advertisement";
         case 16:
             return "Does not route common traffic";
         default:
             return $icmp_code;
      }
	}elseif ( $icmp_type == 11 ){
		switch ( $icmp_code ){
         case 0:
             return "TTL exceeded in transit";
         case 1:
             return "Fragment reassembly time exceeded";
         default:
             return $icmp_code;
      }
	}elseif ( $icmp_type == 12 ){
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
	}elseif ( $icmp_type == 40 ){
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
	}else{
		return $icmp_code;
	}
}

function PrintPayloadChar( $char, $output_type ){
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

function PrintBase64PacketPayload( $encoded_payload, $output_type ){
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

function PrintAsciiPacketPayload( $encoded_payload, $output_type ){
	return wordwrap($encoded_payload, 70);
}

function PrintHexPacketPayload( $encoded_payload, $output_type ){
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

function PrintCleanHexPacketPayload( $encoded_payload, $output_type ){
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

function PrintCleanPayloadChar( $char, $output_type ){
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

function PrintPacketPayload( $data, $encode_type, $output_type ){
     if ( $output_type == 1 )
        printf("\n<PRE>\n");

     /* print the packet based on encoding type */;
     if ( $encode_type == "1" )
         $payload = PrintBase64PacketPayload($data, $output_type);
     else if ($encode_type == "0" )
     {
		if ( GetAsciiClean() ){ // Print clean ascii display
			$payload = PrintCleanHexPacketPayload($data, $output_type);
		}else{
			$payload = PrintHexPacketPayload($data, $output_type);
		}
     }
     else if ($encode_type == "2" )
         $payload = PrintAsciiPacketPayload($data, $output_type); 
   
     if ( $output_type == 1 )
        echo "$payload\n</PRE>\n";

	return $payload;
}

function GetQueryResultID( $submit, &$seq, &$sid, &$cid ){
	// Extract the sid and cid from the $submit variable of the form
	// #XX-(XX-XX)
	//  |   |  |
	//  |   |  |--- cid
	//  |   |------ sid
	//  |---------- sequence number of DB lookup

	if ( preg_match('/#[0-9]+-\([0-9]+-[0-9]+\)$/', $submit) ){
		$submit = strstr($submit, '#');
		$find = array('#','(',')');
		$submit = str_replace($find, '', $submit);
		// Since the submit variable is not cleaned do so here:
		$tmp = CleanVariable(explode("-", $submit), VAR_DIGIT);
		$seq = intval($tmp[0]);
		$sid = intval($tmp[1]);
		$cid = intval($tmp[2]);
		return true;
	}else{
		return false;
	}
}

function ExportPacket( $sid, $cid, $db ){
	GLOBAL $action, $action_arg;
	// Event.
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

function ExportPacket_summary( $sid, $cid, $db, $export_type = 0 ){
	GLOBAL $action, $action_arg;
	// Event.
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

function base_microtime(){
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}

function Percent ( $Value = 1, $Count = 1 ){
	if( !is_numeric($Value) ){ // Input Validation
		$Value = 1;
	}
	if( !is_numeric($Count) ){
		$Count = 1;
	}
	if( $Value > $Count ){
		$Count = $Value;
	}
	if( $Count <= 0 ){
		$Count = 1;
	}
	if( $Value <= 0 ){ // Set %
		$Ret = 0;
	}else{
		$Ret = round($Value/$Count*100);
	}
	return $Ret;
}

// Returns true if file passes include safety checks.
// Also includes the file.
function base_include ( $file = '' ){
	GLOBAL $BASE_path, $debug_mode;
	$Ret = false;
	$EMsg = '';
	$tfile = "$BASE_path/custom/" . $file;
	$ReqRE = preg_quote("$BASE_path/custom/",'/');
	$ReqRE .= ".*\.htm(l)?";
	if ( preg_match("/^" . $ReqRE ."$/i", $tfile) ){
		// File must be in specific location with specific extension.
		$Loc = realpath($tfile); // Final file must
		if ( $Loc != false // exist and resolve to an absolute path.
			&& fileowner($Loc) != false // not be owned by UID 0 (root).
			&& ChkAccess($Loc) > 0 // be a real file & be readable.
		){
			if ( preg_match("/^" . $ReqRE ."$/i", $Loc) ){
				// be in specific location with specific extension.
				$Ret = true;
				$EMsg = 'OK';
				include_once($Loc);
			}else{
				$EMsg = 'Loc';
				$tfile .= " -> $Loc";
			}
		}else{
			$EMsg = 'Access';
		}
	}else{
		$EMsg = 'File';
	}
	if ( $debug_mode > 0 ){
		print "Test: $file\n";
		print "$EMsg: $tfile\n";
	}
	return $Ret;
}

// Returns true if asciiclean is set.
// HTTP GET params take precedence over cookie values.
function GetAsciiClean(){
	$Ret = false;
	if( isset($_GET['asciiclean']) ){ // Check HTTP GET param.
		$Ret = ChkGet('asciiclean', 1);
	}else{ // No GET, check for cookie.
		$Ret = ChkCookie('asciiclean', 1);
	}
	return $Ret;
}

// Returns Library if found & file passes access checks.
// Returns empty string otherwise.
function ChkLib ( $path = '', $LibLoc = '', $LibFile = '' ){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = '';
	if( LoadedString($LibFile) ){
		$sc = DIRECTORY_SEPARATOR;
		$tmp = $LibFile;
		// Strip leading or trailing seperators from Lib file.
		$ReqRE = "(^\\$sc|\\$sc\$)";
		$LibFile = preg_replace("/".$ReqRE."/", '', $LibFile);
		if ( $tmp != $LibFile ){
			KML($EMPfx . "Req Lib: $tmp", 3);
			KML($EMPfx . "Mod Lib: $LibFile", 3);
		}
		if( LoadedString($path) ){ // Path to Lib
			$tmp = $path; // Strip trailing seperator from path.
			$ReqRE = "\\$sc\$";
			$path = preg_replace("/".$ReqRE."/", '', $path);
			if( $tmp != $path ){
				KML($EMPfx . "Req Loc: $tmp", 3);
				KML($EMPfx . "Mod Loc: $path", 3);
			}
			$LibFile .= '.php';
			$FinalLib = implode( $sc, array($path, $LibFile) );
			KML($EMPfx . "Chk: $FinalLib", 3);
			$tmp = ChkAccess($FinalLib);
			$Msg = $EMPfx . "Lib: $FinalLib ";
			if( $tmp > 0 ){
				$Msg .= 'found';
				$Ret = $FinalLib;
			}else{
				$Msg .= 'not ';
			}
			if( $tmp == 0 ){
				$Msg .= 'file';
			}elseif( $tmp == -1 ){
				$Msg .= 'found';
			}elseif( $tmp == -2 ){
				$Msg .= 'readable';
			}
			$Msg .= '.';
			KML($Msg, 3);
		}else{ // Relative path to Lib.
			if( LoadedString($LibLoc) ){
				$tmp = $LibLoc; // Strip leading seperators from Loc.
				$ReqRE = "^\\$sc";
				$LibLoc = preg_replace("/".$ReqRE."/", '', $LibLoc);
				if( $tmp != $LibLoc ){
					KML($EMPfx . "Req Loc: $tmp", 3);
					KML($EMPfx . "Mod Loc: $LibLoc", 3);
				}
			}
			$PSPath = explode(PATH_SEPARATOR, ini_get('include_path'));
			foreach( $PSPath as $single_path ){
				if( LoadedString($LibLoc) ){
					$FinalLoc = implode( $sc, array($single_path, $LibLoc) );
				}else{
					$FinalLoc = $single_path;
				}
				$tmp = ChkLib( $FinalLoc, '', $LibFile);
				if( LoadedString($tmp) ){
					$Ret = $tmp;
					break;
				}
			}
		}
	}else{
		KML($EMPfx . 'No Lib specified.', 1);
	}
	return $Ret;
}

// Returns true if cookie is set & contains value.
function ChkCookie( $var, $val ){
	$Ret = false;
	if ( LoadedString($var) ){
		if ( isset($_COOKIE[$var]) && $_COOKIE[$var] == $val ){
			$Ret = true;
		}
	}
	return $Ret;
}

// Returns true if HTTP GET param is set & contains value.
function ChkGET( $var, $val ){
	$Ret = false;
	if ( LoadedString($var) ){
		if ( isset($_GET[$var]) && $_GET[$var] == $val ){
			$Ret = true;
		}
	}
	return $Ret;
}

// Returns true if PEAR library can be loaded, false otherwise.
function PearInc( $Desc = '', $Loc = '', $Lib = '', $Silent = 1, $Fatal = 0 ){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = false;
	if ( LoadedString($Lib) ){
		if ( !is_int($Silent) ){ // Input Validation
			$Silent = 1; // Default to no error message display.
		}
		if ( !is_int($Fatal) ){
			$Fatal = 0;
		}
		$LLF = ChkLib('', $Loc , $Lib); // Load Lib File.
		if ( LoadedString($LLF) ){
			$LLI = include_once($LLF); // Load Lib Include.
		}
		if ( $LLF == '' || $LLI == false ){
			if ( LoadedString($Loc) ){
				$LibName = $Loc.'_'.$Lib;
			}else{
				$LibName = $Lib;
				$Loc = '';
			}
			if ( !LoadedString($Desc) ){
				$Desc = $LibName;
			}
			$sc = DIRECTORY_SEPARATOR;
			$Lib = implode( $sc, array($Loc, $Lib) ).'.php';
			$EMsg = "$Desc Lib: $Lib not ";
			if ( $LLF == '' ){
				$EMsg .= 'accessable';
			// @codeCoverageIgnoreStart
			}elseif( $LLI == false ){
				// This code path should never run.
				$EMsg .= 'loaded';
			}
			// @codeCoverageIgnoreEnd
			$EMsg .= '.';
			if ( $Silent != 1 ){ // Display fancy error to user.
				$URL = "https://pear.php.net/package/$LibName";
				LibIncError (
					$Desc, $Loc, $Lib, $EMsg, $LibName, $URL, $Fatal, 1
				);
			}else{
				if ( $debug_mode > 0 ){
					ErrorMessage("$EMPfx$EMsg", 0, 1);
				}
			}
		}else{
			$Ret = true;
		}
	}else{
		if ( $debug_mode > 0 ){
			ErrorMessage($EMPfx . 'No Lib specified.', 0, 1);
		}
	}
	return $Ret;
}

// Returns true if Archive DB is in use, false otherwise.
function ChkArchive(){ // Issue #183
	GLOBAL $archive_exists;
	$EMPfx = 'BASE Security Alert ' . __FUNCTION__ . ': ';
	$Ret = false;
	if ( $archive_exists != 0 ){
		if ( ChkCookie ('archive', 1) || ChkGet ('archive', 1) ){
			$Ret = true;
		}
	}else{ // Archive DB disabled. Alert or param tampering.
		$tmp = ''; // No Alert
		if ( isset($_GET['archive']) ){ // Get param Hack Alert
			$tmp = 'HTTP GET';
		}
		if ( isset($_COOKIE['archive']) ){ // Cookie Hack Alert.
			$tmp = 'COOKIE';
		}
		if ( $tmp != '' ){
			error_log($EMPfx . "$tmp tampering detected.");
		}
	}
	return $Ret;
}

// Function: RegisterGlobalState()
// @doc Application-specific wrapper for PHP session_start(). It performs a
// couple of additional configuration checks (notably for custom PHP session
// handlers).
function RegisterGlobalState(){
	GLOBAL $use_user_session, $user_session_path, $user_session_function;
	$EMsg = '';
	// Deal with user specified session handlers.
	if( session_module_name() == 'user' ){
		if( $use_user_session != 1 ){
			$EMsg = _PHPERRORCSESSION;
		}elseif( $user_session_path != '' ){
			if( is_file($user_session_path) ){
				include_once($user_session_path);
				if( $user_session_function != '' ){
					$user_session_function();
				}
			}else{
				$EMsg = _PHPERRORCSESSIONCODE;
			}
		}else{
			$EMsg = _PHPERRORCSESSIONVAR;
		}
	}
	if( $EMsg != '' ){
		FatalError($EMsg);
	}
	session_start();
	KML("Start: Session", 1);
}

function BCS( $Name, $Value = '' ){
	GLOBAL $BCR;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = false;
	if( LoadedString($Name) ){
		$msg = 'Clear';
		$expire = 1;
		if( LoadedString(strval($Value)) ){ // Type Slam it.
			$msg = 'Set';
			$expire = time() + 60*60*24*14; // 2 weeks
			if ( $Name == 'BASERole' ){
				$expire = time() + 60*60; // 1 Hour
			}
		}
		if( isset($BCR) && is_object($BCR) ){
			// @codeCoverageIgnoreStart
			$tmp = $BCR->GetCap('UIMode');
			if( $tmp != 'Con' ){ // Don't set cookies in Console UIMode.
				$tmp = CCS();
				$SF = $tmp[0];
				$Stat = $tmp[1];
				if ($SF && $msg == 'Set' ){
					KML($EMPfx . "Sec: $Stat", 3);
					$msg .= ' Secure';
				}
				$path = $BCR->GetCap('BASE_SSUrlPath');
				if( !LoadedString($path) ){
					$path = '/';
				}
				$domain = $BCR->GetCap('BASE_SSDomain');
				if( !LoadedString($domain) ){
					$domain = '';
				}else{
					// Strip trialing dot if any.
					$domain = preg_replace("/\.$/", '', $domain);
					$tmp = substr_count($domain, '.');
					if( $tmp == 1 ){ // Not a subdomain.
						// Add leading dot for compatibility.
						$domain .= ".$domain";
					}
				}
				$BCO = array(
					'expires' => $expire,
					'path' => $path,
					'domain' => $domain,
					'secure' => $SF,
					'httponly' => true,
					'samesite' => 'Strict' // None || Lax  || Strict
				);
				$PHPVer = GetPHPSV();
				if( $PHPVer[0] > 7 || ($PHPVer[0] == 7 && $PHPVer[1] > 2)
				){ // PHP 7.3+
					$Ret = setcookie($Name, $Value, $BCO);
				}else{ // Older PHP < 7.3
					// Path param hack to slam the SameSite param into cookies
					// on versions of setcookie() that don't support it.
					$tmp = $BCO['path'] . '; SameSite=' . $BCO['samesite'];
					$Ret = setcookie(
						$Name, $Value, $BCO['expires'], $tmp, $BCO['domain'],
						$BCO['secure'], $BCO['httponly']
					);
				}
			}else{ // UI Mode not Web, fake successful cookie Op.
				$Ret = true;
			}
			// @codeCoverageIgnoreEnd
		}else{ // Can't get UI Mode (PHPUnit)? fake successful cookie Op.
			$Ret = true;
		}
		$msg .= " Cookie: $Name Exp: ". date('F-d-Y H:i:s', $expire);
	}else{
		$msg = 'No Cookie.';
	}
	KML($EMPfx . $msg, 3);
	return $Ret;
}

?>
