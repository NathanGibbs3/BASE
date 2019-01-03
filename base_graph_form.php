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
** Purpose: Displays form for graphing
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

  include_once ("$BASE_path/base_graph_common.php"); 



  echo '<FORM ACTION="base_graph_main.php" METHOD="post">';

  echo '<TABLE WIDTH="100%" BORDER="2" class="query" cellpadding="20" summary="Outer table">
          <TR>
           <TD COLSPAN=2>';
  echo '<TABLE WIDTH="100%" BORDER="1" SUMMARY="1st inner table"><TR>';
  //  echo '<B>'._CHARTTYPE.'</B>&nbsp;
  echo '<TD><B>What do you want to know:</B></TD><TD>
        <SELECT NAME="chart_type">
         <OPTION VALUE=" "  '. chk_select($chart_type, " ").'>'._CHARTTYPES.'
         <OPTION VALUE="' . CHARTTYPE_HOUR . '" ' . chk_select($chart_type, CHARTTYPE_HOUR).'>'._CHRTTYPEHOUR.'
         <OPTION VALUE="' . CHARTTYPE_DAY . '" ' . chk_select($chart_type, CHARTTYPE_DAY).'>'._CHRTTYPEDAY.'
         <!--<OPTION VALUE="' . CHARTTYPE_WEEK . '" ' . chk_select($chart_type, CHARTTYPE_WEEK).'>'._CHRTTYPEWEEK.'-->
         <OPTION VALUE="' . CHARTTYPE_MONTH . '" ' . chk_select($chart_type, CHARTTYPE_MONTH).'>'._CHRTTYPEMONTH.'
         <!--<OPTION VALUE="' . CHARTTYPE_YEAR . '" ' . chk_select($chart_type, CHARTTYPE_YEAR).'>'._CHRTTYPEYEAR.'-->
         <OPTION VALUE="' . CHARTTYPE_SRC_IP . '" ' . chk_select($chart_type, CHARTTYPE_SRC_IP).'>'._CHRTTYPESRCIP.'
         <OPTION VALUE="' . CHARTTYPE_DST_IP . '" ' . chk_select($chart_type, CHARTTYPE_DST_IP).'>'._CHRTTYPEDSTIP.'
         <OPTION VALUE="' . CHARTTYPE_SRC_TCP_PORT . '" ' . chk_select($chart_type, CHARTTYPE_SRC_TCP_PORT).'>'._CHRTTYPESRCPORT.'
         <OPTION VALUE="' . CHARTTYPE_DST_TCP_PORT . '" ' . chk_select($chart_type, CHARTTYPE_DST_TCP_PORT).'>'._CHRTTYPEDSTPORT.'
         <OPTION VALUE="' . CHARTTYPE_SRC_UDP_PORT . '" ' . chk_select($chart_type, CHARTTYPE_SRC_UDP_PORT).'>'._CHRTTYPESRCUDP.'
         <OPTION VALUE="' . CHARTTYPE_DST_UDP_PORT . '" ' . chk_select($chart_type, CHARTTYPE_DST_UDP_PORT).'>'._CHRTTYPEDSTUDP.'

         <OPTION VALUE="' . CHARTTYPE_SRC_COUNTRY . '" ' . chk_select($chart_type, CHARTTYPE_SRC_COUNTRY).'>'. 'Src. countries vs. number of alerts
         <OPTION VALUE="' . CHARTTYPE_SRC_COUNTRY_ON_MAP . '" ' . chk_select($chart_type, CHARTTYPE_SRC_COUNTRY_ON_MAP).'>'. 'Src. countries vs. number of alerts on a worldmap
         <OPTION VALUE="' . CHARTTYPE_DST_COUNTRY . '" ' . chk_select($chart_type, CHARTTYPE_DST_COUNTRY).'>'. 'Dst. countries vs. number of alerts
         <OPTION VALUE="' . CHARTTYPE_DST_COUNTRY_ON_MAP . '" ' . chk_select($chart_type, CHARTTYPE_DST_COUNTRY_ON_MAP).'>'. 'Dst. countries vs. number of alerts on a worldmap
         <OPTION VALUE="' . CHARTTYPE_SENSOR . '" ' . chk_select($chart_type, CHARTTYPE_SENSOR).'>'._CHRTTYPESENSOR.'
         <OPTION VALUE="' . CHARTTYPE_UNIQUE_SIGNATURE .'" ' . chk_select($chart_type, CHARTTYPE_UNIQUE_SIGNATURE).'>'. 'Unique alerts vs. number of alerts' . '
         <OPTION VALUE="' . CHARTTYPE_CLASSIFICATION . '" ' . chk_select($chart_type, CHARTTYPE_CLASSIFICATION).'>'._CHRTTYPESIG;
 

	echo '</SELECT>
	      </TD></TR>';
  
  
  //echo '&nbsp;&nbsp;<B>'._PLOTTYPE.'</B> &nbsp;&nbsp;
  echo '<TR><TD><B>How should it be displayed?</B></TD><TD>As&nbsp;
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="bar" '.chk_check($chart_style, "bar").'> '._TYPEBAR.' &nbsp;&nbsp;
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="line" '.chk_check($chart_style, "line").'> '._TYPELINE.' &nbsp;&nbsp;
            <INPUT TYPE="radio" NAME="chart_style"
                   VALUE="pie" '.chk_check($chart_style, "pie").'> '._TYPEPIE.' ';
  echo '</TD></TR>';

  //  echo '&nbsp;&nbsp;<B>'._CHARTSIZE.'</B>
  echo '<TD><B>... with a size of:</B></TD><TD>(width x height)
        &nbsp;<INPUT TYPE="text" NAME="width" SIZE=4 VALUE="'.$width.'">
        &nbsp;<B>x</B>
        &nbsp;<INPUT TYPE="text" NAME="height" SIZE=4 VALUE="'.$height.'">
        &nbsp;&nbsp;<BR></TD></TR>';

// not implemented:
/*
  //echo '&nbsp;&nbsp;<B>'._PLOTMARGINS.'</B>
  echo '<TR><TD><B>... and with margins of:</B></TD><TD>(left x right x top x bottom)<BR>
        &nbsp;<INPUT TYPE="text" NAME="pmargin0" SIZE=4 VALUE="'.$pmargin0.'">
        &nbsp;<B>x</B>
        &nbsp;<INPUT TYPE="text" NAME="pmargin1" SIZE=4 VALUE="'.$pmargin1.'">
        &nbsp;<B>x</B>
        &nbsp;<INPUT TYPE="text" NAME="pmargin2" SIZE=4 VALUE="'.$pmargin2.'">
        &nbsp;<B>x</B>
        &nbsp;<INPUT TYPE="text" NAME="pmargin3" SIZE=4 VALUE="'.$pmargin3.'">
        &nbsp;&nbsp;<BR></TD></TR>';
*/


    echo '<TR><TD><B>Do you want to know<BR>the data just of a<BR>particular time frame?</B>&nbsp;(optional)</TD><TD>';
    echo '<b>'._CHRTBEGIN.'</B>&nbsp;
        <SELECT NAME="chart_begin_hour">
         <OPTION VALUE=" "  '.chk_select($chart_begin_hour, " ").'>'._CHARTHOUR."\n";
        for ( $i = 0; $i <= 23; $i++ )
            echo "<OPTION VALUE=\"$i\" ".chk_select($chart_begin_hour, $i)." >$i\n";

  echo '</SELECT>
        <SELECT NAME="chart_begin_day">
         <OPTION VALUE=" "  '.chk_select($chart_begin_day, " ").'>'._CHARTDAY."\n";
        for ( $i = 1; $i <= 31; $i++ )
            echo "<OPTION VALUE=\"$i\" ".chk_select($chart_begin_day, $i).">$i\n";

  echo '</SELECT>
        <SELECT NAME="chart_begin_month">
         <OPTION VALUE=" "  '.chk_select($chart_begin_month, " ").'>'._CHARTMONTH.'
         <OPTION VALUE="01" '.chk_select($chart_begin_month, "01").'>'._JANUARY.'
         <OPTION VALUE="02" '.chk_select($chart_begin_month, "02").'>'._FEBRUARY.'
         <OPTION VALUE="03" '.chk_select($chart_begin_month, "03").'>'._MARCH.'
         <OPTION VALUE="04" '.chk_select($chart_begin_month, "04").'>'._APRIL.'
         <OPTION VALUE="05" '.chk_select($chart_begin_month, "05").'>'._MAY.'
         <OPTION VALUE="06" '.chk_select($chart_begin_month, "06").'>'._JUNE.'
         <OPTION VALUE="07" '.chk_select($chart_begin_month, "07").'>'._JULY.'
         <OPTION VALUE="08" '.chk_select($chart_begin_month, "08").'>'._AUGUST.'
         <OPTION VALUE="09" '.chk_select($chart_begin_month, "09").'>'._SEPTEMBER.'
         <OPTION VALUE="10" '.chk_select($chart_begin_month, "10").'>'._OCTOBER.'
         <OPTION VALUE="11" '.chk_select($chart_begin_month, "11").'>'._NOVEMBER.'
         <OPTION VALUE="12" '.chk_select($chart_begin_month, "12").'>'._DECEMBER.'
        </SELECT>
        <SELECT NAME="chart_begin_year">'.
        dispYearOptions($chart_begin_year)
        .'</SELECT>';

  echo '<br><b>'._CHRTEND.'</B>&nbsp;&nbsp;&nbsp;&nbsp;
        <SELECT NAME="chart_end_hour">
         <OPTION VALUE=" "  '.chk_select($chart_end_hour, " ").'>'._CHARTHOUR."\n";
        for ( $i = 0; $i <= 23; $i++ )
           echo "<OPTION VALUE=$i ".chk_select($chart_end_hour, $i).">$i\n";

  echo '</SELECT>
        <SELECT NAME="chart_end_day">
         <OPTION VALUE=" "  '.chk_select($chart_end_day, " ").'>'._CHARTDAY."\n";
        for ( $i = 1; $i <= 31; $i++ )
           echo "<OPTION VALUE=$i ".chk_select($chart_end_day, $i).">$i\n";

  echo '</SELECT>
        <SELECT NAME="chart_end_month">
         <OPTION VALUE=" "  '.chk_select($chart_end_month, " ").'>'._CHARTMONTH.'
         <OPTION VALUE="01" '.chk_select($chart_end_month, "01").'>'._JANUARY.'
         <OPTION VALUE="02" '.chk_select($chart_end_month, "02").'>'._FEBRUARY.'
         <OPTION VALUE="03" '.chk_select($chart_end_month, "03").'>'._MARCH.'
         <OPTION VALUE="04" '.chk_select($chart_end_month, "04").'>'._APRIL.'
         <OPTION VALUE="05" '.chk_select($chart_end_month, "05").'>'._MAY.'
         <OPTION VALUE="06" '.chk_select($chart_end_month, "06").'>'._JUNE.'
         <OPTION VALUE="07" '.chk_select($chart_end_month, "07").'>'._JULY.'
         <OPTION VALUE="08" '.chk_select($chart_end_month, "08").'>'._AUGUST.'
         <OPTION VALUE="09" '.chk_select($chart_end_month, "09").'>'._SEPTEMBER.'
         <OPTION VALUE="10" '.chk_select($chart_end_month, "10").'>'._OCTOBER.'
         <OPTION VALUE="11" '.chk_select($chart_end_month, "11").'>'._NOVEMBER.'
         <OPTION VALUE="12" '.chk_select($chart_end_month, "12").'>'._DECEMBER.'
        </SELECT>
        <SELECT NAME="chart_end_year">'.
        dispYearOptions($chart_end_year)
        .'</SELECT></TD></TR>';
      
	echo '<TR><TD><B>'._CHARTTITLE.'</B></TD><TD>
		<INPUT TYPE="text" NAME="user_chart_title" SIZE="35" VALUE="'.$user_chart_title.'"></TD></TR>';
		
		
		
	
  //  echo '&nbsp;&nbsp;<b>'._CHARTPERIOD.'</B>&nbsp;
  echo '<TR><TD><B>How many columns or elements do you want to see?</B>&nbsp;</TD><TD>';
  echo '<SELECT NAME="chart_interval">'.
	'<OPTION VALUE="0"  '.chk_select($chart_interval, "0").'>{all of them}' . /* _PERIODNO. */
  '<OPTION VALUE="5"  '.chk_select($chart_interval, "5").'> 5 elements' .  
	'<OPTION VALUE="10" '.chk_select($chart_interval, "10").'>10 elements' . /* _PERIODWEEK. */
	'<OPTION VALUE="15" '.chk_select($chart_interval, "15").'>15 elements' . /* _PERIODDAY. */
	'<OPTION VALUE="20" '.chk_select($chart_interval, "20").'>20 elements' . /* _PERIOD168. */
  '<OPTION VALUE="25" '.chk_select($chart_interval, "25").'>25 elements' .
  '<OPTION VALUE="30" '.chk_select($chart_interval, "30").'>30 elements' .
	'</SELECT><BR></TD></TR>';

	echo '<TR><TD><B>... and starting from which element on?</B>&nbsp;</TD>' .
       '<TD>From element no.&nbsp;<INPUT TYPE="text" NAME="element_start" SIZE="10" VALUE="'.$element_start.'"></TD></TR>';


  // submit button
  echo '<TR align=middle><TD colspan="2">';
  echo '<BR><BR><div class="center"><INPUT TYPE="submit" NAME="submit" VALUE="'._GRAPHALERTS.'" align=center></div><BR><BR>';
	echo '</TR></TABLE>';





  echo '<TR><TD COLSPAN=2>
  <ul id="zMenu">
    <li>'._AXISCONTROLS.':<BR>';
  echo '<TABLE WIDTH="100%" BORDER="1" SUMMARY="2nd inner table">
        <TR>
         <TD ALIGN="CENTER" WIDTH="50%"><B>'._CHRTX.'</B></TD>
         <TD ALIGN="CENTER" WIDTH="50%"><B>'._CHRTY.'</B></TD>
        </TR>
        <TR>
         <TD>
           <B>'._CHRTDS.'</B> &nbsp;
           <SELECT NAME="data_source">
           <OPTION VALUE=" " '.chk_select($data_source, " ").'>{ data source (AG) }';

           $temp_sql = "SELECT ag_id, ag_name FROM acid_ag";
           $tmp_result = $db->baseExecute($temp_sql);
           if ( ( $tmp_result ) )
           {
              while ( $myrow = $tmp_result->baseFetchRow() )
                echo '<OPTION VALUE="'.$myrow[0].'" '.chk_select($data_source, $myrow[0]).'>'.
                     '['.$myrow[0].'] '.$myrow[1];

              $tmp_result->baseFreeRows();
           }

           echo '</SELECT><BR>'.
                 '<B>'._CHRTMINTRESH.':</B>
                 <INPUT TYPE="text" NAME="min_size" SIZE="5" VALUE='.$min_size.'>
                 &nbsp;&nbsp;
                 <BR>
                 <INPUT TYPE="checkbox" NAME="rotate_xaxis_lbl" VALUE="1" '.
                   chk_check($rotate_xaxis_lbl, "1").'>
                 &nbsp;
                 <B>'._CHRTROTAXISLABEL.'</B><BR>
                 <INPUT TYPE="checkbox" NAME="xaxis_grid" VALUE="1"  '.
                   chk_check($xaxis_grid, "1").'>
                  &nbsp;
                 <B>'._CHRTSHOWX.'</B><BR>
                 <!--
                 Disabled because it unexpectedly prevents displaying
                 the whole bar instead of just suppressing the label... 
                 -->
                 <!--
                 <B>'._CHRTDISPLABELX.'
                 <INPUT TYPE="text" NAME="xaxis_label_inc" SIZE=4 VALUE='.$xaxis_label_inc.'>
                 &nbsp; '._CHRTDATAPOINTS.'
                 -->
                 <INPUT TYPE="hidden" NAME="xaxis_label_inc" VALUE="1"><BR> 
         </TD>


         <TD VALIGN="top">
         <!--
         Logarithmic y-axis temporarily disabled, as the bars seem
         to be displayed in a wrong way, although the y-axis grid lines
         look correct
         -->
         <!--
           <INPUT TYPE="checkbox" NAME="yaxis_scale" VALUE="1" '.
             chk_check($yaxis_scale, "1").'>&nbsp;
           <B>'._CHRTYLOG.'</B>
           <BR>
         --> 
           <INPUT TYPE="hidden" NAME="yaxis_scale" VALUE="0">
         
           

           <INPUT TYPE="checkbox" NAME="yaxis_grid" VALUE="1"  '.
             chk_check($yaxis_grid, "1").'>&nbsp;
           <B>'._CHRTYGRID.'</B>
         </TD>
        </TR>
        </TABLE>
	</ul></li>

        </TD></TR>
     </TABLE>';

  echo '</FORM><P><HR>';
echo '
 <!-- ************ JavaScript for Hiding Details ******************** -->
 <script type="text/javascript">
// <![CDATA[
function loopElements(el,level){
        for(var i=0;i<el.childNodes.length;i++){
                //just want LI nodes:
                if(el.childNodes[i] && el.childNodes[i]["tagName"] && el.childNodes[i].tagName.toLowerCase() == "li"){
                        //give LI node a className
                        el.childNodes[i].className = "zMenu"+level
                        //Look for the A and if it has child elements (another UL tag)
                        childs = el.childNodes[i].childNodes
                        for(var j=0;j<childs.length;j++){
                                temp = childs[j]
                                if(temp && temp["tagName"]){
                                        if(temp.tagName.toLowerCase() == "a"){
                                                //found the A tag - set class
                                                temp.className = "zMenu"+level
                                                //adding click event
                                                temp.onclick=showHide;
                                        }else if(temp.tagName.toLowerCase() == "ul"){
                                                //Hide sublevels
                                                temp.style.display = "none"
                                                //Set class
                                                temp.className= "zMenu"+level
                                                //Recursive - calling self with new found element - go all the way through
                                                loopElements(temp,level +1)
                                        }
                                }
                        }
                }
        }
}

var menu = document.getElementById("zMenu") //get menu div
menu.className="zMenu"+0 //Set class to top level
loopElements(menu,0) //function call

function showHide(){
        //from the LI tag check for UL tags:
        el = this.parentNode
        //Loop for UL tags:
        for(var i=0;i<el.childNodes.length;i++){
                temp = el.childNodes[i]
                if(temp && temp["tagName"] && temp.tagName.toLowerCase() == "ul"){
                        //Check status:
                        if(temp.style.display=="none"){
                                temp.style.display = ""
                        }else{
                                temp.style.display = "none"
                        }
                }
        }
        return false
}
// ]]>
</script>
';

/*
vim:shiftwidth=2:tabstop=2:expandtab
*/

?>


