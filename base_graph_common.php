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
//            Purpose: Graphing System support functions.
//                   : Graph data extraction and calculations.
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once("$BASE_path$sc". 'base_qry_common.php');
include_once(BASE_IPath . 'base_log_error.inc.php');
include_once(BASE_IPath . 'base_signature.inc.php');
include_once(BASE_IPath . 'base_iso3166.inc.php');

$named_colors = array( // Some colors to be used in graphs.
	'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige',
	'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown',
	'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral',
	'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan',
	'darkgoldenrod', 'darkdray', 'darkgreen', 'darkhaki', 'darkorange',
	'darkolivegreen', 'darkmagenta', 'darkorchid', 'darkred', 'darksalmon',
	'darkseagreen', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray',
	'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia',
	'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green',
	'greenyellow', 'indianred', 'indigo', 'ivory'
);

// Chart type constants:
// Not prefixed with '_' so we don't interfere with PHP define's.
SetConst('CHARTTYPE_DEFAULT', 0);
SetConst('CHARTTYPE_HOUR', 1);
SetConst('CHARTTYPE_DAY', 2);
SetConst('CHARTTYPE_WEEK', 3);
SetConst('CHARTTYPE_MONTH', 4);
SetConst('CHARTTYPE_YEAR', 5);
SetConst('CHARTTYPE_SRC_IP', 6);
SetConst('CHARTTYPE_DST_IP', 7);
SetConst('CHARTTYPE_DST_UDP_PORT', 8);
SetConst('CHARTTYPE_DST_TCP_PORT', 9);
SetConst('CHARTTYPE_SRC_UDP_PORT', 10);
SetConst('CHARTTYPE_SRC_TCP_PORT', 11);
SetConst('CHARTTYPE_CLASSIFICATION', 12);
SetConst('CHARTTYPE_SENSOR', 13);
SetConst('CHARTTYPE_SRC_COUNTRY', 14);
SetConst('CHARTTYPE_SRC_COUNTRY_ON_MAP', 15);
SetConst('CHARTTYPE_DST_COUNTRY', 16);
SetConst('CHARTTYPE_DST_COUNTRY_ON_MAP', 17);
SetConst('CHARTTYPE_UNIQUE_SIGNATURE', 18);

// @codeCoverageIgnoreStart
// Installation dependent code paths. Testing would be problematic.
function VerifyGraphingLib(){
	GLOBAL $BCR;
	$Ret = false; // Lib Error Flag.
	$GD = false; // PHP GD Flag.
	$IG = false; // Graphing Lib Flag.
	if( isset($BCR) && is_object($BCR) ){
		$GD = $BCR->GetCap('PHP_GD');
		$IG = $BCR->GetCap('Graph');
	}else{
		$GD = function_exists('imagecreate');
		$IG = PearInc('Graphing', 'Image', 'Graph');
	}
	if( !$GD ){
		print returnBuildError('GD', '--with-gd');
	}else{
		$Ret = $IG;
	}
	if( $Ret == false ){ // Keep Issue #100 from happening here.
		sleep(60);
	}
	return $Ret;
}
// @codeCoverageIgnoreEnd

function check_worldmap(){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = 0;
	$php_path_array = explode(PATH_SEPARATOR, ini_get('include_path'));
	if( $debug_mode > 0 ){
		ErrorMessage( $EMPfx . 'Find the worldmap?','black',1);
	}
	$EMPfx .= 'ERROR: ';
	$sc = DIRECTORY_SEPARATOR;
	$MapLoc = implode( $sc, array('Image','Graph','Images','Maps') );
	$WMif = 'world_map6.png';
	$WMcf = 'world_map6.txt';
	foreach( $php_path_array as $single_path ){
		$WMapImg = implode( $sc, array($single_path, $MapLoc, $WMif) );
		if( $debug_mode > 0 ){
			ErrorMessage( "&quot;" . $WMapImg . "&quot;",'black',1);
		}
		$tmp = ChkAccess($WMapImg);
		$EMsg = '';
		if( $tmp > 0 ){
			// We ASSUME, that this is the correct worldmap file.
			// Not necessarily true, though. A simplification, therefore.
			$WMapCsf = implode($sc, array($single_path, $MapLoc, $WMcf));
			$tmp = ChkAccess($WMapCsf);
			if( $tmp > 0 ){
					$Ret = 1;
					break;
			}else{
				$EMsg = "$EMPfx Coordinates: $WMapCsf not ";
				if( $tmp == 0 ){
					$EMsg .= 'file';
				}elseif( $tmp == -1 ){
					$EMsg .= 'found';
				}elseif( $tmp == -2 ){
					$EMsg .= 'readable';
				}
				$$EMsg .= '.';
				ErrorMessage($EMsg, 0, 1);
			}
		}else{
			$EMsg = "$EMPfx Image: $WMapImg not ";
			if( $tmp == 0 ){
				$EMsg .= 'file';
			}elseif( $tmp == -1 ){
				$EMsg .= 'found';
			}elseif( $tmp == -2 ){
				$EMsg .= 'readable';
			}
			$$EMsg .= '.';
			ErrorMessage($EMsg, 0, 1);
		}
		if( $EMsg != '' ){ // Safe Mode File Owner Notice if necessary.
			print returnSMFN("both $WMif and $WMcf");
		}
	}
	if( $Ret != 1 ){
		ErrorMessage(
			$EMPfx . 'Worldmap functions not available. Go into the "PEAR '
			. 'directory", as can be found by "pear config-show", and then '
			. "into the subdirectory $MapLoc$sc. This is the location where "
			. "$WMif and $WMcf must be installed.",
			0, 1
		);
		// Safe Mode File Owner Notice if necessary.
		print returnSMFN("both $WMif and $WMcf");
	}
	return $Ret;
}

function ProcessChartTimeConstraint(
	$start_hour, $start_day, $start_month, $start_year,
	$stop_hour,  $stop_day,  $stop_month,  $stop_year
){ //Generates the required SQL from the chart time criteria.
	GLOBAL $debug_mode;
	$start_hour = trim($start_hour);
	$stop_hour = trim($stop_hour);
	$start_day = trim($start_day);
	$stop_day = trim($stop_day);
	$tmp_sql = '';
	if (
		empty($start_month) && empty($start_day) && empty($start_year) &&
		empty($stop_month) && empty($stop_day) && empty($stop_year)
	){
		return '';
	}
	$start = 0;
	$end = 1;
	$op = 1;
	$month = 2;
	$day = 3;
	$year = 4;
	$hour = 5;
	$minute = 6;
	$second = 7;
	$stop = 8;
	$SQLOP = 9;
	InitArray($tmp_time,2,10,''); //Setup Time Array
	// Array is based on TimeCriteria class as defined in:
	// ./includes/base_state_citems.inc.php
	if( empty($start_month) && empty($start_day) && empty($start_year) ){
		$tmp_time[$end][$op] = '<=';
		$tmp_time[$end][$month] = $stop_month;
		$tmp_time[$end][$day] = $stop_day;
		$tmp_time[$end][$year] = $stop_year;
		$tmp_time[$end][$hour] = $stop_hour;
		$cnt = 2;
	}elseif( empty($stop_month) && empty($stop_day) && empty($stop_year) ){
		$tmp_time[$start][$op] = '>=';
		$tmp_time[$start][$month] = $start_month;
		$tmp_time[$start][$day] = $start_day;
		$tmp_time[$start][$year] = $start_year;
		$tmp_time[$start][$hour] = $start_hour;
		$cnt = 1;
	}else{
		$tmp_time[$start][$op] = '>=';
		$tmp_time[$start][$month] = $start_month;
		$tmp_time[$start][$day] = $start_day;
		$tmp_time[$start][$year] = $start_year;
		$tmp_time[$start][$hour] = $start_hour;
		$tmp_time[$start][$SQLOP] = 'AND';
		$tmp_time[$end][$op] = '<=';
		$tmp_time[$end][$month] = $stop_month;
		$tmp_time[$end][$day] = $stop_day;
		$tmp_time[$end][$year] = $stop_year;
		$tmp_time[$end][$hour] = $stop_hour;
		$cnt = 2;
	}
	DateTimeRows2sql($tmp_time, $cnt, $tmp_sql);
	if ( $debug_mode > 0 ){
		var_dump($tmp_time);
		ErrorMessage(__FUNCTION__ . "() Returned SQL: $tmp_sql");
	}
	return $tmp_sql;
}

function StoreAlertNum( $sql, $label, &$xdata, &$cnt, $min_threshold ){
	GLOBAL $db, $debug_mode;
	if ( $debug_mode > 0 ){
		ErrorMessage( $sql, 'black', 1 );
	}
	$result = $db->baseExecute($sql);
	if ( $result != false ){ // Error Check
		$myrow = $result->baseFetchRow();
		if ( $myrow[0] >= $min_threshold ){
			$xdata [ $cnt ][0] = $label;
			$xdata [ $cnt ][1] = $myrow[0];
			$cnt++;
		}
		$result->baseFreeRows();
	}
}

function GetTimeDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode, $chart_begin_year, $chart_begin_month,
	$chart_begin_day, $chart_begin_hour, $chart_end_year, $chart_end_month,
	$chart_end_day, $chart_end_hour;
	// Get time range for whole DB.
	$sql = "SELECT min(timestamp), max(timestamp) FROM acid_event " .
	$criteria[0] . " WHERE ".$criteria[1];
	$result = $db->baseExecute($sql);
	$myrow = $result->baseFetchRow();
	$start_time = $myrow[0];
	$stop_time = $myrow[1];
	$result->baseFreeRows();
	if ( $debug_mode > 0 ){
		ErrorMessage(
			__FUNCTION__ . "() DB Time Range: $start_time - $stop_time", '', 1
		);
	}
	// Get Time range parts.
	$year_start  = date("Y", strtotime($start_time)); // Start
	$month_start = date("m", strtotime($start_time));
	$day_start   = date("d", strtotime($start_time));
	$hour_start  = date("H", strtotime($start_time));
	$year_end  = date("Y", strtotime($stop_time)); // End
	$month_end = date("m", strtotime($stop_time));
	$day_end   = date("d", strtotime($stop_time));
	$hour_end  = date("H", strtotime($stop_time));
	// using the settings from begin_xyz and end_xyz
	// minutes are not supported actually
	// begin
	if ( is_numeric($chart_begin_year) && $year_start < $chart_begin_year ){
		$year_start = $chart_begin_year;
	}
	if ( is_numeric($chart_begin_month) && $month_start < $chart_begin_month ){
		$month_start = $chart_begin_month;
	}
	if ( is_numeric($chart_begin_day) && $day_start < $chart_begin_day ){
		$day_start = $chart_begin_day;
	}
	if ( is_numeric($chart_begin_hour) && $hour_start < $chart_begin_hour ) {
		$hour_start = $chart_begin_hour;
	}
	//end
	if ( is_numeric($chart_end_year) && $year_end < $chart_end_year ){
		$year_end = $chart_end_year;
	}
	if ( is_numeric($chart_end_month) && $month_end < $chart_end_month ){
		$month_end = $chart_end_month;
	}
	if ( is_numeric($chart_end_day) && $day_end < $chart_end_day ){
		$day_end = $chart_end_day;
	}
	if ( is_numeric($chart_end_hour) && $hour_end < $chart_end_hour ) {
		$hour_end = $chart_end_hour;
	}
	switch ( $chart_type ){
		case 1: // hour
			if ( $debug_mode > 0 ){
				ErrorMessage(
					"chart_begin_hour = \"$chart_begin_hour\", hour_start = \"$hour_start\"",
					'black',1
				);
				ErrorMessage(
					"chart_end_hour = \"$chart_end_hour\", hour_end = \"$hour_end\"",
					'black',1
				);
			}
			if ( !is_numeric($chart_end_hour) || $chart_end_hour == '' ){
				// hour_start = -1 is NOT possible, because with
				// chart_type == 1 each hour is to be queried.
				// We want bars hour by hour.
				$hour_end = 23;
			}
			break;
		case 2: // day
			$hour_start = -1;
			break;
		case 4: // month
			$day_start = -1;
			$hour_start = -1;
			break;
	}
	if ( $debug_mode > 0 ){
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
		DDT($DI,$DD,'Time Constraints');
	}
	$cnt = 0;
	$ag = $criteria[0];
	$ag_criteria = $criteria[1];
	// SQL peices
	$ts = 'timestamp';
	$A = ' AND ';
	$W = ' WHERE ';
//	$sqlpfx = "SELECT count(*) FROM acid_event ";
//	if ( $ag != '' ){ // Not Querying Alert Groups
//		$sqlpfx .= "$ag$W$ag_criteria";
//	}else{
//		$sqlpfx .= $W;
//	}
//	$sqlpfx .= $A;
	$sqlpfx = "SELECT count(*) FROM acid_event $ag WHERE $ag_criteria$A";
	for ( $i_year = $year_start; $i_year <= $year_end; $i_year++ ){
		// Catch 2 digit years, default to YYYY in current century.
		if ( strlen($i_year) <= 2 ){
			$i_year = substr(date("Y"),0,2).FormatTimeDigit($year);
		}
		// removed AND below
		// !!! AVN !!!
		// to_date() must used!
		$sql = $sqlpfx.$db->baseSQL_YEAR( $ts, '=', $i_year );
		if ( $month_start != -1 ){
			if ( $i_year == $year_start ){
				$month_start2 = $month_start;
			}else{
				$month_start2 = 1;
			}
			if ( $i_year == $year_end ){
				$month_end2 = $month_end;
			}else{
				$month_end2 = 12;
			}
			for (
				$i_month = $month_start2; $i_month <= $month_end2; $i_month++
			){
				$i_month = FormatTimeDigit($i_month);
				$sql = $sqlpfx.$db->baseSQL_YEAR( $ts, '=', $i_year ) . $A.
				$db->baseSQL_MONTH( $ts, '=', $i_month );
				if ( $day_start != -1 ){
					if ( $i_month == $month_start ){
						$day_start2 = $day_start;
					}else{
						$day_start2 = 1;
					}
					if ( $i_month == $month_end ){
						$day_end2 = $day_end;
					}else{
						$day_end2 = 31;
						while (
							!checkdate( $i_month, $day_end2, $i_year )
						){ // Bring it into reality.
							--$day_end2;
						}
					}
					for (
						$i_day = $day_start2; $i_day <= $day_end2; $i_day++
					){
						$i_day = FormatTimeDigit($i_day);
						$sql = $sqlpfx.
						$db->baseSQL_YEAR( $ts, '=', $i_year ) . $A.
						$db->baseSQL_MONTH( $ts, '=', $i_month ) . $A.
						$db->baseSQL_DAY( $ts, '=', $i_day );
						$Lbl = implode ('/',array( $i_month, $i_day, $i_year ));
						if ( $hour_start != -1 ){
							// jl: The condition "i_hour <= hour_end" is
							// correct ONLY if the first day is equal to the
							// last day of the query.
							// Otherwise we want 24 hours of all the days
							// preceding the last day of the query.
							// Analogously for hour_start.
							if ( $i_day == $day_start2 ){
								$hour_start2 = $hour_start;
							}else{
								$hour_start2 = 0;
							}
							if ( $i_day == $day_end2 ){
								$hour_end2 = $hour_end;
							}else{
								$hour_end2 = 23;
							}
							for (
								$i_hour = $hour_start2;
								$i_hour <= $hour_end2; $i_hour++
							){
								$i_hour = FormatTimeDigit($i_hour);
								$sql = $sqlpfx.
								$db->baseSQL_YEAR( $ts, '=', $i_year ) . $A.
								$db->baseSQL_MONTH( $ts, '=', $i_month ) . $A.
								$db->baseSQL_DAY( $ts, '=', $i_day ) . $A.
								$db->baseSQL_HOUR( $ts, '=', $i_hour );
								StoreAlertNum( $sql,
									"$Lbl $i_hour:00:00 - $i_hour:59:59",
									$xdata, $cnt, $min_threshold
								);
							} // end hour
						}else{
							StoreAlertNum(
								$sql, $Lbl, $xdata, $cnt, $min_threshold
							);
						}
					} // end day
				}else{
					StoreAlertNum(
						$sql, implode ('/',array( $i_month, $i_year )), $xdata,
						$cnt, $min_threshold
					);
				}
			} // end month
		}else{
			StoreAlertNum($sql, $i_year, $xdata, $cnt, $min_threshold);
		}
	} // end year
	return $cnt;
}

function GetIPDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode;

   if ( $chart_type == 6 ) 
      $sql = "SELECT DISTINCT ip_src, COUNT(acid_event.cid) ".
             "FROM acid_event ".$criteria[0].
             "WHERE ".$criteria[1]." AND ip_src is NOT NULL ".
             "GROUP BY ip_src ORDER BY ip_src";
   else if ( $chart_type == 7 )
      $sql = "SELECT DISTINCT ip_dst, COUNT(acid_event.cid) ".
             "FROM acid_event ".$criteria[0].
             "WHERE ".$criteria[1]." AND ip_dst is NOT NULL ".
             "GROUP BY ip_dst ORDER BY ip_dst";

	DumpSQL($sql, 1);
   $result = $db->baseExecute($sql);

   $cnt = 0;
   while ( $myrow = $result->baseFetchRow() )
   {
      if ( $myrow[1] >= $min_threshold )
      {
         $xdata[$cnt][0] = baseLong2IP($myrow[0]); 
         $xdata[$cnt][1] = $myrow[1]; 
         ++$cnt;
      }
   }

   $result->baseFreeRows();
   return $cnt;
}

function GetPortDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode;

   if ( ($chart_type == 8) || ($chart_type == 9) ) 
      $sql = "SELECT DISTINCT layer4_dport, COUNT(acid_event.cid) ".
             "FROM acid_event ".$criteria[0].
             "WHERE ".$criteria[1]." AND layer4_dport is NOT NULL ".
             "GROUP BY layer4_dport ORDER BY layer4_dport";
   else if ( ($chart_type == 10) || ($chart_type == 11) ) 
      $sql = "SELECT DISTINCT layer4_sport, COUNT(acid_event.cid) ".
             "FROM acid_event ".$criteria[0].
             "WHERE ".$criteria[1]." AND layer4_sport is NOT NULL ".
             "GROUP BY layer4_sport ORDER BY layer4_sport";

	DumpSQL($sql, 1);
   $result = $db->baseExecute($sql);

   $cnt = 0;
   while ( $myrow = $result->baseFetchRow() )
   {
      if ( $myrow[1] >= $min_threshold )
      {
         $xdata[$cnt][0] = $myrow[0]; 
         $xdata[$cnt][1] = $myrow[1]; 
         ++$cnt;
      }
   }

   $result->baseFreeRows();
   return $cnt;
}

function GetClassificationDataSet(
	&$xdata, $chart_type, $min_threshold, $criteria
){
	GLOBAL $db, $debug_mode;
  
   $sql = "SELECT DISTINCT sig_class_id, COUNT(acid_event.cid) ".
          "FROM acid_event ".$criteria[0].
          "WHERE ".$criteria[1].
          " GROUP BY sig_class_id ORDER BY sig_class_id";

	DumpSQL($sql, 1);
   $result = $db->baseExecute($sql);

   $cnt = 0;
   while ( $myrow = $result->baseFetchRow() )
   {
      if ( $myrow[1] >= $min_threshold )
      {
         if ($debug_mode > 0)
         {
           // Sig. classification vs. number of alerts
           error_log(__FILE__ . ":" . __LINE__ . ": \$myrow[0] = \""  . $myrow[0] . "\"");
         }


         $xdata[$cnt][0] = strip_tags(GetSigClassName($myrow[0], $db)); 
         if ($debug_mode > 0)
         {
           // Sig. classification vs. number of alerts
           error_log(__FILE__ . ":" . __LINE__ . ": \$xdata[\$cnt][0] = \""  . $xdata[$cnt][0] . "\"");
         }

         if (empty($xdata[$cnt][0]) || $xdata[$cnt][0] == "unclassified")
         {
           $xdata[$cnt][0] = $myrow[0];
         }

         

         $xdata[$cnt][1] = $myrow[1];
         ++$cnt;
      }
   }

   $result->baseFreeRows();
   return $cnt;
}

function GetUniqueDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode;

  $cnt = 0;
  $sql = "SELECT signature, " .
         "sig_name, " .
         "COUNT(signature) " .
         "FROM acid_event " . $criteria[0] . " " .
         "WHERE " . $criteria[1] . " " .
         "GROUP BY signature, sig_name " . 
         "ORDER BY sig_name";

	DumpSQL($sql, 1);
  $result = $db->baseExecute($sql);
  
  while($myrow = $result->baseFetchRow())
  {

  #  echo "<BR><BR>-------&lt;row&gt;---------<BR><pre>";
  #  var_dump($myrow);
  #  echo "<BR><BR><BR>";
  #  print_r($myrow);
  #  echo "</PRE><BR>---------&lt;/row&gt;--------<BR>";

    if ( $myrow[2] >= $min_threshold )
    {
      $xdata[$cnt][0] = strip_tags($myrow[1]); 
      $xdata[$cnt][1] = $myrow[2];

      if ($debug_mode > 0)
      {
        print $xdata[$cnt][0] . ": " . $xdata[$cnt][1] . " alerts <BR>\n";
      }
      ++$cnt;
    }
  }
  
  $result->baseFreeRows();

  return $cnt;
}

function GetSensorDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode;

   $sql = "SELECT DISTINCT acid_event.sid, COUNT(acid_event.cid) ".
          "FROM acid_event ".$criteria[0].
          "WHERE ".$criteria[1].
          " GROUP BY acid_event.sid ORDER BY acid_event.sid";

	DumpSQL($sql, 1);
   $result = $db->baseExecute($sql);

   $cnt = 0;
   while ( $myrow = $result->baseFetchRow() )
   {
      if ( $myrow[1] >= $min_threshold )
      {
         $result2 = $db->baseExecute("SELECT hostname FROM sensor where sid=".$myrow[0]);
         $sensor_name = $result2->baseFetchRow();
         $xdata[$cnt][0] = $sensor_name[0];
         $result2->baseFreeRows();
 
         $xdata[$cnt][1] = $myrow[1];
         ++$cnt;
      }
   }

   $result->baseFreeRows();
   return $cnt;
}

// xxx jl
function ReadGeoIPfreeFileAscii( &$Geo_IPfree_array ){
	GLOBAL $Geo_IPfree_file_ascii, $debug_mode, $iso_3166;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = 0;
	if( !isset($iso_3166) ){
		ErrorMessage($EMPfx . 'ERROR: $iso_3166 has not been defined.');
	}else{
		ini_set("memory_limit", "256M");
		$lines = file($Geo_IPfree_file_ascii);
		if( $lines === false ){
			ErrorMessage(
				$EMPfx . "WARNING: Can't open $Geo_IPfree_file_ascii.", 0, 1
			);
		}else{
			$Ret = 1;
			foreach( $lines as $line_num => $line ){
				$line_array[$line_num] = explode(' ', rtrim($line));
    $index = rtrim($line_array[$line_num][0], ':');
    $begin = sprintf("%u", ip2long($line_array[$line_num][1]));
    $end = sprintf("%u", ip2long($line_array[$line_num][2]));

				if( !is_key($index, $iso_3166) ){
        $estr = "ERROR: index \"" . $index . "\" = ascii codes ";
        $estr .= ord($index[0]) . ", " . ord($index[1]) . " ";
        $estr .= "does not exist. Ignoring.<BR>\n";
        ErrorMessage($estr);
				}else{
					if( $debug_mode > 1 ){
          print "Full name of " . $index . " = \"" . $iso_3166[$index]. "\"<BR>\n";
					}
        $index .= " (" . $iso_3166[$index] . ")";
				}
				if(
					!isset($Geo_IPfree_array)
					|| !is_key($index, $Geo_IPfree_array)
				){
        $Geo_IPfree_array[$index][0] = array($begin, $end);
				}else{
					array_push($Geo_IPfree_array[$index], array($begin, $end));
				}
			}
		}
	}
	return $Ret;
}

// GEO IP lookup First method: CPAN Geo-IPfree.
// http://search.cpan.org/CPAN/authors/id/G/GM/GMPASSOS/Geo-IPfree-0.2.tar.gz
// Requires the transformation of the included database into human readable
// ASCII format, similarly to:
//          cd /usr/lib/perl5/site_perl/5.8.8/Geo/
//          perl ipct2txt.pl ./ipscountry.dat /tmp/ips-ascii.txt
// The web server needs permission to read ips-ascii.txt..
//
// $Geo_IPfree_file_ascii must contain the absolute path to ips-ascii.txt.

function GeoIPfree_IP2Country(
	$Geo_IPfree_array, $address_with_dots, &$country
){
	GLOBAL $debug_mode;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = 0;
	if ( !empty($Geo_IPfree_array) && !empty($address_with_dots) ){
		$address = sprintf("%u", ip2long($address_with_dots));
		foreach ( $Geo_IPfree_array as $key => $val ){ // Issue #153
			$nelements = count($val);
			if ( count($val) > 0 ){
				foreach ( $val as $key2 => $val2 ){ // Issue #153
					if ( $debug_mode > 1 ){
						if ( $val2[0] > $val2[1] ){
							ErrorMessage(
								$EMPfx . "WARNING: Inconsistency with $key "
								. "array element no. $key2: "
								. long2ip($val2[0]) . ' - '
								. long2ip($val2[1])
							);
						}
					}
					if ( $address >= $val2[0] && $address <= $val2[1] ){
						$country = $key;
						if ( $debug_mode > 0 ){
							$tmp = $EMPfx . "Found: $address_with_dots "
							. "belongs to $country ";
							$tmp .= ': ' . long2ip($val2[0]) . ' - '
							. long2ip($val2[1]) . ' ';
							ErrorMessage($tmp, 'black');
						}
						$Ret = 1;
					}
				}
			}
		}
	}
	return $Ret;
}

// GEO IP lookup Second method: CPAN IP::Country.
// http://search.cpan.org/dist/IP-Country/
// The web server needs permission to execute "ip2cc".
// Quoting from the php manual: "Note: When safe mode is enabled, you can only
// execute executables within the safe_mode_exec_dir. For practical reasons it
// is currently not allowed to have .. components in the path to the
// executable."
//
// $IP2CC must contain the absolute path to this executable.

function run_ip2cc( $address_with_dots, &$country ){
	GLOBAL $debug_mode, $IP2CC;
	$EMPfx = __FUNCTION__ . ': ';
	$Ret = 0;
	if( empty($address_with_dots) ){
		ErrorMessage($EMPfx . 'ERROR: $address_with_dots is empty.', 0, 1);
	}else{
		$cmd = $IP2CC . ' ' . $address_with_dots;
		unset($output);
		unset($rv);
		if( exec($cmd, $output, $rv) !== false && $rv == 0 ){
			$country = preg_replace('/^  Country\: /', '', $output[6]);
			$Ret = 1;
			if( $debug_mode > 0 ){
				ErrorMessage(
					$EMPfx . "Found: $address_with_dots belongs to $country ",
					'black'
				);
			}
		}else{
			ErrorMessage($EMPfx . "ERROR with $cmd Return: $rv", 0, 1);
			print_r($output);
		}
	}
	return $Ret;
}

function IncreaseCountryValue( &$countries, $to_search, $number_of_alerts ){
	GLOBAL $debug_mode;
	if( count($countries) == 0 ){
		$countries[$to_search] = $number_of_alerts;
		return;
	}
	$tmp = '';
	if( is_key($to_search, $countries) ){
		$countries[$to_search] += $number_of_alerts;
	}else{
		$tmp = 'NOT ';
		$countries[$to_search] = $number_of_alerts;
	}
	if( $debug_mode > 1 ){
		ErrorMessage($to_search . ' does ' . $tmp .'exist.', 0, 1);
	}
}

function GetCountryDataSet( &$xdata, $chart_type, $min_threshold, $criteria ){
	GLOBAL $db, $debug_mode, $Geo_IPfree_file_ascii, $IP2CC;
	$country_method = 0;
	$EMPfx = __FUNCTION__ . ': ';
	if( $chart_type > 13 && $chart_type < 16 ){
		// 14 = Src Countries vs. Num Alerts. 15 = Same, on worldmap.
		$tmp = 'src';
	}elseif( $chart_type > 15 && $chart_type < 18 ){
		// 16 = Dst Countries vs. Num Alerts. 17 = Same, on worldmap.
		$tmp = 'dst';
	}
	$sql = "SELECT DISTINCT ip_$tmp, COUNT(acid_event.cid) "
	. 'FROM acid_event ' . $criteria[0] . 'WHERE ' . $criteria[1]
	. " AND ip_$tmp is NOT NULL GROUP BY ip_$tmp ORDER BY ip_$tmp";
	DumpSQL($sql, 1);
	$result = $db->baseExecute($sql);
	if( LoadedString($Geo_IPfree_file_ascii) ){ // Try Geo::IP
		$tmp = ChkAccess($Geo_IPfree_file_ascii);
		if( $tmp < 1 ){
			$EMsg = $EMPfx . "ERROR: $Geo_IPfree_file_ascii not ";
			if( $tmp == 0 ){
				$EMsg .= 'file';
			}elseif( $tmp == -1 ){
				$EMsg .= 'found';
			}elseif( $tmp == -2 ){
				$EMsg .= 'readable';
			}
			$$EMsg .= '.';
			ErrorMessage($EMsg, 0, 1);
		}else{ // Read in DB with country data for ip addresses.
			$country_method = 1;
			if ( $debug_mode > 0 ){
				ErrorMessage(
					$EMPfx . 'Country method 1: Using Geo::IP.',
					0, 1
				);
			}
			ReadGeoIPfreeFileAscii($Geo_IPfree_array);
		}
	}elseif( LoadedString($IP2CC) ){ // Try IP::Country
		$rv = ini_get('safe_mode');
		$tmp = ChkAccess($IP2CC);
		if( $tmp < 1 ){
			$EMsg = $EMPfx . "ERROR: $IP2CC not ";
			if( $tmp == 0 ){
				$EMsg .= 'file';
			}elseif( $tmp == -1 ){
				$EMsg .= 'found';
			}elseif( $tmp == -2 ){
				$EMsg .= 'readable';
			}
			$$EMsg .= '.';
			ErrorMessage($EMsg, 0, 1);
			if( $rv == 1 ){
				print returnSMFN('the file "' . $IP2CC . '"');
				$EMsg = 'Adding it to both safe_mode_exec_dir and to '
				. 'include_path in /etc/php.ini does NOT seem to be '
				. 'sufficient.';
				ErrorMessage($EMsg, 'black', 1);
			}
		}elseif( $tmp < 2 ){ // Not Executable
			$EMsg = $EMPfx . "ERROR: $IP2CC not executable.";
			ErrorMessage($EMsg, 0, 1);
			if( $rv == 1 ){
				$EMsg = XSSPrintSafe('In "safe_mode" the path "')
				. dirname($IP2CC) . XSSPrintSafe('"')
				. ' must also be part of safe_mode_exec_dir in /etc/php.ini.'
				. '<pre>';
				. XSSPrintSafe( 'safe_mode_exec_dir = "')
				. dirname($IP2CC) . XSSPrintSafe('"');
				. '</pre>'
				. 'It seems that <b>not more than ONE SINGLE directory</b>'
				. ' may be assigned to safe_mode_exec_dir.'
				ErrorMessage($EMsg, 'black', 1);
			}
		}else{
			$country_method = 2;
			if ( $debug_mode > 0 ){
				ErrorMessage(
					$EMPfx . 'Country method 2: Using IP::Country.', 0, 1
				);
			}
		}
	}else{
		ErrorMessage(
			$EMPfx . 'ERROR: Conf Var $Geo_IPfree_file_ascii or $IP2CC not '
			. 'configured.', 0, 1
		);
	}
	if( $country_method == 0 ){ // should not be reached
		ErrorMessage(
			$EMPfx . 'ERROR: No GEO IP lookup method available.', 0, 1
		);
		return 0;
	}
  // Loop through all the ip addresses returned by the sql query
  $cnt = 0;
  $not_an_array = 0;
  while ($myrow = $result->baseFetchRow())
  {
    if (!is_array($myrow))
    {
      $not_an_array += 1;
      if ($not_an_array <= 3)
      {
        // Ok. We accept getting something that is not an array,
        // if this happens not more than three times.        
        next;
      }
      else
      {
        // Now we are fed up with getting something that is not
        // even an array. Break!
        break; 
      }
    }

    if ($myrow[1] >= $min_threshold)
    {
      $addresses[$cnt][0] = baseLong2IP($myrow[0]); 
      $addresses[$cnt][1] = $myrow[1]; 
      
      // xxx jl
      // Which country belongs this ip address to?
      switch($country_method)
      {
        case 1:
	        GeoIPfree_IP2Country($Geo_IPfree_array, $addresses[$cnt][0], $mycountry);      
	        break;

	      case 2:
	        run_ip2cc($addresses[$cnt][0], $mycountry);
	        break;

	      default:
	        print "WARNING: country_method no. " . $country_method . " is not supported.<BR>\n";
	      return 0;
      }

		if( $debug_mode > 0 ){
			ErrorMessage(
				"$EMPfx$mycountry: ". $addresses[$cnt][1] . ' alerts.',
				'black', 1
			);
		}

      // Either GeoIPfree_IP2Country() or run_ip2cc() should have set
      // this variable:
      if (!isset($mycountry) || empty($mycountry))
      {
        ErrorMessage("ERROR: \$mycountry has not been set as expected.<BR>\n");
        return 0;
      }


      // Increase number of alerts for this country 
      IncreaseCountryValue($countries, $mycountry, $addresses[$cnt][1]);

      ++$cnt;
    }
  }

  if ($cnt <= 0)
  {
    // then there are no data points to plot.
    return $cnt;
  }


  if (!isset($countries))
  {
    print "ERROR: \$countries has not even been defined. Returning 0.\n";
    return 0;
  }


  if (!is_array($countries))
  {
    print "ERROR: \$countries is not an array. Returning 0.\n";
    print "<BR><PRE>\n";
    var_dump($countries);
    print "</PRE><BR>\n";    
    return 0;
  }


  if ($debug_mode > 1)
  { 
    print "<pre>############\n";
    //var_dump($countries);
    print_r($countries);
    print "###########</pre>\n";
  }
  // Now setup the chart array:
  $cnt2 = 0;
	foreach ( $countries as $key => $val ){ // Issue #153
		$xdata[$cnt2][0] = $key;
		$xdata[$cnt2][1] = $val;
		$cnt2++;
	}
	$result->baseFreeRows();
	// return number of countries rather than number of addresses!
	return $cnt2;
}

?>
