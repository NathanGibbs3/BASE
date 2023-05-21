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
** Purpose: Input GET/POST variables
**   - submit:
**   - time:
**   - time_sep:
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');

function StoreAlertNum($sql, $label, $time_sep, $i_year, $i_month, $i_day, $i_hour)
{  
  GLOBAL $db, $cnt, $label_lst, $value_lst, $value_POST_lst, $debug_mode;

  $label_lst [ $cnt ] = $label;

  if (sizeof($time_sep) == 0) {
      $time_sep = array(0 => '', 1 => '');
  }
  
  if ( $debug_mode > 0 )
     echo $sql."<BR>";

  $result = $db->baseExecute($sql);
  if ( $myrow = $result->baseFetchRow() )
  {
     $value_lst [ $cnt ] = $myrow[0];
     $result->baseFreeRows();

     $value_POST_lst[$cnt] = "base_qry_main.php?new=1&amp;submit="._QUERYDBP."&amp;num_result_rows=-1&amp;time_cnt=1".
                             "&amp;time%5B0%5D%5B0%5D=+&time%5B0%5D%5B1%5D=%3D";

     if ( $time_sep[0] == "hour" )
        $value_POST_lst[$cnt] = $value_POST_lst[$cnt].'&amp;time%5B0%5D%5B2%5D='.$i_month.
                                '&amp;time%5B0%5D%5B3%5D='.$i_day.
                                '&amp;time%5B0%5D%5B4%5D='.$i_year.
                                '&amp;time%5B0%5D%5B5%5D='.$i_hour;

     else if ( $time_sep[0] == "day" )
        $value_POST_lst[$cnt] = $value_POST_lst[$cnt].'&amp;time%5B0%5D%5B2%5D='.$i_month.
                                '&amp;time%5B0%5D%5B3%5D='.$i_day.
                                '&amp;time%5B0%5D%5B4%5D='.$i_year;

     else if ( $time_sep[0] == "month" )
        $value_POST_lst[$cnt] = $value_POST_lst[$cnt].'&amp;time%5B0%5D%5B2%5D='.$i_month.
                                '&amp;time%5B0%5D%5B4%5D='.$i_year;

     /* add no parentheses and no operator */
     $value_POST_lst[$cnt] = $value_POST_lst[$cnt].'&amp;time%5B0%5D%5B8%5D=+&amp;time%5B0%5D%5B9%5D=+';
 
		$cnt++;
	}else{
		$value_lst [ $cnt++ ] = 0;
	}
}

function PrintTimeProfile(){
	GLOBAL $cnt, $label_lst, $value_lst, $value_POST_lst;
	// Find max value.
	$max_cnt = $value_lst[0];
	for ( $i = 0; $i < $cnt; $i++ ){
		if ( $value_lst[$i] > $max_cnt ){
			$max_cnt = $value_lst[$i];
		}
	}
	NLIO('<!-- TimeProfile -->', 2);
	$tmp ="<td class='plfieldhdr'";
	NLIO("<table border='1' width='100%'>", 2);
	NLIO('<tr>',3);
	NLIO($tmp." width='25%'>"._CHRTTIME.'</td>',4);
	NLIO($tmp." width='15%'># "._QSCOFALERTS.'</td>',4);
	NLIO($tmp.'>'._ALERT.'</td>',4);
	NLIO('</tr>',3);
	for ($i = 0; $i < $cnt; $i++ ){
		NLIO('<tr>',3);
		if ( $value_lst[$i] == 0 ){
			$tmp = $label_lst[$i];
			$idx = 4;
		}else{
			$tmp = "<a href='$value_POST_lst[$i]'>$label_lst[$i]</a>";
			$idx = 7;
		}
		NLIO("<td>$tmp</td>",4);
		NLIO("<td align='center'>$value_lst[$i]</td>",4);
		if ( $idx == 7 ){
			NLIO('<td>',4);
			NLIO("<table border='0' cellPadding='0' cellSpacing='0' width='100%'>",5);
			NLIO('<tr>',6);
		}
		NLIO(HBarGraph($value_lst[$i],$max_cnt),$idx);
		if ( $idx == 7 ){
			NLIO('</tr>',6);
			NLIO('</table>',5);
			NLIO('</td>',4);
		}
		NLIO('</tr>',3);
	}
	NLIO('</table>',2);
}

include_once("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_stat_common.php");
include_once("$BASE_path/base_qry_common.php");

AuthorizedRole(10000);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to DB.
$db->baseDBConnect(
	$db_connect_method, $alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);
$time_sep = ImportHTTPVar("time_sep", VAR_ALPHA);
$time = ImportHTTPVar("time", VAR_DIGIT);
$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);
$cs = new CriteriaState("base_stat_alerts.php");
$cs->ReadState();
$page_title = _BSTTITLE;
PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), 1);
$criteria_clauses = ProcessCriteria();
PrintCriteria('');
$from = " FROM acid_event ".$criteria_clauses[0];
$where = " WHERE ".$criteria_clauses[1];
UpdateAlertCache($db);
  if ( $submit == "" )
  {
   InitArray($time, $MAX_ROWS, TIME_CFCNT, "");
  }

  echo '<FORM ACTION="base_stat_time.php" METHOD="get">
        <TABLE WIDTH="100%" BORDER=0>
         <TR>
          <TD WIDTH="40%" CLASS="metatitle"><B>'._BSTTIMECRIT.'</B></TD>
          <TD>';
PrintFramedBoxFooter(1,2);

echo '        <TABLE WIDTH="100%" BORDER=2 class="query">
        <TR>
         <TD>';

  echo '<B>'._BSTPROFILEBY.' :</B> &nbsp;
        <INPUT NAME="time_sep[0]" TYPE="radio" VALUE="hour" '.@chk_check($time_sep[0],"hour").'> '._HOUR.'
        <INPUT NAME="time_sep[0]" TYPE="radio" VALUE="day" '.@chk_check($time_sep[0], "day").'> '._DAY.'
        <INPUT NAME="time_sep[0]" TYPE="radio" VALUE="month" '.@chk_check($time_sep[0], "month").'> '._MONTH.'
        <BR>';

  echo '<SELECT NAME="time_sep[1]">
         <OPTION VALUE=" "  '.@chk_select($time_sep[1], " ").'>'._DISPTIME.'
         <OPTION VALUE="on" '.@chk_select($time_sep[1], "on").'>'._TIMEON.'
         <OPTION VALUE="between"'.@chk_select($time_sep[1], "between").'>'._TIMEBETWEEN.'
        </SELECT>';
 
  for ( $i = 0; $i < 2; $i++ )
  {
      echo '<SELECT NAME="time['.$i.'][0]">
             <OPTION VALUE=" "  '.chk_select($time[$i][0]," " ).'>'._DISPMONTH.'
             <OPTION VALUE="01" '.chk_select($time[$i][0],"01").'>'._JANUARY.'
             <OPTION VALUE="02" '.chk_select($time[$i][0],"02").'>'._FEBRUARY.'
             <OPTION VALUE="03" '.chk_select($time[$i][0],"03").'>'._MARCH.'
             <OPTION VALUE="04" '.chk_select($time[$i][0],"04").'>'._APRIL.'
             <OPTION VALUE="05" '.chk_select($time[$i][0],"05").'>'._MAY.'
             <OPTION VALUE="06" '.chk_select($time[$i][0],"06").'>'._JUNE.'
             <OPTION VALUE="07" '.chk_select($time[$i][0],"07").'>'._JULY.'
             <OPTION VALUE="08" '.chk_select($time[$i][0],"08").'>'._AUGUST.'
             <OPTION VALUE="09" '.chk_select($time[$i][0],"09").'>'._SEPTEMBER.'
             <OPTION VALUE="10" '.chk_select($time[$i][0],"10").'>'._OCTOBER.'
             <OPTION VALUE="11" '.chk_select($time[$i][0],"11").'>'._NOVEMBER.'
             <OPTION VALUE="12" '.chk_select($time[$i][0],"12").'>'._DECEMBER.'
            </SELECT>';
      
      echo '<INPUT TYPE="text" NAME="time['.$i.'][1]" SIZE=2 VALUE="'.$time[$i][1].'"> &nbsp;'."\n";
      echo '<SELECT NAME="time['.$i.'][2]">'.
             dispYearOptions($time[$i][2])
            .'</SELECT>';

      if ( $i == 0 ) echo '&nbsp; -- &nbsp;&nbsp;';
  }

  echo '<INPUT TYPE="submit" NAME="submit" VALUE="'._PROFILEALERT.'">';
PrintFramedBoxFooter(1,3);
NLIO('</form>',2);

  if ( $submit != "" && @$time_sep[0] == "" )
     echo _BSTERRPROFILECRIT;     
  else if ( $submit != "" && $time_sep[1] == " " )
     echo _BSTERRTIMETYPE;

  else if ( $submit != "" && $time_sep[0] != "" && $time_sep[1] == "on" &&
            $time[0][2] == " " )
     echo _BSTERRNOYEAR;

  else if ( $submit != "" && $time_sep[0] != "" && $time_sep[1] == "between" &&
            ($time[1][2] == " " || $time[0][2] == " ") )
     echo _BSTERRNOYEAR;

  else if ( $submit != "" && $time_sep[0] != "" && $time_sep[1] == "between" &&
            ($time[1][0] == " " || $time[0][0] == " ") )
     echo _BSTERRNOMONTH; 
 
  else if ( $submit != "" && ($time_sep[0] != "") 
            && $time_sep[1] == "between" && ($time[1][1] == "" || $time[0][1] == "") )
     echo _BSTERRNODAY;

  else if ($submit != "")
  {

  /* Dump the results of the above specified query */
           
  $year_start = $year_end = NULL;
  $month_start = $month_end = NULL;
  $day_start = $day_end = NULL;
  $hour_start = $hour_end = NULL;

  if ( $time_sep[1] == "between" )
  {
     if ($time_sep[0] == "hour")       
     { 
        $year_start = $time[0][2];  $year_end = $time[1][2];
        $month_start = $time[0][0]; $month_end = $time[1][0];
        $day_start = $time[0][1]; $day_end = $time[1][1];
        $hour_start = 0; $hour_end = 23; 
     }
     else if ($time_sep[0] == "day")          
     { 
        $year_start = $time[0][2];  $year_end = $time[1][2];
        $month_start = $time[0][0]; $month_end = $time[1][0];
        $day_start = $time[0][1]; $day_end = $time[1][1];
        $hour_start = -1; 
     }
     else if ($time_sep[0] == "month")           
     { 
        $year_start = $time[0][2];  $year_end = $time[1][2];
        $month_start = $time[0][0]; $month_end = $time[1][0];
        $day_start = -1;
        $hour_start = -1; 
     }
	}elseif( $time_sep[1] == 'on' ){
     if ($time_sep[0] == "hour")       
     { 
        $year_start = $time[0][2];  $year_end = $time[0][2];
        if ( $time[0][0] != " " )
        {   $month_start = $time[0][0]; $month_end = $time[0][0];  }
        else
        {   $month_start = 1; $month_end = 12;  }

        if ( $time[0][1] != "" )
        {  $day_start = $time[0][1]; $day_end = $time[0][1];  }
        else
        {  $day_start = 1; $day_end = 31;  }
        $hour_start = 0; $hour_end = 23; 
     }
     else if ($time_sep[0] == "day")          
     { 
        $year_start = $time[0][2];  $year_end = $time[0][2];
        if ( $time[0][0] != " " )
        {   $month_start = $time[0][0]; $month_end = $time[0][0];  }
        else
        {   $month_start = 1; $month_end = 12;  }

        if ( $time[0][1] != "" )
        {  $day_start = $time[0][1]; $day_end = $time[0][1];  }
        else
        {  $day_start = 1; $day_end = 31;  }

        $hour_start = -1; 
     }
     else if ($time_sep[0] == "month")           
     { 
        $year_start = $time[0][2];  $year_end = $time[0][2];
        if ( $time[0][0] != " " )
        {   $month_start = $time[0][0]; $month_end = $time[0][0];  }
        else
        {   $month_start = 1; $month_end = 12;  }  
        $day_start = -1;
        $hour_start = -1; 
     }
  }

if( $debug_mode == 1 ){
	$TK = array ( 'year', 'month', 'day', 'hour' );
	$DI = array();
	$DD = array();
	foreach ( $TK as $val ){
		foreach ( array( 'start', 'end' ) as $vsf ){
			$tmp = $val . '_' . $vsf;
			array_push($DD, $tmp);
			array_push($DI, $$tmp);
		}
	}
	DDT($DI,$DD,'Time Constraints',2);
}
  $cnt = 0;
  $i_year = $i_month = $i_day = $i_hour = NULL;

  for ( $i_year = $year_start; $i_year <= $year_end; $i_year++ )
  {
      // !!! AVN !!!
      // to_date() must used!
      $sql = "SELECT count(*) ".$from.$where." AND ".
             $db->baseSQL_YEAR("timestamp", "=", $i_year);

      if ( $month_start != -1 )
      {
         if ($i_year == $year_start)  $month_start2 = $month_start;  else  $month_start2 = 1;
         if ($i_year == $year_end)    $month_end2 = $month_end;      else  $month_end2 = 12;

         for ( $i_month = $month_start2; $i_month <= $month_end2; $i_month++ )
         {
             $sql = "SELECT count(*) ".$from.$where." AND ".
                    $db->baseSQL_YEAR("timestamp", "=", $i_year)." AND ".
                    $db->baseSQL_MONTH("timestamp", "=", $i_month);

             if ( $day_start != -1 )
             {
                if ($i_month == $month_start)  $day_start2 = $day_start;  else  $day_start2 = 1;
                if ($i_month == $month_end)    $day_end2 = $day_end;      else  $day_end2 = 31;

                for ( $i_day = $day_start2; $i_day <= $day_end2; $i_day++ )
                {
                  if ( checkdate($i_month, $i_day, $i_year) )
                  {
                    $sql = "SELECT count(*) ".$from.$where." AND ".
                           $db->baseSQL_YEAR("timestamp", "=", $i_year)." AND ".
                           $db->baseSQL_MONTH("timestamp", "=",$i_month)." AND ".
                           $db->baseSQL_DAY("timestamp", "=", $i_day);

                    $i_hour = "";
                    if ( $hour_start != -1 )
                    {
                       for ( $i_hour = $hour_start; $i_hour <= $hour_end; $i_hour++ )
                       {
                           $sql = "SELECT count(*) ".$from.$where." AND ".
                                  $db->baseSQL_YEAR("timestamp", "=", $i_year)." AND ".
                                  $db->baseSQL_MONTH("timestamp", "=", $i_month)." AND ".
                                  $db->baseSQL_DAY("timestamp", "=", $i_day)." AND ".
                                  $db->baseSQL_HOUR("timestamp", "=", $i_hour);

                           StoreAlertNum($sql, $i_month."/".$i_day."/".$i_year." ".
                                               $i_hour.":00:00 - ".$i_hour.":59:59", 
                                         $time_sep, $i_year, $i_month, $i_day, $i_hour);
                       }  // end hour
                    }
                    else
                        StoreAlertNum($sql, $i_month."/".$i_day."/".$i_year,
                                      $time_sep, $i_year, $i_month, $i_day, $i_hour);
                  }
                }   // end day
             }
             else
               StoreAlertNum($sql, $i_month."/".$i_year, $time_sep, $i_year, $i_month, $i_day, $i_hour);
         }   // end month
      }
      else
        StoreAlertNum($sql, $i_year, $time_sep, $i_year, $i_month, $i_day, $i_hour);
  }   // end year

  echo '</TABLE>';
	PrintTimeProfile();
}
PrintBASESubFooter();
?>
