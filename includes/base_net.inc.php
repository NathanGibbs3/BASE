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
** Purpose: TCP/IP network routines
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
// Ensure the conf file has been loaded.  Prevent direct access to this file.
defined('_BASE_INC') or die('Accessing this file directly is not allowed.');

/****************************************************************************
 *
 * Function: baseIP2long()
 *
 * Purpose: convert a text string IPv4 address into its 32-bit numeric 
 *          equivalent
 *
 :* Arguments: $IP_str => dotted IPv4 address string (e.g. 1.2.3.4)
 *
 * Returns: 32-bit integer equivalent of the dotted address
 *          (e.g. 255.255.255.255 => 4294967295 ) 
 *
 ***************************************************************************/
function baseIP2long($IP_str)
{
   if (!is_string($IP_str))
   {
      error_log("baseIP2long(): WARNING: \$IP_str is a " . gettype($IP_str) . " Returning 0.");
      return 0;
   }
   if (empty($IP_str))
   {
      error_log("baseIP2long(): WARNING: \$IP_str is empty. Returning 0.");
      return 0;
   }


   $tmp_long = ip2long($IP_str);


   if (is_bool($tmp_long))
   {
     if ($tmp_long == FALSE)
     {
       error_log("baseIP2long(): WARNING: ip2long() complains about an invalid ip address().");
       error_log("\$IP_str = \"" . $IP_str . "\"");
       error_log("This is a " . gettype($IP_str));
       error_log("Returning 0.");
       return 0;
     }
   }

   if ( $tmp_long < 0 )
      $tmp_long = 4294967296 - abs($tmp_long);

   return $tmp_long;
}

/****************************************************************************
 *
 * Function: baseLong2IP()
 *
 * Purpose: convert a 32-bit integer into the corresponding IPv4 address 
 *          in dotted notation
 *
 * Arguments: $long_IP => 32-bit integer representation of IPv4 address
 *
 * Returns: IPv4 dotted address string (e.g. 4294967295 => 255.255.255.255)
 *
 ***************************************************************************/ 
function baseLong2IP($long_IP)
{
   $tmp_IP = $long_IP;
   if ( $long_IP > 2147483647 )
   {
      $tmp_IP = 4294967296 -  $tmp_IP;
      $tmp_IP = $tmp_IP * (-1); 
   }

   $tmp_IP = long2ip($tmp_IP);
   return $tmp_IP;
}

function baseDecBin($num)
{
   // Doesn't appear to be called???? -- Kevin
   $str = decbin($num);
   for ( $i = strlen($str); $i < 8; $i++ )
     $str = "0".$str;

   return $str;
}

function getIPMask($ipaddr, $mask)
{
   if ( $mask == 32 )
      return array($ipaddr, $ipaddr);

   $ip_octets = explode(".", $ipaddr);
   $octet_id = floor($mask/8);
   $octet_bit = $mask % 8;
   $new_octet = $ip_octets[$octet_id];

   $mask_high = 0;
   for ( $i = 7; $i > 7 - $octet_bit; $i--)
      $mask_high += pow(2, $i);

   $mask_low = 0;
   for ( $i = 0; $i <= 7 - $octet_bit; $i++)
       $mask_low += pow(2, $i);

   $mask_bottom = (integer) $new_octet & (integer) $mask_high;
   $mask_top = (integer) $mask_bottom | (integer) $mask_low;

   $ip_top = $ip_bottom = $ip_octets;
 
   $ip_bottom[$octet_id] = $mask_bottom;
   $ip_top[$octet_id] = $mask_top;

   for ( $i = $octet_id+1; $i < 4; $i++ )
   {
      $ip_top[$i] = 255;
      $ip_bottom[$i] = 0;
   }
  
   $ip_top_str = implode(".", $ip_top);
   $ip_bottom_str = implode(".", $ip_bottom);

   return array($ip_bottom_str, $ip_top_str);
}

//  Function: baseGetHostByAddr()
//   Purpose: Caches, and if possible, resolves an IP address to a hostname.
// Arguments: $ipaddr         => IPv4 address in dotted notation.
//            $db             => DB handle.
//            $cache_lifetime => lifetime of DNS resolution.
//   Returns: hostname of $ipaddr
//            OR an error message indicating resolution was not possible
function baseGetHostByAddr($ipaddr, $db, $cache_lifetime){
	$Epfx = 'BASE ' . __FUNCTION__ . '() ';
	$Emsg = '';
	// Need to extend this to support IPv6
	$Validipv4 = '/(\d{1,3}\.){3}\d{1,3}/';
	if ( !LoadedString($ipaddr) || ($ipaddr == '')){
		$Emsg = $Epfx.'Invalid Parameter(s) $ipaddr.';
		$Ret = "<I>"._ERRRESOLVEADDRESS."</I>";
	}elseif (!preg_match($Validipv4, $ipaddr)){
		$Emsg = $Epfx.'Invalid Parameter(s) $ipaddr.';
		// Not sure why we are not returning the error message like above.
		// The lagecy code returns the $ipaddr param in this isntance, so
		// we left it here until we can verify that changing it won't break
		// soemthing else.
		$Ret = $ipaddr;
	}
	if ( $Emsg != ''){
		trigger_error($Emsg);
		return $Ret;
	}
  $ip32 = baseIP2long($ipaddr);

  $current_unixtime = time();
  $current_time = date("Y-m-d H:i:s",$current_unixtime);
  $sql = "SELECT ipc_ip,ipc_fqdn,ipc_dns_timestamp".
         " FROM acid_ip_cache ".
         " WHERE ipc_ip = '$ip32' ";

  $result = $db->baseExecute($sql);
  $ip_cache = $result->baseFetchRow();
	// Fix Issue #58
	$tmp = gethostbyaddr($ipaddr);
	// Doesn't affect postgresql. Only run on other DB engines.
	if ( $db->DB_type != 'postgres' ){
		// Get the length of the ipc-fqdn field from the DB.
		$maxlength = GetFieldLength($db,'acid_ip_cache','ipc_fqdn');
		if ( strlen($tmp) > $maxlength) { // Concat data at to maxlength.
			$tmp = substr($tmp, -$maxlength);
			$Emsg = $Epfx;
			$Emsg .= "DB Field Overflow, FQDN for $ipaddr concatenated to $tmp. ";
			$Emsg .= 'See: https://github.com/NathanGibbs3/BASE/issues/58';
//			error_log($Emsg);
			// Using trigger_error, as error_log trips up the Unit tests that
			// currently requirs process isolation. We should be able to fix
			// this once we fix Issue #11.
			trigger_error($Emsg);
		}
	}
	if ( $ip_cache == "" ){ // Cache miss. Add to cache.
     if( $db->DB_type == "oci8" )
       $sql= "INSERT INTO acid_ip_cache (ipc_ip, ipc_fqdn, ipc_dns_timestamp) ".
             "VALUES ($ip32, '$tmp', to_date( '$current_time', 'YYYY-MM-DD HH24:MI:SS' ) )";
     else
       $sql = "INSERT INTO acid_ip_cache (ipc_ip, ipc_fqdn, ipc_dns_timestamp) ".
              "VALUES ('$ip32', '$tmp', '$current_time')";
     $db->baseExecute($sql);
	}else{ // Cache hit.
     if ($ip_cache[2] != "" && 
         ( ( (strtotime($ip_cache[2]) / 60) + $cache_lifetime ) >= ($current_unixtime / 60) )
		){
        /* valid entry */
        if ( ($ip_cache[2] != "") && ($ip_cache[2] != 0) )
        {
           $tmp = $ip_cache[1];
        }
        else  /* name could not be resolved */
           $tmp = $ipaddr;
		}else{ // Cache expired.
        /* Update entry in cache regardless of whether can resolve */
        $sql = "UPDATE acid_ip_cache SET ipc_fqdn='$tmp', ".
               " ipc_dns_timestamp='$current_time' WHERE ipc_ip='$ip32'"; 
        $db->baseExecute($sql);
		}
	}

  if ( $tmp == $ipaddr )
     return "<I>"._ERRRESOLVEADDRESS."</I>";
  else
     return $tmp;
}

/****************************************************************************
 *
 * Function: baseGetWhois()
 *
 * Purpose: Queries the proper whois server to determine info about 
 *          the given IP address
 *
 * Arguments: $ipaddr         => IPv4 address in dotted notation
 *            $db             => DB handle
 *            $cache_lifetime => lifetime of whois lookup
 *
 * Returns: whois information on $ipaddr
 *          OR an error message indicating resolution was not possible
 *
 ***************************************************************************/
function baseGetWhois($ipaddr, $db, $cache_lifetime)
{
  $ip32 = baseIP2long($ipaddr);
  $current_unixtime = time();
  $current_time = date("Y-m-d H:i:s",$current_unixtime);
  $sql = "SELECT ipc_ip,ipc_whois,ipc_whois_timestamp".
         " FROM acid_ip_cache ".
         " WHERE ipc_ip = '$ip32' ";

  $result = $db->baseExecute($sql);
  $whois_cache = $result->baseFetchRow();

  /* cache miss */
  if ( $whois_cache == "" )
  {
     $tmp = CallWhoisServer($ipaddr, $whois_server);

     /* add to cache regardless of whether can resolve */
     /* xxx jl: Why? Hmmm, no. Not, if tmp is empty. */
     if (!empty($tmp))
     {
       if ( $db->DB_type == "oci8" )
         $sql = "INSERT INTO acid_ip_cache (ipc_ip, ipc_whois, ipc_whois_timestamp) ".
                "VALUES ($ip32, '".$db->getSafeSQLString($tmp)."', to_date( '$current_time','YYYY-MM-DD HH24:MI:SS' ) )";
       else
         $sql = "INSERT INTO acid_ip_cache (ipc_ip, ipc_whois, ipc_whois_timestamp) ".
                "VALUES ($ip32, '".$db->getSafeSQLString($tmp)."', '$current_time')";
     
       $db->baseExecute($sql);
     }
  }
  else     /* cache hit */
  {
     /* cache valid */
     if ( ($whois_cache[2] != "") && 
         ( ( (strtotime($whois_cache[2]) / 60) + $cache_lifetime ) >= ($current_unixtime / 60) ) )
     {
        $tmp = $whois_cache[1];
        if ( strstr($tmp, "RIPE ") )  $whois_server = "RIPE";
        else if ( strstr($tmp, "www.apnic.net" ) ) $whois_server = "AP";
        else if ( strstr($tmp, "JPNIC database" ) ) $whois_server = "JPNIC";
        else $whois_server = "ARIN";
     }
     else  /* cache expired */
     { 
        $tmp = CallWhoisServer($ipaddr, $whois_server);

        /* Update entry in cache regardless of whether can resolve */
        // xxx jl: Well, no. Not, if tmp is empty.
        if (!empty($tmp)) 
        {
          if ( $db->DB_type == "oci8" )
            $sql = "UPDATE acid_ip_cache SET ipc_whois='".$db->getSafeSQLString($tmp)."', ".
                   " ipc_whois_timestamp=to_date( '$current_time','YYYY-MM-DD HH24:MI:SS' ) WHERE ipc_ip='$ip32'";
          else
            $sql = "UPDATE acid_ip_cache SET ipc_whois='".$db->getSafeSQLString($tmp)."', ".
                   " ipc_whois_timestamp='$current_time' WHERE ipc_ip='$ip32'";

          $db->baseExecute($sql);
        }
     }
  }

  return $tmp;
}

function GetWhoisRaw($ipaddr, $whois_addr)
{
	GLOBAL $debug_mode;
	$response = "";


	if ($debug_mode >= 1)
	{
		print "<BR><BR>\n\n" . __FILE__ . ":" . __LINE__ . ":<BR>\n";
		print "ipaddr     = \"$ipaddr\"<BR>\n";
		print "whois_addr = \"$whois_addr\"<BR>\n";
		print "<BR><BR>\n\n";
	}

  $fp = @fsockopen ($whois_addr, 43, $errno, $errstr, 15);

  if (!$fp)
  {
     echo "$errstr ($errno)<br>\n";
  }
  else
  {
     $response = "";
     fputs ($fp, "$ipaddr \r\n\r\n");
     while (!feof($fp))
     {
        $response = $response.(fgets ($fp,128));
     }
     fclose ($fp);
  }

  return $response;
}

function CallWhoisServer($ipaddr, &$whois_server)
{
	GLOBAL $arin_ip, $apnic_ip, $ripe_ip, $jnic_ip, $afrinic_ip, $lacnic_ip, $debug_mode;

  $whois_server = "ARIN";
  $response = GetWhoisRaw($ipaddr, $arin_ip);

  if ( stristr($response, "Allocated to RIPE NCC") || stristr($response, "Transferred to RIPE NCC") )
  {
     $response = GetWhoisRaw($ipaddr, $ripe_ip);
     $whois_server = "RIPE";
  }
  else if ( stristr($response, "Allocated to AP") )
  {
     $response = GetWhoisRaw($ipaddr, $apnic_ip);
     $whois_server = "AP";
  }
  else if ( stristr($response, "Allocated to JNIC") )
  {
     $response = GetWhoisRaw($ipaddr, $jnic_ip);
     $whois_server = "JNIC";
	}else if ( stristr($response, "Allocated to AfriNIC") ) {
		$response = GetWhoisRaw($ipaddr, $afrinic_ip);
		$whois_server = "AFR";
	}else if ( stristr($response, "Allocated to LACNIC") ) {
		$response = GetWhoisRaw($ipaddr, $lacnic_ip);
		$whois_server = "LAC";
	}

  return $response;
} 

function baseProcessWhoisRaw($response, &$org, &$email, $server)
{
   // Not called anywhere????? -- Kevin
  if ( $server == "ARIN" )
  {
     $response_l = explode("\n", $response);

     /* organizational name */
     $org = $response_l[0];

    /* email */
    $email = "";
    for ( $i=1; $i < sizeof($response_l); $i++)
    {
        $line = explode(" ", $response_l[$i]);
        for ($j=0; $j < sizeof($line); $j++ )
        {
          if ( preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $line[$j]) )
          {
             if ( $email == "" )
                $email = $line[$j];
             else
                $email = $email.", ".$line[$j];
          }
        }
    }
  }
  else if ( $server == "RIPE" )
  {
     $response_l = explode("\n", $response);

     /* organizational name */
     $org = "";
     for ( $i=1; $i < sizeof($response_l); $i++)
     {
        if ( strstr($response_l[$i], "notify: ") )
           $email = chop(strstr($response_l[$i], ": "));

     }



     /* email */
     $email = "";
     for ( $i=1; $i < sizeof($response_l); $i++)
     {
        if ( strstr($response_l[$i], "notify:") )
        {
           $email = substr( chop( strstr($response_l[$i], ": ") ), 1, strlen($response_l[$i])-1 );
        }
     }

  }  
  else if ( $server == "AP" )
  {
  }
  else if ( $server == "JNIC" )
  {
  }

  echo "<BR><BR>org = $org<BR>email = $email<BR>";
}

function VerifySocketSupport()
{
   return function_exists("fsockopen");
}

?>
