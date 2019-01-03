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
** Purpose: manages the output of Query results
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

include_once("$BASE_path/includes/base_constants.inc.php");

class QueryResultsOutput
{
  var $qroHeader;
  var $url;

  function QueryResultsOutput($uri)
  {
    $this->url = $uri;
  }

  function AddTitle($title, $asc_sort = " ", $asc_sort_sql1 = "", $asc_sort_sql2 = "",
                            $desc_sort = " ", $desc_sort_sql1 = "", $desc_sort_sql2 = "")
  {
    $this->qroHeader[$title] = array( $asc_sort  => array( $asc_sort_sql1, $asc_sort_sql2 ),
                                     $desc_sort => array( $desc_sort_sql1, $desc_sort_sql2 ) ); 
 }

  function GetSortSQL($sort, $sort_order)
  {
    reset($this->qroHeader);
    while( $title = each($this->qroHeader) )
    {
      if ( in_array($sort, array_keys($title["value"])) )
      {
         $tmp_sort = $title["value"][$sort];
         return $tmp_sort;
      }      
    }

    /* $sort is not a valid sort type of any header */
    return NULL;
  }
 
  function PrintHeader($text = '')
  {
     /* Client-side Javascript to select all the check-boxes on the screen
      *   - Bill Marque (wlmarque@hewitt.com) */
     echo '
          <SCRIPT type="text/javascript">
            function SelectAll()
            {
               for(var i=0;i<document.PacketForm.elements.length;i++)
               {
                  if(document.PacketForm.elements[i].type == "checkbox")
                  {
                    document.PacketForm.elements[i].checked = true;
                  }
               }
            }
      
            function UnselectAll()
            {
                for(var i=0;i<document.PacketForm.elements.length;i++)
                {
                    if(document.PacketForm.elements[i].type == "checkbox")
                    {
                      document.PacketForm.elements[i].checked = false;
                    }
                }
            }
           </SCRIPT>';

     if ('' != $text) {
         echo $text;
     }
     
     echo '<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH="100%" BGCOLOR="#000000">'."\n".
          "<TR><TD>\n".
          '<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH="100%" BGCOLOR="#FFFFFF">'."\n".
          "\n\n<!-- Query Results Title Bar -->\n   <TR>\n";

     reset($this->qroHeader);
     while( $title = each($this->qroHeader) )
     {
       $print_title = "";

       $sort_keys = array_keys($title["value"]);
       if ( count($sort_keys) == 2 )
       {
          $print_title = "<A HREF=\"".$this->url."&amp;sort_order=".$sort_keys[0]."\">&lt;</A>".
                         "&nbsp;".$title["key"]."&nbsp;".
                         "<A HREF=\"".$this->url."&amp;sort_order=".$sort_keys[1]."\">&gt;</A>";
       }
       else
       {
          $print_title = $title["key"];
       }
    
       echo '    <TD CLASS="plfieldhdr">&nbsp;'.$print_title.'&nbsp;</TD>'."\n";
     }

    echo "   </TR>\n";
  }

  function PrintFooter()
  {
    echo "  </TABLE>\n
           </TD></TR>\n
          </TABLE>\n";
  }

  function DumpQROHeader()
  {
    echo "<B>"._QUERYRESULTSHEADER."</B>
          <PRE>";
    print_r($this->qroHeader);
    echo "</PRE>";
  }
}

function qroReturnSelectALLCheck()
{
  return '<INPUT type=checkbox value="Select All" onClick="if (this.checked) SelectAll(); if (!this.checked) UnselectAll();">';
}

function qroPrintEntryHeader($prio=1, $color=0) {
 global $priority_colors;
 if($color == 1) {
        echo '<TR BGCOLOR="#'.$priority_colors[$prio].'">';
 } else {
        echo '<TR BGCOLOR="#'.((($prio % 2) == 0) ? "DDDDDD" : "FFFFFF").'">';
 }
}

function qroPrintEntry($value, $halign="center", $valign="top", $passthru="")
{
  echo "<TD align=\"".$halign."\" valign=\"".$valign."\" ".$passthru.">\n".
       "  $value\n".
       "</TD>\n\n";
}

function qroPrintEntryFooter()
{
  echo '</TR>';
}

?>
