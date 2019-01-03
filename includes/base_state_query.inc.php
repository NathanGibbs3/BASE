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
** Purpose: manages the necessary state information for
**          query results 
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/base_common.php");
include_once("$BASE_path/includes/base_db.inc.php");
include_once("$BASE_path/includes/base_constants.inc.php");
include_once("$BASE_path/includes/base_action.inc.php");
// include_once("$BASE_path/includes/base_capabilities.php"); //Commented out by Kevin for testing

class QueryState
{
  var $canned_query_list = NULL;
  var $num_result_rows = -1;
  var $current_canned_query = "";
  var $current_sort_order = "";
  var $current_view = -1;
  var $show_rows_on_screen = -1;
  var $valid_action_list = NULL;
  var $action;
  var $valid_action_op_list = NULL;
  var $action_arg;
  var $action_lst;
  var $action_chk_lst = NULL;
  var $action_sql;

  function QueryState()
  {
    $this->ReadState();

    if ( $this->num_result_rows == "" )
       $this->num_result_rows = -1;

    if ( $this->current_view == "" )
       $this->current_view = -1;
  }

  function AddCannedQuery($caller, $caller_num, $caller_desc, $caller_sort)
  {
    $this->canned_query_list [$caller] = array($caller_num, $caller_desc, $caller_sort);
  }

  function PrintCannedQueryList()
  {
    echo "<BR><B>"._VALIDCANNED."</B>\n<PRE>\n";
    print_r($this->canned_query_list);
    echo "</PRE>\n";
  }

  function isCannedQuery()
  {
    return ( $this->current_canned_query != ""); 
  }

  /* returns the name of the current canned query (e.g. "last_tcp") */
  function GetCurrentCannedQuery()
  {
    return $this->current_canned_query;
  }

  function GetCurrentCannedQueryCnt()
  {
    return $this->canned_query_list[$this->current_canned_query][0];
  }

  function GetCurrentCannedQueryDesc()
  {
    return $this->canned_query_list[$this->current_canned_query][0]." ".
           $this->canned_query_list[$this->current_canned_query][1];
  }

  function GetCurrentCannedQuerySort()
  {
    if ( $this->isCannedQuery() )
      return $this->canned_query_list[$this->current_canned_query][2];
    else
      return "";
  }

  function isValidCannedQuery($potential_caller)
  {
    if ( $this->canned_query_list == NULL )
       return false;

    return in_array($potential_caller, array_keys($this->canned_query_list));
  }

  function GetCurrentView()
  {
    return $this->current_view;
  }

  function GetCurrentSort()
  {
    return $this->current_sort_order;
  }

  /* returns the number of rows to display for a single screen of the
   * query results
   */
  function GetDisplayRowCnt()
  {
    return $this->show_rows_on_screen;
  }

  function AddValidAction($action)
  {
      if (($action == "archive_alert" || $action == "archive_alert2") && isset($_COOKIE['archive']) && $_COOKIE['archive'] == 1)
      {
        // We do nothing here because we are looking at the archive tables
        // We do not want to add the archive actions to this list -- Kevin
      } else {
        $this->valid_action_list[ count($this->valid_action_list) ] = $action;
      }
  }

  function AddValidActionOp($action_op)
  {
     $this->valid_action_op_list[ count($this->valid_action_op_list) ] = $action_op;
  }

  function SetActionSQL($sql)
  {
     $this->action_sql = $sql;
  }

  function RunAction($submit, $which_page, $db)
  {
    GLOBAL $show_rows;

    ActOnSelectedAlerts($this->action, $this->valid_action_list, 
                        $submit, 
                        $this->valid_action_op_list, $this->action_arg,
                        $which_page,
                        $this->action_chk_lst, $this->action_lst,
                        $show_rows, $this->num_result_rows,
                        $this->action_sql, $this->current_canned_query,
                        $db );
  }

  function GetNumResultRows($cnt_sql = "", $db = NULL)
  {
     if ( !($this->isCannedQuery()) && ($this->num_result_rows == -1) )
     {
        $this->current_view = 0;
        $result = $db->baseExecute($cnt_sql);
        if ( $result )
        {
           $rows = $result->baseFetchRow();
           $this->num_result_rows = $rows[0];
           $result->baseFreeRows();
        }
        else
        {
           $this->num_result_rows = 0;
        }
      }
      else
      { 
         if ( $this->isValidCannedQuery($this->current_canned_query) )
         {
            reset($this->canned_query_list);
            while ( $tmp_canned = each ($this->canned_query_list) )
            {
               if ( $this->current_canned_query == $tmp_canned["key"] )
               {
                  $this->current_view = 0;
                  $this->num_result_rows = $tmp_canned["value"][0];
               }
            } 
         }
      } 
  }

  function MoveView($submit)
  {
    if ( is_numeric($submit) )
      $this->current_view = $submit;
  }

  function ExecuteOutputQuery($sql, $db)
  {
     GLOBAL $show_rows;

     if ( $this->isCannedQuery() )
     {
        $this->show_rows_on_screen = $this->GetCurrentCannedQueryCnt();
        return $db->baseExecute($sql, 0, 
                                $this->show_rows_on_screen);
     }
     else
     {
        $this->show_rows_on_screen = $show_rows;
        return $db->baseExecute($sql, ($this->current_view * $show_rows), 
                                $show_rows); 
     }
  }

  function PrintResultCnt()
  {
     GLOBAL $show_rows;

     if ( $this->num_result_rows != 0 )
     {
        if ( $this->isCannedQuery() )
        {
           echo "<div style='text-align:center;margin:auto'>"._DISPLAYING." ".$this->GetCurrentCannedQueryDesc().
                "</div><BR>\n";
        }
        else      
        {  
           printf("<div style='text-align:center;margin:auto'>"._DISPLAYINGTOTAL.
                  "</div><BR>\n", 
                  ($this->current_view * $show_rows)+1, 
                  (($this->current_view * $show_rows) + $show_rows-1) < $this->num_result_rows ? 
                  (($this->current_view * $show_rows) + $show_rows) : $this->num_result_rows, 
                  $this->num_result_rows);
        }
     }
     else
        printf("<P><B>"._NOALERTS."</B><P>\n");
  }

  function PrintBrowseButtons()
  {
    GLOBAL $show_rows, $max_scroll_buttons;

    /* Don't print browsing buttons for canned query */
    if ( $this->isCannedQuery() )
       return;

    if ( ($this->num_result_rows > 0) && ($this->num_result_rows > $show_rows) )
    {
       echo "<!-- Query Result Browsing Buttons -->\n".
            "<P><CENTER>\n".
            "<TABLE BORDER=1>\n".
            "   <TR><TD ALIGN=CENTER>"._QUERYRESULTS."<BR>&nbsp\n";

     $tmp_num_views = ($this->num_result_rows / $show_rows);
     $tmp_top = $tmp_bottom = $max_scroll_buttons / 2;

     if ( ($this->current_view - ($max_scroll_buttons/2)) >= 0 )
        $tmp_bottom = $this->current_view - $max_scroll_buttons/2;
     else
        $tmp_bottom = 0;

     if ( ($this->current_view + ($max_scroll_buttons/2)) <= $tmp_num_views )
        $tmp_top = $this->current_view + $max_scroll_buttons/2;
     else
        $tmp_top = $tmp_num_views;

     /* Show a '<<' symbol of have scrolled beyond the 0 view */
     if ( $tmp_bottom != 0 )
        echo ' << ';

     for ( $i = $tmp_bottom; $i < $tmp_top; $i++)
     {
         if ( $i != $this->current_view )
            echo '<INPUT TYPE="submit" NAME="submit" VALUE="'.$i.'">'."\n";
         else
            echo '['.$i.'] '."\n";
     }  
    
     /* Show a '>>' symbol if last view is not visible */
     if ( ($tmp_top) < $tmp_num_views )
        echo ' >> ';

     echo "  </TD></TR>\n</TABLE>\n</CENTER>\n\n";
   }
  }

  function PrintAlertActionButtons()
  { 
    if ( $this->valid_action_list == NULL )
       return;

    echo "\n\n<!-- Alert Action Buttons -->\n". 
         "<CENTER>\n".
         " <TABLE BORDER=1>\n".
         "  <TR>\n".
         "   <TD ALIGN=CENTER>"._ACTION."<BR>\n".
         "\n".   
         "    <SELECT NAME=\"action\">\n".
         '      <OPTION VALUE=" "         '.chk_select($this->action," ").'>'._DISPACTION."\n";
     
    reset($this->valid_action_list);
    while( $current_action = each($this->valid_action_list) )
    {
       echo '    <OPTION VALUE="'.$current_action["value"].'" '.
              chk_select($this->action,$current_action["value"]).'>'.
              GetActionDesc($current_action["value"])."\n";      
    }     
    
    echo "    </SELECT>\n".
         "    <INPUT TYPE=\"text\" NAME=\"action_arg\" VALUE=\"".$this->action_arg."\">\n";

    reset($this->valid_action_op_list);
    while( $current_op = each($this->valid_action_op_list) )
    {
       echo "    <INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"".$current_op["value"]."\">\n";
    } 

    echo "   </TD>\n".
         "  </TR>\n".
         " </TABLE>\n".
         "</CENTER>\n\n";
  }

  function ReadState()
  {
     $this->current_canned_query = ImportHTTPVar("caller", VAR_LETTER | VAR_USCORE);
     $this->num_result_rows      = ImportHTTPVar("num_result_rows", VAR_DIGIT | VAR_SCORE);
     $this->current_sort_order   = ImportHTTPVar("sort_order", VAR_LETTER | VAR_USCORE);
     $this->current_view         = ImportHTTPVar("current_view", VAR_DIGIT);
     $this->action_arg           = ImportHTTPVar("action_arg", VAR_ALPHA | VAR_PERIOD | VAR_USCORE | VAR_SCORE | VAR_AT);
     $this->action_chk_lst       = ImportHTTPVar("action_chk_lst", VAR_DIGIT | VAR_PUNC);   /* array */
     $this->action_lst           = ImportHTTPVar("action_lst", VAR_DIGIT | VAR_PUNC | VAR_SCORE);   /* array */
     $this->action               = ImportHTTPVar("action", VAR_ALPHA | VAR_USCORE);
  }

  function SaveState()
  {
     echo "<!-- Saving Query State -->\n";
     ExportHTTPVar("caller", $this->current_canned_query);
     ExportHTTPVar("num_result_rows", $this->num_result_rows);
     // The below line is commented to fix bug #1761605 please verify this doesnt break anything else -- Kevin Johnson
     //ExportHTTPVar("sort_order", $this->current_sort_order);
     ExportHTTPVar("current_view", $this->current_view);
  }

  function SaveStateGET()
  {
     return "?caller=".$this->current_canned_query.
            "&amp;num_result_rows=".$this->num_result_rows.
            "&amp;current_view=".$this->current_view;
  }

  function DumpState()
  {
    echo "<B>"._QUERYSTATE."</B><BR>
          caller = '$this->current_canned_query'<BR>
          num_result_rows = '$this->num_result_rows'<BR>
          sort_order = '$this->current_sort_order'<BR>
          current_view = '$this->current_view'<BR>
          action_arg = '$this->action_arg'<BR>
          action = '$this->action'<BR>";
  }
}

?>
