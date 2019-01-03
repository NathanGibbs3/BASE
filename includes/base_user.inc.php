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
** Purpose: Creates a user preferences object.  This object will allow the system
**  to track the preferences of the user.  It will also provide basic functions
**  like change password, etc.....
** 
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

class BaseUserPrefs
{
    var $db;
    
    function BaseUserPrefs()
    {
        // Constructor
        GLOBAL $DBlib_path, $DBtype, $db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password;
        $db = NewBASEDBConnection($DBlib_path, $DBtype);
        $db->baseDBConnect($db_connect_method, $alert_dbname, $alert_host,
                            $alert_port, $alert_user, $alert_password);
        $this->db = $db;
    }
    
    
}

?>