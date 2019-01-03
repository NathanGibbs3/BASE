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
** Purpose: generates timing information   
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

class EventTiming
{
  var $start_time;
  var $num_events;
  var $event_log;
  var $verbose;

  function EventTiming($verbose)
  {
    $this->num_events = 0;
    $this->verbose = $verbose;
    $this->start_time = time();
    $this->Mark("Page Load");
  }


  function Mark($desc)
  {
    $this->event_log[$this->num_events++] = array ( time(), $desc );
  }

  function PrintTiming()
  {
    if ( $this->verbose > 0 )
    {
       echo "\n\n<!-- Timing Information -->\n".
            "<div class='systemdebug'>["._LOADEDIN." ".(time()- ($this->start_time) )." "._SECONDS."]</div>\n";
    }

    if ( $this->verbose > 1 )
    {
       for ( $i = 1; $i < $this->num_events; $i++ )
          echo "<LI>".$this->event_log[$i][1]." [".
               ($this->event_log[$i][0] - ($this->event_log[$i-1][0])).
               " "._SECONDS."]\n";
    } 
  }
}

?>