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

  include ("base_conf.php");
  include ("$BASE_path/includes/base_constants.inc.php");
  include ("$BASE_path/includes/base_include.inc.php");
  include_once ("$BASE_path/base_db_common.php");
  include_once ("$BASE_path/base_common.php");
  include_once ("$BASE_path/base_graph_common.php");



  function check_worldmap()
  {
    GLOBAL $debug_mode;


    $ok = 0;
    $php_path = ini_get('include_path');
    $php_path_array = explode(':', $php_path);
    if ($debug_mode > 0)
    {
      print "Where is the worldmap?<BR>\n";
    }
    foreach($php_path_array as $single_path)
    {
      $where_is_it = "$single_path/Image/Graph/Images/Maps/world_map6.png";
      if ($debug_mode > 0)
      {
        print "&quot;" . $where_is_it . "&quot;<BR>\n";
      }
      if (file_exists($where_is_it))
      // then we ASSUME, that this is the correct worldmap file. Not necessarily true, though. A simplification, therefore.
      {
        if (is_readable($where_is_it))
        {
    		  $where_is_it2 = "$single_path/Image/Graph/Images/Maps/world_map6.txt";
    		  if (file_exists($where_is_it2))
          {
            if (is_readable($where_is_it2))
            {
    			    $ok = 1;
    			    break;
            }
            else
            {
              ErrorMessage("ERROR: $where_is_it2 does exist, but it is NOT READABLE.<BR>\n");
            }
    		  }
    		  else
    		  {
            ErrorMessage("ERROR: $where_is_it could be found, but $where_is_it2 does NOT exist.<BR>\n");

            $rv = ini_get("safe_mode");
            if ($rv == 1)
            {
              ErrorMessage("In &quot;safe_mode&quot; both world_map6.png and world_map6.txt must be owned by the user under which the web server is running.<BR>\n");
            }
          }
        }
        else
        {
          ErrorMessage("ERROR: $where_is_it does exist, but it is NOT READABLE.<BR>\n");
        }
    	}
    }
    
    if ($ok != 1)
    {
      ErrorMessage("ERROR: The worldmap function is not available, because world_map6.png and world_map6.txt could not be found. Go into the \"PEAR directory\", as can be found by \"pear config-show\", and then into the subdirectory Image/Graph/Images/Maps/. This is the location where world_map6.png and world_map6.txt must be installed.<BR>\n");
      $rv = ini_get("safe_mode");
      if ($rv == 1)
      {
        ErrorMessage("In &quot;safe_mode&quot; both world_map6.png and world_map6.txt must be owned by the user under which the web server is running.<BR>\n");
      }
      return 0;
    }

    return 1;
  }



  ($debug_time_mode >= 1) ? $et = new EventTiming($debug_time_mode) : '';
  $cs = new CriteriaState("base_stat_alerts.php");
  $cs->ReadState();
  
  $submit = ImportHTTPVar("submit", VAR_ALPHA | VAR_SPACE);

  /**
  * Set default values if the submit button hasn't been pressed
  */
  if ( $submit == "" )
  {
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
  } else {
  /**
  * Otherwise, retrieve the data from the submit and
  * store it in local variables for use later
  */
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

   // Check role out and redirect if needed -- Kevin
  $roleneeded = 10000;
  $BUser = new BaseUser();
  if (($BUser->hasRole($roleneeded) == 0) && ($Use_Auth_System == 1))
    base_header("Location: ". $BASE_urlpath . "/index.php");

  $page_title = _GRAPHALERTDATA;
  PrintBASESubHeader($page_title, $page_title, $cs->GetBackLink(), $refresh_all_pages);

  // Check if Image_Graph install is ok -- Alejandro
  VerifyGraphingLib();  

  /* Connect to the Alert database */
  $db = NewBASEDBConnection($DBlib_path, $DBtype);
  $db->baseDBConnect($db_connect_method,
                     $alert_dbname, $alert_host, $alert_port, $alert_user, $alert_password);

  if ( $event_cache_auto_update == 1 )  UpdateAlertCache($db);

  if ( ini_get("safe_mode") != true )
     set_time_limit($max_script_runtime);

  include("$BASE_path/base_graph_form.php");

  $data_pnt_cnt = 0;
  /* Error Conditions */
  if ( $submit != "" && $chart_type == " " )
     echo '<B>'._ERRCHRTNOTYPE.'</B>.';

  /* Calculate the data set */
  else if ($submit != "")
  {
     if ( $data_source == " " )
     {
        ErrorMessage(_ERRNOAGSPEC);
        $data_source = NULL;
     }

     unset($xdata);
     unset($xlabel);

     if ( $debug_mode > 1 ) {
       echo "<H3>"._CHRTDATAIMPORT."...</H3>";
     }

     /* Building Criteria */
     $time_constraint = ProcessChartTimeConstraint($chart_begin_hour, 
                                                   $chart_begin_day, 
                                                   $chart_begin_month, 
                                                   $chart_begin_year,
                                                   $chart_end_hour,  
                                                   $chart_end_day,  
                                                   $chart_end_month, 
                                                   $chart_end_year );

     $criteria = array(2);
     if (!empty($data_source))
     {
        $criteria[0] = "LEFT JOIN acid_ag_alert ".
                      "ON (acid_event.sid=acid_ag_alert.ag_sid AND acid_event.cid=acid_ag_alert.ag_cid) ";
        $criteria[1] = "acid_ag_alert.ag_id = $data_source";

        if (!empty($time_constraint))
           $criteria[1] = $criteria[1].$time_constraint; 
     }
     else
     {
        $criteria[0] = "";
        // $criteria[1] = "acid_event.sid > 0 ".$time_constraint;

        if (empty($time_constraint))
          $criteria[1] = " 1 = 1 ";
        else
          $criteria[1] = " 1 = 1 " . $time_constraint;
     }

     if ( $debug_mode > 0 ) 
     {
       echo "<H3>Chart criteria</H3><PRE>";
       print_r($criteria);
       echo "</PRE>";
     }

   switch ($chart_type)
   {
     case CHARTTYPE_HOUR:
       // hours vs num of alerts
     case CHARTTYPE_DAY:
       // days vs num of alerts
     case CHARTTYPE_WEEK:
       // weeks vs num of alerts
     case CHARTTYPE_MONTH:
       // months vs num of alerts
     case CHARTTYPE_YEAR:
       // years vs num of alerts
         {
            $chart_title = _CHRTTIMEVNUMBER;
            $xaxis_label = _CHRTTIME;
            $yaxis_label = _CHRTALERTOCCUR;
            $data_pnt_cnt = GetTimeDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            /* Any kinds of special characters, like slashes, two points
             * and so on seem to NOT work with Image_Graph */
            //$chart_title = $chart_title."\n ( ".$xdata[0][0]." - ".$xdata[count($xdata)-1][0]." )";      
            //$xaxis_label .= " from ". $xdata[0][0] . " to " . $xdata[count($xdata)-1][0] . " ";
            break;
         }
     case CHARTTYPE_SRC_IP:  // Src. IP vs. Num Alerts
         {
            $chart_title = _CHRTSIPNUMBER;
            $xaxis_label = _CHRTSIP;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetIPDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_DST_IP:  // Dst. IP vs. Num Alerts
         {
            $chart_title = _CHRTDIPALERTS;
            $xaxis_label = _CHRTDIP;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetIPDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_DST_UDP_PORT:  // UDP Port vs. Num Alerts 
         {
            $chart_title = _CHRTUDPPORTNUMBER;
            $xaxis_label = _CHRTDUDPPORT;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetPortDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_SRC_UDP_PORT:  // UDP Port vs. Num Alerts 
         {
            $chart_title = _CHRTSUDPPORTNUMBER;
            $xaxis_label = _CHRTSUDPPORT;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetPortDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_DST_TCP_PORT:  // TCP Port vs. Num Alerts 
         {
            $chart_title = _CHRTPORTDESTNUMBER;
            $xaxis_label = _CHRTPORTDEST;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetPortDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_SRC_TCP_PORT:  // TCP Port vs. Num Alerts 
         {
            $chart_title = _CHRTPORTSRCNUMBER;
            $xaxis_label = _CHRTPORTSRC;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetPortDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_CLASSIFICATION:  // Classification vs. Num Alerts 
         {
            $chart_title = _CHRTSIGNUMBER;
            $xaxis_label = _CHRTCLASS;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetClassificationDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }
     case CHARTTYPE_SENSOR:  // Sensor vs. Num Alerts 
         {
            $chart_title = _CHRTSENSORNUMBER;
            $xaxis_label = _SENSOR;
            $yaxis_label = _CHRTALERTOCCUR;

            $data_pnt_cnt = GetSensorDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
            break;
         }

     case CHARTTYPE_SRC_COUNTRY: // Src Countries vs. Num Alerts
     case CHARTTYPE_SRC_COUNTRY_ON_MAP: // dto., but on worldmap
       {
         if ($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP)
         {
           if (!check_worldmap())
           {
             return 0;
           }
         }
         $chart_title = "Countries of origin vs. number of alerts";
         $xaxis_label = "Src countries";
         $yaxis_label = "Number of alerts";

         $data_pnt_cnt = GetCountryDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
         break;
       }
     case CHARTTYPE_DST_COUNTRY: // Dst Countries vs. Num Alerts
     case CHARTTYPE_DST_COUNTRY_ON_MAP: // dto., but on worldmap
       {
         if ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP)
         {
           if (!check_worldmap())
           {
             return 0;
           }
         }
         $chart_title = "Destination Countries vs. number of alerts";
         $xaxis_label = "Dst countries";
         $yaxis_label = "Number of alerts";

         $data_pnt_cnt = GetCountryDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
         break;
       }
     case CHARTTYPE_UNIQUE_SIGNATURE: // Unique alerts vs. number of alerts
       {
         $chart_title = "Unique alerts vs. number of alerts";
         $xaxis_label = "Unique alerts";
         $yaxis_label = "Number of alerts";

         $data_pnt_cnt = GetUniqueDataSet($xdata, $chart_type, $data_source, $min_size, $criteria);
         break;
       }
     default:
       {
         print "WARNING: charttype \"$charttype\" is not supported. Returning.";
         return 0;
       }
    }

    


    if ( $data_pnt_cnt > 0 )
    {
      $number_array_elements = count($xdata);
      if ( $debug_mode > 0 )
      {
        echo "chart_type = $chart_type<BR>
              data_source = $data_source<BR>
              chart_interval = $chart_interval<BR>
              element_start  = $element_start<BR>
              count(\$xdata) = $number_array_elements<BR>\n";
        echo "<H3>"._CHRTHANDLEPERIOD."...</H3>\n";
      }

      if ( $chart_interval || $number_array_elements) {

        // quick validity check 
        if ($element_start >= $number_array_elements)
        {
          if ($debug_mode > 0)
          {
            print "WARNING: i >= number_array_elements<BR>";
          }

          ErrorMessage(_ERRCHRTNODATAPOINTS);

          ($debug_time_mode >= 1) ? $et->PrintTiming() : '';

          PrintBASESubFooter();
          echo "</body>\r\n</html>";
          return;
        }


        // From which element on should the "for"-loop start:
        if (
             (ctype_digit($element_start)) && 
             ($element_start > 0) && 
             ($element_start < $number_array_elements)
           )
        {
          $i = $element_start;
        }
        else
        {
          $i = 0;
        }

        if ($debug_mode > 0)
        {
          print "i = $i<BR>";
          print "element_start = $element_start<BR>";
          print "number_array_elements = " . $number_array_elements . "<BR>";
          print "count(xdata) = " . count($xdata) . "<BR><BR>";
        }

        // set up array
        for ($j = 0; 
             $i < $number_array_elements;
             $i++, $j++) 
        {
          // "How many columns/elements do you want to see?"
          if ($chart_interval > 0)
          {
            if ($j >= $chart_interval)
            {
              break;
            }
          }

          if ($debug_mode > 1)
          {
            print $i . ": " . $xdata[$i][0] . " - " . $xdata[$i][1] . "<BR>";
          }


          // define x-axis value:
          if (isset($xdata[$i][0]))
          { 
            $chart_array [$j][0] = $xdata[$i][0];
          }
          else
          {
            $chart_array[$j][0] = "";
          }

          // define y-axis value:
          if (isset($xdata[$i][1]))
          {
            $chart_array [$j][1] = $xdata[$i][1];
          }
          else
          {
            $chart_array[$j][1] = 0;
          }
        } // for-loop




        if (
             (
               ($chart_style == "bar") ||
               ($chart_style == "line")
             ) && 
             (count($chart_array) == 1)
           )
        /* then there's is a bug in PEAR::Image_Graph; 
         * Cf. http://pear.php.net/bugs/bug.php?id=12763
         *     http://pear.php.net/bugs/7423
         * the following
         * appends one element, that does, of course, not really exist,
         * as a workaround: */
        {
          if ($debug_mode > 0)
          {
            print "WARNING: Workaround: Adding one dummy element, that does NOT really exist!<BR>\n";
          }

          $chart_array[1][0] = "";
          $chart_array[1][1] = 0;          
        }

        // finally, set up xdata
        $xdata = $chart_array;     
      } //  if ( $chart_interval || $number_array_elements) {

      if ( $debug_mode > 0 )     
      {
        print "count(xdata) = " . count($xdata) . "<BR>\n";
        // disabled because does not work as expected
        //echo "<H3>"._CHRTDUMP." $xaxis_label_inc)</H3>";
      }

      for ( $i = 0; $i < count($xdata); $i++)
      {
        if ( $debug_mode > 0 )
        {
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
     

      if ( $debug_mode > 0 ) 
      {
        echo "<H3>"._CHRTDRAW." ($width x $height)</H3>";
      }

      ($debug_time_mode >= 1) ? $et->Mark("Extracting data") : '';
      echo '<CENTER>
            <TABLE BGCOLOR="#000000" CELLSPACING=0 CELLPADDING=2 BORDER=0 SUMMARY="table from base_graph_main.php">
            <TR>
            <TD>';

      $_SESSION['xdata'] = $xdata;
      echo "<CENTER>";
      if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
      {
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
      }
      else
      {
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
          
      if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
      {
        echo "</A><BR>\n";
      }
      echo "</CENTER>";

      echo '</TD>
            </TR>
            </TABLE>
            <BR>';
      if (($chart_type == CHARTTYPE_SRC_COUNTRY_ON_MAP) || ($chart_type == CHARTTYPE_DST_COUNTRY_ON_MAP))
      {
        echo '(click at the image or - after it has been reloaded - click at it for a second time to get a bigger size of it)<BR><BR>';
      }
      echo '</CENTER>';
      
      ($debug_time_mode >= 1) ? $et->Mark("Rendering graph") : '';
    }
    else
       ErrorMessage(_ERRCHRTNODATAPOINTS);
  }

  ($debug_time_mode >= 1) ? $et->PrintTiming() : '';

  PrintBASESubFooter();
  echo "</body>\r\n</html>";

// vim:shiftwidth=2:tabstop=2:expandtab 
?>

