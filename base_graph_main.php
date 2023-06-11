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
//            Purpose: BASE Graphing System.
//
//     Input GET/POST:
//           - submit:
//             - time:
//         - time_sep:
//
//          Author(s): Nathan Gibbs
//                     Kevin Johnson

$sc = DIRECTORY_SEPARATOR;
require_once("includes$sc" . 'base_krnl.php');
include_once(BASE_IPath . 'base_include.inc.php');
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_graph_common.php");

AuthorizedRole(10000);
$db = NewBASEDBConnection($DBlib_path, $DBtype); // Connect to the Alert DB.
$db->baseDBConnect(
	$db_connect_method,$alert_dbname, $alert_host, $alert_port, $alert_user,
	$alert_password
);
UpdateAlertCache($db);

$EMPfx = __FILE__ . ': '; // Error Message Prefix.
if( VerifyGraphingLib() ){ // Graphics Libs Check
	$cs = new CriteriaState("base_stat_alerts.php");
	$cs->ReadState();
	$new = ImportHTTPVar("new", VAR_DIGIT);
	$submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);
	// Set default values if the submit button hasn't been pressed.
	if( $new == 1 && $submit == '' ){ // New Graph
    $height            = 800;
    $width             = 600;
    $pmargin0          = 50;
    $pmargin1          = 50;
    $pmargin2          = 70;
    $pmargin3          = 80;
    $user_chart_title  = _CHRTTITLE;
    $min_size          = 0;
    $rotate_xaxis_lbl  = 0;
    $xaxis_label_inc   = 1;
    $yaxis_scale       = 0;
    $chart_style       = "bar";
    $use_alerts        = 0;
    $xaxis_grid        = 0;
    $yaxis_grid        = 1;
    $element_start     = 0;
	}else{ // Retrieve data from submit and store for later use.
    $height       = ImportHTTPVar("height", VAR_DIGIT);
    $width        = ImportHTTPVar("width", VAR_DIGIT);  
    $pmargin0     = ImportHTTPVar("pmargin0", VAR_DIGIT);
    $pmargin1     = ImportHTTPVar("pmargin1", VAR_DIGIT);
    $pmargin2     = ImportHTTPVar("pmargin2", VAR_DIGIT);
    $pmargin3     = ImportHTTPVar("pmargin3", VAR_DIGIT);
    $user_chart_title = ImportHTTPVar("user_chart_title", VAR_ALPHA | VAR_SPACE);
    $min_size         = ImportHTTPVar("min_size", VAR_DIGIT);
    $rotate_xaxis_lbl = ImportHTTPVar("rotate_xaxis_lbl", VAR_DIGIT);
    $xaxis_label_inc  = ImportHTTPVar("xaxis_label_inc", VAR_DIGIT);
    $yaxis_scale      = ImportHTTPVar("yaxis_scale", VAR_DIGIT);
    $chart_style      = ImportHTTPVar("chart_style", VAR_ALPHA);
    $xaxis_grid       = ImportHTTPVar("xaxis_grid", VAR_DIGIT);
    $yaxis_grid       = ImportHTTPVar("yaxis_grid", VAR_DIGIT);
    $element_start    = ImportHTTPVar("element_start", VAR_DIGIT);
	}
  $data_source       = ImportHTTPVar("data_source", VAR_DIGIT);
  $chart_type        = ImportHTTPVar("chart_type", VAR_DIGIT);
  $chart_interval    = ImportHTTPVar("chart_interval", VAR_DIGIT);
  $chart_begin_hour  = ImportHTTPVar("chart_begin_hour", VAR_DIGIT);
  $chart_begin_month = ImportHTTPVar("chart_begin_month", VAR_DIGIT);
  $chart_begin_day   = ImportHTTPVar("chart_begin_day", VAR_DIGIT);
  $chart_begin_year  = ImportHTTPVar("chart_begin_year", VAR_DIGIT);
  $chart_end_hour    = ImportHTTPVar("chart_end_hour", VAR_DIGIT);
  $chart_end_month   = ImportHTTPVar("chart_end_month", VAR_DIGIT);
  $chart_end_day     = ImportHTTPVar("chart_end_day", VAR_DIGIT);
  $chart_end_year    = ImportHTTPVar("chart_end_year", VAR_DIGIT);
  $aggregate_type    = ImportHTTPVar("aggregate_type", VAR_DIGIT);

	$page_title = _GRAPHALERTDATA;
	PrintBASESubHeader(
		$page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages
	);
	include("$BASE_path/base_graph_form.php");
	$data_pnt_cnt = 0;
	if( $submit != '' && $chart_type == ' ' ){ // Error Conditions.
		ErrorMessage('<b>'._ERRCHRTNOTYPE.'</b>.');
	}elseif( $submit != '' ){ // Calculate the data set.
		if ( $data_source == ' ' ){
			ErrorMessage(_ERRNOAGSPEC);
			$data_source = NULL;
		}
		unset($xdata);
		unset($xlabel);
		if( $debug_mode > 1 ){
       echo "<H3>"._CHRTDATAIMPORT."...</H3>";
		}
		// Building Criteria.
		$criteria = array(2);
		if( !empty($data_source) ){
        $criteria[0] = "LEFT JOIN acid_ag_alert ".
                      "ON (acid_event.sid=acid_ag_alert.ag_sid AND acid_event.cid=acid_ag_alert.ag_cid) ";
        $criteria[1] = "acid_ag_alert.ag_id = $data_source";
		}else{
			$criteria[0] = '';
			// $tmp = "acid_event.sid > 0 ";
			$tmp = " 1 = 1 "; // Shim SQL when not querying alert groups.
			$criteria[1] = $tmp;
		}
		// Adding Time Constraint
		$time_constraint = ProcessChartTimeConstraint(
			$chart_begin_hour, $chart_begin_day, $chart_begin_month,
			$chart_begin_year, $chart_end_hour, $chart_end_day,
			$chart_end_month, $chart_end_year
		);
		if( !empty($time_constraint) ){
			$criteria[1] .= $time_constraint;
		}
		if( $debug_mode > 0 ){
       echo "<H3>Chart criteria</H3><PRE>";
       print_r($criteria);
       echo "</PRE>";
		}
		$WorldMap = false;
		if( $chart_type == 15 || $chart_type == 17 ){
			$WorldMap = true; // CHARTTYPE_*_COUNTRY_ON_MAP
		}
		switch( $chart_type ){
			case CHARTTYPE_HOUR; // hours vs num of alerts
			case CHARTTYPE_DAY; // days vs num of alerts
			case CHARTTYPE_WEEK; // weeks vs num of alerts
			case CHARTTYPE_MONTH; // months vs num of alerts
			case CHARTTYPE_YEAR; // years vs num of alerts
            $chart_title = _CHRTTIMEVNUMBER;
            $xaxis_label = _CHRTTIME;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetTimeDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            /* Any kinds of special characters, like slashes, two points
             * and so on seem to NOT work with Image_Graph */
            //$chart_title = $chart_title."\n ( ".$xdata[0][0]." - ".$xdata[count($xdata)-1][0]." )";      
            //$xaxis_label .= " from ". $xdata[0][0] . " to " . $xdata[count($xdata)-1][0] . " ";
            break;
			case CHARTTYPE_SRC_IP;  // Src. IP vs. Num Alerts
            $chart_title = _CHRTSIPNUMBER;
            $xaxis_label = _CHRTSIP;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetIPDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_DST_IP; // Dst. IP vs. Num Alerts
            $chart_title = _CHRTDIPALERTS;
            $xaxis_label = _CHRTDIP;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetIPDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_DST_UDP_PORT; // UDP Port vs. Num Alerts
            $chart_title = _CHRTUDPPORTNUMBER;
            $xaxis_label = _CHRTDUDPPORT;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetPortDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_SRC_UDP_PORT; // UDP Port vs. Num Alerts
            $chart_title = _CHRTSUDPPORTNUMBER;
            $xaxis_label = _CHRTSUDPPORT;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetPortDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_DST_TCP_PORT; // TCP Port vs. Num Alerts
            $chart_title = _CHRTPORTDESTNUMBER;
            $xaxis_label = _CHRTPORTDEST;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetPortDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_SRC_TCP_PORT; // TCP Port vs. Num Alerts
            $chart_title = _CHRTPORTSRCNUMBER;
            $xaxis_label = _CHRTPORTSRC;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetPortDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_CLASSIFICATION; // Classification vs. Num Alerts
            $chart_title = _CHRTSIGNUMBER;
            $xaxis_label = _CHRTCLASS;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetClassificationDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_SENSOR; // Sensor vs. Num Alerts
            $chart_title = _CHRTSENSORNUMBER;
            $xaxis_label = _SENSOR;
            $yaxis_label = _CHRTALERTOCCUR;
				$data_pnt_cnt = GetSensorDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
            break;
			case CHARTTYPE_SRC_COUNTRY; // Src Countries vs. Num Alerts
			case CHARTTYPE_SRC_COUNTRY_ON_MAP; // dto., but on worldmap
				if ( $WorldMap ){
           if (!check_worldmap())
           {
             return 0;
           }
				}
         $chart_title = "Countries of origin vs. number of alerts";
         $xaxis_label = "Src countries";
         $yaxis_label = "Number of alerts";
				$data_pnt_cnt = GetCountryDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
         break;
			case CHARTTYPE_DST_COUNTRY; // Dst Countries vs. Num Alerts
			case CHARTTYPE_DST_COUNTRY_ON_MAP; // dto., but on worldmap
				if ( $WorldMap ){
           if (!check_worldmap())
           {
             return 0;
           }
				}
         $chart_title = "Destination Countries vs. number of alerts";
         $xaxis_label = "Dst countries";
         $yaxis_label = "Number of alerts";
				$data_pnt_cnt = GetCountryDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
         break;
			case CHARTTYPE_UNIQUE_SIGNATURE; // Unique alerts vs. number of alerts
         $chart_title = "Unique alerts vs. number of alerts";
         $xaxis_label = "Unique alerts";
         $yaxis_label = "Number of alerts";
				$data_pnt_cnt = GetUniqueDataSet(
					$xdata, $chart_type, $min_size, $criteria
				);
         break;
			default;
         print "WARNING: charttype \"$charttype\" is not supported. Returning.";
         return 0;
		}
		if( $data_pnt_cnt > 0 ){
			$number_array_elements = count($xdata);
			if( $debug_mode > 0 ){
				$TK = array (
					'chart_type', 'data_source', 'chart_interval',
					'element_start'
				);
				$DI = array();
				$DD = array();
				foreach( $TK as $val ){
					array_push($DD, $val);
					array_push($DI, $$val);
				}
				array_push($DD,'X-data Items');
				array_push($DI, $number_array_elements);
				DDT($DI,$DD,'Chart criteria', '', '', 1);
        echo "<H3>"._CHRTHANDLEPERIOD."...</H3>\n";
			}
			if( $chart_interval || $number_array_elements ){
				// Validity check
				if( $element_start >= $number_array_elements ){
					if( $debug_mode > 0 ){
						ErrorMessage("WARNING: i >= number_array_elements");
					}
					ErrorMessage(_ERRCHRTNODATAPOINTS);
					PrintBASESubFooter();
					return;
				}

				// From which element on should the "for"-loop start:
				if(
					ctype_digit($element_start) && $element_start > 0
					&& $element_start < $number_array_elements
				){
					$i = $element_start;
				}else{
					$i = 0;
				}
				if( $debug_mode > 0 ){
          print "i = $i<BR>";
          print "element_start = $element_start<BR>";
          print "number_array_elements = " . $number_array_elements . "<BR>";
          print "count(xdata) = " . count($xdata) . "<BR><BR>";
				}
				// set up array
				for( $j = 0; $i < $number_array_elements; $i++, $j++ ){
					// How many columns/elements do you want to see?
					if( $chart_interval > 0 ){
						if( $j >= $chart_interval ){
							break;
						}
					}
					if( $debug_mode > 1 ){
            print $i . ": " . $xdata[$i][0] . " - " . $xdata[$i][1] . "<BR>";
					}
					if( isset($xdata[$i][0]) ){ // define x-axis value:
						$chart_array [$j][0] = $xdata[$i][0];
					}else{
						$chart_array[$j][0] = '';
					}
					if( isset($xdata[$i][1]) ){ // define y-axis value:
						$chart_array [$j][1] = $xdata[$i][1];
					}else{
						$chart_array[$j][1] = 0;
					}
				} // for-loop
				if(
					($chart_style == "bar" || $chart_style == "line")
					&& count($chart_array) == 1
				){
        /* then there's is a bug in PEAR::Image_Graph; 
         * Cf. http://pear.php.net/bugs/bug.php?id=12763
         *     http://pear.php.net/bugs/7423
         *     https://pear.php.net/bugs/bug.php?id=16335
         * the following
         * appends one element, that does, of course, not really exist,
         * as a workaround: */
				if( $debug_mode > 0 ){
            print "WARNING: Workaround: Adding one dummy element, that does NOT really exist!<BR>\n";
				}
				$chart_array[1][0] = '';
				$chart_array[1][1] = 0;
			}
			// finally, set up xdata
			$xdata = $chart_array;
		} //  if ( $chart_interval || $number_array_elements) {
		if( $debug_mode > 0 ){
        print "count(xdata) = " . count($xdata) . "<BR>\n";
        // disabled because does not work as expected
        //echo "<H3>"._CHRTDUMP." $xaxis_label_inc)</H3>";
		}
		for( $i = 0; $i < count($xdata); $i++ ){
			if( $debug_mode > 0 ){
          echo $i." -- ".$xdata[$i][0]." - ".$xdata[$i][1]."<BR>";
			}
       // The following does not work as expected with PEAR::Image_Graph-0.7.2
       // Although as many pieces of data are added to the plot
       // as count($xdata) suggests, in the end are only those
       // bars (lines) displayed, that do NOT have an empty string
       // in $xdata[$i][0] (and even not a space!). I'm inclined to
       // consider this as one more bug of Image_Graph library.
       /*  
       // Apply the X-Axis label clean-up -- 
       // only write every N axis labels (erase the rest)
       if (($xaxis_label_inc != 0) && ( ($i % $xaxis_label_inc ) != 0 ))
       {
         $xdata[$i][0] = "";
       }
       */
		}
		if( $debug_mode > 0 ){
        echo "<H3>"._CHRTDRAW." ($width x $height)</H3>";
		}
		$et->Mark("Extracting data");
      echo '<CENTER>
            <TABLE BGCOLOR="#000000" CELLSPACING=0 CELLPADDING=2 BORDER=0 SUMMARY="table from base_graph_main.php">
            <TR>
            <TD>';

      $_SESSION['xdata'] = $xdata;
      echo "<CENTER>";
		if( $WorldMap ){
        echo "<A HREF=\"base_graph_display.php?";
        echo "&amp;pmargin0=$pmargin0&pmargin1=$pmargin1".
             "&amp;pmargin2=$pmargin2&pmargin3=$pmargin3".
             "&amp;title=".rawurlencode($user_chart_title." \n".$chart_title).
             "&amp;xaxis_label=".rawurlencode($xaxis_label).
             "&amp;yaxis_label=".rawurlencode($yaxis_label).
             //         "&amp;yaxis_scale=".rawurlencode($yaxis_scale).
             "&amp;yaxis_scale=" . $yaxis_scale .
             "&amp;rotate_xaxis_lbl=".rawurlencode($rotate_xaxis_lbl).
             "&amp;xaxis_grid=".$xaxis_grid.
             "&amp;yaxis_grid=".$yaxis_grid.
             "&amp;chart_type=".$chart_type.
             "&amp;style=".$chart_style."\">";

        echo "<IMG WIDTH=600 HEIGHT=300 SRC=\"base_graph_display.php?";
		}else{
        echo "<IMG SRC=\"base_graph_display.php?width=$width&amp;height=$height";
		}
      echo "&amp;pmargin0=$pmargin0&pmargin1=$pmargin1".
           "&amp;pmargin2=$pmargin2&pmargin3=$pmargin3".
           "&amp;title=".rawurlencode($user_chart_title." \n".$chart_title).
           "&amp;xaxis_label=".rawurlencode($xaxis_label).
           "&amp;yaxis_label=".rawurlencode($yaxis_label).
           //         "&amp;yaxis_scale=".rawurlencode($yaxis_scale).
           "&amp;yaxis_scale=" . $yaxis_scale .
           "&amp;rotate_xaxis_lbl=".rawurlencode($rotate_xaxis_lbl).
           "&amp;xaxis_grid=".$xaxis_grid.
           "&amp;yaxis_grid=".$yaxis_grid.
           "&amp;chart_type=".$chart_type.
           "&amp;style=".$chart_style."\">";
		if( $WorldMap ){
        echo "</A><BR>\n";
		}
      echo "</CENTER>";

	PrintFramedBoxFooter(1, 2);
	NLIO('<br/>', 2);
		if( $WorldMap ){
        echo '(click at the image or - after it has been reloaded - click at it for a second time to get a bigger size of it)<BR><BR>';
		}
      echo '</CENTER>';
			$et->Mark('Rendering graph.');
		}else{
			ErrorMessage(_ERRCHRTNODATAPOINTS);
		}
	}
	PrintBASESubFooter();
}else{ // Graphics Libs Check failed.
	error_log($EMPfx . 'Graphics Libs check failed.');
	HTTP_header('Location: base_main.php');
}
?>
